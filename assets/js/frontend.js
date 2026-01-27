/**
 * GW Elements - Frontend JavaScript
 * Main frontend script for all widgets
 */
(function() {
    'use strict';

    /**
     * Global GW Elements namespace
     */
    window.GWElements = window.GWElements || {};

    /**
     * Scroll animation observer
     */
    class ScrollAnimator {
        constructor() {
            this.observer = null;
            this.init();
        }

        init() {
            if ('IntersectionObserver' in window) {
                this.observer = new IntersectionObserver(
                    (entries) => this.handleIntersection(entries),
                    {
                        threshold: 0.1,
                        rootMargin: '0px 0px -10% 0px'
                    }
                );

                this.observe();
            } else {
                // Fallback: show all elements
                document.querySelectorAll('.gw-scroll-animate').forEach(el => {
                    el.classList.add('gw-scroll-animate--visible');
                });
            }
        }

        observe() {
            document.querySelectorAll('.gw-scroll-animate:not(.gw-scroll-animate--visible)').forEach(el => {
                this.observer.observe(el);
            });
        }

        handleIntersection(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('gw-scroll-animate--visible');
                    this.observer.unobserve(entry.target);
                }
            });
        }
    }

    /**
     * Add to Cart handler
     */
    class AddToCart {
        constructor() {
            this.init();
        }

        init() {
            document.addEventListener('click', (e) => {
                const button = e.target.closest('.gw-add-to-cart-btn, .gw-product-card__button, [data-gw-add-to-cart]');
                if (button && button.dataset.productId) {
                    e.preventDefault();
                    this.addToCart(button);
                }
            });
        }

        addToCart(button) {
            const productId = button.dataset.productId;
            if (!productId || button.classList.contains('is-loading')) return;

            button.classList.add('is-loading');

            // Use WooCommerce localized data if available, fallback to gwElements
            const wcData = window.gwWooCommerce || window.gwElements || {};
            const ajaxUrl = wcData.ajaxUrl || '/wp-admin/admin-ajax.php';
            const nonce = wcData.nonce || '';

            const formData = new FormData();
            formData.append('action', 'gw_add_to_cart');
            formData.append('product_id', productId);
            formData.append('quantity', button.dataset.quantity || 1);
            formData.append('nonce', nonce);

            fetch(ajaxUrl, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                button.classList.remove('is-loading');

                if (data.success) {
                    // Update all cart count elements
                    document.querySelectorAll('.gw-cart-count').forEach(el => {
                        el.textContent = data.data.cartCount;
                    });

                    // Update cart count badges
                    document.querySelectorAll('.gw-cart-count-badge').forEach(el => {
                        el.textContent = data.data.cartCount;
                        el.classList.toggle('gw-hidden', !data.data.cartCount);
                    });

                    // Show toast notification
                    this.showToast(data.data.message, 'success');

                    // Trigger custom event for cart drawer
                    if (typeof jQuery !== 'undefined') {
                        jQuery(document.body).trigger('gw_added_to_cart', [data.data]);
                    }

                    // Trigger native WooCommerce event
                    document.body.dispatchEvent(new CustomEvent('added_to_cart', {
                        detail: { product_id: productId, response: data.data }
                    }));
                } else {
                    this.showToast(data.data?.message || 'Error adding to cart', 'error');
                }
            })
            .catch(error => {
                button.classList.remove('is-loading');
                this.showToast('Error adding to cart', 'error');
                console.error('Add to cart error:', error);
            });
        }

        showToast(message, type = 'success') {
            const existing = document.querySelector('.gw-toast');
            if (existing) existing.remove();

            const toast = document.createElement('div');
            toast.className = `gw-toast gw-toast--${type}`;
            toast.innerHTML = `
                <span class="gw-toast__message">${message}</span>
                <button type="button" class="gw-toast__close" aria-label="Close">×</button>
            `;
            document.body.appendChild(toast);

            // Close button
            toast.querySelector('.gw-toast__close').addEventListener('click', () => {
                toast.classList.remove('gw-toast--visible');
                setTimeout(() => toast.remove(), 300);
            });

            // Trigger animation
            requestAnimationFrame(() => {
                toast.classList.add('gw-toast--visible');
            });

            // Remove after delay
            setTimeout(() => {
                if (toast.classList.contains('gw-toast--visible')) {
                    toast.classList.remove('gw-toast--visible');
                    setTimeout(() => toast.remove(), 300);
                }
            }, 4000);
        }
    }

    /**
     * Form handler
     */
    class FormHandler {
        constructor() {
            this.init();
        }

        init() {
            document.querySelectorAll('.gw-contact-form__form[data-ajax="true"]').forEach(form => {
                form.addEventListener('submit', (e) => this.handleSubmit(e, form));
            });
        }

        handleSubmit(e, form) {
            e.preventDefault();

            const submitBtn = form.querySelector('[type="submit"]');
            const messageEl = form.querySelector('.gw-contact-form__message');

            if (submitBtn.classList.contains('is-loading')) return;

            submitBtn.classList.add('is-loading');
            messageEl.style.display = 'none';

            const formData = new FormData(form);

            fetch(window.gwElements?.ajaxUrl || '/wp-admin/admin-ajax.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                submitBtn.classList.remove('is-loading');

                messageEl.style.display = 'block';
                messageEl.className = `gw-contact-form__message gw-contact-form__message--${data.success ? 'success' : 'error'}`;
                messageEl.textContent = data.data?.message || (data.success ? 'Message sent!' : 'Error sending message');

                if (data.success) {
                    form.reset();
                }
            })
            .catch(error => {
                submitBtn.classList.remove('is-loading');
                messageEl.style.display = 'block';
                messageEl.className = 'gw-contact-form__message gw-contact-form__message--error';
                messageEl.textContent = 'Error sending message';
                console.error('Form submit error:', error);
            });
        }
    }

    /**
     * Smooth scroll for anchor links
     */
    class SmoothScroll {
        constructor() {
            this.init();
        }

        init() {
            document.addEventListener('click', (e) => {
                const anchor = e.target.closest('a[href^="#"]:not([href="#"])');
                if (anchor) {
                    const target = document.querySelector(anchor.getAttribute('href'));
                    if (target) {
                        e.preventDefault();
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                }
            });
        }
    }

    /**
     * Quantity buttons for single product
     */
    class QuantityButtons {
        constructor() {
            this.init();
        }

        init() {
            this.wrapQuantityInputs();
            document.addEventListener('click', (e) => {
                if (e.target.classList.contains('gw-qty-btn')) {
                    this.handleClick(e.target);
                }
            });
        }

        wrapQuantityInputs() {
            document.querySelectorAll('.woocommerce .quantity:not(.gw-qty-wrapped)').forEach(wrapper => {
                const input = wrapper.querySelector('input.qty');
                if (!input) return;

                wrapper.classList.add('gw-qty-wrapped');

                const minus = document.createElement('button');
                minus.type = 'button';
                minus.className = 'gw-qty-btn gw-qty-minus';
                minus.textContent = '−';

                const plus = document.createElement('button');
                plus.type = 'button';
                plus.className = 'gw-qty-btn gw-qty-plus';
                plus.textContent = '+';

                input.before(minus);
                input.after(plus);
            });
        }

        handleClick(btn) {
            const wrapper = btn.closest('.quantity');
            const input = wrapper.querySelector('input.qty');
            if (!input) return;

            const min = parseFloat(input.min) || 1;
            const max = parseFloat(input.max) || 9999;
            const step = parseFloat(input.step) || 1;
            let value = parseFloat(input.value) || min;

            if (btn.classList.contains('gw-qty-minus')) {
                value = Math.max(min, value - step);
            } else if (btn.classList.contains('gw-qty-plus')) {
                value = Math.min(max, value + step);
            }

            input.value = value;
            input.dispatchEvent(new Event('change', { bubbles: true }));
        }
    }

    /**
     * Product Gallery Fix - handles FlexSlider inline styles + touch swipe
     */
    class GalleryFix {
        constructor() {
            this.currentIndex = 0;
            this.slides = [];
            this.touchStartX = 0;
            this.touchEndX = 0;
            this.init();
        }

        init() {
            // Fix gallery on load
            this.fixGallery();

            // Fix after FlexSlider initializes (it runs on window load)
            window.addEventListener('load', () => {
                setTimeout(() => this.fixGallery(), 100);
                setTimeout(() => this.fixGallery(), 500);
            });

            // Watch for gallery changes (FlexSlider modifies DOM)
            this.observeGallery();

            // Setup touch swipe
            this.setupTouchSwipe();
        }

        fixGallery() {
            // Fix flex-viewport inline height
            document.querySelectorAll('.woocommerce-product-gallery .flex-viewport').forEach(viewport => {
                viewport.style.height = 'auto';
                viewport.style.maxHeight = 'none';
            });

            // Fix gallery wrapper
            document.querySelectorAll('.woocommerce-product-gallery__wrapper').forEach(wrapper => {
                wrapper.style.transform = 'none';
                wrapper.style.width = '100%';
            });

            // Get all slides
            this.slides = Array.from(document.querySelectorAll('.woocommerce-product-gallery__image'));

            // Fix gallery images - reset FlexSlider inline styles
            this.slides.forEach(slide => {
                slide.style.width = '100%';
                slide.style.float = 'none';
                slide.style.display = 'none';
                slide.style.position = 'relative';
            });

            // Find current index
            const activeSlide = document.querySelector('.woocommerce-product-gallery__image.flex-active-slide');
            if (activeSlide) {
                this.currentIndex = this.slides.indexOf(activeSlide);
                activeSlide.style.display = 'block';
            } else if (this.slides.length > 0) {
                // If no active slide, show first one
                this.currentIndex = 0;
                this.slides[0].style.display = 'block';
                this.slides[0].classList.add('flex-active-slide');
            }

            // Update thumbnails
            this.updateThumbnails();
        }

        setupTouchSwipe() {
            const gallery = document.querySelector('.woocommerce-product-gallery');
            if (!gallery) return;

            const viewport = gallery.querySelector('.flex-viewport') || gallery;

            // Prevent default zoom behavior on drag
            viewport.addEventListener('touchstart', (e) => {
                this.touchStartX = e.changedTouches[0].screenX;
            }, { passive: true });

            viewport.addEventListener('touchend', (e) => {
                this.touchEndX = e.changedTouches[0].screenX;
                this.handleSwipe();
            }, { passive: true });

            // Prevent click on links during swipe
            viewport.addEventListener('click', (e) => {
                if (Math.abs(this.touchStartX - this.touchEndX) > 30) {
                    e.preventDefault();
                }
            });
        }

        handleSwipe() {
            const diff = this.touchStartX - this.touchEndX;
            const threshold = 50;

            if (Math.abs(diff) < threshold) return;

            if (diff > 0) {
                // Swipe left - next
                this.goToSlide(this.currentIndex + 1);
            } else {
                // Swipe right - prev
                this.goToSlide(this.currentIndex - 1);
            }
        }

        goToSlide(index) {
            if (this.slides.length === 0) return;

            // Wrap around
            if (index < 0) index = this.slides.length - 1;
            if (index >= this.slides.length) index = 0;

            // Hide all, show target
            this.slides.forEach((slide, i) => {
                slide.style.display = i === index ? 'block' : 'none';
                slide.classList.toggle('flex-active-slide', i === index);
            });

            this.currentIndex = index;
            this.updateThumbnails();
        }

        updateThumbnails() {
            const thumbs = document.querySelectorAll('.flex-control-thumbs img');
            thumbs.forEach((thumb, i) => {
                thumb.classList.toggle('flex-active', i === this.currentIndex);
            });
        }

        observeGallery() {
            const gallery = document.querySelector('.woocommerce-product-gallery');
            if (!gallery) return;

            // Watch for thumbnail clicks
            gallery.addEventListener('click', (e) => {
                const thumb = e.target.closest('.flex-control-thumbs img');
                if (thumb) {
                    e.preventDefault();
                    this.handleThumbClick(thumb);
                }
            });
        }

        handleThumbClick(thumb) {
            const thumbList = thumb.closest('ol, ul');
            if (!thumbList) return;

            const index = Array.from(thumbList.querySelectorAll('img')).indexOf(thumb);
            this.goToSlide(index);
        }
    }

    /**
     * Wishlist button handler
     */
    class WishlistHandler {
        constructor() {
            this.init();
        }

        init() {
            document.addEventListener('click', (e) => {
                const btn = e.target.closest('.gw-wishlist-btn');
                if (btn) {
                    e.preventDefault();
                    this.toggle(btn);
                }
            });
        }

        toggle(btn) {
            const productId = btn.dataset.productId;
            if (!productId) return;

            btn.classList.toggle('is-wishlisted');

            const wcData = window.gwWooCommerce || {};
            const ajaxUrl = wcData.ajaxUrl || '/wp-admin/admin-ajax.php';
            const nonce = wcData.nonce || '';

            const formData = new FormData();
            formData.append('action', 'gw_toggle_wishlist');
            formData.append('product_id', productId);
            formData.append('nonce', nonce);

            fetch(ajaxUrl, {
                method: 'POST',
                body: formData
            }).catch(err => console.error('Wishlist error:', err));
        }
    }

    /**
     * Initialize all components
     */
    function initAll() {
        GWElements.scrollAnimator = new ScrollAnimator();
        GWElements.addToCart = new AddToCart();
        GWElements.formHandler = new FormHandler();
        GWElements.smoothScroll = new SmoothScroll();
        GWElements.quantityButtons = new QuantityButtons();
        GWElements.galleryFix = new GalleryFix();
        GWElements.wishlistHandler = new WishlistHandler();
    }

    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAll);
    } else {
        initAll();
    }

    // Re-observe scroll animations after Elementor loads new elements
    if (typeof jQuery !== 'undefined') {
        jQuery(window).on('elementor/frontend/init', function() {
            if (typeof elementorFrontend !== 'undefined') {
                elementorFrontend.hooks.addAction('frontend/element_ready/global', function() {
                    if (GWElements.scrollAnimator) {
                        GWElements.scrollAnimator.observe();
                    }
                });
            }
        });
    }

})();
