<?php
/**
 * Product Card Widget.
 *
 * @package GW_Elements
 */

namespace GW\Elements\Widgets;

use Elementor\Controls_Manager;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class Product_Card
 */
class Product_Card extends Widget_Base_GW {

    /**
     * Widget slug.
     *
     * @var string
     */
    protected string $widget_slug = 'product-card';

    /**
     * Get widget name.
     *
     * @return string
     */
    public function get_name(): string {
        return 'gw-product-card';
    }

    /**
     * Get widget title.
     *
     * @return string
     */
    public function get_title(): string {
        return esc_html__( 'GW Product Card', 'gw-elements' );
    }

    /**
     * Get widget icon.
     *
     * @return string
     */
    public function get_icon(): string {
        return 'eicon-product-images';
    }

    /**
     * Get widget keywords.
     *
     * @return array
     */
    public function get_keywords(): array {
        return [ 'gw', 'product', 'card', 'woocommerce', 'laviasana' ];
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
                'default' => 'woocommerce',
                'options' => [
                    'woocommerce' => esc_html__( 'WooCommerce Product', 'gw-elements' ),
                    'manual'      => esc_html__( 'Manual', 'gw-elements' ),
                ],
            ]
        );

        // WooCommerce product select.
        if ( class_exists( 'WooCommerce' ) ) {
            $products = wc_get_products( [
                'status' => 'publish',
                'limit'  => -1,
                'return' => 'objects',
            ] );

            $options = [];
            foreach ( $products as $product ) {
                $options[ $product->get_id() ] = $product->get_name();
            }

            $this->add_control(
                'product_id',
                [
                    'label'     => esc_html__( 'Product', 'gw-elements' ),
                    'type'      => Controls_Manager::SELECT2,
                    'options'   => $options,
                    'condition' => [
                        'source' => 'woocommerce',
                    ],
                ]
            );
        }

        // Manual inputs.
        $this->add_control(
            'image',
            [
                'label'     => esc_html__( 'Image', 'gw-elements' ),
                'type'      => Controls_Manager::MEDIA,
                'condition' => [
                    'source' => 'manual',
                ],
            ]
        );

        $this->add_control(
            'category',
            [
                'label'     => esc_html__( 'Category', 'gw-elements' ),
                'type'      => Controls_Manager::TEXT,
                'condition' => [
                    'source' => 'manual',
                ],
            ]
        );

        $this->add_control(
            'title',
            [
                'label'     => esc_html__( 'Title', 'gw-elements' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => 'Product Name',
                'condition' => [
                    'source' => 'manual',
                ],
            ]
        );

        $this->add_control(
            'price',
            [
                'label'     => esc_html__( 'Price', 'gw-elements' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => '€29.90',
                'condition' => [
                    'source' => 'manual',
                ],
            ]
        );

        $this->add_control(
            'link',
            [
                'label'     => esc_html__( 'Link', 'gw-elements' ),
                'type'      => Controls_Manager::URL,
                'condition' => [
                    'source' => 'manual',
                ],
            ]
        );

        $this->add_control(
            'show_add_to_cart',
            [
                'label'        => esc_html__( 'Show Add to Cart', 'gw-elements' ),
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
            'image_aspect',
            [
                'label'   => esc_html__( 'Image Aspect Ratio', 'gw-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => '4-3',
                'options' => [
                    'square' => esc_html__( 'Square (1:1)', 'gw-elements' ),
                    '4-3'    => esc_html__( '4:3', 'gw-elements' ),
                    '3-4'    => esc_html__( '3:4', 'gw-elements' ),
                    '16-9'   => esc_html__( '16:9', 'gw-elements' ),
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

        // Get product data.
        $product_data = $this->get_product_data( $settings );

        if ( empty( $product_data['title'] ) ) {
            return;
        }

        $aspect_class = 'gw-aspect-' . $settings['image_aspect'];
        ?>
        <article class="gw-product-card" data-product-id="<?php echo esc_attr( $product_data['id'] ?? '' ); ?>">
            <a href="<?php echo esc_url( $product_data['link'] ); ?>" class="gw-product-card__image-link">
                <div class="gw-product-card__image <?php echo esc_attr( $aspect_class ); ?> gw-image-zoom">
                    <?php if ( ! empty( $product_data['image'] ) ) : ?>
                        <img src="<?php echo esc_url( $product_data['image'] ); ?>" alt="<?php echo esc_attr( $product_data['title'] ); ?>" loading="lazy" decoding="async">
                    <?php endif; ?>
                </div>
            </a>
            <div class="gw-product-card__content">
                <?php if ( ! empty( $product_data['category'] ) ) : ?>
                    <span class="gw-product-card__category"><?php echo esc_html( $product_data['category'] ); ?></span>
                <?php endif; ?>
                <a href="<?php echo esc_url( $product_data['link'] ); ?>">
                    <h3 class="gw-product-card__title"><?php echo esc_html( $product_data['title'] ); ?></h3>
                </a>
                <span class="gw-product-card__price"><?php echo wp_kses_post( $product_data['price'] ); ?></span>

                <?php if ( 'yes' === $settings['show_add_to_cart'] && ! empty( $product_data['id'] ) ) : ?>
                    <button type="button" class="gw-product-card__button gw-button gw-button--card" data-product-id="<?php echo esc_attr( $product_data['id'] ); ?>">
                        <span class="gw-button__text"><?php esc_html_e( 'Aggiungi al carrello', 'gw-elements' ); ?></span>
                        <span class="gw-button__loading" style="display: none;">
                            <svg class="gw-spinner" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" fill="none" stroke-linecap="round" stroke-dasharray="31.4 31.4" /></svg>
                        </span>
                    </button>
                <?php endif; ?>
            </div>
        </article>
        <?php
    }

    /**
     * Get product data from settings.
     *
     * @param array $settings Widget settings.
     * @return array
     */
    private function get_product_data( array $settings ): array {
        if ( 'woocommerce' === $settings['source'] && ! empty( $settings['product_id'] ) && class_exists( 'WooCommerce' ) ) {
            $product = wc_get_product( $settings['product_id'] );

            if ( ! $product ) {
                return [];
            }

            $image_id  = $product->get_image_id();
            $image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'medium_large' ) : wc_placeholder_img_src( 'medium_large' );
            $terms     = get_the_terms( $product->get_id(), 'product_cat' );
            $category  = $terms && ! is_wp_error( $terms ) ? $terms[0]->name : '';

            return [
                'id'       => $product->get_id(),
                'image'    => $image_url,
                'category' => $category,
                'title'    => $product->get_name(),
                'price'    => $product->get_price_html(),
                'link'     => $product->get_permalink(),
            ];
        }

        // Manual data.
        return [
            'id'       => '',
            'image'    => $settings['image']['url'] ?? '',
            'category' => $settings['category'] ?? '',
            'title'    => $settings['title'] ?? '',
            'price'    => $settings['price'] ?? '',
            'link'     => $settings['link']['url'] ?? '#',
        ];
    }
}
