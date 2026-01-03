<?php
/**
 * Product Slider Widget.
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
 * Class Product_Slider
 */
class Product_Slider extends Widget_Base_GW {

    /**
     * Widget slug.
     *
     * @var string
     */
    protected string $widget_slug = 'product-slider';

    /**
     * Whether the widget requires the carousel library.
     *
     * @var bool
     */
    protected bool $requires_carousel = true;

    /**
     * Get widget name.
     *
     * @return string
     */
    public function get_name(): string {
        return 'gw-product-slider';
    }

    /**
     * Get widget title.
     *
     * @return string
     */
    public function get_title(): string {
        return esc_html__( 'GW Product Slider', 'gw-elements' );
    }

    /**
     * Get style dependencies.
     *
     * @return array
     */
    public function get_style_depends(): array {
        $styles = parent::get_style_depends();
        $styles[] = 'gw-widget-product-card';
        return $styles;
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
        return [ 'gw', 'product', 'slider', 'carousel', 'woocommerce', 'laviasana' ];
    }

    /**
     * Register widget controls.
     */
    protected function register_controls(): void {
        // Header Section.
        $this->start_controls_section(
            'section_header',
            [
                'label' => esc_html__( 'Header', 'gw-elements' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'subtitle',
            [
                'label'       => esc_html__( 'Subtitle', 'gw-elements' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'I Nostri Prodotti',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'title',
            [
                'label'       => esc_html__( 'Title', 'gw-elements' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'In Evidenza',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'show_view_all',
            [
                'label'        => esc_html__( 'Show View All Link', 'gw-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'gw-elements' ),
                'label_off'    => esc_html__( 'No', 'gw-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'view_all_text',
            [
                'label'     => esc_html__( 'View All Text', 'gw-elements' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => 'Vedi tutti',
                'condition' => [
                    'show_view_all' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'view_all_link',
            [
                'label'       => esc_html__( 'View All Link', 'gw-elements' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => '/shop',
                'condition'   => [
                    'show_view_all' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Query Section.
        $this->start_controls_section(
            'section_query',
            [
                'label' => esc_html__( 'Products Query', 'gw-elements' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'products_type',
            [
                'label'   => esc_html__( 'Products Type', 'gw-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'new',
                'options' => [
                    'new'      => esc_html__( 'New Arrivals', 'gw-elements' ),
                    'featured' => esc_html__( 'Featured', 'gw-elements' ),
                    'sale'     => esc_html__( 'On Sale', 'gw-elements' ),
                    'best'     => esc_html__( 'Best Selling', 'gw-elements' ),
                    'category' => esc_html__( 'By Category', 'gw-elements' ),
                ],
            ]
        );

        // Get WooCommerce categories.
        if ( class_exists( 'WooCommerce' ) || class_exists( '\WooCommerce' ) ) {
            $categories = get_terms( [
                'taxonomy'   => 'product_cat',
                'hide_empty' => true,
            ] );

            $cat_options = [];
            if ( ! is_wp_error( $categories ) ) {
                foreach ( $categories as $cat ) {
                    $cat_options[ $cat->slug ] = $cat->name;
                }
            }

            $this->add_control(
                'category',
                [
                    'label'     => esc_html__( 'Category', 'gw-elements' ),
                    'type'      => Controls_Manager::SELECT2,
                    'options'   => $cat_options,
                    'condition' => [
                        'products_type' => 'category',
                    ],
                ]
            );
        }

        $this->add_control(
            'limit',
            [
                'label'   => esc_html__( 'Number of Products', 'gw-elements' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 8,
                'min'     => 1,
                'max'     => 24,
            ]
        );

        $this->end_controls_section();

        // Slider Settings.
        $this->start_controls_section(
            'section_slider',
            [
                'label' => esc_html__( 'Slider Settings', 'gw-elements' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label'        => esc_html__( 'Autoplay', 'gw-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'gw-elements' ),
                'label_off'    => esc_html__( 'No', 'gw-elements' ),
                'return_value' => 'yes',
                'default'      => '',
            ]
        );

        $this->add_control(
            'show_arrows',
            [
                'label'        => esc_html__( 'Show Arrows', 'gw-elements' ),
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
            'padding',
            [
                'label'      => esc_html__( 'Padding', 'gw-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'rem' ],
                'default'    => [
                    'top'    => '3',
                    'right'  => '0',
                    'bottom' => '3',
                    'left'   => '0',
                    'unit'   => 'rem',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .gw-product-slider' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'background_color',
            [
                'label'     => esc_html__( 'Background Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-product-slider' => 'background-color: {{VALUE}};',
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

        // Check if WooCommerce is active.
        if ( ! class_exists( 'WooCommerce' ) && ! class_exists( '\WooCommerce' ) ) {
            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                echo '<div style="padding: 2rem; text-align: center; background: #fff3cd; border: 1px solid #ffc107; border-radius: 4px;">';
                echo '<p style="margin: 0; color: #856404;">WooCommerce is not active. Please install and activate WooCommerce to use this widget.</p>';
                echo '</div>';
            }
            return;
        }

        // Get products.
        $products = $this->get_products( $settings );

        if ( empty( $products ) ) {
            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                echo '<div style="padding: 2rem; text-align: center; background: #f5f5f5; border: 1px dashed #ccc; border-radius: 4px;">';
                echo '<p style="margin: 0 0 0.5rem; color: #666;">No products found.</p>';
                echo '<p style="margin: 0; color: #999; font-size: 0.875rem;">Try changing the "Products Type" setting or add some products to WooCommerce.</p>';
                echo '</div>';
            }
            return;
        }

        // Build slider config.
        $slider_config = [
            'type'         => 'loop',
            'perPage'      => 4,
            'perMove'      => 1,
            'gap'          => '1rem',
            'pagination'   => false,
            'arrows'       => false,
            'autoplay'     => 'yes' === $settings['autoplay'],
            'interval'     => 5000,
            'pauseOnHover' => true,
            'breakpoints'  => [
                1280 => [ 'perPage' => 4 ],
                1024 => [ 'perPage' => 3 ],
                768  => [ 'perPage' => 2 ],
                480  => [ 'perPage' => 2, 'gap' => '0.75rem' ],
            ],
        ];

        $this->add_render_attribute( 'wrapper', [
            'class'              => 'gw-product-slider',
            'data-slider-config' => wp_json_encode( $slider_config ),
        ] );
        $this->add_animation_wrapper_attributes( $settings );
        ?>
        <section <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
            <div class="gw-product-slider__container gw-container-wide">
                <div class="gw-product-slider__header">
                    <div class="gw-product-slider__header-text">
                        <?php if ( ! empty( $settings['subtitle'] ) ) : ?>
                            <span class="gw-section-subtitle"><?php echo esc_html( $settings['subtitle'] ); ?></span>
                        <?php endif; ?>
                        <?php if ( ! empty( $settings['title'] ) ) : ?>
                            <h2 class="gw-section-title"><?php echo esc_html( $settings['title'] ); ?></h2>
                        <?php endif; ?>
                    </div>

                    <?php if ( 'yes' === $settings['show_arrows'] ) : ?>
                        <div class="gw-product-slider__arrows">
                            <button type="button" class="gw-product-slider__arrow gw-product-slider__arrow--prev">
                                <?php echo $this->render_icon( 'chevron-left' ); ?>
                            </button>
                            <button type="button" class="gw-product-slider__arrow gw-product-slider__arrow--next">
                                <?php echo $this->render_icon( 'chevron-right' ); ?>
                            </button>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="gw-product-slider__slider splide">
                    <div class="splide__track">
                        <ul class="splide__list">
                            <?php foreach ( $products as $product ) : ?>
                                <li class="splide__slide">
                                    <?php echo WooCommerce::render_product_card( $product ); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <?php if ( 'yes' === $settings['show_arrows'] ) : ?>
                    <div class="gw-product-slider__mobile-arrows">
                        <button type="button" class="gw-product-slider__arrow gw-product-slider__arrow--prev">
                            <?php echo $this->render_icon( 'chevron-left' ); ?>
                        </button>
                        <button type="button" class="gw-product-slider__arrow gw-product-slider__arrow--next">
                            <?php echo $this->render_icon( 'chevron-right' ); ?>
                        </button>
                    </div>
                <?php endif; ?>

                <?php if ( 'yes' === $settings['show_view_all'] && ! empty( $settings['view_all_text'] ) ) : ?>
                    <div class="gw-product-slider__footer">
                        <a href="<?php echo esc_url( $settings['view_all_link']['url'] ?? '/shop' ); ?>" class="gw-button gw-button--editorial">
                            <?php echo esc_html( $settings['view_all_text'] ); ?>
                            <?php echo $this->render_icon( 'arrow-right', [ 'width' => '16', 'height' => '16' ] ); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        <?php
    }

    /**
     * Get products based on settings.
     *
     * @param array $settings Widget settings.
     * @return array
     */
    private function get_products( array $settings ): array {
        if ( ! class_exists( 'WooCommerce' ) && ! class_exists( '\WooCommerce' ) ) {
            return [];
        }

        $args = [
            'status'  => 'publish',
            'limit'   => (int) $settings['limit'],
            'return'  => 'objects',
        ];

        switch ( $settings['products_type'] ) {
            case 'featured':
                $args['featured'] = true;
                break;
            case 'new':
                $args['orderby'] = 'date';
                $args['order']   = 'DESC';
                break;
            case 'sale':
                $args['on_sale'] = true;
                break;
            case 'best':
                $args['orderby']  = 'meta_value_num';
                $args['meta_key'] = 'total_sales';
                $args['order']    = 'DESC';
                break;
            case 'category':
                if ( ! empty( $settings['category'] ) ) {
                    $args['category'] = [ $settings['category'] ];
                }
                break;
        }

        return wc_get_products( $args );
    }
}
