(function() {
  function loadScript(src) {
    return new Promise(function(resolve, reject) {
      const existing = document.querySelector('script[src="' + src + '"]');
      if (existing) {
        existing.addEventListener('load', function() { resolve(); }, { once: true });
        if (existing.dataset.loaded === 'true') resolve();
        return;
      }
      const s = document.createElement('script');
      s.src = src;
      s.defer = true;
      s.onload = function() {
        s.dataset.loaded = 'true';
        resolve();
      };
      s.onerror = reject;
      document.head.appendChild(s);
    });
  }

  async function init() {
    if (!document.querySelector('[data-plan-ui]')) {
      return;
    }

    const base = window.location.pathname.startsWith('/leadstar/') || window.location.pathname === '/leadstar'
      ? '/leadstar'
      : '';

    try {
      await loadScript('https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2');
      await loadScript(base + '/assets/licensing-web.js');
    } catch (err) {
      console.error('Failed to load licensing modules', err);
    }
  }

  init();
})();
