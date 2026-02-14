<?php
require_once __DIR__ . '/includes/config.php';
$base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
if ($base === '/' || $base === '\\') {
    $base = '';
}
$ctaBase = 'https://apps.ejaj.space/leadstar';
$signInUrl = $ctaBase . '/sign';
function leadstar_href(string $path, string $base): string {
    $path = '/' . ltrim($path, '/');
    return ($base === '' ? '' : $base) . $path;
}
function leadstar_asset_href(string $path, string $base): string {
    $path = '/' . ltrim($path, '/');
    $full = __DIR__ . $path;
    $ver = is_file($full) ? (string)filemtime($full) : (string)time();
    return leadstar_href($path, $base) . '?v=' . rawurlencode($ver);
}
function leadstar_cta(string $path, string $ctaBase): string {
    $path = '/' . ltrim($path, '/');
    return $ctaBase . $path;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>LeadStar · hyper-modern extraction suite</title>
  <meta name="theme-color" content="#0b101f" />
  <meta name="color-scheme" content="dark" />
  <link rel="stylesheet" href="<?php echo htmlspecialchars(leadstar_asset_href('/assets/css/leadstar-home.css', $base), ENT_QUOTES, 'UTF-8'); ?>" />
</head>
<body>
<div class="bg">
  <div class="grain"></div>
  <div class="blob b1"></div>
  <div class="blob b2"></div>
  <div class="grid"></div>
  <div class="vignette"></div>
</div>

<header class="topbar">
  <div class="wrap topbar-inner">
    <a class="brand" href="<?php echo htmlspecialchars(leadstar_href('/', $base), ENT_QUOTES, 'UTF-8'); ?>">
      <span class="brand-mark">
        <img class="brand-logo" src="<?php echo htmlspecialchars(leadstar_href('/assets/logo.png', $base), ENT_QUOTES, 'UTF-8'); ?>" alt="LeadStar logo" />
      </span>
      <span class="brand-stack">
        <span class="brand-text">LeadStar</span>
        <span class="brand-sub">Advance Lead Scrapper</span>
      </span>
    </a>

    <nav class="nav" aria-label="Primary navigation">
      <a class="nav-link active" href="<?php echo htmlspecialchars(leadstar_href('/', $base), ENT_QUOTES, 'UTF-8'); ?>">Home</a>
      <a class="nav-link" href="<?php echo htmlspecialchars(leadstar_href('/pricing', $base), ENT_QUOTES, 'UTF-8'); ?>">Pricing</a>
      <a class="nav-link" href="<?php echo htmlspecialchars(leadstar_href('/docs', $base), ENT_QUOTES, 'UTF-8'); ?>">Docs</a>
      <a class="nav-link" href="<?php echo htmlspecialchars(leadstar_href('/privacy', $base), ENT_QUOTES, 'UTF-8'); ?>">Privacy</a>

      <a class="nav-cta" href="<?php echo htmlspecialchars($signInUrl, ENT_QUOTES, 'UTF-8'); ?>" data-signin-link>
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
          <a href="<?php echo htmlspecialchars(leadstar_href('/account', $base), ENT_QUOTES, 'UTF-8'); ?>">My Account</a>
          <a href="<?php echo htmlspecialchars(leadstar_href('/docs', $base), ENT_QUOTES, 'UTF-8'); ?>">Documentation</a>
          <a href="<?php echo htmlspecialchars(leadstar_href('/pricing', $base), ENT_QUOTES, 'UTF-8'); ?>">Plan & Billing</a>
          <a href="<?php echo htmlspecialchars(leadstar_href('/support', $base), ENT_QUOTES, 'UTF-8'); ?>">Support</a>
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
          <span class="brand-mark small"><img class="brand-logo" src="<?php echo htmlspecialchars(leadstar_href('/assets/logo.png', $base), ENT_QUOTES, 'UTF-8'); ?>" alt="LeadStar logo" /></span>
          <div>
            <div class="drawer-title">LeadStar</div>
            <div class="drawer-sub">Advance Lead Scrapper</div>
          </div>
        </div>
        <button class="drawer-close" type="button" aria-label="Close menu" data-close>✕</button>
      </div>

      <div class="drawer-links">
        <a class="drawer-link active" href="<?php echo htmlspecialchars(leadstar_href('/', $base), ENT_QUOTES, 'UTF-8'); ?>">Home</a>
        <a class="drawer-link" href="<?php echo htmlspecialchars(leadstar_href('/pricing', $base), ENT_QUOTES, 'UTF-8'); ?>">Pricing</a>
        <a class="drawer-link" href="<?php echo htmlspecialchars(leadstar_href('/docs', $base), ENT_QUOTES, 'UTF-8'); ?>">Docs</a>
        <a class="drawer-link" href="<?php echo htmlspecialchars(leadstar_href('/privacy', $base), ENT_QUOTES, 'UTF-8'); ?>">Privacy</a>
      </div>

      <div class="drawer-foot">
        <a class="btn primary full" href="<?php echo htmlspecialchars(leadstar_cta('/pricing', $ctaBase), ENT_QUOTES, 'UTF-8'); ?>" data-upgrade>Go Pro</a>
        <a class="btn ghost full" href="<?php echo htmlspecialchars($signInUrl, ENT_QUOTES, 'UTF-8'); ?>" data-drawer-signin>Sign In</a>
        <a class="btn ghost full" href="<?php echo htmlspecialchars(leadstar_href('/account', $base), ENT_QUOTES, 'UTF-8'); ?>" data-drawer-profile hidden>Account Settings</a>
        <button type="button" class="btn ghost full" data-signout data-drawer-profile hidden>Sign Out</button>
      </div>
    </div>
  </div>
</header>

<main class="wrap">
  <section class="hero">
    <div class="hero-left">
      <div class="badge">
        <span class="dot"></span>
        Fast setup • Clean output • Team-ready
      </div>

      <h1>
        Prospect smarter.
        <span class="grad">Export cleaner.</span>
      </h1>

      <p class="lead">
        LeadStar helps you collect lead lists, business contacts, and page details,
        then export everything into clean files your team can use immediately.
      </p>

      <div class="cta-row">
        <a class="btn primary" href="<?php echo htmlspecialchars(leadstar_cta('/pricing', $ctaBase), ENT_QUOTES, 'UTF-8'); ?>" data-upgrade>See Pricing</a>
        <a class="btn ghost" href="<?php echo htmlspecialchars(leadstar_cta('/docs', $ctaBase), ENT_QUOTES, 'UTF-8'); ?>">Read Docs</a>
      </div>

      <div class="stats">
        <div class="stat">
          <div class="stat-top">Fast setup</div>
          <div class="stat-sub">Get started in minutes</div>
        </div>
        <div class="stat">
          <div class="stat-top">Flexible plans</div>
          <div class="stat-sub">Free and Pro options</div>
        </div>
        <div class="stat">
          <div class="stat-top">Team-ready</div>
          <div class="stat-sub">Built for daily prospecting</div>
        </div>
      </div>
    </div>

    <div class="hero-right">
      <div class="card glass">
        <div class="card-head">
          <div>
            <div class="card-title">Quick Start</div>
            <div class="muted small">Install → Sign in → Collect leads → Export</div>
          </div>
          <div class="pill">LeadStar Workflow</div>
        </div>

        <div class="steps">
          <div class="step">
            <div class="step-num">1</div>
            <div class="step-body">
              <div class="step-title">Install Extension</div>
              <div class="step-text">Load unpacked or Chrome Store.</div>
            </div>
          </div>
          <div class="step">
            <div class="step-num">2</div>
            <div class="step-body">
              <div class="step-title">Sign in</div>
              <div class="step-text">Settings → License → Sign in.</div>
            </div>
          </div>
          <div class="step">
            <div class="step-num">3</div>
            <div class="step-body">
              <div class="step-title">Confirm sign in</div>
              <div class="step-text">Complete the sign-in step and return to LeadStar.</div>
            </div>
          </div>
          <div class="step">
            <div class="step-num">4</div>
            <div class="step-body">
              <div class="step-title">Refresh License</div>
              <div class="step-text">Back in extension → Refresh License.</div>
            </div>
          </div>
        </div>

        <div class="divider"></div>

        <div class="callout">
          <div class="callout-title">Secure Sign In</div>
          <div class="callout-text">
            Use this page to complete your sign-in and continue in the extension.
            It is designed for a quick and smooth handoff.
          </div>
          <div class="cta-row callout-cta">
            <a class="btn small ghost" href="<?php echo htmlspecialchars($signInUrl, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer">Open Sign In</a>
            <a class="btn small" href="<?php echo htmlspecialchars(leadstar_cta('/privacy', $ctaBase), ENT_QUOTES, 'UTF-8'); ?>">Privacy</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="section-head">
      <h2>Core features</h2>
      <p class="muted">Built for speed, resilience, and clean exports.</p>
    </div>

    <div class="grid3">
      <div class="feature glass">
        <div class="icon">🧲</div>
        <div class="f-title">List Extractor</div>
        <div class="f-text">Table mode, infinite scroll, multi-page extraction with dataset export.</div>
        <div class="tag-row">
          <span class="tag">Picker</span><span class="tag">Infinite</span><span class="tag">Multi-page</span>
        </div>
      </div>

      <div class="feature glass">
        <div class="icon">🗺️</div>
        <div class="f-title">Maps Leads</div>
        <div class="f-text">Parallel workers, live viewer, dedupe, and stage-based progress updates.</div>
        <div class="tag-row">
          <span class="tag">Workers</span><span class="tag">Live table</span><span class="tag">Dedupe</span>
        </div>
      </div>

      <div class="feature glass">
        <div class="icon">📦</div>
        <div class="f-title">Exports</div>
        <div class="f-text">CSV, Excel (Pro), TXT, PNG screenshot, PDF via DevTools protocol.</div>
        <div class="tag-row">
          <span class="tag">CSV</span><span class="tag">Excel</span><span class="tag">PDF</span>
        </div>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="section-head">
      <h2>Freemium that feels fair</h2>
      <p class="muted">Free users get real value. Pro users get serious throughput.</p>
    </div>

    <div class="grid2">
      <div class="panel glass">
        <h3>Free</h3>
        <ul class="bullets">
          <li>Daily quotas for Maps + List extraction</li>
          <li>CSV export</li>
          <li>Core tools and datasets/history</li>
        </ul>
      </div>
      <div class="panel glass">
        <h3>Pro</h3>
        <ul class="bullets">
          <li>Unlimited quotas</li>
          <li>Excel export + automation runners</li>
          <li>Pro Configuration (workers, nearby seconds, etc.)</li>
        </ul>
      </div>
    </div>

    <div class="cta-row section-cta">
      <a class="btn primary" href="<?php echo htmlspecialchars(leadstar_cta('/pricing', $ctaBase), ENT_QUOTES, 'UTF-8'); ?>" data-upgrade>Upgrade to Pro</a>
      <a class="btn ghost" href="<?php echo htmlspecialchars(leadstar_cta('/docs', $ctaBase), ENT_QUOTES, 'UTF-8'); ?>">Implementation details</a>
    </div>
  </section>

  <section class="section">
    <div class="section-head">
      <h2>Simple, transparent pricing</h2>
    </div>
    <div class="pricing">
      <div class="price-card glass">
        <div class="price-top">
          <div class="price-name">Free</div>
          <div class="price">$0</div>
          <div class="price-sub">Light usage & testing</div>
        </div>
        <ul class="bullets">
          <li>Daily quotas</li>
          <li>CSV export</li>
          <li>Core extractors</li>
        </ul>
        <a class="btn ghost full" href="<?php echo htmlspecialchars(leadstar_cta('/docs', $ctaBase), ENT_QUOTES, 'UTF-8'); ?>">How it works</a>
      </div>

      <div class="price-card glass featured">
        <div class="ribbon">Recommended</div>
        <div class="price-top">
          <div class="price-name">Pro</div>
          <div class="price">$19<span class="small">/mo</span></div>
          <div class="price-sub">Daily prospecting & agencies</div>
        </div>
        <ul class="bullets">
          <li>Unlimited quotas</li>
          <li>Excel export</li>
          <li>Automation runners</li>
        </ul>
        <a class="btn primary full" href="<?php echo htmlspecialchars($signInUrl, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer" data-upgrade>Go Pro</a>
      </div>
    </div>
  </section>
</main>

<footer class="footer">
  <div class="wrap footer-inner">
    <div class="footer-left">
      <div class="footer-brand-wrap">
        <img class="brand-logo" src="<?php echo htmlspecialchars(leadstar_href('/assets/logo.png', $base), ENT_QUOTES, 'UTF-8'); ?>" alt="LeadStar logo" />
      </div>
      <div class="footer-brand">LeadStar</div>
      <div class="footer-note">Built for clean lead discovery and fast public outreach workflows.</div>
    </div>
    <div class="footer-right">
      <a href="<?php echo htmlspecialchars(leadstar_href('/privacy', $base), ENT_QUOTES, 'UTF-8'); ?>">Privacy</a>
      <a href="<?php echo htmlspecialchars(leadstar_href('/terms', $base), ENT_QUOTES, 'UTF-8'); ?>">Terms</a>
      <a href="<?php echo htmlspecialchars(leadstar_href('/docs', $base), ENT_QUOTES, 'UTF-8'); ?>">Docs</a>
      <div class="footer-credit">Extension Developed by <a href="https://ejaj.space" target="_blank" rel="noopener noreferrer">Ejaj</a></div>
    </div>
  </div>
</footer>

<script>
window.LEADSTAR_WEB_CONFIG = Object.assign({}, window.LEADSTAR_WEB_CONFIG || {}, {
  supabaseUrl: <?php echo json_encode(SUPABASE_URL, JSON_UNESCAPED_SLASHES); ?>,
  supabaseAnonKey: <?php echo json_encode(SUPABASE_ANON_KEY, JSON_UNESCAPED_SLASHES); ?>,
  siteBase: <?php echo json_encode('https://apps.ejaj.space/leadstar', JSON_UNESCAPED_SLASHES); ?>
});
</script>
<script src="<?php echo htmlspecialchars(leadstar_asset_href('/assets/js/leadstar-home.js', $base), ENT_QUOTES, 'UTF-8'); ?>"></script>
<script src="<?php echo htmlspecialchars(leadstar_asset_href('/assets/app.js', $base), ENT_QUOTES, 'UTF-8'); ?>"></script>
</body>
</html>
