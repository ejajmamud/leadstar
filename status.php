<?php
require_once 'header-functions.php';
page_header('LeadStar - Status', $_SERVER['REQUEST_URI'] ?? '/status');
?>

<section class="page-hero page-hero-left">
    <p class="hero-kicker">Service health</p>
    <h1>All LeadStar systems are currently operational.</h1>
    <p class="hero-copy">This page tracks availability for account access, licensing checks, and product services.</p>
</section>

<section class="section">
    <div class="status-list">
        <article class="glass page-card status-item">
            <div>
                <h3>Account Sign-In</h3>
                <p class="muted">User login and session flow</p>
            </div>
            <div class="status-ok">Operational</div>
        </article>

        <article class="glass page-card status-item">
            <div>
                <h3>License Validation</h3>
                <p class="muted">Plan checks and usage access</p>
            </div>
            <div class="status-ok">Operational</div>
        </article>

        <article class="glass page-card status-item">
            <div>
                <h3>Usage Tracking</h3>
                <p class="muted">Daily usage and plan limits</p>
            </div>
            <div class="status-ok">Operational</div>
        </article>
    </div>
</section>

<?php page_footer(); ?>
