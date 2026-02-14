// Authentication handling
(function() {
    'use strict';

    const AuthHandler = {
        init() {
            this.processHashFragment();
            this.setupMessageListener();
        },

        processHashFragment() {
            const hash = window.location.hash.substring(1);
            if (!hash) return;

            const params = new URLSearchParams(hash);
            const accessToken = params.get('access_token');
            
            if (accessToken) {
                this.sendToExtension({
                    type: 'LEADSTAR_AUTH',
                    payload: {
                        access_token: accessToken,
                        refresh_token: params.get('refresh_token'),
                        expires_at: params.get('expires_at'),
                        type: params.get('type')
                    }
                });
                
                // Clean URL
                history.replaceState({}, document.title, window.location.pathname);
                this.updateUI('success', 'Authentication successful');
            }
        },

        sendToExtension(message) {
            window.postMessage(message, '*');
        },

        setupMessageListener() {
            window.addEventListener('message', (event) => {
                if (event.data.type === 'LEADSTAR_AUTH_RESPONSE') {
                    this.handleExtensionResponse(event.data);
                }
            });
        },

        handleExtensionResponse(data) {
            if (data.success) {
                this.updateUI('success', 'Extension updated');
            } else {
                this.updateUI('error', 'Failed to update extension');
            }
        },

        updateUI(status, message) {
            const statusEl = document.getElementById('auth-status');
            if (!statusEl) return;

            statusEl.innerHTML = `
                <div style="font-size: 3rem; margin-bottom: 1.5rem;">
                    ${status === 'success' ? '✓' : '⚠️'}
                </div>
                <h2 style="margin-bottom: 0.5rem; ${status === 'error' ? 'color: var(--danger);' : ''}">
                    ${message}
                </h2>
            `;
        }
    };

    // Initialize on page load
    if (document.getElementById('auth-status')) {
        AuthHandler.init();
    }
})();