/**
 * GW Single Product Page JavaScript
 * Handles gallery thumbnail switching and product tabs
 */
(function () {
  'use strict';

  /**
   * Gallery Thumbnail Switcher
   */
  class GWProductGallery {
    constructor(element) {
      this.gallery = element;
      this.mainImage = element.querySelector('.gw-single-product__main-image img');
      this.mainImageLink = element.querySelector('.gw-single-product__main-image a');
      this.thumbnails = element.querySelectorAll('.gw-single-product__thumb');

      if (this.mainImage && this.thumbnails.length > 0) {
        this.init();
      }
    }

    init() {
      this.thumbnails.forEach((thumb) => {
        thumb.addEventListener('click', (e) => {
          e.preventDefault();
          this.switchImage(thumb);
        });
      });
    }

    switchImage(thumb) {
      const imageUrl = thumb.dataset.imageUrl;
      const fullUrl = thumb.dataset.fullUrl;

      if (!imageUrl) return;

      // Update main image
      this.mainImage.src = imageUrl;

      // Update lightbox link
      if (this.mainImageLink && fullUrl) {
        this.mainImageLink.href = fullUrl;
      }

      // Update active state
      this.thumbnails.forEach((t) => t.classList.remove('is-active'));
      thumb.classList.add('is-active');
    }
  }

  /**
   * Product Tabs
   */
  class GWProductTabs {
    constructor(element) {
      this.container = element;
      this.buttons = element.querySelectorAll('.gw-single-product__tab-btn');
      this.panels = element.querySelectorAll('.gw-single-product__tab-panel');

      if (this.buttons.length > 0 && this.panels.length > 0) {
        this.init();
      }
    }

    init() {
      this.buttons.forEach((btn) => {
        btn.addEventListener('click', (e) => {
          e.preventDefault();
          this.switchTab(btn.dataset.tab);
        });
      });
    }

    switchTab(tabId) {
      // Update buttons
      this.buttons.forEach((btn) => {
        btn.classList.toggle('is-active', btn.dataset.tab === tabId);
      });

      // Update panels
      this.panels.forEach((panel) => {
        panel.classList.toggle(
          'is-active',
          panel.dataset.tabContent === tabId
        );
      });
    }
  }

  /**
   * Initialize all components
   */
  function initSingleProduct() {
    // Initialize gallery
    document
      .querySelectorAll('.gw-single-product__gallery')
      .forEach((gallery) => {
        new GWProductGallery(gallery);
      });

    // Initialize tabs
    document.querySelectorAll('.gw-single-product__tabs').forEach((tabs) => {
      new GWProductTabs(tabs);
    });
  }

  // Initialize on DOM ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initSingleProduct);
  } else {
    initSingleProduct();
  }

  // Also run on window load as fallback
  window.addEventListener('load', initSingleProduct);

  // Re-initialize on Elementor frontend init
  if (typeof jQuery !== 'undefined') {
    jQuery(window).on('elementor/frontend/init', function () {
      if (typeof elementorFrontend !== 'undefined') {
        elementorFrontend.hooks.addAction(
          'frontend/element_ready/global',
          function () {
            initSingleProduct();
          }
        );
      }
    });
  }
})();
