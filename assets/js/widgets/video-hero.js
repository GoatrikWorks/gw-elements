/**
 * GW Video Hero Widget JavaScript
 */
(function() {
    'use strict';

    class GWVideoHero {
        constructor(element) {
            this.wrapper = element;
            this.video = element.querySelector('.gw-video-hero__video');
            this.muteButton = element.querySelector('.gw-video-hero__mute');
            this.mutedIcon = element.querySelector('.gw-video-hero__mute-icon--muted');
            this.unmutedIcon = element.querySelector('.gw-video-hero__mute-icon--unmuted');

            this.init();
        }

        init() {
            // Trigger loaded state after a short delay
            setTimeout(() => {
                this.wrapper.classList.add('gw-video-hero--loaded');
            }, 100);

            // Auto-play video
            if (this.video) {
                this.video.play().catch(() => {
                    // Autoplay prevented, that's okay
                });
            }

            // Mute toggle
            if (this.muteButton && this.video) {
                this.muteButton.addEventListener('click', () => this.toggleMute());
            }
        }

        toggleMute() {
            if (!this.video) return;

            this.video.muted = !this.video.muted;

            if (this.video.muted) {
                this.mutedIcon.style.display = '';
                this.unmutedIcon.style.display = 'none';
            } else {
                this.mutedIcon.style.display = 'none';
                this.unmutedIcon.style.display = '';
            }
        }
    }

    // Initialize on DOM ready
    function initVideoHeroes() {
        document.querySelectorAll('.gw-video-hero').forEach(element => {
            if (!element.dataset.gwInitialized) {
                new GWVideoHero(element);
                element.dataset.gwInitialized = 'true';
            }
        });
    }

    // Initialize
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initVideoHeroes);
    } else {
        initVideoHeroes();
    }

    // Re-initialize on Elementor frontend init
    if (typeof jQuery !== 'undefined') {
        jQuery(window).on('elementor/frontend/init', function() {
            if (typeof elementorFrontend !== 'undefined') {
                elementorFrontend.hooks.addAction('frontend/element_ready/gw-video-hero.default', function($element) {
                    new GWVideoHero($element[0].querySelector('.gw-video-hero'));
                });
            }
        });
    }
})();
