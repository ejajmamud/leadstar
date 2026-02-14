<?php
require_once 'header-functions.php';
page_header('LeadStar - Terms', $_SERVER['REQUEST_URI'] ?? '/terms');
?>

<section class="page-hero page-hero-left">
    <p class="hero-kicker">Terms of use</p>
    <h1>Clear rules for using LeadStar responsibly.</h1>
    <p class="hero-copy">These terms are designed to keep the platform safe, fair, and reliable for all users.</p>
</section>

<section class="section legal-stack">
    <article class="glass page-card legal-card">
        <h2>1. Agreement</h2>
        <p>By using LeadStar, you agree to these terms. If you do not agree, please do not use the service.</p>
    </article>

    <article class="glass page-card legal-card">
        <h2>2. Plans and billing</h2>
        <ul class="bullets">
            <li>Free plan includes daily limits</li>
            <li>Pro plan includes expanded usage and features</li>
            <li>You can cancel your paid plan at any time</li>
        </ul>
    </article>

    <article class="glass page-card legal-card">
        <h2>3. Acceptable use</h2>
        <ul class="bullets">
            <li>Use LeadStar for lawful business activity</li>
            <li>Respect the terms of websites you collect data from</li>
            <li>Do not use LeadStar for spam, fraud, or abuse</li>
        </ul>
    </article>

    <article class="glass page-card legal-card">
        <h2>4. Your responsibility</h2>
        <p>You are responsible for how you use extracted data, including compliance with applicable privacy and marketing laws.</p>
    </article>

    <article class="glass page-card legal-card">
        <h2>5. Service updates</h2>
        <p>We may improve or change features over time. Continued use means you accept current terms.</p>
    </article>
</section>

<?php page_footer(); ?>
