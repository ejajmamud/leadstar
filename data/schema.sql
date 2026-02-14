-- SQLite schema for licensing
CREATE TABLE profiles (
    id TEXT PRIMARY KEY,
    plan TEXT DEFAULT 'free',
    expires_at TIMESTAMP,
    usage_reset_date DATE DEFAULT CURRENT_DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE usage_logs (
    id TEXT PRIMARY KEY DEFAULT (lower(hex(randomblob(16)))),
    user_id TEXT NOT NULL,
    feature TEXT NOT NULL,
    quantity INTEGER NOT NULL,
    consumed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES profiles(id)
);

CREATE INDEX idx_usage_logs_user_date ON usage_logs(user_id, date(consumed_at));
CREATE INDEX idx_profiles_plan ON profiles(plan);

-- Triggers for usage limits
CREATE TRIGGER prevent_excess_maps_usage
BEFORE INSERT ON usage_logs
WHEN NEW.feature = 'maps_leads'
BEGIN
    SELECT CASE
        WHEN (
            SELECT SUM(quantity) FROM usage_logs 
            WHERE user_id = NEW.user_id 
            AND feature = 'maps_leads'
            AND date(consumed_at) = date('now')
        ) + NEW.quantity > FREE_MAPS_LIMIT
        AND (
            SELECT plan FROM profiles WHERE id = NEW.user_id
        ) = 'free'
    THEN RAISE(ABORT, 'Daily maps limit exceeded')
    END;
END;