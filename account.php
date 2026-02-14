<?php
require_once 'header-functions.php';
require_once __DIR__ . '/includes/config.php';

page_header('LeadStar - Account', $_SERVER['REQUEST_URI'] ?? '/account');
?>

<section class="page-hero page-hero-left">
    <p class="hero-kicker">Account Dashboard</p>
    <h1>Current Package</h1>
    <p class="hero-copy">View your current plan and refresh licensing details instantly.</p>
</section>

<section class="section" data-plan-ui>
    <div class="grid2">
        <article class="glass page-card">
            <h2>License Status</h2>
            <div class="account-grid">
                <div class="account-row"><span class="muted">Connected Email</span><strong data-plan-email>--</strong></div>
                <div class="account-row"><span class="muted">Current Plan</span><strong data-plan-name>--</strong></div>
                <div class="account-row"><span class="muted">Pro Expires</span><strong data-plan-expiry>--</strong></div>
                <div class="account-row"><span class="muted">Last Verified</span><strong data-plan-verified>--</strong></div>
                <div class="account-row"><span class="muted">Usage Today</span><strong data-plan-usage>n/a</strong></div>
            </div>
            <p class="sign-msg muted" data-plan-message>Checking session...</p>
            <div class="sign-actions">
                <button type="button" class="btn ghost full" data-refresh-plan>Refresh Status</button>
                <button type="button" class="btn ghost full" data-signout>Sign Out</button>
            </div>
            <div class="sign-actions">
                <a class="btn primary full" href="<?= h(UPGRADE_URL) ?>">Go Pro</a>
                <a class="btn ghost full" href="<?= h(leadstar_url('/support')) ?>">Contact Support</a>
            </div>
        </article>
    </div>
</section>

<script>
window.LEADSTAR_WEB_CONFIG = {
  supabaseUrl: <?= json_encode(SUPABASE_URL, JSON_UNESCAPED_SLASHES) ?>,
  supabaseAnonKey: <?= json_encode(SUPABASE_ANON_KEY, JSON_UNESCAPED_SLASHES) ?>,
  siteBase: <?= json_encode(leadstar_cta_url('/'), JSON_UNESCAPED_SLASHES) ?>,
  upgradeUrl: <?= json_encode(UPGRADE_URL, JSON_UNESCAPED_SLASHES) ?>
};
</script>

<?php page_footer(); ?>
