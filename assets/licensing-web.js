(function() {
  const SESSION_KEY = 'leadstar_web_session';

  function qs(selector) {
    return document.querySelector(selector);
  }

  function formatDate(value) {
    if (!value) return 'n/a';
    const d = new Date(value);
    if (Number.isNaN(d.getTime())) return 'n/a';
    return d.toLocaleString();
  }

  function setText(selector, value) {
    const el = qs(selector);
    if (el) el.textContent = value;
  }

  function syncTopbarAuth(session) {
    const signInLinks = document.querySelectorAll('[data-signin-link], [data-drawer-signin]');
    const profileShells = document.querySelectorAll('[data-profile-shell]');
    const drawerProfileItems = document.querySelectorAll('[data-drawer-profile]');
    const names = document.querySelectorAll('[data-profile-name]');
    const emails = document.querySelectorAll('[data-profile-email]');
    const initials = document.querySelectorAll('[data-profile-initial]');
    const badges = document.querySelectorAll('[data-topbar-plan]');

    if (!session || !session.user) {
      signInLinks.forEach(function(el) { el.hidden = false; });
      profileShells.forEach(function(el) { el.hidden = true; });
      drawerProfileItems.forEach(function(el) { el.hidden = true; });
      badges.forEach(function(el) { el.hidden = true; });
      return;
    }

    const email = session.user.email || '';
    const name = (session.user.user_metadata && (session.user.user_metadata.full_name || session.user.user_metadata.name)) || (email ? email.split('@')[0] : 'User');
    const initial = (name[0] || 'U').toUpperCase();

    signInLinks.forEach(function(el) { el.hidden = true; });
    profileShells.forEach(function(el) { el.hidden = false; });
    drawerProfileItems.forEach(function(el) { el.hidden = false; });
    names.forEach(function(el) { el.textContent = name; });
    emails.forEach(function(el) { el.textContent = email; });
    initials.forEach(function(el) { el.textContent = initial; });
  }

  function renderTopbarPlan(planValue) {
    const badges = document.querySelectorAll('[data-topbar-plan]');
    if (!badges.length) return;
    const normalized = String(planValue || 'free').toLowerCase() === 'pro' ? 'Pro' : 'Free';
    badges.forEach(function(el) {
      el.textContent = 'Current Package: ' + normalized;
      el.hidden = false;
    });
  }

  function setMessage(text, isError) {
    const el = qs('[data-plan-message]');
    if (!el) return;
    el.textContent = text;
    el.classList.toggle('sign-error', !!isError);
    el.classList.toggle('sign-ok', !isError);
  }

  function setAdminMessage(text, isError) {
    const el = qs('[data-admin-message]');
    if (!el) return;
    el.textContent = text;
    el.classList.toggle('sign-error', !!isError);
    el.classList.toggle('sign-ok', !isError);
  }

  function normalizeUsage(usage) {
    if (!usage) return 'n/a';
    if (typeof usage === 'string') return usage;
    if (typeof usage === 'number') return String(usage);
    if (typeof usage === 'object') {
      const keys = Object.keys(usage);
      if (!keys.length) return 'n/a';
      return keys.map(function(k) { return k + ': ' + usage[k]; }).join(' | ');
    }
    return 'n/a';
  }

  function readLocalSession() {
    try {
      const raw = localStorage.getItem(SESSION_KEY);
      if (!raw) return null;
      const s = JSON.parse(raw);
      return s && s.access_token ? s : null;
    } catch (_) {
      return null;
    }
  }

  function clearLocalSession() {
    localStorage.removeItem(SESSION_KEY);
  }

  function decodeJwtPayload(token) {
    if (!token) return {};
    try {
      const part = token.split('.')[1] || '';
      const normalized = part.replace(/-/g, '+').replace(/_/g, '/');
      return JSON.parse(atob(normalized));
    } catch (_) {
      return {};
    }
  }

  const cfg = window.LEADSTAR_WEB_CONFIG || {};
  const supabaseUrl = cfg.supabaseUrl || '';
  const supabaseAnonKey = cfg.supabaseAnonKey || '';
  const adminUpgradeEnabled = !!cfg.adminUpgradeEnabled;
  const adminUpgradeKey = cfg.adminUpgradeKey || '';
  const siteBase = cfg.siteBase || '/leadstar/';

  if (!supabaseUrl || !supabaseAnonKey || !window.supabase || !window.supabase.createClient) {
    setMessage('Dashboard configuration is missing.', true);
    return;
  }

  const client = window.supabase.createClient(supabaseUrl, supabaseAnonKey);

  async function hydrateSession() {
    const local = readLocalSession();
    if (local && local.access_token && local.refresh_token) {
      try {
        await client.auth.setSession({
          access_token: local.access_token,
          refresh_token: local.refresh_token
        });
      } catch (_) {
        // keep local fallback path below
      }
    }

    const { data } = await client.auth.getSession();
    if (data && data.session && data.session.user) {
      try {
        localStorage.setItem(SESSION_KEY, JSON.stringify({
          access_token: data.session.access_token,
          refresh_token: data.session.refresh_token || '',
          expires_at: data.session.expires_at ? String(data.session.expires_at) : '',
          email: data.session.user && data.session.user.email ? data.session.user.email : '',
          name: data.session.user && data.session.user.user_metadata && (data.session.user.user_metadata.full_name || data.session.user.user_metadata.name) ? (data.session.user.user_metadata.full_name || data.session.user.user_metadata.name) : ''
        }));
      } catch (_) {
        // ignore storage failures
      }

      return data.session;
    }

    if (local && local.access_token) {
      try {
        const userResp = await client.auth.getUser(local.access_token);
        if (userResp && userResp.data && userResp.data.user) {
          return {
            access_token: local.access_token,
            refresh_token: local.refresh_token || '',
            user: userResp.data.user
          };
        }
      } catch (_) {
        // ignore and fallback to decoded token below
      }

      const payload = decodeJwtPayload(local.access_token);
      if (payload && payload.sub) {
        return {
          access_token: local.access_token,
          refresh_token: local.refresh_token || '',
          user: {
            id: payload.sub,
            email: local.email || payload.email || '',
            user_metadata: {
              full_name: local.name || ''
            }
          }
        };
      }
    }

    return null;
  }

  async function fetchLicenseStatus(userId, accessToken) {
    try {
      const resp = await fetch(supabaseUrl + '/functions/v1/get_license_status', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'apikey': supabaseAnonKey,
          'Authorization': 'Bearer ' + accessToken
        },
        body: JSON.stringify({ user_id: userId })
      });

      if (resp.ok) {
        const payload = await resp.json();
        return {
          plan: payload.plan || (payload.profile && payload.profile.plan) || 'free',
          pro_expires_at: payload.pro_expires_at || (payload.profile && payload.profile.pro_expires_at) || null,
          last_verified_at: payload.last_verified_at || (payload.profile && payload.profile.last_verified_at) || null,
          usage_today: payload.usage_today || payload.usage || null
        };
      }
    } catch (_) {
      // fallback below
    }

    const { data, error } = await client
      .from('profiles')
      .select('plan, pro_expires_at, last_verified_at')
      .eq('id', userId)
      .single();

    if (error) {
      return {
        plan: 'free',
        pro_expires_at: null,
        last_verified_at: null,
        usage_today: null
      };
    }

    return {
      plan: data.plan || 'free',
      pro_expires_at: data.pro_expires_at || null,
      last_verified_at: data.last_verified_at || null,
      usage_today: null
    };
  }

  async function renderStatus() {
    setMessage('Refreshing status...', false);

    const session = await hydrateSession();
    if (!session || !session.user) {
      syncTopbarAuth(null);
      setText('[data-plan-email]', '--');
      setText('[data-plan-name]', 'Not signed in');
      setText('[data-plan-expiry]', 'n/a');
      setText('[data-plan-verified]', 'n/a');
      setText('[data-plan-usage]', 'n/a');
      setMessage('Please sign in first.', true);
      return null;
    }

    syncTopbarAuth(session);
    setText('[data-plan-email]', session.user.email || '--');

    try {
      const status = await fetchLicenseStatus(session.user.id, session.access_token);
      setText('[data-plan-name]', String(status.plan || 'free').toUpperCase());
      setText('[data-plan-expiry]', formatDate(status.pro_expires_at));
      setText('[data-plan-verified]', formatDate(status.last_verified_at));
      setText('[data-plan-usage]', normalizeUsage(status.usage_today));
      renderTopbarPlan(status.plan || 'free');
      setMessage('Status updated.', false);
      return { session: session, status: status };
    } catch (err) {
      renderTopbarPlan('free');
      setMessage(err.message || 'Failed to fetch status.', true);
      return { session: session, status: null };
    }
  }

  async function runAdminUpgrade() {
    const state = await renderStatus();
    if (!state || !state.session || !state.session.user) {
      setAdminMessage('Sign in first.', true);
      return;
    }

    const keyInput = qs('#adminUpgradeKey');
    const expiryInput = qs('#proExpiry');
    const key = keyInput ? keyInput.value.trim() : '';

    if (!key) {
      setAdminMessage('Enter admin key.', true);
      return;
    }

    if (adminUpgradeKey && key !== adminUpgradeKey) {
      setAdminMessage('Admin key does not match website setting.', true);
      return;
    }

    setAdminMessage('Applying Pro plan...', false);

    const payload = {
      user_id: state.session.user.id,
      plan: 'pro'
    };

    if (expiryInput && expiryInput.value) {
      payload.pro_expires_at = new Date(expiryInput.value).toISOString();
    }

    const resp = await fetch(supabaseUrl + '/functions/v1/admin_set_plan', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'apikey': supabaseAnonKey,
        'Authorization': 'Bearer ' + state.session.access_token,
        'x-admin-key': key
      },
      body: JSON.stringify(payload)
    });

    const body = await resp.json().catch(function() { return {}; });

    if (!resp.ok || !body.ok) {
      setAdminMessage(body.error || 'Upgrade failed.', true);
      return;
    }

    setAdminMessage('Plan updated to Pro.', false);
    await renderStatus();
  }

  const refreshButtons = document.querySelectorAll('[data-refresh-plan]');
  refreshButtons.forEach(function(btn) {
    btn.addEventListener('click', function() {
      renderStatus();
    });
  });

  const signoutButtons = document.querySelectorAll('[data-signout]');
  signoutButtons.forEach(function(btn) {
    btn.addEventListener('click', async function() {
      await client.auth.signOut();
      clearLocalSession();
      window.location.href = siteBase;
    });
  });

  const toggleAdmin = qs('[data-toggle-admin]');
  const adminPanel = qs('[data-admin-upgrade]');
  if (adminUpgradeEnabled && toggleAdmin && adminPanel) {
    toggleAdmin.addEventListener('click', function() {
      adminPanel.hidden = !adminPanel.hidden;
    });
  }

  const adminUpgradeBtn = qs('[data-admin-upgrade-btn]');
  if (adminUpgradeBtn) {
    adminUpgradeBtn.addEventListener('click', function() {
      runAdminUpgrade();
    });
  }

  renderStatus();
})();
