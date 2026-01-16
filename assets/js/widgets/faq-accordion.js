/**
 * GW FAQ Accordion Widget JavaScript
 */
(function () {
  'use strict';

  class GWAccordion {
    constructor(element) {
      this.wrapper = element;
      this.items = element.querySelectorAll('.gw-accordion__item');
      this.isSingle = element.dataset.accordion === 'single';

      this.init();
    }

    init() {
      this.items.forEach((item) => {
        const trigger = item.querySelector('.gw-accordion__trigger');
        const content = item.querySelector('.gw-accordion__content');

        if (trigger && content) {
          // Remove any existing listeners first
          trigger.replaceWith(trigger.cloneNode(true));
          const newTrigger = item.querySelector('.gw-accordion__trigger');

          newTrigger.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            this.toggle(item);
          });
        }
      });
    }

    toggle(item) {
      const isOpen = item.dataset.state === 'open';

      // Close all if single mode
      if (this.isSingle && !isOpen) {
        this.items.forEach((i) => this.close(i));
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
        if (item.dataset.state === 'closed') {
          content.hidden = true;
        }
      }, 200);
    }
  }

  // Initialize all accordions
  function initAccordions() {
    document.querySelectorAll('.gw-accordion').forEach((element) => {
      // Always reinitialize to ensure event listeners are attached
      element.removeAttribute('data-gw-initialized');
      new GWAccordion(element);
      element.dataset.gwInitialized = 'true';
    });
  }

  // Initialize on various events to ensure it works
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAccordions);
  } else {
    // DOM already loaded
    initAccordions();
  }

  // Also run on window load as fallback
  window.addEventListener('load', initAccordions);

  // Re-initialize on Elementor frontend init
  if (typeof jQuery !== 'undefined') {
    jQuery(window).on('elementor/frontend/init', function () {
      if (typeof elementorFrontend !== 'undefined') {
        elementorFrontend.hooks.addAction(
          'frontend/element_ready/gw-faq-accordion.default',
          function ($element) {
            $element[0].querySelectorAll('.gw-accordion').forEach((el) => {
              new GWAccordion(el);
            });
          }
        );
      }
    });

    // Also handle Elementor preview changes
    jQuery(document).on('elementor/popup/show', initAccordions);
  }

  // MutationObserver for dynamically added content
  const observer = new MutationObserver((mutations) => {
    mutations.forEach((mutation) => {
      mutation.addedNodes.forEach((node) => {
        if (node.nodeType === 1) {
          // Element node
          if (node.classList && node.classList.contains('gw-accordion')) {
            new GWAccordion(node);
          }
          // Check for accordions inside added nodes
          const accordions = node.querySelectorAll
            ? node.querySelectorAll('.gw-accordion:not([data-gw-initialized])')
            : [];
          accordions.forEach((el) => {
            new GWAccordion(el);
            el.dataset.gwInitialized = 'true';
          });
        }
      });
    });
  });

  // Start observing
  observer.observe(document.body, {
    childList: true,
    subtree: true,
  });
})();
