<?php
declare(strict_types=1);

// Site configuration
define('SITE_NAME', 'LeadStar');
define('SITE_URL', 'https://apps.ejaj.space/leadstar');
define('SITE_ENV', 'production'); // development, staging, production

// Supabase (public safe values)
define('SUPABASE_URL', 'https://rspvhpcezswfvsjbbofo.supabase.co');
define('SUPABASE_ANON_KEY', 'sb_publishable_lyhy821PKQxbvaRG7YKl9A_sQrnArC9');

// Auth callback URL on your domain
define('SUPABASE_REDIRECT_URL', 'https://apps.ejaj.space/leadstar/auth');

// Optional: where "Go Pro" points
define('UPGRADE_URL', 'https://apps.ejaj.space/leadstar/pricing');

// Website admin-only upgrade gate
define('UPGRADE_ADMIN_KEY', 'change-this-admin-upgrade-key');

// License limits
define('FREE_MAPS_LIMIT', 20);
define('FREE_LIST_LIMIT', 500);
define('FREE_PDF_LIMIT', 5);
define('CACHE_TTL', 1800); // 30 minutes
define('OFFLINE_GRACE', 86400); // 24 hours

// Feature flags
define('ENABLE_BLOG', true);
define('ENABLE_SUPPORT_CHAT', false);
define('MAINTENANCE_MODE', false);

// Security
define('SESSION_LIFETIME', 7200); // 2 hours
define('BCRYPT_COST', 12);
