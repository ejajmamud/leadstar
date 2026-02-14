<?php
require_once 'header-functions.php';
page_header('LeadStar - Pricing', $_SERVER['REQUEST_URI'] ?? '/pricing');
?>

<section class="page-hero">
    <p class="hero-kicker">Plans for every stage</p>
    <h1>Simple pricing for teams that prospect daily.</h1>
    <p class="hero-copy">Start free. Upgrade when you need more speed, more exports, and unlimited daily usage.</p>
</section>

<section class="section">
    <div class="pricing-grid">
        <article class="glass page-card price-plan">
            <div class="price-head">
                <h2 class="price-name">Free</h2>
                <p class="price-copy">Great for testing and early outreach.</p>
                <div class="price-value">$0</div>
            </div>
            <ul class="bullets">
                <li>Daily extraction limits</li>
                <li>CSV export</li>
                <li>Core lead extraction tools</li>
                <li>Saved history and datasets</li>
            </ul>
            <a class="btn ghost full" href="<?= h(leadstar_cta_url('/docs')) ?>">Explore Features</a>
        </article>

        <article class="glass page-card price-plan featured-plan">
            <div class="plan-badge">Most Popular</div>
            <div class="price-head">
                <h2 class="price-name">Pro</h2>
                <p class="price-copy">Built for agencies and high-volume outreach.</p>
                <div class="price-value">$19<span class="price-period">/mo</span></div>
            </div>
            <ul class="bullets">
                <li>Unlimited daily usage</li>
                <li>Excel export</li>
                <li>Automation workflows</li>
                <li>Priority product updates</li>
            </ul>
            <a class="btn primary full" href="<?= h(leadstar_sign_in_url()) ?>" target="_blank" rel="noopener noreferrer">Upgrade to Pro</a>
        </article>
    </div>
</section>

<section class="section">
    <div class="glass page-card compare-card">
        <h2>What changes when you upgrade</h2>
        <div class="table-wrap">
            <table class="feature-table">
                <thead>
                    <tr>
                        <th>Feature</th>
                        <th>Free</th>
                        <th>Pro</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Daily lead extraction</td>
                        <td>Limited</td>
                        <td>Unlimited</td>
                    </tr>
                    <tr>
                        <td>File exports</td>
                        <td>CSV</td>
                        <td>CSV + Excel</td>
                    </tr>
                    <tr>
                        <td>Automation</td>
                        <td>No</td>
                        <td>Yes</td>
                    </tr>
                    <tr>
                        <td>Best for</td>
                        <td>Individuals</td>
                        <td>Teams and agencies</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<section class="section">
    <div class="glass page-card faq-card">
        <h2>Pricing FAQ</h2>
        <div class="faq-grid">
            <div>
                <div class="faq-q">Can I cancel anytime?</div>
                <div class="faq-a">Yes. You can cancel at any time and keep using your plan until the billing period ends.</div>
            </div>
            <div>
                <div class="faq-q">Do you offer yearly pricing?</div>
                <div class="faq-a">Yearly plans are coming soon. Contact support if your team needs annual billing today.</div>
            </div>
            <div>
                <div class="faq-q">Is there a trial?</div>
                <div class="faq-a">The Free plan is your trial. It includes real extraction tools so you can evaluate before upgrading.</div>
            </div>
            <div>
                <div class="faq-q">Will my data be shared?</div>
                <div class="faq-a">No. Your extracted records stay in your browser unless you export them yourself.</div>
            </div>
        </div>
    </div>
</section>

<?php page_footer(); ?>
