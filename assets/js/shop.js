/**
 * GW Elements - Shop Page Enhancements
 * Filter drawer, view toggle, and enhanced UX
 */
(function () {
  'use strict';

  class ShopEnhancements {
    constructor() {
      this.filterDrawer = null;
      this.filterToggle = null;
      this.productsGrid = null;

      this.init();
    }

    init() {
      // Wait for DOM
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

      this.createShopControls();
      this.createFilterDrawer();
      this.bindEvents();
    }

    createShopControls() {
      // Find existing WooCommerce elements
      const resultCount = document.querySelector('.woocommerce-result-count');
      const ordering = document.querySelector('.woocommerce-ordering');

      if (!resultCount && !ordering) return;

      // Get parent container
      const parent = resultCount?.parentElement || ordering?.parentElement;
      if (!parent) return;

      // Create controls wrapper
      const controlsWrapper = document.createElement('div');
      controlsWrapper.className = 'gw-shop-controls';

      // Left side
      const leftSide = document.createElement('div');
      leftSide.className = 'gw-shop-controls__left';

      // Filter toggle button
      const filterBtn = document.createElement('button');
      filterBtn.type = 'button';
      filterBtn.className = 'gw-filter-toggle';
      filterBtn.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
        </svg>
        <span>Filtri</span>
      `;
      this.filterToggle = filterBtn;
      leftSide.appendChild(filterBtn);

      // Category pills (get from widgets if available)
      const categoryPills = this.createCategoryPills();
      if (categoryPills) {
        leftSide.appendChild(categoryPills);
      }

      // Right side
      const rightSide = document.createElement('div');
      rightSide.className = 'gw-shop-controls__right';

      // Move ordering dropdown
      if (ordering) {
        const select = ordering.querySelector('select');
        if (select) {
          select.style.cssText = '';
        }
        rightSide.appendChild(ordering);
      }

      // Assemble
      controlsWrapper.appendChild(leftSide);
      controlsWrapper.appendChild(rightSide);

      // Product count row
      if (resultCount) {
        const countRow = document.createElement('div');
        countRow.className = 'gw-product-count';
        countRow.innerHTML = resultCount.innerHTML;
        controlsWrapper.appendChild(countRow);
        resultCount.remove();
      }

      // Insert before products
      const products = parent.querySelector('ul.products');
      if (products) {
        products.before(controlsWrapper);
        this.productsGrid = products;
      } else {
        parent.prepend(controlsWrapper);
      }
    }

    createCategoryPills() {
      // Try to get categories from sidebar widget or build from current taxonomy
      const categories = [];

      // Check for category widget
      const categoryWidget = document.querySelector(
        '.widget_product_categories ul'
      );
      if (categoryWidget) {
        const items = categoryWidget.querySelectorAll('li > a');
        items.forEach(item => {
          categories.push({
            name: item.textContent.trim(),
            url: item.href,
            active: item.parentElement.classList.contains('current-cat'),
          });
        });
      }

      if (categories.length === 0) return null;

      const container = document.createElement('div');
      container.className = 'gw-category-pills';

      // Add "All" pill
      const allPill = document.createElement('a');
      allPill.href = '/shop/';
      allPill.className = 'gw-category-pill';
      if (!categories.some(c => c.active)) {
        allPill.classList.add('gw-category-pill--active');
      }
      allPill.textContent = 'Tutti';
      container.appendChild(allPill);

      // Add category pills
      categories.slice(0, 6).forEach(cat => {
        const pill = document.createElement('a');
        pill.href = cat.url;
        pill.className = 'gw-category-pill';
        if (cat.active) {
          pill.classList.add('gw-category-pill--active');
        }
        pill.textContent = cat.name;
        container.appendChild(pill);
      });

      return container;
    }

    createFilterDrawer() {
      // Create drawer HTML
      const drawer = document.createElement('div');
      drawer.className = 'gw-filter-drawer';
      drawer.id = 'gw-filter-drawer';
      drawer.innerHTML = `
        <div class="gw-filter-drawer__overlay"></div>
        <div class="gw-filter-drawer__panel">
          <div class="gw-filter-drawer__header">
            <h3 class="gw-filter-drawer__title">Filtri</h3>
            <button type="button" class="gw-filter-drawer__close" aria-label="Chiudi">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
              </svg>
            </button>
          </div>
          <div class="gw-filter-drawer__content">
            ${this.getFilterContent()}
          </div>
          <div class="gw-filter-drawer__footer">
            <button type="button" class="gw-filter-drawer__clear">Cancella</button>
            <button type="button" class="gw-filter-drawer__apply">Applica Filtri</button>
          </div>
        </div>
      `;

      document.body.appendChild(drawer);
      this.filterDrawer = drawer;
    }

    getFilterContent() {
      let content = '';

      // Categories
      const categoryWidget = document.querySelector(
        '.widget_product_categories'
      );
      if (categoryWidget) {
        const categories = categoryWidget.querySelectorAll('li');
        if (categories.length > 0) {
          content += `
            <div class="gw-filter-group">
              <h4 class="gw-filter-group__title">Categorie</h4>
              <ul class="gw-filter-group__list">
          `;
          categories.forEach(cat => {
            const link = cat.querySelector('a');
            const count = cat.querySelector('.count');
            const isActive = cat.classList.contains('current-cat');
            content += `
              <li class="gw-filter-group__item">
                <label class="gw-filter-group__label">
                  <input type="checkbox" class="gw-filter-group__checkbox" data-url="${
                    link?.href || '#'
                  }" ${isActive ? 'checked' : ''}>
                  ${link?.textContent.trim() || ''}
                  ${
                    count
                      ? `<span class="gw-filter-group__count">${count.textContent}</span>`
                      : ''
                  }
                </label>
              </li>
            `;
          });
          content += `
              </ul>
            </div>
          `;
        }
      }

      // Price filter
      const priceWidget = document.querySelector('.widget_price_filter');
      if (priceWidget) {
        content += `
          <div class="gw-filter-group">
            <h4 class="gw-filter-group__title">Prezzo</h4>
            <div class="gw-price-range">
              <div class="gw-price-range__inputs">
                <input type="number" class="gw-price-range__input" placeholder="Min" min="0">
                <span class="gw-price-range__separator">—</span>
                <input type="number" class="gw-price-range__input" placeholder="Max">
              </div>
            </div>
          </div>
        `;
      }

      // If no filters found, show message
      if (!content) {
        content = `
          <div style="text-align: center; padding: 2rem 0; color: hsl(var(--gw-muted-foreground));">
            <p>Nessun filtro disponibile</p>
          </div>
        `;
      }

      return content;
    }

    bindEvents() {
      // Filter toggle
      if (this.filterToggle) {
        this.filterToggle.addEventListener('click', () => this.openDrawer());
      }

      // Filter drawer events
      if (this.filterDrawer) {
        const overlay = this.filterDrawer.querySelector(
          '.gw-filter-drawer__overlay'
        );
        const closeBtn = this.filterDrawer.querySelector(
          '.gw-filter-drawer__close'
        );
        const applyBtn = this.filterDrawer.querySelector(
          '.gw-filter-drawer__apply'
        );
        const clearBtn = this.filterDrawer.querySelector(
          '.gw-filter-drawer__clear'
        );

        overlay?.addEventListener('click', () => this.closeDrawer());
        closeBtn?.addEventListener('click', () => this.closeDrawer());
        applyBtn?.addEventListener('click', () => this.applyFilters());
        clearBtn?.addEventListener('click', () => this.clearFilters());

        // Escape key
        document.addEventListener('keydown', e => {
          if (
            e.key === 'Escape' &&
            this.filterDrawer.classList.contains('gw-filter-drawer--open')
          ) {
            this.closeDrawer();
          }
        });
      }
    }

    openDrawer() {
      if (this.filterDrawer) {
        this.filterDrawer.classList.add('gw-filter-drawer--open');
        document.body.style.overflow = 'hidden';
      }
    }

    closeDrawer() {
      if (this.filterDrawer) {
        this.filterDrawer.classList.remove('gw-filter-drawer--open');
        document.body.style.overflow = '';
      }
    }

    applyFilters() {
      // Get checked categories
      const checkedCategories = this.filterDrawer.querySelectorAll(
        '.gw-filter-group__checkbox:checked'
      );

      if (checkedCategories.length > 0) {
        // Navigate to first checked category
        const firstChecked = checkedCategories[0];
        const url = firstChecked.dataset.url;
        if (url && url !== '#') {
          window.location.href = url;
          return;
        }
      }

      this.closeDrawer();
    }

    clearFilters() {
      // Uncheck all
      const checkboxes = this.filterDrawer.querySelectorAll(
        '.gw-filter-group__checkbox'
      );
      checkboxes.forEach(cb => (cb.checked = false));

      // Navigate to shop
      window.location.href = '/shop/';
    }
  }

  // Initialize
  new ShopEnhancements();
})();
