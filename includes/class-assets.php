<?php
/**
 * Assets handler class.
 *
 * @package GW_Elements
 */

namespace GW\Elements;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class Assets
 *
 * Handles CSS and JS asset loading for widgets.
 */
class Assets {

    /**
     * Singleton instance.
     *
     * @var Assets|null
     */
    private static ?Assets $instance = null;

    /**
     * Registered widget styles.
     *
     * @var array
     */
    private array $widget_styles = [];

    /**
     * Registered widget scripts.
     *
     * @var array
     */
    private array $widget_scripts = [];

    /**
     * Get singleton instance.
     *
     * @return Assets
     */
    public static function instance(): Assets {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor.
     */
    private function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'register_assets' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_base_assets' ] );

        // Elementor editor/preview assets.
        add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'enqueue_editor_assets' ] );
        add_action( 'elementor/preview/enqueue_styles', [ $this, 'enqueue_all_assets' ] );
        add_action( 'elementor/preview/enqueue_scripts', [ $this, 'enqueue_all_scripts' ] );
        add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'enqueue_all_scripts' ] );
    }

    /**
     * Register all assets.
     */
    public function register_assets(): void {
        // Base CSS with variables.
        wp_register_style(
            'gw-elements-base',
            GW_ELEMENTS_ASSETS_URL . 'css/base.css',
            [],
            GW_ELEMENTS_VERSION
        );

        // Frontend CSS.
        wp_register_style(
            'gw-elements-frontend',
            GW_ELEMENTS_ASSETS_URL . 'css/frontend.css',
            [ 'gw-elements-base' ],
            GW_ELEMENTS_VERSION
        );

        // Splide carousel library.
        wp_register_style(
            'splide',
            GW_ELEMENTS_ASSETS_URL . 'vendor/splide/splide.min.css',
            [],
            '4.1.4'
        );

        wp_register_script(
            'splide',
            GW_ELEMENTS_ASSETS_URL . 'vendor/splide/splide.min.js',
            [],
            '4.1.4',
            true
        );

        // Frontend JS.
        wp_register_script(
            'gw-elements-frontend',
            GW_ELEMENTS_ASSETS_URL . 'js/frontend.js',
            [ 'jquery' ],
            GW_ELEMENTS_VERSION,
            true
        );

        // Localize script for AJAX.
        wp_localize_script(
            'gw-elements-frontend',
            'gwElements',
            [
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'nonce'   => wp_create_nonce( 'gw_elements_nonce' ),
            ]
        );

        // Shop enhancements JS.
        wp_register_script(
            'gw-shop',
            GW_ELEMENTS_ASSETS_URL . 'js/shop.js',
            [ 'gw-elements-frontend' ],
            GW_ELEMENTS_VERSION,
            true
        );

        // Register individual widget assets.
        $this->register_widget_assets();
    }

    /**
     * Register individual widget CSS/JS.
     */
    private function register_widget_assets(): void {
        $widgets = [
            // Layout
            'header',
            'footer',

            // Hero
            'video-hero',
            'image-hero',
            'page-header',

            // Product (WooCommerce)
            'product-slider',
            'product-grid',
            'product-card',
            'product-tabs',
            'category-slider',
            'category-card',
            'cart-drawer',
            'search-modal',
            'single-product',

            // Content
            'story-card',
            'storytelling',
            'service-card',
            'trust-points',
            'values-grid',
            'feature-highlight',
            'cta-section',
            'faq-accordion',
            'upcoming-events',

            // Forms
            'contact-form',
            'contact-info',
            'newsletter',
        ];

        foreach ( $widgets as $widget ) {
            $css_file = GW_ELEMENTS_PATH . 'assets/css/widgets/' . $widget . '.css';
            $js_file  = GW_ELEMENTS_PATH . 'assets/js/widgets/' . $widget . '.js';

            // Register CSS if exists.
            if ( file_exists( $css_file ) ) {
                wp_register_style(
                    'gw-widget-' . $widget,
                    GW_ELEMENTS_ASSETS_URL . 'css/widgets/' . $widget . '.css',
                    [ 'gw-elements-frontend' ],
                    GW_ELEMENTS_VERSION
                );
                $this->widget_styles[ $widget ] = 'gw-widget-' . $widget;
            }

            // Register JS if exists.
            if ( file_exists( $js_file ) ) {
                // Carousel widgets need Splide as dependency.
                $carousel_widgets = [ 'category-slider', 'product-slider' ];
                $dependencies = [ 'gw-elements-frontend' ];

                if ( in_array( $widget, $carousel_widgets, true ) ) {
                    $dependencies[] = 'splide';
                }

                wp_register_script(
                    'gw-widget-' . $widget,
                    GW_ELEMENTS_ASSETS_URL . 'js/widgets/' . $widget . '.js',
                    $dependencies,
                    GW_ELEMENTS_VERSION,
                    true
                );
                $this->widget_scripts[ $widget ] = 'gw-widget-' . $widget;
            }
        }
    }

    /**
     * Enqueue base assets on frontend.
     */
    public function enqueue_base_assets(): void {
        wp_enqueue_style( 'gw-elements-base' );
        wp_enqueue_style( 'gw-elements-frontend' );
        wp_enqueue_script( 'gw-elements-frontend' );

        // Enqueue shop enhancements on WooCommerce shop pages.
        if ( function_exists( 'is_shop' ) && ( is_shop() || is_product_category() || is_product_tag() ) ) {
            wp_enqueue_script( 'gw-shop' );
        }

        // Enqueue single product JS on product pages.
        if ( function_exists( 'is_product' ) && is_product() ) {
            wp_enqueue_script( 'gw-widget-single-product' );
        }
    }

    /**
     * Enqueue assets in Elementor editor.
     */
    public function enqueue_editor_assets(): void {
        if ( \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
            // Enqueue all widget styles in preview mode.
            foreach ( $this->widget_styles as $handle ) {
                wp_enqueue_style( $handle );
            }
            // Enqueue all widget scripts in preview mode.
            foreach ( $this->widget_scripts as $handle ) {
                wp_enqueue_script( $handle );
            }
            // Enqueue carousel library.
            wp_enqueue_style( 'splide' );
            wp_enqueue_script( 'splide' );
        }
    }

    /**
     * Enqueue widget-specific style.
     *
     * @param string $widget Widget slug.
     */
    public function enqueue_widget_style( string $widget ): void {
        if ( isset( $this->widget_styles[ $widget ] ) ) {
            wp_enqueue_style( $this->widget_styles[ $widget ] );
        }
    }

    /**
     * Enqueue widget-specific script.
     *
     * @param string $widget Widget slug.
     */
    public function enqueue_widget_script( string $widget ): void {
        if ( isset( $this->widget_scripts[ $widget ] ) ) {
            wp_enqueue_script( $this->widget_scripts[ $widget ] );
        }
    }

    /**
     * Enqueue carousel library.
     */
    public function enqueue_carousel(): void {
        wp_enqueue_style( 'splide' );
        wp_enqueue_script( 'splide' );
    }

    /**
     * Enqueue all styles for Elementor preview.
     */
    public function enqueue_all_assets(): void {
        // Enqueue Splide.
        wp_enqueue_style( 'splide' );

        // Enqueue all widget styles.
        foreach ( $this->widget_styles as $handle ) {
            wp_enqueue_style( $handle );
        }
    }

    /**
     * Enqueue all scripts for Elementor preview/editor.
     */
    public function enqueue_all_scripts(): void {
        // Enqueue Splide.
        wp_enqueue_script( 'splide' );

        // Enqueue all widget scripts.
        foreach ( $this->widget_scripts as $handle ) {
            wp_enqueue_script( $handle );
        }
    }
}
