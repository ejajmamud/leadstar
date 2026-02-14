<?php
require_once 'header-functions.php';
page_header('LeadStar - Privacy', $_SERVER['REQUEST_URI'] ?? '/privacy');
?>

<section class="page-hero page-hero-left">
    <p class="hero-kicker">Privacy first</p>
    <h1>Your lead data stays under your control.</h1>
    <p class="hero-copy">LeadStar is built so your extracted information remains local unless you choose to export it.</p>
</section>

<section class="section legal-stack">
    <article class="glass page-card legal-card">
        <h2>What we collect</h2>
        <p>We collect only what is needed to run your account and plan:</p>
        <ul class="bullets">
            <li>Account email for sign-in</li>
            <li>Plan status (Free or Pro)</li>
            <li>Usage counters for plan limits</li>
        </ul>
    </article>

    <article class="glass page-card legal-card">
        <h2>What we do not collect</h2>
        <ul class="bullets">
            <li>Your extracted lead lists</li>
            <li>Your page-level extracted content</li>
            <li>Your exported files</li>
        </ul>
    </article>

    <article class="glass page-card legal-card">
        <h2>How your data is used</h2>
        <p>Account information is used only for authentication, licensing, and fair usage checks. We do not sell your data.</p>
    </article>

    <article class="glass page-card legal-card">
        <h2>Your choices</h2>
        <ul class="bullets">
            <li>Export your data any time</li>
            <li>Delete local datasets from the extension</li>
            <li>Stop using the service whenever you want</li>
        </ul>
    </article>

    <article class="glass page-card legal-card">
        <h2>Contact</h2>
        <p>Questions about privacy can be sent through the support page. We respond as quickly as possible.</p>
    </article>
</section>

<?php page_footer(); ?>
