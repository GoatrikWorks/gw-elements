/**
 * GW FAQ Accordion Widget JavaScript
 */
(function() {
    'use strict';

    class GWAccordion {
        constructor(element) {
            this.wrapper = element;
            this.items = element.querySelectorAll('.gw-accordion__item');
            this.isSingle = element.dataset.accordion === 'single';

            this.init();
        }

        init() {
            this.items.forEach(item => {
                const trigger = item.querySelector('.gw-accordion__trigger');
                const content = item.querySelector('.gw-accordion__content');

                if (trigger && content) {
                    trigger.addEventListener('click', () => this.toggle(item));
                }
            });
        }

        toggle(item) {
            const isOpen = item.dataset.state === 'open';

            // Close all if single mode
            if (this.isSingle && !isOpen) {
                this.items.forEach(i => this.close(i));
            }

            if (isOpen) {
                this.close(item);
            } else {
                this.open(item);
            }
        }

        open(item) {
            const trigger = item.querySelector('.gw-accordion__trigger');
            const content = item.querySelector('.gw-accordion__content');

            item.dataset.state = 'open';
            trigger.setAttribute('aria-expanded', 'true');
            content.hidden = false;

            // Animate height
            content.style.maxHeight = content.scrollHeight + 'px';
        }

        close(item) {
            const trigger = item.querySelector('.gw-accordion__trigger');
            const content = item.querySelector('.gw-accordion__content');

            item.dataset.state = 'closed';
            trigger.setAttribute('aria-expanded', 'false');
            content.style.maxHeight = '0';

            // Hide after animation
            setTimeout(() => {
                content.hidden = true;
            }, 200);
        }
    }

    // Initialize on DOM ready
    function initAccordions() {
        document.querySelectorAll('.gw-accordion').forEach(element => {
            if (!element.dataset.gwInitialized) {
                new GWAccordion(element);
                element.dataset.gwInitialized = 'true';
            }
        });
    }

    // Initialize
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAccordions);
    } else {
        initAccordions();
    }

    // Re-initialize on Elementor frontend init
    if (typeof jQuery !== 'undefined') {
        jQuery(window).on('elementor/frontend/init', function() {
            if (typeof elementorFrontend !== 'undefined') {
                elementorFrontend.hooks.addAction('frontend/element_ready/gw-faq-accordion.default', function($element) {
                    $element[0].querySelectorAll('.gw-accordion').forEach(el => {
                        new GWAccordion(el);
                    });
                });
            }
        });
    }
})();
