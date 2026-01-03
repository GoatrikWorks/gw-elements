<?php
/**
 * Category Slider Widget.
 *
 * @package GW_Elements
 */

namespace GW\Elements\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class Category_Slider
 */
class Category_Slider extends Widget_Base_GW {

    /**
     * Widget slug.
     *
     * @var string
     */
    protected string $widget_slug = 'category-slider';

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
        return 'gw-category-slider';
    }

    /**
     * Get widget title.
     *
     * @return string
     */
    public function get_title(): string {
        return esc_html__( 'GW Category Slider', 'gw-elements' );
    }

    /**
     * Get widget icon.
     *
     * @return string
     */
    public function get_icon(): string {
        return 'eicon-slider-push';
    }

    /**
     * Get widget keywords.
     *
     * @return array
     */
    public function get_keywords(): array {
        return [ 'gw', 'category', 'slider', 'carousel', 'laviasana' ];
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
                'default'     => 'Scopri',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'title',
            [
                'label'       => esc_html__( 'Title', 'gw-elements' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'Esplora le Categorie',
                'label_block' => true,
            ]
        );

        $this->end_controls_section();

        // Categories Section.
        $this->start_controls_section(
            'section_categories',
            [
                'label' => esc_html__( 'Categories', 'gw-elements' ),
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
                    'woocommerce' => esc_html__( 'WooCommerce Categories', 'gw-elements' ),
                ],
            ]
        );

        // WooCommerce category options.
        if ( class_exists( 'WooCommerce' ) || class_exists( '\WooCommerce' ) ) {
            $wc_categories = get_terms( [
                'taxonomy'   => 'product_cat',
                'hide_empty' => false,
            ] );

            $cat_options = [];
            if ( ! is_wp_error( $wc_categories ) && ! empty( $wc_categories ) ) {
                foreach ( $wc_categories as $cat ) {
                    $cat_options[ $cat->term_id ] = $cat->name;
                }
            }

            $this->add_control(
                'wc_categories',
                [
                    'label'       => esc_html__( 'Select Categories', 'gw-elements' ),
                    'type'        => Controls_Manager::SELECT2,
                    'options'     => $cat_options,
                    'multiple'    => true,
                    'label_block' => true,
                    'description' => esc_html__( 'Leave empty to show all categories', 'gw-elements' ),
                    'condition'   => [
                        'source' => 'woocommerce',
                    ],
                ]
            );

            $this->add_control(
                'wc_hide_empty',
                [
                    'label'        => esc_html__( 'Hide Empty Categories', 'gw-elements' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'label_on'     => esc_html__( 'Yes', 'gw-elements' ),
                    'label_off'    => esc_html__( 'No', 'gw-elements' ),
                    'return_value' => 'yes',
                    'default'      => '',
                    'condition'    => [
                        'source' => 'woocommerce',
                    ],
                ]
            );

            $this->add_control(
                'wc_exclude_uncategorized',
                [
                    'label'        => esc_html__( 'Exclude Uncategorized', 'gw-elements' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'label_on'     => esc_html__( 'Yes', 'gw-elements' ),
                    'label_off'    => esc_html__( 'No', 'gw-elements' ),
                    'return_value' => 'yes',
                    'default'      => 'yes',
                    'condition'    => [
                        'source' => 'woocommerce',
                    ],
                ]
            );
        } else {
            $this->add_control(
                'wc_notice',
                [
                    'type'      => Controls_Manager::RAW_HTML,
                    'raw'       => '<div style="padding: 10px; background: #fff3cd; border-left: 4px solid #ffc107; color: #856404;">WooCommerce is not active. Install and activate WooCommerce to use this source.</div>',
                    'condition' => [
                        'source' => 'woocommerce',
                    ],
                ]
            );
        }

        $repeater = new Repeater();

        $repeater->add_control(
            'image',
            [
                'label' => esc_html__( 'Image', 'gw-elements' ),
                'type'  => Controls_Manager::MEDIA,
            ]
        );

        $repeater->add_control(
            'title',
            [
                'label'       => esc_html__( 'Title', 'gw-elements' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Category Name', 'gw-elements' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'description',
            [
                'label'       => esc_html__( 'Description', 'gw-elements' ),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => esc_html__( 'Category description', 'gw-elements' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label'       => esc_html__( 'Link', 'gw-elements' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => '/shop?category=name',
            ]
        );

        $this->add_control(
            'categories',
            [
                'label'       => esc_html__( 'Categories', 'gw-elements' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'title'       => 'Integratori',
                        'description' => 'Supplementi naturali per la tua salute',
                    ],
                    [
                        'title'       => 'Vini & Bio',
                        'description' => 'Vini biologici certificati',
                    ],
                    [
                        'title'       => 'Saponi',
                        'description' => 'Cura del corpo naturale',
                    ],
                ],
                'title_field' => '{{{ title }}}',
                'condition'   => [
                    'source' => 'manual',
                ],
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
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'autoplay_delay',
            [
                'label'     => esc_html__( 'Autoplay Delay (ms)', 'gw-elements' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 5000,
                'condition' => [
                    'autoplay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'loop',
            [
                'label'        => esc_html__( 'Loop', 'gw-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'gw-elements' ),
                'label_off'    => esc_html__( 'No', 'gw-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'show_dots',
            [
                'label'        => esc_html__( 'Show Dots', 'gw-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'gw-elements' ),
                'label_off'    => esc_html__( 'No', 'gw-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes',
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
                    '{{WRAPPER}} .gw-category-slider' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

        // Build slider config.
        $slider_config = [
            'type'        => 'loop',
            'perPage'     => 4,
            'perMove'     => 1,
            'gap'         => '1rem',
            'pagination'  => 'yes' === $settings['show_dots'],
            'arrows'      => 'yes' === $settings['show_arrows'],
            'autoplay'    => 'yes' === $settings['autoplay'],
            'interval'    => (int) $settings['autoplay_delay'],
            'pauseOnHover' => true,
            'breakpoints' => [
                1280 => [ 'perPage' => 4 ],
                1024 => [ 'perPage' => 3 ],
                768  => [ 'perPage' => 2 ],
                480  => [ 'perPage' => 1, 'gap' => '0.5rem' ],
            ],
        ];

        // Get categories based on source.
        $categories = [];
        $source = $settings['source'] ?? 'manual';

        if ( 'woocommerce' === $source && ( class_exists( 'WooCommerce' ) || class_exists( '\WooCommerce' ) ) ) {
            // Build term query args.
            $term_args = [
                'taxonomy'   => 'product_cat',
                'hide_empty' => 'yes' === ( $settings['wc_hide_empty'] ?? '' ),
            ];

            // Filter by selected categories if specified.
            $selected_cats = $settings['wc_categories'] ?? [];
            if ( ! empty( $selected_cats ) && is_array( $selected_cats ) ) {
                $term_args['include'] = array_map( 'intval', $selected_cats );
            }

            // Exclude uncategorized if set.
            if ( 'yes' === ( $settings['wc_exclude_uncategorized'] ?? 'yes' ) ) {
                $uncategorized = get_term_by( 'slug', 'uncategorized', 'product_cat' );
                if ( $uncategorized ) {
                    $term_args['exclude'] = [ $uncategorized->term_id ];
                }
            }

            $terms = get_terms( $term_args );

            if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
                foreach ( $terms as $term ) {
                    $thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
                    $image_url    = $thumbnail_id ? wp_get_attachment_image_url( $thumbnail_id, 'medium_large' ) : '';

                    $categories[] = [
                        'image'       => [ 'url' => $image_url ],
                        'title'       => $term->name,
                        'description' => $term->description,
                        'link'        => [ 'url' => get_term_link( $term ) ],
                    ];
                }
            }
        } else {
            // Manual source - get from repeater.
            $manual_categories = $settings['categories'] ?? [];
            if ( is_array( $manual_categories ) ) {
                $categories = $manual_categories;
            }
        }

        // Don't render if no categories.
        if ( empty( $categories ) ) {
            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                echo '<div style="padding: 2rem; text-align: center; background: #f5f5f5; border: 1px dashed #ccc;">';
                if ( 'woocommerce' === $source ) {
                    if ( ! class_exists( 'WooCommerce' ) && ! class_exists( '\WooCommerce' ) ) {
                        echo '<p style="margin: 0; color: #856404;"><strong>WooCommerce is not active.</strong><br>Install and activate WooCommerce to use this source.</p>';
                    } else {
                        echo '<p style="margin: 0; color: #666;"><strong>No WooCommerce categories found.</strong><br>Create product categories in Products &gt; Categories.</p>';
                    }
                } else {
                    echo '<p style="margin: 0; color: #666;">No categories found. Add categories in Content &gt; Categories.</p>';
                }
                echo '</div>';
            }
            return;
        }

        $this->add_render_attribute( 'wrapper', [
            'class'              => 'gw-category-slider',
            'data-slider-config' => wp_json_encode( $slider_config ),
        ] );
        $this->add_animation_wrapper_attributes( $settings );
        ?>
        <section <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
            <div class="gw-category-slider__container gw-container-wide">
                <?php if ( ! empty( $settings['subtitle'] ) || ! empty( $settings['title'] ) ) : ?>
                    <div class="gw-category-slider__header">
                        <?php if ( ! empty( $settings['subtitle'] ) ) : ?>
                            <span class="gw-section-subtitle"><?php echo esc_html( $settings['subtitle'] ); ?></span>
                        <?php endif; ?>
                        <?php if ( ! empty( $settings['title'] ) ) : ?>
                            <h2 class="gw-section-title"><?php echo esc_html( $settings['title'] ); ?></h2>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <div class="gw-category-slider__slider splide">
                    <div class="splide__track">
                        <ul class="splide__list">
                            <?php foreach ( $categories as $category ) :
                                // Handle both array formats (from repeater or WooCommerce).
                                $link = '#';
                                if ( isset( $category['link'] ) ) {
                                    $link = is_array( $category['link'] ) ? ( $category['link']['url'] ?? '#' ) : $category['link'];
                                }

                                $image_url = '';
                                if ( isset( $category['image'] ) ) {
                                    $image_url = is_array( $category['image'] ) ? ( $category['image']['url'] ?? '' ) : $category['image'];
                                }

                                $title = $category['title'] ?? '';
                                $description = $category['description'] ?? '';
                            ?>
                                <li class="splide__slide">
                                    <a href="<?php echo esc_url( $link ); ?>" class="gw-category-card">
                                        <div class="gw-category-card__image gw-image-zoom--lg">
                                            <?php if ( $image_url ) : ?>
                                                <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy" decoding="async">
                                            <?php endif; ?>
                                            <div class="gw-category-card__overlay"></div>
                                            <div class="gw-category-card__content">
                                                <h3 class="gw-category-card__title"><?php echo esc_html( $title ); ?></h3>
                                                <?php if ( $description ) : ?>
                                                    <p class="gw-category-card__description"><?php echo esc_html( $description ); ?></p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <?php if ( 'yes' === $settings['show_arrows'] || 'yes' === $settings['show_dots'] ) : ?>
                    <div class="gw-category-slider__nav">
                        <?php if ( 'yes' === $settings['show_arrows'] ) : ?>
                            <button type="button" class="gw-category-slider__arrow gw-category-slider__arrow--prev">
                                <?php echo $this->render_icon( 'chevron-left' ); ?>
                            </button>
                        <?php endif; ?>

                        <?php if ( 'yes' === $settings['show_dots'] ) : ?>
                            <div class="gw-category-slider__dots"></div>
                        <?php endif; ?>

                        <?php if ( 'yes' === $settings['show_arrows'] ) : ?>
                            <button type="button" class="gw-category-slider__arrow gw-category-slider__arrow--next">
                                <?php echo $this->render_icon( 'chevron-right' ); ?>
                            </button>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        <?php
    }
}
