<?php
/**
 * Category Card Widget.
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
 * Class Category_Card
 */
class Category_Card extends Widget_Base_GW {

    /**
     * Widget slug.
     *
     * @var string
     */
    protected string $widget_slug = 'category-card';

    /**
     * Get widget name.
     *
     * @return string
     */
    public function get_name(): string {
        return 'gw-category-card';
    }

    /**
     * Get widget title.
     *
     * @return string
     */
    public function get_title(): string {
        return esc_html__( 'GW Category Card', 'gw-elements' );
    }

    /**
     * Get widget icon.
     *
     * @return string
     */
    public function get_icon(): string {
        return 'eicon-image-box';
    }

    /**
     * Get widget keywords.
     *
     * @return array
     */
    public function get_keywords(): array {
        return [ 'gw', 'category', 'card', 'laviasana' ];
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
                'default' => 'manual',
                'options' => [
                    'manual'      => esc_html__( 'Manual', 'gw-elements' ),
                    'woocommerce' => esc_html__( 'WooCommerce Category', 'gw-elements' ),
                ],
            ]
        );

        // Get WooCommerce categories.
        if ( class_exists( 'WooCommerce' ) ) {
            $categories = get_terms( [
                'taxonomy'   => 'product_cat',
                'hide_empty' => false,
            ] );

            $cat_options = [];
            if ( ! is_wp_error( $categories ) ) {
                foreach ( $categories as $cat ) {
                    $cat_options[ $cat->term_id ] = $cat->name;
                }
            }

            $this->add_control(
                'category_id',
                [
                    'label'     => esc_html__( 'Category', 'gw-elements' ),
                    'type'      => Controls_Manager::SELECT2,
                    'options'   => $cat_options,
                    'condition' => [
                        'source' => 'woocommerce',
                    ],
                ]
            );
        }

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
            'title',
            [
                'label'       => esc_html__( 'Title', 'gw-elements' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'Category Name',
                'label_block' => true,
                'condition'   => [
                    'source' => 'manual',
                ],
            ]
        );

        $this->add_control(
            'description',
            [
                'label'       => esc_html__( 'Description', 'gw-elements' ),
                'type'        => Controls_Manager::TEXTAREA,
                'label_block' => true,
                'condition'   => [
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

        $this->end_controls_section();

        // Style Section.
        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__( 'Style', 'gw-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'image_aspect',
            [
                'label'   => esc_html__( 'Image Aspect Ratio', 'gw-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => '3-4',
                'options' => [
                    'square' => '1:1',
                    '4-3'    => '4:3',
                    '3-4'    => '3:4',
                    '16-9'   => '16:9',
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

        $data = $this->get_category_data( $settings );

        if ( empty( $data['title'] ) ) {
            return;
        }

        $aspect_class = 'gw-aspect-' . $settings['image_aspect'];

        $this->add_render_attribute( 'wrapper', 'class', 'gw-category-card' );
        ?>
        <a href="<?php echo esc_url( $data['link'] ); ?>" <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
            <div class="gw-category-card__image <?php echo esc_attr( $aspect_class ); ?> gw-image-zoom--lg">
                <?php if ( ! empty( $data['image'] ) ) : ?>
                    <img src="<?php echo esc_url( $data['image'] ); ?>" alt="<?php echo esc_attr( $data['title'] ); ?>" loading="lazy" decoding="async">
                <?php endif; ?>
                <div class="gw-category-card__overlay"></div>
                <div class="gw-category-card__content">
                    <h3 class="gw-category-card__title"><?php echo esc_html( $data['title'] ); ?></h3>
                    <?php if ( ! empty( $data['description'] ) ) : ?>
                        <p class="gw-category-card__description"><?php echo esc_html( $data['description'] ); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </a>
        <?php
    }

    /**
     * Get category data from settings.
     *
     * @param array $settings Widget settings.
     * @return array
     */
    private function get_category_data( array $settings ): array {
        if ( 'woocommerce' === $settings['source'] && ! empty( $settings['category_id'] ) && class_exists( 'WooCommerce' ) ) {
            $term = get_term( $settings['category_id'], 'product_cat' );

            if ( ! $term || is_wp_error( $term ) ) {
                return [];
            }

            $thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
            $image_url    = $thumbnail_id ? wp_get_attachment_image_url( $thumbnail_id, 'medium_large' ) : '';

            return [
                'image'       => $image_url,
                'title'       => $term->name,
                'description' => $term->description,
                'link'        => get_term_link( $term ),
            ];
        }

        return [
            'image'       => $settings['image']['url'] ?? '',
            'title'       => $settings['title'] ?? '',
            'description' => $settings['description'] ?? '',
            'link'        => $settings['link']['url'] ?? '#',
        ];
    }
}
