/**
 * GW Header Widget JavaScript
 */
(function () {
  'use strict';

  class GWHeader {
    constructor(element) {
      this.header = element;
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

      this.lastScrollY = 0;
      this.init();
    }

    init() {
      // Always listen to scroll for shrink + mobile hide
      if (this.shouldShrink || this.announcementBar) {
        this.handleScroll = this.handleScroll.bind(this);
        window.addEventListener('scroll', this.handleScroll, { passive: true });
        this.handleScroll();
      }

      // Mobile: hide header on scroll down, show on scroll up
      this.handleMobileScroll = this.handleMobileScroll.bind(this);
      window.addEventListener('scroll', this.handleMobileScroll, { passive: true });

      // Build floating mobile menu
      this.buildMobileMenu();
    }

    handleScroll() {
      const scrolled = window.scrollY > this.scrollThreshold;

      if (scrolled !== this.isScrolled) {
        this.isScrolled = scrolled;
        this.header.classList.toggle('gw-header--scrolled', scrolled);

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

    handleMobileScroll() {
      // Only on mobile
      if (window.innerWidth >= 1024) {
        this.header.classList.remove('gw-header--hidden');
        this.lastScrollY = window.scrollY;
        return;
      }

      const currentY = window.scrollY;

      if (currentY > this.scrollThreshold) {
        if (currentY > this.lastScrollY) {
          // Scrolling down - hide
          this.header.classList.add('gw-header--hidden');
          if (this.announcementBar) {
            this.announcementBar.classList.add('gw-announcement--hidden');
          }
        } else {
          // Scrolling up - show
          this.header.classList.remove('gw-header--hidden');
        }
      } else {
        // At top - always show
        this.header.classList.remove('gw-header--hidden');
      }

      this.lastScrollY = currentY;
    }

    buildMobileMenu() {
      // Don't build on desktop
      if (document.querySelector('.gw-mobile-menu-fab')) return;

      // Collect nav items from desktop nav
      const desktopNav = this.header.querySelector('.gw-header__nav--desktop');
      if (!desktopNav) return;

      const navLinks = desktopNav.querySelectorAll('.gw-header__link');
      if (!navLinks.length) return;

      // Create floating action button
      const fab = document.createElement('button');
      fab.className = 'gw-mobile-menu-fab';
      fab.setAttribute('aria-label', 'Menu');
      fab.setAttribute('aria-expanded', 'false');
      fab.innerHTML = `
        <svg class="gw-fab-icon-menu" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
        <svg class="gw-fab-icon-close" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
      `;

      // Create fullscreen overlay
      const overlay = document.createElement('div');
      overlay.className = 'gw-mobile-menu-overlay';
      overlay.setAttribute('data-open', 'false');

      // Build nav list
      const navList = document.createElement('ul');
      navList.className = 'gw-mobile-menu-overlay__nav';

      navLinks.forEach(link => {
        const li = document.createElement('li');
        const a = document.createElement('a');
        a.href = link.href;
        a.textContent = link.textContent;
        a.addEventListener('click', () => this.closeMobileOverlay(fab, overlay));
        li.appendChild(a);
        navList.appendChild(li);
      });

      // Build action buttons (search + account + cart)
      const actions = document.createElement('div');
      actions.className = 'gw-mobile-menu-overlay__actions';

      const searchBtn = this.header.querySelector('.gw-header__search-btn');
      if (searchBtn) {
        const btn = document.createElement('button');
        btn.className = 'gw-mobile-menu-overlay__action';
        btn.setAttribute('type', 'button');
        btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg> Cerca`;
        btn.addEventListener('click', () => {
          this.closeMobileOverlay(fab, overlay);
          setTimeout(() => {
            const trigger = document.querySelector('[data-search-modal-trigger]');
            if (trigger) trigger.click();
          }, 300);
        });
        actions.appendChild(btn);
      }

      const accountBtn = this.header.querySelector('.gw-header__account-btn');
      if (accountBtn) {
        const a = document.createElement('a');
        a.href = accountBtn.href;
        a.className = 'gw-mobile-menu-overlay__action';
        a.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg> Account`;
        a.addEventListener('click', () => this.closeMobileOverlay(fab, overlay));
        actions.appendChild(a);
      }

      const cartBtn = this.header.querySelector('.gw-header__cart-btn');
      if (cartBtn) {
        const btn = document.createElement('button');
        btn.className = 'gw-mobile-menu-overlay__action';
        btn.setAttribute('type', 'button');
        btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg> Carrello`;
        btn.addEventListener('click', () => {
          this.closeMobileOverlay(fab, overlay);
          // Trigger cart drawer
          setTimeout(() => {
            const cartTrigger = document.querySelector('[data-cart-drawer-trigger]');
            if (cartTrigger) cartTrigger.click();
          }, 300);
        });
        actions.appendChild(btn);
      }

      // Language switcher (may be in announcement bar, a sibling of header)
      const langSwitcher = document.querySelector('.gw-header__lang-switcher');
      if (langSwitcher) {
        const langDiv = document.createElement('div');
        langDiv.className = 'gw-mobile-menu-overlay__lang';
        try {
          const languages = JSON.parse(langSwitcher.dataset.languages || '{}');
          const flags = JSON.parse(langSwitcher.dataset.flags || '{}');
          const activeLink = langSwitcher.querySelector('.gw-header__lang.is-active');
          const activeLang = activeLink ? activeLink.textContent.trim().replace(/[^\w]/g, '').toLowerCase() : 'it';
          const codes = Object.keys(languages);
          langDiv.innerHTML = codes.map((code) => {
            const active = code === activeLang ? ' is-active' : '';
            const flag = flags[code] || '';
            return `<a href="${languages[code]}" class="gw-mobile-menu-overlay__lang-link${active}"><span class="gw-mobile-menu-overlay__lang-flag">${flag}</span>${code.toUpperCase()}</a>`;
          }).join('');
        } catch (e) { /* fallback: no switcher */ }
        overlay.appendChild(langDiv);
      }

      overlay.appendChild(navList);
      overlay.appendChild(actions);

      document.body.appendChild(overlay);
      document.body.appendChild(fab);

      // Toggle
      fab.addEventListener('click', () => {
        const isOpen = overlay.getAttribute('data-open') === 'true';
        if (isOpen) {
          this.closeMobileOverlay(fab, overlay);
        } else {
          this.openMobileOverlay(fab, overlay);
        }
      });

      // Close on Escape
      document.addEventListener('keydown', e => {
        if (e.key === 'Escape' && overlay.getAttribute('data-open') === 'true') {
          this.closeMobileOverlay(fab, overlay);
        }
      });

      // Close on resize to desktop
      window.addEventListener('resize', () => {
        if (window.innerWidth >= 1024 && overlay.getAttribute('data-open') === 'true') {
          this.closeMobileOverlay(fab, overlay);
        }
      });
    }

    openMobileOverlay(fab, overlay) {
      this.savedScrollY = window.scrollY;
      document.body.style.top = `-${this.savedScrollY}px`;
      document.documentElement.classList.add('gw-menu-open');
      overlay.setAttribute('data-open', 'true');
      fab.setAttribute('aria-expanded', 'true');
    }

    closeMobileOverlay(fab, overlay) {
      overlay.setAttribute('data-open', 'false');
      fab.setAttribute('aria-expanded', 'false');
      document.documentElement.classList.remove('gw-menu-open');
      document.body.style.top = '';
      window.scrollTo(0, this.savedScrollY || 0);
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
