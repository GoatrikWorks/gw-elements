/**
 * Cart Drawer Widget JavaScript
 * Handles cart drawer open/close and AJAX cart operations
 */

(function($) {
    'use strict';

    const CartDrawer = {
        drawer: null,
        isOpen: false,
        isLoading: false,

        init() {
            this.drawer = document.querySelector('.gw-cart-drawer');
            if (!this.drawer) return;

            this.bindEvents();
            this.setupGlobalTriggers();
        },

        bindEvents() {
            // Close button
            const closeBtn = this.drawer.querySelector('.gw-cart-drawer__close');
            if (closeBtn) {
                closeBtn.addEventListener('click', () => this.close());
            }

            // Overlay click
            const overlay = this.drawer.querySelector('.gw-cart-drawer__overlay');
            if (overlay) {
                overlay.addEventListener('click', () => this.close());
            }

            // Escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && this.isOpen) {
                    this.close();
                }
            });

            // Quantity buttons and remove
            this.drawer.addEventListener('click', (e) => this.handleCartAction(e));
        },

        setupGlobalTriggers() {
            // Cart icon clicks
            document.addEventListener('click', (e) => {
                const trigger = e.target.closest('.gw-cart-trigger, .gw-header__cart-btn, [data-cart-drawer-trigger]');
                if (trigger) {
                    e.preventDefault();
                    this.open();
                }
            });

            // Auto-open after add to cart (optional)
            $(document.body).on('gw_added_to_cart', () => {
                this.open();
                this.refreshCart();
            });
        },

        open() {
            if (this.isOpen) return;

            this.isOpen = true;
            this.drawer.classList.add('is-open');
            document.body.classList.add('gw-cart-drawer-open');

            // Focus trap
            const closeBtn = this.drawer.querySelector('.gw-cart-drawer__close');
            if (closeBtn) {
                setTimeout(() => closeBtn.focus(), 100);
            }

            // Trigger event
            $(document.body).trigger('gw_cart_drawer_opened');
        },

        close() {
            if (!this.isOpen) return;

            this.isOpen = false;
            this.drawer.classList.remove('is-open');
            document.body.classList.remove('gw-cart-drawer-open');

            // Trigger event
            $(document.body).trigger('gw_cart_drawer_closed');
        },

        handleCartAction(e) {
            const button = e.target.closest('[data-action]');
            if (!button) return;

            const action = button.dataset.action;
            const cartItem = button.closest('.gw-cart-item');
            if (!cartItem) return;

            const cartItemKey = cartItem.dataset.cartItemKey;
            const qtyValue = cartItem.querySelector('.gw-cart-item__qty-value');
            let quantity = parseInt(qtyValue?.textContent || 1, 10);

            switch (action) {
                case 'increase':
                    this.updateQuantity(cartItemKey, quantity + 1);
                    break;
                case 'decrease':
                    if (quantity > 1) {
                        this.updateQuantity(cartItemKey, quantity - 1);
                    } else {
                        this.removeItem(cartItemKey);
                    }
                    break;
                case 'remove':
                    this.removeItem(cartItemKey);
                    break;
            }
        },

        updateQuantity(cartItemKey, quantity) {
            if (this.isLoading) return;
            this.setLoading(true);

            $.ajax({
                url: gwWooCommerce.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'gw_update_cart_item',
                    nonce: gwWooCommerce.nonce,
                    cart_item_key: cartItemKey,
                    quantity: quantity
                },
                success: (response) => {
                    if (response.success) {
                        this.updateUI(response.data);
                    }
                },
                complete: () => {
                    this.setLoading(false);
                }
            });
        },

        removeItem(cartItemKey) {
            if (this.isLoading) return;
            this.setLoading(true);

            $.ajax({
                url: gwWooCommerce.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'gw_remove_cart_item',
                    nonce: gwWooCommerce.nonce,
                    cart_item_key: cartItemKey
                },
                success: (response) => {
                    if (response.success) {
                        this.updateUI(response.data);
                    }
                },
                complete: () => {
                    this.setLoading(false);
                }
            });
        },

        refreshCart() {
            if (this.isLoading) return;
            this.setLoading(true);

            $.ajax({
                url: gwWooCommerce.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'gw_get_cart'
                },
                success: (response) => {
                    if (response.success) {
                        this.updateUI(response.data);
                    }
                },
                complete: () => {
                    this.setLoading(false);
                }
            });
        },

        updateUI(data) {
            // Update cart content
            const content = this.drawer.querySelector('.gw-mini-cart-content');
            if (content && data.cartHtml) {
                content.innerHTML = data.cartHtml;
            }

            // Update all cart count elements
            document.querySelectorAll('.gw-cart-count').forEach(el => {
                el.textContent = data.cartCount || 0;
            });

            // Update cart count badges visibility
            document.querySelectorAll('.gw-cart-count-badge').forEach(el => {
                el.textContent = data.cartCount || 0;
                el.classList.toggle('gw-hidden', !data.cartCount);
            });

            // Update fragments if provided
            if (data.fragments) {
                $.each(data.fragments, (selector, html) => {
                    $(selector).replaceWith(html);
                });
            }

            // Trigger event
            $(document.body).trigger('gw_cart_updated', [data]);
        },

        setLoading(loading) {
            this.isLoading = loading;
            this.drawer.classList.toggle('is-loading', loading);
        }
    };

    // Initialize on document ready
    $(document).ready(() => {
        CartDrawer.init();
    });

    // Reinitialize on Elementor frontend init
    $(window).on('elementor/frontend/init', () => {
        if (typeof elementorFrontend !== 'undefined') {
            elementorFrontend.hooks.addAction('frontend/element_ready/gw-cart-drawer.default', () => {
                CartDrawer.init();
            });
        }
    });

    // Expose globally for other widgets
    window.GWCartDrawer = CartDrawer;

})(jQuery);
