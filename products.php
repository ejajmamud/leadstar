<?php
require_once 'header-functions.php';
page_header('LeadStar - Product', $_SERVER['REQUEST_URI'] ?? '/products');
?>

<section class="page-hero">
    <p class="hero-kicker">One product. Complete workflow.</p>
    <h1>LeadStar is your all-in-one lead extraction workspace.</h1>
    <p class="hero-copy">Capture leads, enrich details, and export ready-to-use lists from one extension.</p>
</section>

<section class="section">
    <div class="grid3">
        <article class="glass page-card feature-card">
            <h2>List Extraction</h2>
            <p>Collect structured leads from directory pages and listing sites in a few clicks.</p>
        </article>
        <article class="glass page-card feature-card">
            <h2>Maps Leads</h2>
            <p>Build local business lead lists from map results and organize them by campaign.</p>
        </article>
        <article class="glass page-card feature-card">
            <h2>Page Details</h2>
            <p>Capture business fields like names, contacts, and key page details quickly.</p>
        </article>
    </div>
</section>

<section class="section">
    <div class="glass page-card">
        <h2>Built for real teams</h2>
        <div class="grid2">
            <div>
                <ul class="bullets">
                    <li>Simple learning curve for new team members</li>
                    <li>Flexible exports for CRM and spreadsheet workflows</li>
                    <li>Consistent output quality for daily prospecting</li>
                </ul>
            </div>
            <div>
                <ul class="bullets">
                    <li>Free plan for evaluation</li>
                    <li>Pro plan for high-volume operations</li>
                    <li>Clear upgrade path as your workload grows</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<?php page_footer(); ?>
