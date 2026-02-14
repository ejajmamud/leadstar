// Core functionality
(function() {
    'use strict';

    // Mobile navigation
    const initMobileNav = () => {
        const burger = document.querySelector('[data-burger]');
        const drawer = document.querySelector('[data-drawer]');
        
        if (burger && drawer) {
            burger.addEventListener('click', () => {
                const expanded = burger.getAttribute('aria-expanded') === 'true';
                burger.setAttribute('aria-expanded', String(!expanded));
                drawer.setAttribute('aria-hidden', String(expanded));
            });
        }
    };

    // Copy code blocks
    const initCodeCopy = () => {
        document.querySelectorAll('pre code').forEach(block => {
            const button = document.createElement('button');
            button.className = 'copy-button';
            button.textContent = 'Copy';
            button.setAttribute('aria-label', 'Copy code to clipboard');
            
            const pre = block.parentNode;
            pre.style.position = 'relative';
            pre.appendChild(button);
            
            button.addEventListener('click', async () => {
                try {
                    await navigator.clipboard.writeText(block.textContent);
                    button.textContent = 'Copied!';
                    setTimeout(() => {
                        button.textContent = 'Copy';
                    }, 2000);
                } catch (err) {
                    button.textContent = 'Failed';
                }
            });
        });
    };

    // Smooth scroll for anchor links
    const initSmoothScroll = () => {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href === '#') return;
                
                const target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    };

    // Initialize all
    document.addEventListener('DOMContentLoaded', () => {
        initMobileNav();
        initCodeCopy();
        initSmoothScroll();
    });
})();