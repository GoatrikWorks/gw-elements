/**
 * Product Tabs Widget JavaScript
 * Handles tab switching with accessibility support
 */

(function($) {
    'use strict';

    const ProductTabs = {
        init() {
            this.initAll();
        },

        initAll() {
            document.querySelectorAll('.gw-product-tabs').forEach(tabs => {
                this.initTabs(tabs);
            });
        },

        initTabs(container) {
            const tabs = container.querySelectorAll('.gw-product-tabs__tab');
            const panels = container.querySelectorAll('.gw-product-tabs__panel');

            if (!tabs.length || !panels.length) return;

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    this.switchTab(container, tab);
                });

                // Keyboard navigation
                tab.addEventListener('keydown', (e) => {
                    this.handleKeydown(e, tabs);
                });
            });
        },

        switchTab(container, selectedTab) {
            const tabs = container.querySelectorAll('.gw-product-tabs__tab');
            const panels = container.querySelectorAll('.gw-product-tabs__panel');
            const index = parseInt(selectedTab.dataset.tabIndex, 10);

            // Update tabs
            tabs.forEach((tab, i) => {
                const isSelected = i === index;
                tab.classList.toggle('is-active', isSelected);
                tab.setAttribute('aria-selected', isSelected ? 'true' : 'false');
                tab.setAttribute('tabindex', isSelected ? '0' : '-1');
            });

            // Update panels
            panels.forEach((panel, i) => {
                const isActive = i === index;
                panel.classList.toggle('is-active', isActive);
                panel.hidden = !isActive;
            });
        },

        handleKeydown(e, tabs) {
            const currentIndex = Array.from(tabs).findIndex(tab => tab === e.target);
            let newIndex;

            switch (e.key) {
                case 'ArrowLeft':
                    newIndex = currentIndex > 0 ? currentIndex - 1 : tabs.length - 1;
                    break;
                case 'ArrowRight':
                    newIndex = currentIndex < tabs.length - 1 ? currentIndex + 1 : 0;
                    break;
                case 'Home':
                    newIndex = 0;
                    break;
                case 'End':
                    newIndex = tabs.length - 1;
                    break;
                default:
                    return;
            }

            e.preventDefault();
            tabs[newIndex].focus();
            tabs[newIndex].click();
        }
    };

    // Initialize on document ready
    $(document).ready(() => {
        ProductTabs.init();
    });

    // Reinitialize on Elementor frontend init
    $(window).on('elementor/frontend/init', () => {
        if (typeof elementorFrontend !== 'undefined') {
            elementorFrontend.hooks.addAction('frontend/element_ready/gw-product-tabs.default', () => {
                ProductTabs.initAll();
            });
        }
    });

})(jQuery);
