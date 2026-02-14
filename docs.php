<?php
require_once 'header-functions.php';
page_header('LeadStar - Documentation', $_SERVER['REQUEST_URI'] ?? '/docs');
?>

<section class="page-hero page-hero-left">
    <p class="hero-kicker">LeadStar Public Documentation</p>
    <h1>Complete guide for teams using LeadStar.</h1>
    <p class="hero-copy">Last updated: February 13, 2026</p>
</section>

<section class="section">
    <div class="glass page-card">
        <h2>1) Product Summary</h2>
        <p>LeadStar is a Chrome extension for fast web data extraction. It helps teams collect and export Google Maps leads, list and table data, page details, emails, images, page text, screenshots, and PDF files.</p>
        <p>LeadStar runs locally in your browser and is designed for lead generation, market research, and workflow automation.</p>
    </div>
</section>

<section class="section">
    <div class="grid2">
        <article class="glass page-card">
            <h2>2) Who It Is For</h2>
            <ul class="bullets">
                <li>Sales and lead generation teams</li>
                <li>Agencies and growth teams</li>
                <li>Researchers and analysts</li>
                <li>Operations teams collecting structured web data</li>
            </ul>
        </article>

        <article class="glass page-card">
            <h2>3) Core Features</h2>
            <ul class="bullets">
                <li>Maps Leads Extractor</li>
                <li>List and Table Extractor</li>
                <li>Page Details Extractor</li>
                <li>Email Extractor</li>
                <li>Image Downloader</li>
                <li>Page Text Extractor</li>
                <li>Page to PDF</li>
                <li>Local Data and History</li>
            </ul>
        </article>
    </div>
</section>

<section class="section">
    <div class="grid2">
        <article class="glass page-card feature-card">
            <h3>Maps Leads Extractor</h3>
            <ul class="bullets">
                <li>Scrapes Google Maps business listings</li>
                <li>Tracks live progress in real time</li>
                <li>Supports pause, resume, and stop</li>
                <li>Saves output for later export</li>
            </ul>
        </article>

        <article class="glass page-card feature-card">
            <h3>List and Table Extractor</h3>
            <ul class="bullets">
                <li>Click-to-select list and table scraping</li>
                <li>Infinite-scroll extraction</li>
                <li>Multi-page extraction</li>
                <li>JSON output and dataset history</li>
            </ul>
        </article>

        <article class="glass page-card feature-card">
            <h3>Page Details Extractor</h3>
            <ul class="bullets">
                <li>Extracts custom fields from multiple URLs</li>
                <li>Uses selector-based rules</li>
                <li>Useful for bulk metadata capture</li>
            </ul>
        </article>

        <article class="glass page-card feature-card">
            <h3>Email, Image, Text and PDF Tools</h3>
            <ul class="bullets">
                <li>Deduplicated email extraction</li>
                <li>Image scan including lazy-loaded sources</li>
                <li>Visible page text extraction</li>
                <li>Page-to-PDF generation</li>
            </ul>
        </article>
    </div>
</section>

<section class="section">
    <div class="glass page-card compare-card">
        <h2>4) Freemium Plans</h2>
        <p>LeadStar uses Free and Pro plans.</p>
        <div class="table-wrap">
            <table class="feature-table">
                <thead>
                    <tr>
                        <th>Action</th>
                        <th>Free Plan</th>
                        <th>Pro Plan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>maps_leads</td>
                        <td>20/day</td>
                        <td>Higher or unlimited</td>
                    </tr>
                    <tr>
                        <td>list_extract_rows</td>
                        <td>500/day</td>
                        <td>Higher or unlimited</td>
                    </tr>
                    <tr>
                        <td>page_to_pdf</td>
                        <td>5/day</td>
                        <td>Higher or unlimited</td>
                    </tr>
                    <tr>
                        <td>export_excel</td>
                        <td>Blocked</td>
                        <td>Allowed</td>
                    </tr>
                    <tr>
                        <td>automation_run</td>
                        <td>Blocked</td>
                        <td>Allowed</td>
                    </tr>
                    <tr>
                        <td>export_csv</td>
                        <td>Allowed</td>
                        <td>Allowed</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p class="muted">Usage limits are validated by license checks. If verification is unavailable too long, behavior safely falls back to Free rules.</p>
    </div>
</section>

<section class="section">
    <div class="grid2">
        <article class="glass page-card">
            <h2>5) Licensing and Account</h2>
            <ul class="bullets">
                <li>Sign up</li>
                <li>Sign in</li>
                <li>Sign out</li>
                <li>Refresh license status</li>
            </ul>
            <p>Header badge shows active plan: Free or Pro. Settings show plan, verification status, and usage counters.</p>
            <p>Pro status can be assigned via the license backend.</p>
        </article>

        <article class="glass page-card">
            <h2>6) Exports</h2>
            <ul class="bullets">
                <li>CSV</li>
                <li>Excel (.xls) plan-dependent</li>
                <li>TXT</li>
                <li>PNG</li>
                <li>PDF</li>
            </ul>
        </article>
    </div>
</section>

<section class="section">
    <div class="grid2">
        <article class="glass page-card">
            <h2>7) Installation Guide</h2>
            <ol class="number-list">
                <li>Open Chrome and go to chrome://extensions.</li>
                <li>Enable Developer mode.</li>
                <li>Click Load unpacked.</li>
                <li>Select the LeadStar extension folder.</li>
                <li>Pin the extension (optional).</li>
                <li>Open LeadStar side panel and start extracting.</li>
            </ol>
        </article>

        <article class="glass page-card">
            <h2>8) Quick Start</h2>
            <ol class="number-list">
                <li>Open a normal https page.</li>
                <li>Open LeadStar.</li>
                <li>Choose a tool from Menu.</li>
                <li>Run extraction.</li>
                <li>Review output.</li>
                <li>Export or save dataset.</li>
            </ol>
        </article>
    </div>
</section>

<section class="section">
    <div class="grid2">
        <article class="glass page-card">
            <h2>9) System Requirements</h2>
            <ul class="bullets">
                <li>Google Chrome (latest stable recommended)</li>
                <li>Access to normal web pages (http or https)</li>
            </ul>
        </article>

        <article class="glass page-card">
            <h2>10) Permissions Explained</h2>
            <ul class="bullets">
                <li>activeTab: run extraction on the active tab</li>
                <li>scripting: execute extraction scripts</li>
                <li>storage: save settings, history, and datasets</li>
                <li>downloads: export files</li>
                <li>tabs: manage helper tabs for extraction</li>
                <li>sidePanel: open side panel interface</li>
                <li>debugger: required for page-to-PDF</li>
            </ul>
        </article>
    </div>
</section>

<section class="section">
    <div class="grid2">
        <article class="glass page-card">
            <h2>11) Privacy and Data Handling</h2>
            <ul class="bullets">
                <li>Stores local settings, local dataset history, and local extraction outputs</li>
                <li>Does not send scraped lead payloads to licensing services</li>
                <li>Core extraction output stays local unless user exports or downloads</li>
            </ul>
            <p class="muted">Extracted website data may contain personal or business information. Users are responsible for lawful and compliant use.</p>
        </article>

        <article class="glass page-card">
            <h2>12) Security Principles</h2>
            <ul class="bullets">
                <li>Least-privilege licensing model</li>
                <li>Authenticated usage checks</li>
                <li>Safe fallback behavior when license cannot be verified</li>
                <li>No secret server keys in extension client code</li>
            </ul>
        </article>
    </div>
</section>

<section class="section">
    <div class="grid2">
        <article class="glass page-card">
            <h2>13) Known Limits</h2>
            <ul class="bullets">
                <li>Internal browser pages are not extractable</li>
                <li>Some websites with heavy anti-automation UI may require manual retries</li>
                <li>Page structure changes can affect selector-based extraction</li>
            </ul>
        </article>

        <article class="glass page-card">
            <h2>14) Troubleshooting</h2>
            <ul class="bullets">
                <li>Open a regular website tab when extraction is blocked</li>
                <li>Check sign-in credentials and network if login fails</li>
                <li>Retry on a fully loaded page if no data is extracted</li>
                <li>Some export options may depend on your current plan</li>
            </ul>
        </article>
    </div>
</section>

<section class="section">
    <div class="glass page-card faq-card">
        <h2>15) FAQ</h2>
        <div class="faq-grid">
            <div>
                <div class="faq-q">Does LeadStar work only in popup?</div>
                <div class="faq-a">No. Side panel is the primary usage surface.</div>
            </div>
            <div>
                <div class="faq-q">Can I export to CSV?</div>
                <div class="faq-a">Yes, based on current plan and context.</div>
            </div>
            <div>
                <div class="faq-q">Can I run it offline?</div>
                <div class="faq-a">Core extraction is local. Licensing uses cached verification for a limited time.</div>
            </div>
            <div>
                <div class="faq-q">Is there a Pro plan?</div>
                <div class="faq-a">Yes. Pro unlocks gated features and higher usage allowances.</div>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="grid2">
        <article class="glass page-card">
            <h2>16) Suggested Website Page Structure</h2>
            <ol class="number-list">
                <li>Home</li>
                <li>Features</li>
                <li>Pricing</li>
                <li>Install Guide</li>
                <li>Documentation</li>
                <li>FAQ</li>
                <li>Privacy Policy</li>
                <li>Terms of Use</li>
                <li>Contact/Support</li>
            </ol>
        </article>

        <article class="glass page-card">
            <h2>17) Website Copy Snippets</h2>
            <p><strong>Hero Headline:</strong> Extract Leads From Any Website, Faster.</p>
            <p><strong>Hero Subtext:</strong> LeadStar gives you Maps scraping, list extraction, page exports, and clean data workflows directly in Chrome.</p>
            <p><strong>CTA:</strong> Get Started, View Features, Upgrade to Pro.</p>
        </article>
    </div>
</section>

<section class="section">
    <div class="grid2">
        <article class="glass page-card">
            <h2>18) Support</h2>
            <p>For support and bug reports, share:</p>
            <ul class="bullets">
                <li>Browser version</li>
                <li>Tool used</li>
                <li>Page URL pattern (no sensitive credentials)</li>
                <li>Error screenshot or message</li>
                <li>Steps to reproduce</li>
            </ul>
        </article>

        <article class="glass page-card">
            <h2>19) Legal Notice</h2>
            <p>Users are responsible for complying with website terms of service, data protection laws, and local regulations regarding scraping and outreach.</p>

            <h2 class="mt-md">20) Versioning Note</h2>
            <p>This public documentation reflects current extension behavior and can be versioned alongside release notes.</p>
        </article>
    </div>
</section>

<?php page_footer(); ?>
