<?php
require_once 'header-functions.php';
page_header('LeadStar - Company', $_SERVER['REQUEST_URI'] ?? '/company');
?>

<section class="page-hero">
    <p class="hero-kicker">About LeadStar</p>
    <h1>We build lead extraction software for serious growth teams.</h1>
    <p class="hero-copy">Our focus is simple: fast workflows, cleaner exports, and privacy-first handling of your data.</p>
</section>

<section class="section">
    <div class="grid2">
        <article class="glass page-card">
            <h2>Our mission</h2>
            <p>LeadStar helps teams move from manual prospecting to consistent lead generation without adding operational overhead.</p>
        </article>

        <article class="glass page-card">
            <h2>Our approach</h2>
            <ul class="bullets">
                <li>Local-first handling of extracted data</li>
                <li>Simple workflows for non-technical teams</li>
                <li>Reliable outputs for sales and outreach operations</li>
            </ul>
        </article>
    </div>
</section>

<section class="section">
    <div class="glass page-card">
        <h2>What we value</h2>
        <div class="grid3">
            <div class="feature-card">
                <h3>Trust</h3>
                <p>We design with transparency and user control at every step.</p>
            </div>
            <div class="feature-card">
                <h3>Performance</h3>
                <p>Fast extraction and clean exports are central to the product.</p>
            </div>
            <div class="feature-card">
                <h3>Simplicity</h3>
                <p>Powerful results without complicated setup or maintenance.</p>
            </div>
        </div>
    </div>
</section>

<?php page_footer(); ?>
