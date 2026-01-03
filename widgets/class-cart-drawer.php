<?php
/**
 * Cart Drawer Widget.
 *
 * Slide-out mini cart panel matching the React CartDrawer component.
 *
 * @package GW_Elements
 */

namespace GW\Elements\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use GW\Elements\WooCommerce;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class Cart_Drawer
 */
class Cart_Drawer extends Widget_Base_GW {

    /**
     * Widget slug.
     *
     * @var string
     */
    protected string $widget_slug = 'cart-drawer';

    /**
     * Get widget name.
     *
     * @return string
     */
    public function get_name(): string {
        return 'gw-cart-drawer';
    }

    /**
     * Get widget title.
     *
     * @return string
     */
    public function get_title(): string {
        return esc_html__( 'GW Cart Drawer', 'gw-elements' );
    }

    /**
     * Get widget icon.
     *
     * @return string
     */
    public function get_icon(): string {
        return 'eicon-cart';
    }

    /**
     * Get widget keywords.
     *
     * @return array
     */
    public function get_keywords(): array {
        return [ 'gw', 'cart', 'drawer', 'mini-cart', 'woocommerce', 'laviasana' ];
    }

    /**
     * Get script dependencies.
     *
     * @return array
     */
    public function get_script_depends(): array {
        return [ 'gw-elements-frontend', 'gw-widget-cart-drawer' ];
    }

    /**
     * Get style dependencies.
     *
     * @return array
     */
    public function get_style_depends(): array {
        return [ 'gw-elements-frontend', 'gw-widget-cart-drawer' ];
    }

    /**
     * Register widget controls.
     */
    protected function register_controls(): void {
        // Content Section.
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__( 'Content', 'gw-elements' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'drawer_title',
            [
                'label'   => esc_html__( 'Drawer Title', 'gw-elements' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Carrello', 'gw-elements' ),
            ]
        );

        $this->add_control(
            'empty_title',
            [
                'label'   => esc_html__( 'Empty Cart Title', 'gw-elements' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Il tuo carrello è vuoto', 'gw-elements' ),
            ]
        );

        $this->add_control(
            'empty_text',
            [
                'label'   => esc_html__( 'Empty Cart Text', 'gw-elements' ),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => esc_html__( 'Aggiungi qualcosa per iniziare lo shopping!', 'gw-elements' ),
            ]
        );

        $this->add_control(
            'checkout_text',
            [
                'label'   => esc_html__( 'Checkout Button Text', 'gw-elements' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Procedi al checkout', 'gw-elements' ),
            ]
        );

        $this->add_control(
            'continue_text',
            [
                'label'   => esc_html__( 'Continue Shopping Text', 'gw-elements' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Continua lo shopping', 'gw-elements' ),
            ]
        );

        $this->add_control(
            'drawer_position',
            [
                'label'   => esc_html__( 'Drawer Position', 'gw-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'right',
                'options' => [
                    'left'  => esc_html__( 'Left', 'gw-elements' ),
                    'right' => esc_html__( 'Right', 'gw-elements' ),
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Drawer.
        $this->start_controls_section(
            'section_style_drawer',
            [
                'label' => esc_html__( 'Drawer', 'gw-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'drawer_width',
            [
                'label'      => esc_html__( 'Width', 'gw-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'vw' ],
                'range'      => [
                    'px' => [ 'min' => 280, 'max' => 600 ],
                    '%'  => [ 'min' => 20, 'max' => 100 ],
                    'vw' => [ 'min' => 20, 'max' => 100 ],
                ],
                'default'    => [ 'unit' => 'px', 'size' => 420 ],
                'selectors'  => [
                    '{{WRAPPER}} .gw-cart-drawer__panel' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'drawer_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .gw-cart-drawer__panel' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'overlay_color',
            [
                'label'     => esc_html__( 'Overlay Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'rgba(0, 0, 0, 0.5)',
                'selectors' => [
                    '{{WRAPPER}} .gw-cart-drawer__overlay' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Header.
        $this->start_controls_section(
            'section_style_header',
            [
                'label' => esc_html__( 'Header', 'gw-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'label'    => esc_html__( 'Title Typography', 'gw-elements' ),
                'selector' => '{{WRAPPER}} .gw-cart-drawer__title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__( 'Title Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-cart-drawer__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Items.
        $this->start_controls_section(
            'section_style_items',
            [
                'label' => esc_html__( 'Cart Items', 'gw-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'item_bg_color',
            [
                'label'     => esc_html__( 'Item Background', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-cart-item' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'item_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'gw-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 20 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .gw-cart-item' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Buttons.
        $this->start_controls_section(
            'section_style_buttons',
            [
                'label' => esc_html__( 'Buttons', 'gw-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'checkout_btn_bg',
            [
                'label'     => esc_html__( 'Checkout Button Background', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-cart-drawer__checkout-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'checkout_btn_color',
            [
                'label'     => esc_html__( 'Checkout Button Text Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-cart-drawer__checkout-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output.
     */
    protected function render(): void {
        $settings = $this->get_settings_for_display();

        // Check if WooCommerce is active.
        if ( ! class_exists( 'WooCommerce' ) ) {
            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                echo '<div class="gw-editor-notice">' . esc_html__( 'WooCommerce is required for this widget.', 'gw-elements' ) . '</div>';
            }
            return;
        }

        $position = $settings['drawer_position'] ?? 'right';
        $wc       = WooCommerce::instance();
        ?>
        <div class="gw-cart-drawer" data-position="<?php echo esc_attr( $position ); ?>">
            <!-- Overlay -->
            <div class="gw-cart-drawer__overlay" aria-hidden="true"></div>

            <!-- Panel -->
            <div class="gw-cart-drawer__panel" role="dialog" aria-modal="true" aria-labelledby="gw-cart-drawer-title">
                <!-- Header -->
                <header class="gw-cart-drawer__header">
                    <h2 id="gw-cart-drawer-title" class="gw-cart-drawer__title">
                        <?php echo $this->render_icon( 'shopping-bag', [ 'width' => '20', 'height' => '20' ] ); ?>
                        <span><?php echo esc_html( $settings['drawer_title'] ); ?></span>
                        <span class="gw-cart-drawer__count">(<span class="gw-cart-count"><?php echo esc_html( WC()->cart ? WC()->cart->get_cart_contents_count() : 0 ); ?></span>)</span>
                    </h2>
                    <button type="button" class="gw-cart-drawer__close" aria-label="<?php esc_attr_e( 'Chiudi carrello', 'gw-elements' ); ?>">
                        <?php echo $this->render_icon( 'x', [ 'width' => '24', 'height' => '24' ] ); ?>
                    </button>
                </header>

                <!-- Content -->
                <div class="gw-cart-drawer__body">
                    <div class="gw-mini-cart-content">
                        <?php echo $wc->render_mini_cart(); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
