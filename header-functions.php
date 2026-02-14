<?php
require_once __DIR__ . '/includes/config.php';

function h(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

function leadstar_base_path(): string {
    $uriPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    if (str_starts_with($uriPath, '/leadstar/')) {
        return '/leadstar';
    }
    if ($uriPath === '/leadstar') {
        return '/leadstar';
    }
    return '';
}

function leadstar_url(string $path): string {
    $path = '/' . ltrim($path, '/');
    $base = leadstar_base_path();
    return ($base === '' ? '' : $base) . $path;
}

function leadstar_asset_url(string $path): string {
    $path = '/' . ltrim($path, '/');
    $full = __DIR__ . $path;
    $ver = is_file($full) ? (string)filemtime($full) : (string)time();
    return leadstar_url($path) . '?v=' . rawurlencode($ver);
}

function leadstar_cta_url(string $path = '/'): string {
    $path = '/' . ltrim($path, '/');
    return 'https://apps.ejaj.space/leadstar' . ($path === '/' ? '' : $path);
}

function leadstar_sign_in_url(): string {
    return leadstar_cta_url('/sign');
}

function leadstar_current_path(): string {
    $uriPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $base = leadstar_base_path();

    if ($base !== '' && str_starts_with($uriPath, $base . '/')) {
        $uriPath = substr($uriPath, strlen($base));
    } elseif ($base !== '' && $uriPath === $base) {
        $uriPath = '/';
    }

    $normalized = rtrim($uriPath, '/');
    return $normalized === '' ? '/' : $normalized;
}

function leadstar_is_active(string $route): string {
    $current = leadstar_current_path();
    $route = rtrim($route, '/');
    $route = $route === '' ? '/' : $route;
    return $current === $route ? ' active' : '';
}

function page_header(string $title, string $path = ''): void {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="LeadStar - advanced lead scraping for modern teams" />
    <meta name="theme-color" content="#0b101f" />
    <meta name="color-scheme" content="dark" />
    <title><?= h($title) ?></title>
    <link rel="stylesheet" href="<?= h(leadstar_asset_url('/assets/css/leadstar-home.css')) ?>" />
    <link rel="stylesheet" href="<?= h(leadstar_asset_url('/assets/css/leadstar-pages.css')) ?>" />
</head>
<body>
<div class="bg" aria-hidden="true">
  <div class="grain"></div>
  <div class="blob b1"></div>
  <div class="blob b2"></div>
  <div class="grid"></div>
  <div class="vignette"></div>
</div>

<header class="topbar">
  <div class="wrap topbar-inner">
    <a class="brand" href="<?= h(leadstar_url('/')) ?>">
      <span class="brand-mark">
        <img class="brand-logo" src="<?= h(leadstar_url('/assets/logo.png')) ?>" alt="LeadStar logo" />
      </span>
      <span class="brand-stack">
        <span class="brand-text">LeadStar</span>
        <span class="brand-sub">Advance Lead Scrapper</span>
      </span>
    </a>

    <nav class="nav" aria-label="Primary navigation">
      <a class="nav-link<?= leadstar_is_active('/') ?>" href="<?= h(leadstar_url('/')) ?>">Home</a>
      <a class="nav-link<?= leadstar_is_active('/pricing') ?>" href="<?= h(leadstar_url('/pricing')) ?>">Pricing</a>
      <a class="nav-link<?= leadstar_is_active('/docs') ?>" href="<?= h(leadstar_url('/docs')) ?>">Docs</a>
      <a class="nav-link<?= leadstar_is_active('/privacy') ?>" href="<?= h(leadstar_url('/privacy')) ?>">Privacy</a>
      <a class="nav-cta" href="<?= h(leadstar_sign_in_url()) ?>" data-signin-link>
        <span class="spark" aria-hidden="true"></span>
        Sign In
        <svg class="cta-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
          <path d="M10 7h7v7" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
          <path d="M17 7l-9.5 9.5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
        </svg>
      </a>
      <div class="profile-shell" data-profile-shell hidden>
        <span class="topbar-plan-badge" data-topbar-plan hidden>Current Package: --</span>
        <button class="profile-toggle" type="button" data-profile-toggle aria-expanded="false" aria-label="Open profile menu">
          <span class="profile-avatar" data-profile-initial>U</span>
        </button>
        <div class="profile-menu" data-profile-menu hidden>
          <div class="profile-meta">
            <div class="profile-name" data-profile-name>User</div>
            <div class="profile-email" data-profile-email></div>
          </div>
          <a href="<?= h(leadstar_url('/account')) ?>">My Account</a>
          <a href="<?= h(leadstar_url('/docs')) ?>">Documentation</a>
          <a href="<?= h(leadstar_url('/pricing')) ?>">Plan & Billing</a>
          <a href="<?= h(leadstar_url('/support')) ?>">Support</a>
          <button type="button" class="profile-signout" data-signout>Sign Out</button>
        </div>
      </div>
    </nav>

    <button class="nav-burger" type="button" aria-label="Open menu" aria-expanded="false" data-burger>
      <span></span><span></span><span></span>
    </button>
  </div>

  <div class="drawer" data-drawer aria-hidden="true">
    <div class="drawer-panel">
      <div class="drawer-head">
        <div class="drawer-brand">
          <span class="brand-mark small">
            <img class="brand-logo" src="<?= h(leadstar_url('/assets/logo.png')) ?>" alt="LeadStar logo" />
          </span>
          <div>
            <div class="drawer-title">LeadStar</div>
            <div class="drawer-sub">Advance Lead Scrapper</div>
          </div>
        </div>
        <button class="drawer-close" type="button" aria-label="Close menu" data-close>?</button>
      </div>

      <div class="drawer-links">
        <a class="drawer-link<?= leadstar_is_active('/') ?>" href="<?= h(leadstar_url('/')) ?>">Home</a>
        <a class="drawer-link<?= leadstar_is_active('/pricing') ?>" href="<?= h(leadstar_url('/pricing')) ?>">Pricing</a>
        <a class="drawer-link<?= leadstar_is_active('/docs') ?>" href="<?= h(leadstar_url('/docs')) ?>">Docs</a>
        <a class="drawer-link<?= leadstar_is_active('/privacy') ?>" href="<?= h(leadstar_url('/privacy')) ?>">Privacy</a>
      </div>

      <div class="drawer-foot">
        <a class="btn primary full" href="<?= h(leadstar_cta_url('/pricing')) ?>" data-upgrade>Go Pro</a>
        <a class="btn ghost full" href="<?= h(leadstar_sign_in_url()) ?>" data-drawer-signin>Sign In</a>
        <a class="btn ghost full" href="<?= h(leadstar_url('/account')) ?>" data-drawer-profile hidden>Account Settings</a>
        <button type="button" class="btn ghost full" data-signout data-drawer-profile hidden>Sign Out</button>
      </div>
    </div>
  </div>
</header>

<main class="wrap page-main">
<?php
}

function page_footer(): void {
?>
</main>

<footer class="footer">
  <div class="wrap footer-inner">
    <div class="footer-left">
      <div class="footer-brand-wrap">
        <img class="brand-logo" src="<?= h(leadstar_url('/assets/logo.png')) ?>" alt="LeadStar logo" />
      </div>
      <div class="footer-brand">LeadStar</div>
      <div class="footer-note">Built for clean lead discovery and fast public outreach workflows.</div>
    </div>
    <div class="footer-right">
      <a href="<?= h(leadstar_url('/privacy')) ?>">Privacy</a>
      <a href="<?= h(leadstar_url('/terms')) ?>">Terms</a>
      <a href="<?= h(leadstar_url('/docs')) ?>">Docs</a>
      <a href="<?= h(leadstar_url('/status')) ?>">Status</a>
      <div class="footer-credit">Extension Developed by <a href="https://ejaj.space" target="_blank" rel="noopener noreferrer">Ejaj</a></div>
    </div>
  </div>
</footer>

<script>
window.LEADSTAR_WEB_CONFIG = Object.assign({}, window.LEADSTAR_WEB_CONFIG || {}, {
  supabaseUrl: <?= json_encode(defined('SUPABASE_URL') ? SUPABASE_URL : '', JSON_UNESCAPED_SLASHES) ?>,
  supabaseAnonKey: <?= json_encode(defined('SUPABASE_ANON_KEY') ? SUPABASE_ANON_KEY : '', JSON_UNESCAPED_SLASHES) ?>,
  siteBase: <?= json_encode(leadstar_cta_url('/'), JSON_UNESCAPED_SLASHES) ?>
});
</script>
<script src="<?= h(leadstar_asset_url('/assets/js/leadstar-home.js')) ?>"></script>
<script src="<?= h(leadstar_asset_url('/assets/app.js')) ?>"></script>
</body>
</html>
<?php
require_once __DIR__ . '/includes/config.php';
}
?>

