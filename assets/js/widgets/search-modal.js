/**
 * Search Modal Widget JavaScript
 * AJAX live search with debounce
 */

(function($) {
    'use strict';

    const SearchModal = {
        modal: null,
        input: null,
        resultsContainer: null,
        resultsList: null,
        loadingEl: null,
        noResultsEl: null,
        viewAllEl: null,
        debounceTimer: null,
        isOpen: false,

        init() {
            this.modal = document.querySelector('.gw-search-modal');
            if (!this.modal) return;

            this.input = this.modal.querySelector('.gw-search-modal__input');
            this.resultsContainer = this.modal.querySelector('.gw-search-modal__results');
            this.resultsList = this.modal.querySelector('.gw-search-modal__results-list');
            this.loadingEl = this.modal.querySelector('.gw-search-modal__loading');
            this.noResultsEl = this.modal.querySelector('.gw-search-modal__no-results');
            this.viewAllEl = this.modal.querySelector('.gw-search-modal__view-all');

            this.bindEvents();
            this.setupGlobalTriggers();
        },

        bindEvents() {
            // Input handling
            if (this.input) {
                this.input.addEventListener('input', (e) => {
                    this.handleInput(e.target.value);
                });

                // Escape to close
                this.input.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') {
                        this.close();
                    } else if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        this.focusFirstResult();
                    }
                });
            }

            // Close button
            const closeBtn = this.modal.querySelector('.gw-search-modal__close');
            if (closeBtn) {
                closeBtn.addEventListener('click', () => this.close());
            }

            // Clear button
            const clearBtn = this.modal.querySelector('.gw-search-modal__clear');
            if (clearBtn) {
                clearBtn.addEventListener('click', () => {
                    this.input.value = '';
                    this.input.focus();
                    this.modal.classList.remove('has-value');
                    this.clearResults();
                });
            }

            // Backdrop click (click outside to close)
            const backdrop = this.modal.querySelector('.gw-search-modal__backdrop');
            if (backdrop) {
                backdrop.addEventListener('click', () => this.close());
            }

            // Results keyboard navigation
            this.resultsList.addEventListener('keydown', (e) => {
                this.handleResultsKeydown(e);
            });
        },

        setupGlobalTriggers() {
            // Search triggers
            document.addEventListener('click', (e) => {
                const trigger = e.target.closest('.gw-search-trigger, .gw-header__search-btn, [data-search-modal-trigger]');
                if (trigger) {
                    e.preventDefault();
                    this.open();
                }
            });

            // Escape key global
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && this.isOpen) {
                    this.close();
                }

                // Cmd/Ctrl + K to open
                if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
                    e.preventDefault();
                    this.isOpen ? this.close() : this.open();
                }
            });
        },

        open() {
            if (this.isOpen) return;

            this.isOpen = true;
            this.modal.classList.add('is-open');
            document.body.classList.add('gw-search-modal-open');

            // Focus input
            setTimeout(() => {
                this.input?.focus();
            }, 100);

            $(document.body).trigger('gw_search_modal_opened');
        },

        close() {
            if (!this.isOpen) return;

            this.isOpen = false;
            this.modal.classList.remove('is-open');
            document.body.classList.remove('gw-search-modal-open');

            // Clear state
            this.clearResults();
            if (this.input) {
                this.input.value = '';
                this.modal.classList.remove('has-value');
            }

            $(document.body).trigger('gw_search_modal_closed');
        },

        handleInput(value) {
            const trimmedValue = value.trim();
            const minChars = parseInt(this.modal.dataset.minChars || 2, 10);

            // Toggle has-value class
            this.modal.classList.toggle('has-value', trimmedValue.length > 0);

            // Clear previous timer
            if (this.debounceTimer) {
                clearTimeout(this.debounceTimer);
            }

            // Check minimum characters
            if (trimmedValue.length < minChars) {
                this.clearResults();
                return;
            }

            // Debounce search
            this.debounceTimer = setTimeout(() => {
                this.search(trimmedValue);
            }, 300);
        },

        search(query) {
            this.showLoading();

            const maxResults = parseInt(this.modal.dataset.maxResults || 8, 10);

            $.ajax({
                url: gwWooCommerce.ajaxUrl,
                type: 'GET',
                data: {
                    action: 'gw_search_products',
                    s: query,
                    per_page: maxResults
                },
                success: (response) => {
                    if (response.success) {
                        this.displayResults(response.data);
                    } else {
                        this.showNoResults();
                    }
                },
                error: () => {
                    this.showNoResults();
                }
            });
        },

        displayResults(data) {
            this.hideLoading();

            if (!data.results || data.results.length === 0) {
                this.showNoResults();
                return;
            }

            const showCategories = this.modal.dataset.showCategories === 'true';
            const showPrices = this.modal.dataset.showPrices === 'true';

            let html = '';
            data.results.forEach(product => {
                html += `
                    <a href="${product.url}" class="gw-search-result">
                        <div class="gw-search-result__image">
                            <img src="${product.image}" alt="${this.escapeHtml(product.name)}" loading="lazy">
                        </div>
                        <div class="gw-search-result__content">
                            ${showCategories && product.category ? `<span class="gw-search-result__category">${this.escapeHtml(product.category)}</span>` : ''}
                            <h4 class="gw-search-result__title">${this.escapeHtml(product.name)}</h4>
                        </div>
                        ${showPrices ? `<span class="gw-search-result__price">${product.price}</span>` : ''}
                    </a>
                `;
            });

            this.resultsList.innerHTML = html;
            this.noResultsEl.hidden = true;

            // Show "View All" if more results exist
            if (data.count > data.results.length) {
                const viewAllLink = this.viewAllEl.querySelector('.gw-search-modal__view-all-link');
                if (viewAllLink && data.searchUrl) {
                    viewAllLink.href = data.searchUrl;
                }
                this.viewAllEl.hidden = false;
            } else {
                this.viewAllEl.hidden = true;
            }
        },

        showLoading() {
            this.loadingEl.hidden = false;
            this.noResultsEl.hidden = true;
            this.resultsList.innerHTML = '';
            this.viewAllEl.hidden = true;
        },

        hideLoading() {
            this.loadingEl.hidden = true;
        },

        showNoResults() {
            this.hideLoading();
            this.noResultsEl.hidden = false;
            this.resultsList.innerHTML = '';
            this.viewAllEl.hidden = true;
        },

        clearResults() {
            this.loadingEl.hidden = true;
            this.noResultsEl.hidden = true;
            this.resultsList.innerHTML = '';
            this.viewAllEl.hidden = true;
        },

        focusFirstResult() {
            const firstResult = this.resultsList.querySelector('.gw-search-result');
            if (firstResult) {
                firstResult.focus();
            }
        },

        handleResultsKeydown(e) {
            const results = Array.from(this.resultsList.querySelectorAll('.gw-search-result'));
            const currentIndex = results.indexOf(document.activeElement);

            switch (e.key) {
                case 'ArrowDown':
                    e.preventDefault();
                    if (currentIndex < results.length - 1) {
                        results[currentIndex + 1].focus();
                    }
                    break;
                case 'ArrowUp':
                    e.preventDefault();
                    if (currentIndex > 0) {
                        results[currentIndex - 1].focus();
                    } else {
                        this.input?.focus();
                    }
                    break;
                case 'Escape':
                    e.preventDefault();
                    this.input?.focus();
                    break;
            }
        },

        escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    };

    // Initialize
    $(document).ready(() => {
        SearchModal.init();
    });

    // Reinitialize on Elementor
    $(window).on('elementor/frontend/init', () => {
        if (typeof elementorFrontend !== 'undefined') {
            elementorFrontend.hooks.addAction('frontend/element_ready/gw-search-modal.default', () => {
                SearchModal.init();
            });
        }
    });

    // Expose globally
    window.GWSearchModal = SearchModal;

})(jQuery);
