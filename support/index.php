<?php
require_once __DIR__ . '/../header-functions.php';
page_header('LeadStar - Support', $_SERVER['REQUEST_URI'] ?? '/support');
?>

<section class="page-hero page-hero-left">
    <p class="hero-kicker">Need help?</p>
    <h1>Support built for fast answers.</h1>
    <p class="hero-copy">Use these resources to solve issues quickly and keep your outreach moving.</p>
</section>

<section class="section">
    <div class="grid3">
        <a class="glass page-card feature-card" href="<?= h(leadstar_url('/docs')) ?>">
            <h2>Guides</h2>
            <p>Step-by-step help for setup, extraction, and exports.</p>
        </a>

        <a class="glass page-card feature-card" href="<?= h(leadstar_url('/pricing')) ?>">
            <h2>Plans</h2>
            <p>Compare Free and Pro features with clear pricing details.</p>
        </a>

        <a class="glass page-card feature-card" href="<?= h(leadstar_url('/status')) ?>">
            <h2>Status</h2>
            <p>Check current service availability and operational updates.</p>
        </a>
    </div>
</section>

<?php page_footer(); ?>
