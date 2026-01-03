/**
 * GW Product Slider Widget JavaScript
 */
(function () {
  'use strict';

  class GWProductSlider {
    constructor(element) {
      if (!element) {
        console.warn('GWProductSlider: No element provided');
        return;
      }

      this.wrapper = element;
      this.slider = element.querySelector('.splide');
      this.prevBtn = element.querySelector('.gw-product-slider__arrow--prev');
      this.nextBtn = element.querySelector('.gw-product-slider__arrow--next');

      if (!this.slider) {
        console.warn('GWProductSlider: No .splide element found');
        return;
      }

      if (typeof Splide === 'undefined') {
        console.warn('GWProductSlider: Splide library not loaded');
        return;
      }

      this.init();
    }

    init() {
      // Get config from data attribute
      let config = {};
      try {
        config = JSON.parse(this.wrapper.dataset.sliderConfig || '{}');
      } catch (e) {
        console.warn('GWProductSlider: Invalid slider config');
      }

      // Default config
      const defaultConfig = {
        type: 'loop',
        perPage: 4,
        perMove: 1,
        gap: '0.5rem',
        padding: 0,
        pagination: false,
        arrows: false,
        autoplay: false,
        interval: 5000,
        pauseOnHover: true,
        breakpoints: {
          1280: { perPage: 4 },
          1024: { perPage: 3 },
          768: { perPage: 2 },
          480: { perPage: 2, gap: '0.5rem' },
        },
      };

      const finalConfig = { ...defaultConfig, ...config };

      // Initialize Splide
      try {
        this.splide = new Splide(this.slider, finalConfig);
        this.splide.mount();

        // Custom navigation
        if (this.prevBtn) {
          this.prevBtn.addEventListener('click', () => this.splide.go('<'));
        }

        if (this.nextBtn) {
          this.nextBtn.addEventListener('click', () => this.splide.go('>'));
        }
      } catch (e) {
        console.error('GWProductSlider: Error initializing Splide', e);
      }
    }
  }

  // Initialize on DOM ready
  function initProductSliders() {
    document.querySelectorAll('.gw-product-slider').forEach((element) => {
      if (!element.dataset.gwInitialized) {
        new GWProductSlider(element);
        element.dataset.gwInitialized = 'true';
      }
    });
  }

  // Initialize
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initProductSliders);
  } else {
    initProductSliders();
  }

  // Re-initialize on Elementor frontend init
  if (typeof jQuery !== 'undefined') {
    jQuery(window).on('elementor/frontend/init', function () {
      if (typeof elementorFrontend !== 'undefined') {
        elementorFrontend.hooks.addAction(
          'frontend/element_ready/gw-product-slider.default',
          function ($element) {
            // Small delay to ensure DOM is ready
            setTimeout(function() {
              // Find the slider wrapper
              let sliderWrapper = $element[0].querySelector('.gw-product-slider');
              if (
                !sliderWrapper &&
                $element[0].classList.contains('gw-product-slider')
              ) {
                sliderWrapper = $element[0];
              }
              if (sliderWrapper) {
                // Force re-initialization in editor
                delete sliderWrapper.dataset.gwInitialized;
                new GWProductSlider(sliderWrapper);
                sliderWrapper.dataset.gwInitialized = 'true';
              }
            }, 100);
          }
        );
      }
    });
  }

  // Also listen for Elementor preview reload
  if (typeof jQuery !== 'undefined') {
    jQuery(document).on('elementor/popup/show', function() {
      initProductSliders();
    });
  }

  // Expose globally for manual initialization
  window.GWProductSlider = GWProductSlider;
  window.initGWProductSliders = initProductSliders;
})();
