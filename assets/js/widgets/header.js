/**
 * GW Header Widget JavaScript
 */
(function () {
  'use strict';

  class GWHeader {
    constructor(element) {
      this.header = element;
      this.menuToggle = element.querySelector('.gw-header__menu-toggle');
      this.mobileNav = element.querySelector('.gw-header__mobile-nav');
      this.shouldShrink = element.dataset.shrink === 'true';
      this.isScrolled = false;
      this.scrollThreshold = 50;

      // Find announcement bar (previous sibling)
      this.announcementBar = element.previousElementSibling;
      if (
        this.announcementBar &&
        !this.announcementBar.classList.contains('gw-header__announcement')
      ) {
        this.announcementBar = null;
      }

      this.init();
    }

    init() {
      if (this.menuToggle && this.mobileNav) {
        this.menuToggle.addEventListener('click', () =>
          this.toggleMobileMenu()
        );
      }

      // Shrink on scroll (always listen if there's an announcement bar or shrink is enabled)
      if (this.shouldShrink || this.announcementBar) {
        this.handleScroll = this.handleScroll.bind(this);
        window.addEventListener('scroll', this.handleScroll, { passive: true });
        // Check initial scroll position
        this.handleScroll();
      }

      // Close mobile menu on resize
      window.addEventListener('resize', () => {
        if (
          window.innerWidth >= 1024 &&
          this.mobileNav &&
          !this.mobileNav.hidden
        ) {
          this.closeMobileMenu();
        }
      });

      // Close mobile menu on click outside
      document.addEventListener('click', e => {
        if (
          this.mobileNav &&
          !this.header.contains(e.target) &&
          !this.mobileNav.hidden
        ) {
          this.closeMobileMenu();
        }
      });
    }

    handleScroll() {
      const scrolled = window.scrollY > this.scrollThreshold;

      if (scrolled !== this.isScrolled) {
        this.isScrolled = scrolled;

        // Toggle header scrolled class (always for sticky + announcement)
        this.header.classList.toggle('gw-header--scrolled', scrolled);

        // Hide/show announcement bar
        if (
          this.announcementBar &&
          this.announcementBar.classList.contains(
            'gw-header__announcement--hide-on-scroll'
          )
        ) {
          this.announcementBar.classList.toggle(
            'gw-announcement--hidden',
            scrolled
          );
        }
      }
    }

    toggleMobileMenu() {
      if (this.mobileNav.hidden) {
        this.openMobileMenu();
      } else {
        this.closeMobileMenu();
      }
    }

    openMobileMenu() {
      this.mobileNav.hidden = false;
      this.menuToggle.setAttribute('aria-expanded', 'true');
    }

    closeMobileMenu() {
      this.mobileNav.hidden = true;
      this.menuToggle.setAttribute('aria-expanded', 'false');
    }
  }

  // Initialize on DOM ready
  function initHeaders() {
    document.querySelectorAll('.gw-header').forEach(element => {
      if (!element.dataset.gwInitialized) {
        new GWHeader(element);
        element.dataset.gwInitialized = 'true';
      }
    });
  }

  // Initialize
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initHeaders);
  } else {
    initHeaders();
  }

  // Re-initialize on Elementor frontend init
  if (typeof jQuery !== 'undefined') {
    jQuery(window).on('elementor/frontend/init', function () {
      if (typeof elementorFrontend !== 'undefined') {
        elementorFrontend.hooks.addAction(
          'frontend/element_ready/gw-header.default',
          function ($element) {
            const headerEl = $element[0].querySelector('.gw-header');
            if (headerEl) {
              new GWHeader(headerEl);
            }
          }
        );
      }
    });
  }
})();
