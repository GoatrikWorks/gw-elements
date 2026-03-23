/**
 * GW Elements - Shop Page with Sidebar Filters
 * Left sidebar with filters, product grid on right
 */
(function () {
  'use strict';

  // Translations (populated by wp_localize_script, fallback to Italian)
  const t = window.gwShopI18n || {};

  // Main product categories for LaViaSana
  const MAIN_CATEGORIES = [
    { name: t.catBodyCare || 'Cura del corpo', slug: 'body-care' },
    { name: t.catEnergy || 'Stimolanti energetici', slug: 'energy-boosters' },
    { name: t.catCleansing || 'Purificazione', slug: 'cleansing' },
    { name: t.catFitness || 'Fitness', slug: 'body-fitness' },
    { name: t.catSoulCare || 'Cura dell\'anima', slug: 'soul-care' },
    { name: t.catRecovery || 'Recupero', slug: 'recovery' },
  ];

  class ShopPage {
    constructor() {
      this.sidebar = null;
      this.mainContent = null;
      this.mobileToggle = null;
      this.categories = MAIN_CATEGORIES;
      this.currentFilters = this.getFiltersFromURL();

      this.init();
    }

    init() {
      if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => this.setup());
      } else {
        this.setup();
      }
    }

    setup() {
      // Only run on shop pages
      if (
        !document.body.classList.contains('woocommerce-shop') &&
        !document.body.classList.contains('post-type-archive-product') &&
        !document.body.classList.contains('tax-product_cat')
      ) {
        return;
      }

      this.createShopLayout();
      this.bindEvents();
    }

    getFiltersFromURL() {
      const params = new URLSearchParams(window.location.search);
      const path = window.location.pathname;

      // Extract category from URL path
      let category = '';
      const catMatch = path.match(/\/product-category\/([^/]+)/);
      if (catMatch) {
        category = catMatch[1];
      }

      return {
        category: category,
        minPrice: params.get('min_price') || '',
        maxPrice: params.get('max_price') || '',
        orderby: params.get('orderby') || 'date',
      };
    }

    createShopLayout() {
      // Find WooCommerce content
      const wcContent = document.querySelector('.woocommerce-products-header')?.parentElement
                     || document.querySelector('ul.products')?.parentElement;

      if (!wcContent) return;

      // Create shop wrapper
      const shopWrapper = document.createElement('div');
      shopWrapper.className = 'gw-shop-layout';

      // Create sidebar
      this.sidebar = document.createElement('aside');
      this.sidebar.className = 'gw-shop-sidebar';
      this.sidebar.innerHTML = this.getSidebarHTML();

      // Create main content wrapper
      this.mainContent = document.createElement('div');
      this.mainContent.className = 'gw-shop-main';

      // Move existing content to main
      const header = wcContent.querySelector('.woocommerce-products-header');
      const resultCount = wcContent.querySelector('.woocommerce-result-count');
      const ordering = wcContent.querySelector('.woocommerce-ordering');
      const products = wcContent.querySelector('ul.products');
      const pagination = wcContent.querySelector('.woocommerce-pagination');

      // Create top bar (only for mobile filter toggle and ordering)
      const topBar = document.createElement('div');
      topBar.className = 'gw-shop-topbar';

      // Mobile filter toggle (only visible on mobile)
      const mobileToggle = document.createElement('button');
      mobileToggle.type = 'button';
      mobileToggle.className = 'gw-shop-filter-toggle';
      mobileToggle.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
        </svg>
        <span>${t.filters || 'Filtri'}</span>
      `;
      this.mobileToggle = mobileToggle;

      // Top bar left (mobile toggle + result count)
      const topBarLeft = document.createElement('div');
      topBarLeft.className = 'gw-shop-topbar__left';
      topBarLeft.appendChild(mobileToggle);
      if (resultCount) {
        resultCount.className = 'gw-shop-result-count';
        topBarLeft.appendChild(resultCount);
      }

      // Top bar right (ordering)
      const topBarRight = document.createElement('div');
      topBarRight.className = 'gw-shop-topbar__right';
      if (ordering) {
        ordering.className = 'gw-shop-ordering';
        topBarRight.appendChild(ordering);
      }

      topBar.appendChild(topBarLeft);
      topBar.appendChild(topBarRight);

      // Assemble main content
      this.mainContent.appendChild(topBar);
      if (products) {
        products.className = 'products gw-shop-products';
        this.mainContent.appendChild(products);
      }
      if (pagination) this.mainContent.appendChild(pagination);

      // Assemble layout
      shopWrapper.appendChild(this.sidebar);
      shopWrapper.appendChild(this.mainContent);

      // Clear and rebuild content
      wcContent.innerHTML = '';

      // Add header above layout if exists
      if (header) {
        header.className = 'woocommerce-products-header gw-shop-header';
        wcContent.appendChild(header);
      }

      // Add the shop layout
      wcContent.appendChild(shopWrapper);

      // Add body class
      document.body.classList.add('gw-has-shop-sidebar');
    }

    getSidebarHTML() {
      const currentPath = window.location.pathname;
      // Strip language prefix for shop root check
      const cleanPath = currentPath.replace(/^\/(en|de|fr)\//, '/');
      const isShopRoot = cleanPath === '/shop/' || cleanPath === '/shop';

      let html = `
        <div class="gw-shop-sidebar__inner">
          <div class="gw-shop-sidebar__header">
            <h3 class="gw-shop-sidebar__title">${t.filters || 'Filtri'}</h3>
            <button type="button" class="gw-shop-sidebar__close" aria-label="${t.close || 'Chiudi'}">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
              </svg>
            </button>
          </div>

          <div class="gw-shop-sidebar__content">
            <!-- Categories -->
            <div class="gw-filter-section">
              <h4 class="gw-filter-section__title">${t.categories || 'Categorie'}</h4>
              <ul class="gw-filter-list">
                <li class="gw-filter-item">
                  <a href="/shop/" class="gw-filter-link ${isShopRoot ? 'gw-filter-link--active' : ''}">
                    <span class="gw-filter-link__name">${t.allProducts || 'Tutti i prodotti'}</span>
                  </a>
                </li>
      `;

      this.categories.forEach(cat => {
        const isActive = currentPath.includes(`/product-category/${cat.slug}`);
        html += `
                <li class="gw-filter-item">
                  <a href="/product-category/${cat.slug}/" class="gw-filter-link ${isActive ? 'gw-filter-link--active' : ''}">
                    <span class="gw-filter-link__name">${cat.name}</span>
                  </a>
                </li>
        `;
      });

      html += `
              </ul>
            </div>

            <!-- Price Filter -->
            <div class="gw-filter-section">
              <h4 class="gw-filter-section__title">${t.price || 'Prezzo'}</h4>
              <div class="gw-price-filter">
                <div class="gw-price-filter__inputs">
                  <div class="gw-price-filter__field">
                    <span class="gw-price-filter__currency">€</span>
                    <input type="number"
                           class="gw-price-filter__input"
                           id="gw-min-price"
                           placeholder="Min"
                           min="0"
                           value="${this.currentFilters.minPrice}">
                  </div>
                  <span class="gw-price-filter__separator">–</span>
                  <div class="gw-price-filter__field">
                    <span class="gw-price-filter__currency">€</span>
                    <input type="number"
                           class="gw-price-filter__input"
                           id="gw-max-price"
                           placeholder="Max"
                           value="${this.currentFilters.maxPrice}">
                  </div>
                </div>
                <button type="button" class="gw-price-filter__apply" id="gw-apply-price">
                  ${t.apply || 'Applica'}
                </button>
              </div>
            </div>
          </div>

          <!-- Mobile footer -->
          <div class="gw-shop-sidebar__footer">
            <button type="button" class="gw-shop-sidebar__clear">${t.clearAll || 'Cancella tutto'}</button>
            <button type="button" class="gw-shop-sidebar__apply">${t.showResults || 'Mostra risultati'}</button>
          </div>
        </div>
        <div class="gw-shop-sidebar__overlay"></div>
      `;

      return html;
    }

    bindEvents() {
      // Mobile toggle
      if (this.mobileToggle) {
        this.mobileToggle.addEventListener('click', () => this.openSidebar());
      }

      // Sidebar close button
      const closeBtn = this.sidebar?.querySelector('.gw-shop-sidebar__close');
      if (closeBtn) {
        closeBtn.addEventListener('click', () => this.closeSidebar());
      }

      // Overlay click
      const overlay = this.sidebar?.querySelector('.gw-shop-sidebar__overlay');
      if (overlay) {
        overlay.addEventListener('click', () => this.closeSidebar());
      }

      // Price filter apply
      const priceApply = this.sidebar?.querySelector('#gw-apply-price');
      if (priceApply) {
        priceApply.addEventListener('click', () => this.applyPriceFilter());
      }

      // Price input enter key
      const priceInputs = this.sidebar?.querySelectorAll('.gw-price-filter__input');
      priceInputs?.forEach(input => {
        input.addEventListener('keypress', (e) => {
          if (e.key === 'Enter') {
            this.applyPriceFilter();
          }
        });
      });

      // Mobile footer buttons
      const clearBtn = this.sidebar?.querySelector('.gw-shop-sidebar__clear');
      const applyBtn = this.sidebar?.querySelector('.gw-shop-sidebar__apply');

      if (clearBtn) {
        clearBtn.addEventListener('click', () => {
          window.location.href = '/shop/';
        });
      }

      if (applyBtn) {
        applyBtn.addEventListener('click', () => this.closeSidebar());
      }

      // Escape key
      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && this.sidebar?.classList.contains('gw-shop-sidebar--open')) {
          this.closeSidebar();
        }
      });
    }

    openSidebar() {
      this.sidebar?.classList.add('gw-shop-sidebar--open');
      document.body.style.overflow = 'hidden';
    }

    closeSidebar() {
      this.sidebar?.classList.remove('gw-shop-sidebar--open');
      document.body.style.overflow = '';
    }

    applyPriceFilter() {
      const minPrice = this.sidebar?.querySelector('#gw-min-price')?.value;
      const maxPrice = this.sidebar?.querySelector('#gw-max-price')?.value;

      const params = new URLSearchParams(window.location.search);

      if (minPrice) {
        params.set('min_price', minPrice);
      } else {
        params.delete('min_price');
      }

      if (maxPrice) {
        params.set('max_price', maxPrice);
      } else {
        params.delete('max_price');
      }

      const queryString = params.toString();
      const baseUrl = window.location.pathname;
      window.location.href = queryString ? `${baseUrl}?${queryString}` : baseUrl;
    }
  }

  // Initialize
  new ShopPage();
})();
