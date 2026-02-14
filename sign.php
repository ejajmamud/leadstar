<?php
require_once 'header-functions.php';
require_once __DIR__ . '/includes/config.php';

$supabaseUrl = defined('SUPABASE_URL') ? SUPABASE_URL : '';
$supabaseAnonKey = defined('SUPABASE_ANON_KEY') ? SUPABASE_ANON_KEY : '';
$redirectUrl = defined('SUPABASE_REDIRECT_URL') ? SUPABASE_REDIRECT_URL : leadstar_url('/auth');
$envAnon = getenv('LEADSTAR_SUPABASE_ANON_KEY');
if ($envAnon !== false && $envAnon !== '') {
    $supabaseAnonKey = $envAnon;
}
$isConfigured = $supabaseUrl !== '' && $supabaseAnonKey !== '';

page_header('LeadStar - Sign In', $_SERVER['REQUEST_URI'] ?? '/sign');
?>

<section class="auth-wrap">
    <div class="glass page-card auth-status-card">
        <h1 class="auth-title">Sign in to LeadStar</h1>
        <p class="muted">Use your account to unlock plan-based limits and features.</p>

        <?php if (!$isConfigured): ?>
            <div class="sign-alert">Sign-in is currently unavailable. Please contact support.</div>
        <?php endif; ?>

        <form id="signInForm" class="sign-form" novalidate>
            <label class="sign-label" for="email">Email</label>
            <input class="sign-input" type="email" id="email" name="email" autocomplete="email" required />

            <label class="sign-label" for="password">Password</label>
            <input class="sign-input" type="password" id="password" name="password" autocomplete="current-password" required />

            <div class="sign-actions">
                <button class="btn primary full" type="submit" id="signSubmit">Sign In</button>
                <button class="btn ghost full" type="button" id="signUp">Create Account</button>
            </div>
            <p id="signMsg" class="sign-msg muted">Enter your credentials to continue.</p>
        </form>
    </div>
</section>

<script>
(function() {
  const SUPABASE_URL = <?php echo json_encode($supabaseUrl, JSON_UNESCAPED_SLASHES); ?>;
  const SUPABASE_ANON_KEY = <?php echo json_encode($supabaseAnonKey, JSON_UNESCAPED_SLASHES); ?>;
  const CALLBACK_URL = <?php echo json_encode($redirectUrl, JSON_UNESCAPED_SLASHES); ?>;
  const IS_CONFIGURED = <?php echo $isConfigured ? 'true' : 'false'; ?>;

  const form = document.getElementById('signInForm');
  const msg = document.getElementById('signMsg');
  const btn = document.getElementById('signSubmit');
  const signUpBtn = document.getElementById('signUp');

  if (!form || !msg || !btn || !signUpBtn) return;

  function setMessage(text, isError) {
    msg.textContent = text;
    msg.classList.toggle('sign-error', Boolean(isError));
    msg.classList.toggle('sign-ok', !isError);
  }

  form.addEventListener('submit', async function(e) {
    e.preventDefault();

    if (!IS_CONFIGURED || !SUPABASE_URL || !SUPABASE_ANON_KEY) {
      setMessage('Sign-in is unavailable right now. Please contact support.', true);
      return;
    }

    const email = form.email.value.trim();
    const password = form.password.value;

    if (!email || !password) {
      setMessage('Please enter both email and password.', true);
      return;
    }

    btn.disabled = true;
    setMessage('Signing in...', false);

    try {
      const response = await fetch(SUPABASE_URL + '/auth/v1/token?grant_type=password', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'apikey': SUPABASE_ANON_KEY
        },
        body: JSON.stringify({ email: email, password: password })
      });

      const data = await response.json();

      if (!response.ok || !data.access_token) {
        const message = data.error_description || data.msg || 'Invalid credentials.';
        setMessage(message, true);
        btn.disabled = false;
        return;
      }

      setMessage('Sign-in successful. Redirecting...', false);

      const hash = new URLSearchParams({
        access_token: data.access_token,
        refresh_token: data.refresh_token || '',
        expires_at: data.expires_at ? String(data.expires_at) : ''
      }).toString();

      window.location.href = CALLBACK_URL + '#' + hash;
    } catch (err) {
      setMessage('Network error. Please try again.', true);
      btn.disabled = false;
    }
  });

  signUpBtn.addEventListener('click', async function() {
    if (!IS_CONFIGURED || !SUPABASE_URL || !SUPABASE_ANON_KEY) {
      setMessage('Sign-up is unavailable right now. Please contact support.', true);
      return;
    }

    const email = form.email.value.trim();
    const password = form.password.value;

    if (!email || !password) {
      setMessage('Enter email and password, then click Create Account.', true);
      return;
    }

    btn.disabled = true;
    signUpBtn.disabled = true;
    setMessage('Creating account...', false);

    try {
      const response = await fetch(SUPABASE_URL + '/auth/v1/signup', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'apikey': SUPABASE_ANON_KEY
        },
        body: JSON.stringify({
          email: email,
          password: password,
          options: {
            emailRedirectTo: CALLBACK_URL
          }
        })
      });

      const data = await response.json();

      if (!response.ok) {
        const message = data.error_description || data.msg || 'Could not create account.';
        setMessage(message, true);
        btn.disabled = false;
        signUpBtn.disabled = false;
        return;
      }

      if (data.access_token) {
        setMessage('Account created. Redirecting...', false);
        const hash = new URLSearchParams({
          access_token: data.access_token,
          refresh_token: data.refresh_token || '',
          expires_at: data.expires_at ? String(data.expires_at) : ''
        }).toString();
        window.location.href = CALLBACK_URL + '#' + hash;
        return;
      }

      setMessage('Account created. Check your email to verify, then sign in.', false);
      btn.disabled = false;
      signUpBtn.disabled = false;
    } catch (err) {
      setMessage('Network error. Please try again.', true);
      btn.disabled = false;
      signUpBtn.disabled = false;
    }
  });
})();
</script>

<?php page_footer(); ?>
