<?php
/**
 * Elementor integration class.
 *
 * @package GW_Elements
 */

namespace GW\Elements;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class Elementor
 *
 * Handles Elementor integration, widget registration, and category registration.
 */
class Elementor {

    /**
     * Singleton instance.
     *
     * @var Elementor|null
     */
    private static ?Elementor $instance = null;

    /**
     * Widget category slug.
     */
    const CATEGORY_SLUG = 'gw-elements';

    /**
     * List of widget classes.
     *
     * @var array
     */
    private array $widgets = [];

    /**
     * Get singleton instance.
     *
     * @return Elementor
     */
    public static function instance(): Elementor {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor.
     */
    private function __construct() {
        // Register widget category.
        add_action( 'elementor/elements/categories_registered', [ $this, 'register_category' ] );

        // Register widgets.
        add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );

        // Register controls.
        add_action( 'elementor/controls/register', [ $this, 'register_controls' ] );
    }

    /**
     * Register widget category.
     *
     * @param \Elementor\Elements_Manager $elements_manager Elements manager instance.
     */
    public function register_category( \Elementor\Elements_Manager $elements_manager ): void {
        $elements_manager->add_category(
            self::CATEGORY_SLUG,
            [
                'title' => esc_html__( 'GW Elements', 'gw-elements' ),
                'icon'  => 'eicon-apps',
            ]
        );
    }

    /**
     * Register all widgets.
     *
     * @param \Elementor\Widgets_Manager $widgets_manager Widgets manager instance.
     */
    public function register_widgets( \Elementor\Widgets_Manager $widgets_manager ): void {
        // Load base widget class first.
        require_once GW_ELEMENTS_PATH . 'widgets/class-widget-base.php';

        // Define widgets to register.
        $this->widgets = [
            // Layout widgets
            'Header',
            'Footer',

            // Hero widgets
            'Video_Hero',
            'Image_Hero',
            'Page_Header',

            // Product widgets (WooCommerce)
            'Product_Slider',
            'Product_Grid',
            'Product_Card',
            'Product_Tabs',
            'Category_Slider',
            'Category_Card',
            'Cart_Drawer',
            'Search_Modal',

            // Content widgets
            'Story_Card',
            'Storytelling',
            'Service_Card',
            'Trust_Points',
            'Values_Grid',
            'Feature_Highlight',
            'CTA_Section',
            'FAQ_Accordion',
            'Upcoming_Events',

            // Form widgets
            'Contact_Form',
            'Contact_Info',
            'Newsletter',
        ];

        // Require and register each widget.
        foreach ( $this->widgets as $widget_class ) {
            $file_name = 'class-' . strtolower( str_replace( '_', '-', $widget_class ) ) . '.php';
            $file_path = GW_ELEMENTS_PATH . 'widgets/' . $file_name;

            if ( file_exists( $file_path ) ) {
                require_once $file_path;

                $class_name = 'GW\\Elements\\Widgets\\' . $widget_class;

                if ( class_exists( $class_name ) ) {
                    $widgets_manager->register( new $class_name() );
                }
            }
        }
    }

    /**
     * Register custom controls if needed.
     *
     * @param \Elementor\Controls_Manager $controls_manager Controls manager instance.
     */
    public function register_controls( \Elementor\Controls_Manager $controls_manager ): void {
        // Custom controls can be registered here if needed.
    }

    /**
     * Get category slug.
     *
     * @return string
     */
    public static function get_category(): string {
        return self::CATEGORY_SLUG;
    }
}
