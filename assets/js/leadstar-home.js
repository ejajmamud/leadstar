(function() {
  const SESSION_KEY = 'leadstar_web_session';

  function parseJwtPayload(token) {
    try {
      const part = token.split('.')[1];
      const base64 = part.replace(/-/g, '+').replace(/_/g, '/');
      return JSON.parse(atob(base64));
    } catch (e) {
      return {};
    }
  }

  function getSession() {
    try {
      const raw = localStorage.getItem(SESSION_KEY);
      if (!raw) return null;
      const session = JSON.parse(raw);
      if (!session || !session.access_token) return null;
      return session;
    } catch (e) {
      return null;
    }
  }

  function clearSession() {
    localStorage.removeItem(SESSION_KEY);
  }

  function basePath() {
    return window.location.pathname.startsWith('/leadstar/') || window.location.pathname === '/leadstar' ? '/leadstar' : '';
  }

  function initProfileUI() {
    const session = getSession();
    const signInLinks = document.querySelectorAll('[data-signin-link], [data-drawer-signin]');
    const profileShells = document.querySelectorAll('[data-profile-shell]');
    const drawerProfileItems = document.querySelectorAll('[data-drawer-profile]');
    const emails = document.querySelectorAll('[data-profile-email]');
    const names = document.querySelectorAll('[data-profile-name]');
    const initials = document.querySelectorAll('[data-profile-initial]');
    const toggles = document.querySelectorAll('[data-profile-toggle]');
    const menus = document.querySelectorAll('[data-profile-menu]');
    const signouts = document.querySelectorAll('[data-signout]');

    if (session && session.access_token) {
      signInLinks.forEach(function(el) { el.hidden = true; });
      profileShells.forEach(function(el) { el.hidden = false; });
      drawerProfileItems.forEach(function(el) { el.hidden = false; });

      const email = session.email || 'user@leadstar.app';
      const name = session.name || email.split('@')[0] || 'User';
      const initial = (name[0] || 'U').toUpperCase();

      emails.forEach(function(el) { el.textContent = email; });
      names.forEach(function(el) { el.textContent = name; });
      initials.forEach(function(el) { el.textContent = initial; });
    } else {
      signInLinks.forEach(function(el) { el.hidden = false; });
      profileShells.forEach(function(el) { el.hidden = true; });
      drawerProfileItems.forEach(function(el) { el.hidden = true; });
    }

    toggles.forEach(function(toggle) {
      toggle.addEventListener('click', function() {
        const menu = toggle.parentElement.querySelector('[data-profile-menu]');
        if (!menu) return;
        const willOpen = menu.hidden;
        menus.forEach(function(m) { m.hidden = true; });
        toggles.forEach(function(t) { t.setAttribute('aria-expanded', 'false'); });
        menu.hidden = !willOpen;
        toggle.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
      });
    });

    document.addEventListener('click', function(e) {
      const inShell = e.target.closest('[data-profile-shell]');
      if (!inShell) {
        menus.forEach(function(m) { m.hidden = true; });
        toggles.forEach(function(t) { t.setAttribute('aria-expanded', 'false'); });
      }
    });

    signouts.forEach(function(btn) {
      btn.addEventListener('click', function() {
        clearSession();
        window.location.href = basePath() + '/';
      });
    });
  }

  const burger = document.querySelector('[data-burger]');
  const drawer = document.querySelector('[data-drawer]');
  const closeBtn = document.querySelector('[data-close]');

  if (burger && drawer && closeBtn) {
    burger.addEventListener('click', function() {
      const expanded = burger.getAttribute('aria-expanded') === 'true' ? false : true;
      burger.setAttribute('aria-expanded', String(expanded));
      drawer.setAttribute('aria-hidden', String(!expanded));
    });

    closeBtn.addEventListener('click', function() {
      burger.setAttribute('aria-expanded', 'false');
      drawer.setAttribute('aria-hidden', 'true');
    });

    drawer.addEventListener('click', function(e) {
      if (e.target === drawer) {
        burger.setAttribute('aria-expanded', 'false');
        drawer.setAttribute('aria-hidden', 'true');
      }
    });
  }

  initProfileUI();
})();

(function() {
  const path = window.location.pathname;
  const isAuthRoute = path.endsWith('/auth') || path.endsWith('/leadstar/auth');

  if (!isAuthRoute) {
    return;
  }

  const hash = window.location.hash.substring(1);
  const params = new URLSearchParams(hash);
  const accessToken = params.get('access_token');
  const refreshToken = params.get('refresh_token');
  const expiresAt = params.get('expires_at');

  const dot = document.getElementById('stDot');
  const title = document.getElementById('stTitle');
  const desc = document.getElementById('stDesc');

  if (!dot || !title || !desc) {
    return;
  }

  if (accessToken) {
    let payload = {};
    try {
      const part = accessToken.split('.')[1];
      payload = JSON.parse(atob(part.replace(/-/g, '+').replace(/_/g, '/')));
    } catch (e) {
      payload = {};
    }

    localStorage.setItem('leadstar_web_session', JSON.stringify({
      access_token: accessToken,
      refresh_token: refreshToken || '',
      expires_at: expiresAt || '',
      email: payload.email || '',
      name: payload.user_metadata && payload.user_metadata.full_name ? payload.user_metadata.full_name : ''
    }));

    dot.classList.add('ok');
    title.textContent = 'Sign-in complete';
    desc.textContent = 'Your profile is now active on this website and in the extension.';

    window.postMessage({ type: 'LEADSTAR_AUTH', payload: { access_token: accessToken } }, '*');

    setTimeout(function() {
      title.textContent = 'Done';
      desc.textContent = 'You can close this tab or continue browsing.';
    }, 700);

    history.replaceState({}, document.title, window.location.pathname);
  } else {
    dot.classList.add('warn');
    title.textContent = 'No token found';
    desc.textContent = 'Open this page through the sign-in flow to complete authentication.';
  }
})();
