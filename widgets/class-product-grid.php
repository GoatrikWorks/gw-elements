<?php
/**
 * Product Grid Widget.
 *
 * @package GW_Elements
 */

namespace GW\Elements\Widgets;

use Elementor\Controls_Manager;
use GW\Elements\WooCommerce;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class Product_Grid
 */
class Product_Grid extends Widget_Base_GW {

    /**
     * Widget slug.
     *
     * @var string
     */
    protected string $widget_slug = 'product-grid';

    /**
     * Get widget name.
     *
     * @return string
     */
    public function get_name(): string {
        return 'gw-product-grid';
    }

    /**
     * Get widget title.
     *
     * @return string
     */
    public function get_title(): string {
        return esc_html__( 'GW Product Grid', 'gw-elements' );
    }

    /**
     * Get widget icon.
     *
     * @return string
     */
    public function get_icon(): string {
        return 'eicon-products';
    }

    /**
     * Get widget keywords.
     *
     * @return array
     */
    public function get_keywords(): array {
        return [ 'gw', 'product', 'grid', 'shop', 'woocommerce', 'laviasana' ];
    }

    /**
     * Register widget controls.
     */
    protected function register_controls(): void {
        // Query Section.
        $this->start_controls_section(
            'section_query',
            [
                'label' => esc_html__( 'Query', 'gw-elements' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'products_per_page',
            [
                'label'   => esc_html__( 'Products Per Page', 'gw-elements' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 12,
                'min'     => 1,
                'max'     => 48,
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label'   => esc_html__( 'Order By', 'gw-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'date',
                'options' => [
                    'date'       => esc_html__( 'Date', 'gw-elements' ),
                    'title'      => esc_html__( 'Title', 'gw-elements' ),
                    'price'      => esc_html__( 'Price', 'gw-elements' ),
                    'popularity' => esc_html__( 'Popularity', 'gw-elements' ),
                    'rating'     => esc_html__( 'Rating', 'gw-elements' ),
                ],
            ]
        );

        $this->add_control(
            'order',
            [
                'label'   => esc_html__( 'Order', 'gw-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'DESC',
                'options' => [
                    'ASC'  => esc_html__( 'Ascending', 'gw-elements' ),
                    'DESC' => esc_html__( 'Descending', 'gw-elements' ),
                ],
            ]
        );

        // Get WooCommerce categories.
        if ( class_exists( 'WooCommerce' ) ) {
            $categories = get_terms( [
                'taxonomy'   => 'product_cat',
                'hide_empty' => true,
            ] );

            $cat_options = [ '' => esc_html__( 'All Categories', 'gw-elements' ) ];
            if ( ! is_wp_error( $categories ) ) {
                foreach ( $categories as $cat ) {
                    $cat_options[ $cat->slug ] = $cat->name;
                }
            }

            $this->add_control(
                'category',
                [
                    'label'   => esc_html__( 'Category', 'gw-elements' ),
                    'type'    => Controls_Manager::SELECT,
                    'options' => $cat_options,
                ]
            );
        }

        $this->end_controls_section();

        // Layout Section.
        $this->start_controls_section(
            'section_layout',
            [
                'label' => esc_html__( 'Layout', 'gw-elements' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label'          => esc_html__( 'Columns', 'gw-elements' ),
                'type'           => Controls_Manager::SELECT,
                'default'        => '4',
                'tablet_default' => '3',
                'mobile_default' => '2',
                'options'        => [
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                ],
            ]
        );

        $this->add_control(
            'show_pagination',
            [
                'label'        => esc_html__( 'Show Pagination', 'gw-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'gw-elements' ),
                'label_off'    => esc_html__( 'No', 'gw-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'enable_ajax_filter',
            [
                'label'        => esc_html__( 'Enable AJAX Filtering', 'gw-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'gw-elements' ),
                'label_off'    => esc_html__( 'No', 'gw-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->end_controls_section();

        // Style Section.
        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__( 'Style', 'gw-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'gap',
            [
                'label'      => esc_html__( 'Gap', 'gw-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 60,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 4,
                    ],
                ],
                'default'    => [
                    'unit' => 'rem',
                    'size' => 1.5,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .gw-product-grid__items' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Animation controls.
        $this->add_animation_controls();
    }

    /**
     * Render widget output.
     */
    protected function render(): void {
        $settings = $this->get_settings_for_display();

        if ( ! class_exists( 'WooCommerce' ) ) {
            echo '<p class="gw-no-products">' . esc_html__( 'WooCommerce is required for this widget.', 'gw-elements' ) . '</p>';
            return;
        }

        // Build query args.
        $args = [
            'status'  => 'publish',
            'limit'   => (int) $settings['products_per_page'],
            'orderby' => $settings['orderby'],
            'order'   => $settings['order'],
            'return'  => 'objects',
        ];

        if ( ! empty( $settings['category'] ) ) {
            $args['category'] = [ $settings['category'] ];
        }

        $products = wc_get_products( $args );

        $columns = $settings['columns'] ?? '4';

        $this->add_render_attribute( 'wrapper', [
            'class'        => 'gw-product-grid',
            'data-ajax'    => 'yes' === $settings['enable_ajax_filter'] ? 'true' : 'false',
            'data-columns' => $columns,
        ] );
        $this->add_animation_wrapper_attributes( $settings );
        ?>
        <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
            <div class="gw-product-grid__items gw-grid gw-grid--<?php echo esc_attr( $columns ); ?>">
                <?php if ( ! empty( $products ) ) : ?>
                    <?php foreach ( $products as $product ) : ?>
                        <?php echo WooCommerce::render_product_card( $product ); ?>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p class="gw-no-products"><?php esc_html_e( 'No products found.', 'gw-elements' ); ?></p>
                <?php endif; ?>
            </div>

            <?php if ( 'yes' === $settings['show_pagination'] ) : ?>
                <div class="gw-product-grid__pagination">
                    <?php // Pagination would be handled by WooCommerce or custom implementation ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}
