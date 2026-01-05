<?php
/**
 * Plugin Name: GW Elements - Laviasana Widgets
 * Plugin URI: https://goatrikworks.com
 * Description: Custom Elementor widgets for Laviasana - pixel-perfect recreation of the React design system.
 * Version: 1.0.19
 * Author: Erik Elb (GoatrikWorks)
 * Author URI: mailto:goatrikworks@gmail.com
 * Text Domain: gw-elements
 * Domain Path: /languages
 * Requires at least: 6.4
 * Requires PHP: 8.2
 * Elementor tested up to: 3.34
 * Elementor Pro tested up to: 3.34
 * WC requires at least: 8.0
 * WC tested up to: 9.5
 *
 * @package GW_Elements
 */

namespace GW\Elements;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Plugin constants.
define( 'GW_ELEMENTS_VERSION', '1.0.40' );
define( 'GW_ELEMENTS_FILE', __FILE__ );
define( 'GW_ELEMENTS_PATH', plugin_dir_path( __FILE__ ) );
define( 'GW_ELEMENTS_URL', plugin_dir_url( __FILE__ ) );
define( 'GW_ELEMENTS_ASSETS_URL', GW_ELEMENTS_URL . 'assets/' );

/**
 * Autoloader for plugin classes.
 *
 * @param string $class The fully-qualified class name.
 */
spl_autoload_register( function ( $class ) {
    $prefix = 'GW\\Elements\\';
    $base_dir = GW_ELEMENTS_PATH . 'includes/';

    $len = strlen( $prefix );
    if ( strncmp( $prefix, $class, $len ) !== 0 ) {
        return;
    }

    $relative_class = substr( $class, $len );

    // Check in includes directory.
    $file = $base_dir . 'class-' . strtolower( str_replace( [ '\\', '_' ], [ '/', '-' ], $relative_class ) ) . '.php';
    if ( file_exists( $file ) ) {
        require $file;
        return;
    }

    // Check in widgets directory.
    $widgets_dir = GW_ELEMENTS_PATH . 'widgets/';
    $file = $widgets_dir . 'class-' . strtolower( str_replace( [ '\\', '_' ], [ '/', '-' ], $relative_class ) ) . '.php';
    if ( file_exists( $file ) ) {
        require $file;
    }
});

/**
 * Main plugin class.
 */
final class Plugin {

    /**
     * Singleton instance.
     *
     * @var Plugin|null
     */
    private static ?Plugin $instance = null;

    /**
     * Minimum Elementor version.
     */
    const MINIMUM_ELEMENTOR_VERSION = '3.20.0';

    /**
     * Minimum PHP version.
     */
    const MINIMUM_PHP_VERSION = '8.2';

    /**
     * Get singleton instance.
     *
     * @return Plugin
     */
    public static function instance(): Plugin {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor.
     */
    private function __construct() {
        add_action( 'plugins_loaded', [ $this, 'init' ] );
    }

    /**
     * Initialize the plugin.
     */
    public function init(): void {
        // Check requirements.
        if ( ! $this->check_requirements() ) {
            return;
        }

        // Load text domain.
        load_plugin_textdomain( 'gw-elements', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

        // Initialize components.
        Assets::instance();
        Elementor::instance();

        // Initialize WooCommerce integration if WC is active.
        if ( class_exists( 'WooCommerce' ) ) {
            WooCommerce::instance();
        }

        // Enqueue Google Fonts.
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_google_fonts' ] );
    }

    /**
     * Check if requirements are met.
     *
     * @return bool
     */
    private function check_requirements(): bool {
        // Check PHP version.
        if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
            add_action( 'admin_notices', function () {
                printf(
                    '<div class="notice notice-error"><p>%s</p></div>',
                    sprintf(
                        esc_html__( 'GW Elements requires PHP version %s or higher.', 'gw-elements' ),
                        self::MINIMUM_PHP_VERSION
                    )
                );
            });
            return false;
        }

        // Check if Elementor is installed and activated.
        if ( ! did_action( 'elementor/loaded' ) ) {
            add_action( 'admin_notices', function () {
                echo '<div class="notice notice-error"><p>';
                esc_html_e( 'GW Elements requires Elementor to be installed and activated.', 'gw-elements' );
                echo '</p></div>';
            });
            return false;
        }

        // Check Elementor version.
        if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
            add_action( 'admin_notices', function () {
                printf(
                    '<div class="notice notice-error"><p>%s</p></div>',
                    sprintf(
                        esc_html__( 'GW Elements requires Elementor version %s or higher.', 'gw-elements' ),
                        self::MINIMUM_ELEMENTOR_VERSION
                    )
                );
            });
            return false;
        }

        return true;
    }

    /**
     * Enqueue Google Fonts.
     */
    public function enqueue_google_fonts(): void {
        $fonts_url = 'https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&display=swap';

        wp_enqueue_style(
            'gw-elements-google-fonts',
            $fonts_url,
            [],
            GW_ELEMENTS_VERSION
        );
    }
}

// Initialize plugin.
Plugin::instance();
