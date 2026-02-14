<?php
require_once __DIR__ . '/../header-functions.php';
page_header('LeadStar - Blog', $_SERVER['REQUEST_URI'] ?? '/blog');
?>

<section class="page-hero page-hero-left">
    <p class="hero-kicker">LeadStar updates</p>
    <h1>Product news, tips, and growth playbooks.</h1>
    <p class="hero-copy">Short reads focused on lead generation, workflow quality, and practical results.</p>
</section>

<section class="section">
    <div class="grid2">
        <article class="glass page-card">
            <h2>How teams keep lead quality high at scale</h2>
            <p class="muted">February 10, 2026 · 5 min read</p>
            <p>Simple habits that help teams avoid duplicate leads, missing fields, and messy exports.</p>
        </article>

        <article class="glass page-card">
            <h2>From manual copy-paste to repeatable prospecting</h2>
            <p class="muted">February 5, 2026 · 4 min read</p>
            <p>A practical workflow to move from scattered lead collection to consistent weekly output.</p>
        </article>
    </div>
</section>

<?php page_footer(); ?>
