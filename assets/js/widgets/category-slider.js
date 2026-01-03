/**
 * GW Category Slider Widget JavaScript
 */
(function () {
  'use strict';

  class GWCategorySlider {
    constructor(element) {
      if (!element) {
        console.warn('GWCategorySlider: No element provided');
        return;
      }

      this.wrapper = element;
      this.slider = element.querySelector('.splide');
      this.prevBtn = element.querySelector('.gw-category-slider__arrow--prev');
      this.nextBtn = element.querySelector('.gw-category-slider__arrow--next');
      this.dotsContainer = element.querySelector('.gw-category-slider__dots');

      if (!this.slider) {
        console.warn('GWCategorySlider: No .splide element found');
        return;
      }

      if (typeof Splide === 'undefined') {
        console.warn('GWCategorySlider: Splide library not loaded');
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
        console.warn('GWCategorySlider: Invalid slider config');
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
          480: { perPage: 1, gap: '0.5rem' },
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

        // Custom pagination
        if (this.dotsContainer && finalConfig.pagination) {
          this.buildPagination();
        }
      } catch (e) {
        console.error('GWCategorySlider: Error initializing Splide', e);
      }
    }

    buildPagination() {
      const count = this.splide.length;
      this.dotsContainer.innerHTML = '';

      for (let i = 0; i < count; i++) {
        const dot = document.createElement('button');
        dot.className = 'splide__pagination__page';
        dot.setAttribute('aria-label', `Go to slide ${i + 1}`);
        dot.addEventListener('click', () => this.splide.go(i));
        this.dotsContainer.appendChild(dot);
      }

      // Update active state
      this.splide.on('mounted move', () => {
        const active = this.splide.index;
        this.dotsContainer
          .querySelectorAll('.splide__pagination__page')
          .forEach((dot, i) => {
            dot.classList.toggle('is-active', i === active);
          });
      });
    }
  }

  // Initialize on DOM ready
  function initCategorySliders() {
    document.querySelectorAll('.gw-category-slider').forEach((element) => {
      if (!element.dataset.gwInitialized) {
        new GWCategorySlider(element);
        element.dataset.gwInitialized = 'true';
      }
    });
  }

  // Initialize
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initCategorySliders);
  } else {
    initCategorySliders();
  }

  // Re-initialize on Elementor frontend init
  if (typeof jQuery !== 'undefined') {
    jQuery(window).on('elementor/frontend/init', function () {
      if (typeof elementorFrontend !== 'undefined') {
        elementorFrontend.hooks.addAction(
          'frontend/element_ready/gw-category-slider.default',
          function ($element) {
            // Small delay to ensure DOM is ready
            setTimeout(function() {
              // Find the slider wrapper - it might be the element itself or a child
              let sliderWrapper = $element[0].querySelector('.gw-category-slider');
              if (!sliderWrapper && $element[0].classList.contains('gw-category-slider')) {
                sliderWrapper = $element[0];
              }
              if (sliderWrapper) {
                // Force re-initialization in editor
                delete sliderWrapper.dataset.gwInitialized;
                new GWCategorySlider(sliderWrapper);
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
      initCategorySliders();
    });
  }

  // Expose globally for manual initialization
  window.GWCategorySlider = GWCategorySlider;
  window.initGWCategorySliders = initCategorySliders;
})();
