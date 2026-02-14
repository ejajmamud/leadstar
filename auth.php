<?php
require_once 'header-functions.php';
page_header('LeadStar - Auth Callback', $_SERVER['REQUEST_URI'] ?? '/auth');
?>

<section class="auth-wrap">
    <div class="glass page-card auth-status-card">
        <div class="auth-row">
            <div id="stDot" class="status-dot"></div>
            <div>
                <h1 id="stTitle" class="auth-title">Checking sign in</h1>
                <p id="stDesc" class="muted">Please wait while we connect your account to LeadStar.</p>
            </div>
        </div>
        <div class="hint-line">This page only confirms sign in and sends your token to the extension.</div>
    </div>
</section>

<?php page_footer(); ?>
