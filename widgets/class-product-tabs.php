<?php
/**
 * Product Tabs Widget.
 *
 * Custom product tabs matching the React ProductPage component.
 * Displays Description, Details, Shipping, and Reviews tabs.
 *
 * @package GW_Elements
 */

namespace GW\Elements\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class Product_Tabs
 */
class Product_Tabs extends Widget_Base_GW {

    /**
     * Widget slug.
     *
     * @var string
     */
    protected string $widget_slug = 'product-tabs';

    /**
     * Get widget name.
     *
     * @return string
     */
    public function get_name(): string {
        return 'gw-product-tabs';
    }

    /**
     * Get widget title.
     *
     * @return string
     */
    public function get_title(): string {
        return esc_html__( 'GW Product Tabs', 'gw-elements' );
    }

    /**
     * Get widget icon.
     *
     * @return string
     */
    public function get_icon(): string {
        return 'eicon-tabs';
    }

    /**
     * Get widget keywords.
     *
     * @return array
     */
    public function get_keywords(): array {
        return [ 'gw', 'product', 'tabs', 'description', 'reviews', 'woocommerce', 'laviasana' ];
    }

    /**
     * Get script dependencies.
     *
     * @return array
     */
    public function get_script_depends(): array {
        return [ 'gw-elements-frontend', 'gw-widget-product-tabs' ];
    }

    /**
     * Get style dependencies.
     *
     * @return array
     */
    public function get_style_depends(): array {
        return [ 'gw-elements-frontend', 'gw-widget-product-tabs' ];
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
            'source',
            [
                'label'   => esc_html__( 'Source', 'gw-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'dynamic',
                'options' => [
                    'dynamic' => esc_html__( 'Current Product (Dynamic)', 'gw-elements' ),
                    'custom'  => esc_html__( 'Custom Content', 'gw-elements' ),
                ],
            ]
        );

        // Custom Tabs
        $repeater = new Repeater();

        $repeater->add_control(
            'tab_title',
            [
                'label'   => esc_html__( 'Tab Title', 'gw-elements' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Tab', 'gw-elements' ),
            ]
        );

        $repeater->add_control(
            'tab_content',
            [
                'label'   => esc_html__( 'Tab Content', 'gw-elements' ),
                'type'    => Controls_Manager::WYSIWYG,
                'default' => '',
            ]
        );

        $this->add_control(
            'custom_tabs',
            [
                'label'     => esc_html__( 'Tabs', 'gw-elements' ),
                'type'      => Controls_Manager::REPEATER,
                'fields'    => $repeater->get_controls(),
                'default'   => [
                    [ 'tab_title' => esc_html__( 'Description', 'gw-elements' ) ],
                    [ 'tab_title' => esc_html__( 'Details', 'gw-elements' ) ],
                    [ 'tab_title' => esc_html__( 'Shipping', 'gw-elements' ) ],
                    [ 'tab_title' => esc_html__( 'Reviews', 'gw-elements' ) ],
                ],
                'title_field' => '{{{ tab_title }}}',
                'condition' => [
                    'source' => 'custom',
                ],
            ]
        );

        // Dynamic tabs settings
        $this->add_control(
            'show_description',
            [
                'label'     => esc_html__( 'Show Description Tab', 'gw-elements' ),
                'type'      => Controls_Manager::SWITCHER,
                'default'   => 'yes',
                'condition' => [ 'source' => 'dynamic' ],
            ]
        );

        $this->add_control(
            'description_title',
            [
                'label'     => esc_html__( 'Description Tab Title', 'gw-elements' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Description', 'gw-elements' ),
                'condition' => [ 'source' => 'dynamic', 'show_description' => 'yes' ],
            ]
        );

        $this->add_control(
            'show_attributes',
            [
                'label'     => esc_html__( 'Show Details Tab', 'gw-elements' ),
                'type'      => Controls_Manager::SWITCHER,
                'default'   => 'yes',
                'condition' => [ 'source' => 'dynamic' ],
            ]
        );

        $this->add_control(
            'attributes_title',
            [
                'label'     => esc_html__( 'Details Tab Title', 'gw-elements' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Details', 'gw-elements' ),
                'condition' => [ 'source' => 'dynamic', 'show_attributes' => 'yes' ],
            ]
        );

        $this->add_control(
            'show_shipping',
            [
                'label'     => esc_html__( 'Show Shipping Tab', 'gw-elements' ),
                'type'      => Controls_Manager::SWITCHER,
                'default'   => 'yes',
                'condition' => [ 'source' => 'dynamic' ],
            ]
        );

        $this->add_control(
            'shipping_title',
            [
                'label'     => esc_html__( 'Shipping Tab Title', 'gw-elements' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Shipping', 'gw-elements' ),
                'condition' => [ 'source' => 'dynamic', 'show_shipping' => 'yes' ],
            ]
        );

        $this->add_control(
            'shipping_content',
            [
                'label'     => esc_html__( 'Shipping Content', 'gw-elements' ),
                'type'      => Controls_Manager::WYSIWYG,
                'default'   => '<p>Free standard shipping on orders over €50. Delivery within 3-5 business days.</p><p>Express shipping available at checkout for €8.95. Delivery within 1-2 business days.</p><p>We ship to all EU countries and the UK. International shipping rates calculated at checkout.</p>',
                'condition' => [ 'source' => 'dynamic', 'show_shipping' => 'yes' ],
            ]
        );

        $this->add_control(
            'show_reviews',
            [
                'label'     => esc_html__( 'Show Reviews Tab', 'gw-elements' ),
                'type'      => Controls_Manager::SWITCHER,
                'default'   => 'yes',
                'condition' => [ 'source' => 'dynamic' ],
            ]
        );

        $this->add_control(
            'reviews_title',
            [
                'label'     => esc_html__( 'Reviews Tab Title', 'gw-elements' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Reviews', 'gw-elements' ),
                'condition' => [ 'source' => 'dynamic', 'show_reviews' => 'yes' ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Tabs.
        $this->start_controls_section(
            'section_style_tabs',
            [
                'label' => esc_html__( 'Tabs', 'gw-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'tab_typography',
                'label'    => esc_html__( 'Tab Typography', 'gw-elements' ),
                'selector' => '{{WRAPPER}} .gw-product-tabs__tab',
            ]
        );

        $this->add_control(
            'tab_color',
            [
                'label'     => esc_html__( 'Tab Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-product-tabs__tab' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tab_active_color',
            [
                'label'     => esc_html__( 'Active Tab Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-product-tabs__tab.is-active' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tab_indicator_color',
            [
                'label'     => esc_html__( 'Active Indicator Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-product-tabs__tab.is-active::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tab_gap',
            [
                'label'      => esc_html__( 'Tab Gap', 'gw-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'range'      => [
                    'px'  => [ 'min' => 0, 'max' => 100 ],
                    'rem' => [ 'min' => 0, 'max' => 6 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .gw-product-tabs__nav' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Content.
        $this->start_controls_section(
            'section_style_content',
            [
                'label' => esc_html__( 'Content', 'gw-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'content_typography',
                'label'    => esc_html__( 'Content Typography', 'gw-elements' ),
                'selector' => '{{WRAPPER}} .gw-product-tabs__content',
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label'     => esc_html__( 'Content Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-product-tabs__content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'content_padding',
            [
                'label'      => esc_html__( 'Content Padding', 'gw-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'rem', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .gw-product-tabs__panel' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

        $tabs = $this->get_tabs( $settings );

        if ( empty( $tabs ) ) {
            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                echo '<div class="gw-editor-notice">' . esc_html__( 'No tabs to display.', 'gw-elements' ) . '</div>';
            }
            return;
        }

        $widget_id = $this->get_id();
        ?>
        <div class="gw-product-tabs" data-widget-id="<?php echo esc_attr( $widget_id ); ?>">
            <!-- Tab Navigation -->
            <div class="gw-product-tabs__nav-wrapper">
                <nav class="gw-product-tabs__nav" role="tablist">
                    <?php foreach ( $tabs as $index => $tab ) : ?>
                        <button
                            type="button"
                            class="gw-product-tabs__tab<?php echo 0 === $index ? ' is-active' : ''; ?>"
                            role="tab"
                            aria-selected="<?php echo 0 === $index ? 'true' : 'false'; ?>"
                            aria-controls="<?php echo esc_attr( $widget_id . '-panel-' . $index ); ?>"
                            id="<?php echo esc_attr( $widget_id . '-tab-' . $index ); ?>"
                            data-tab-index="<?php echo esc_attr( $index ); ?>"
                        >
                            <?php echo esc_html( $tab['title'] ); ?>
                        </button>
                    <?php endforeach; ?>
                </nav>
            </div>

            <!-- Tab Panels -->
            <div class="gw-product-tabs__content">
                <?php foreach ( $tabs as $index => $tab ) : ?>
                    <div
                        class="gw-product-tabs__panel<?php echo 0 === $index ? ' is-active' : ''; ?>"
                        role="tabpanel"
                        id="<?php echo esc_attr( $widget_id . '-panel-' . $index ); ?>"
                        aria-labelledby="<?php echo esc_attr( $widget_id . '-tab-' . $index ); ?>"
                        <?php echo 0 !== $index ? 'hidden' : ''; ?>
                    >
                        <div class="gw-product-tabs__panel-content">
                            <?php echo wp_kses_post( $tab['content'] ); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }

    /**
     * Get tabs based on settings.
     *
     * @param array $settings Widget settings.
     * @return array
     */
    private function get_tabs( array $settings ): array {
        if ( 'custom' === $settings['source'] ) {
            return $this->get_custom_tabs( $settings );
        }

        return $this->get_dynamic_tabs( $settings );
    }

    /**
     * Get custom tabs from repeater.
     *
     * @param array $settings Widget settings.
     * @return array
     */
    private function get_custom_tabs( array $settings ): array {
        $tabs = [];

        if ( ! empty( $settings['custom_tabs'] ) ) {
            foreach ( $settings['custom_tabs'] as $tab ) {
                $tabs[] = [
                    'title'   => $tab['tab_title'],
                    'content' => $tab['tab_content'],
                ];
            }
        }

        return $tabs;
    }

    /**
     * Get dynamic tabs from WooCommerce product.
     *
     * @param array $settings Widget settings.
     * @return array
     */
    private function get_dynamic_tabs( array $settings ): array {
        $tabs = [];

        // Check if we're on a product page or in editor.
        global $product;

        if ( ! $product && function_exists( 'wc_get_product' ) ) {
            // In editor, try to get a sample product.
            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                $products = wc_get_products( [ 'limit' => 1 ] );
                $product  = $products[0] ?? null;
            }
        }

        // Description Tab
        if ( 'yes' === $settings['show_description'] ) {
            $description = '';
            if ( $product && is_a( $product, 'WC_Product' ) ) {
                $description = $product->get_description();
            }

            $tabs[] = [
                'title'   => $settings['description_title'] ?? __( 'Description', 'gw-elements' ),
                'content' => $description ?: '<p class="gw-text-muted">' . __( 'No description available.', 'gw-elements' ) . '</p>',
            ];
        }

        // Details/Attributes Tab
        if ( 'yes' === $settings['show_attributes'] ) {
            $details_html = '';
            if ( $product && is_a( $product, 'WC_Product' ) ) {
                $attributes = $product->get_attributes();

                if ( ! empty( $attributes ) ) {
                    $details_html .= '<dl class="gw-product-details">';
                    foreach ( $attributes as $attribute ) {
                        $name  = wc_attribute_label( $attribute->get_name() );
                        $value = $attribute->is_taxonomy()
                            ? implode( ', ', wc_get_product_terms( $product->get_id(), $attribute->get_name(), [ 'fields' => 'names' ] ) )
                            : implode( ', ', $attribute->get_options() );

                        $details_html .= sprintf(
                            '<div class="gw-product-details__item"><dt>%s</dt><dd>%s</dd></div>',
                            esc_html( $name ),
                            esc_html( $value )
                        );
                    }
                    $details_html .= '</dl>';
                }

                // Add weight and dimensions if available.
                if ( $product->has_weight() || $product->has_dimensions() ) {
                    $details_html .= '<dl class="gw-product-details">';
                    if ( $product->has_weight() ) {
                        $details_html .= sprintf(
                            '<div class="gw-product-details__item"><dt>%s</dt><dd>%s</dd></div>',
                            esc_html__( 'Weight', 'gw-elements' ),
                            esc_html( wc_format_weight( $product->get_weight() ) )
                        );
                    }
                    if ( $product->has_dimensions() ) {
                        $details_html .= sprintf(
                            '<div class="gw-product-details__item"><dt>%s</dt><dd>%s</dd></div>',
                            esc_html__( 'Dimensions', 'gw-elements' ),
                            esc_html( wc_format_dimensions( $product->get_dimensions( false ) ) )
                        );
                    }
                    $details_html .= '</dl>';
                }
            }

            $tabs[] = [
                'title'   => $settings['attributes_title'] ?? __( 'Details', 'gw-elements' ),
                'content' => $details_html ?: '<p class="gw-text-muted">' . __( 'No details available.', 'gw-elements' ) . '</p>',
            ];
        }

        // Shipping Tab
        if ( 'yes' === $settings['show_shipping'] ) {
            $tabs[] = [
                'title'   => $settings['shipping_title'] ?? __( 'Shipping', 'gw-elements' ),
                'content' => $settings['shipping_content'] ?? '',
            ];
        }

        // Reviews Tab
        if ( 'yes' === $settings['show_reviews'] ) {
            $reviews_html = '';

            if ( $product && is_a( $product, 'WC_Product' ) && comments_open( $product->get_id() ) ) {
                ob_start();
                comments_template();
                $reviews_html = ob_get_clean();
            }

            if ( empty( $reviews_html ) ) {
                $reviews_html = '<p class="gw-text-muted">' . __( 'No reviews yet. Be the first to share your experience.', 'gw-elements' ) . '</p>';
            }

            $tabs[] = [
                'title'   => $settings['reviews_title'] ?? __( 'Reviews', 'gw-elements' ),
                'content' => $reviews_html,
            ];
        }

        return $tabs;
    }
}
