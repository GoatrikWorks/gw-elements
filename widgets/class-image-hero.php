<?php
/**
 * Image Hero Widget.
 *
 * @package GW_Elements
 */

namespace GW\Elements\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class Image_Hero
 */
class Image_Hero extends Widget_Base_GW {

    /**
     * Widget slug.
     *
     * @var string
     */
    protected string $widget_slug = 'image-hero';

    /**
     * Get widget name.
     *
     * @return string
     */
    public function get_name(): string {
        return 'gw-image-hero';
    }

    /**
     * Get widget title.
     *
     * @return string
     */
    public function get_title(): string {
        return esc_html__( 'GW Image Hero', 'gw-elements' );
    }

    /**
     * Get widget icon.
     *
     * @return string
     */
    public function get_icon(): string {
        return 'eicon-single-page';
    }

    /**
     * Get widget keywords.
     *
     * @return array
     */
    public function get_keywords(): array {
        return [ 'gw', 'hero', 'image', 'banner', 'laviasana' ];
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
            'image',
            [
                'label' => esc_html__( 'Background Image', 'gw-elements' ),
                'type'  => Controls_Manager::MEDIA,
            ]
        );

        $this->add_control(
            'subtitle',
            [
                'label'       => esc_html__( 'Subtitle', 'gw-elements' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'title',
            [
                'label'       => esc_html__( 'Title', 'gw-elements' ),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => 'Hero Title',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'description',
            [
                'label'       => esc_html__( 'Description', 'gw-elements' ),
                'type'        => Controls_Manager::TEXTAREA,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => esc_html__( 'Button Text', 'gw-elements' ),
                'type'  => Controls_Manager::TEXT,
            ]
        );

        $this->add_control(
            'button_link',
            [
                'label'       => esc_html__( 'Button Link', 'gw-elements' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => '/page',
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
            'min_height',
            [
                'label'      => esc_html__( 'Minimum Height', 'gw-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'vh', 'px' ],
                'range'      => [
                    'vh' => [
                        'min' => 20,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 200,
                        'max' => 1000,
                    ],
                ],
                'default'    => [
                    'unit' => 'vh',
                    'size' => 50,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .gw-image-hero' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'content_position',
            [
                'label'   => esc_html__( 'Content Position', 'gw-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'center',
                'options' => [
                    'left'   => esc_html__( 'Left', 'gw-elements' ),
                    'center' => esc_html__( 'Center', 'gw-elements' ),
                    'right'  => esc_html__( 'Right', 'gw-elements' ),
                ],
            ]
        );

        $this->add_control(
            'overlay_opacity',
            [
                'label'   => esc_html__( 'Overlay Opacity', 'gw-elements' ),
                'type'    => Controls_Manager::SLIDER,
                'range'   => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'size' => 0.4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .gw-image-hero__overlay' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Typography Section.
        $this->start_controls_section(
            'section_typography',
            [
                'label' => esc_html__( 'Typography', 'gw-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        // Subtitle Typography.
        $this->add_control(
            'subtitle_heading',
            [
                'label' => esc_html__( 'Subtitle', 'gw-elements' ),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'subtitle_typography',
                'selector' => '{{WRAPPER}} .gw-image-hero__subtitle',
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label'     => esc_html__( 'Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .gw-image-hero__subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Title Typography.
        $this->add_control(
            'title_heading',
            [
                'label'     => esc_html__( 'Title', 'gw-elements' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .gw-image-hero__title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__( 'Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .gw-image-hero__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Description Typography.
        $this->add_control(
            'description_heading',
            [
                'label'     => esc_html__( 'Description', 'gw-elements' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'description_typography',
                'selector' => '{{WRAPPER}} .gw-image-hero__description',
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label'     => esc_html__( 'Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .gw-image-hero__description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Button Colors Section.
        $this->start_controls_section(
            'section_button_colors',
            [
                'label' => esc_html__( 'Button Colors', 'gw-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'primary_button_bg',
            [
                'label'     => esc_html__( 'Primary Button Background', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-button--hero' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'primary_button_color',
            [
                'label'     => esc_html__( 'Primary Button Text', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-button--hero' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'primary_button_bg_hover',
            [
                'label'     => esc_html__( 'Primary Button Background (Hover)', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-button--hero:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'secondary_button_color',
            [
                'label'     => esc_html__( 'Secondary Button Text', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-button--editorial' => 'color: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'secondary_button_color_hover',
            [
                'label'     => esc_html__( 'Secondary Button Text (Hover)', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-button--editorial:hover' => 'color: {{VALUE}};',
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

        $position_class = 'gw-image-hero--' . $settings['content_position'];

        $this->add_render_attribute( 'wrapper', 'class', [ 'gw-image-hero', $position_class ] );
        $this->add_animation_wrapper_attributes( $settings );
        ?>
        <section <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
            <?php if ( ! empty( $settings['image']['url'] ) ) : ?>
                <div class="gw-image-hero__background">
                    <img src="<?php echo esc_url( $settings['image']['url'] ); ?>" alt="" loading="eager" decoding="async">
                </div>
            <?php endif; ?>

            <div class="gw-image-hero__overlay"></div>

            <div class="gw-image-hero__container gw-container-wide">
                <div class="gw-image-hero__content">
                    <?php if ( ! empty( $settings['subtitle'] ) ) : ?>
                        <span class="gw-image-hero__subtitle">
                            <?php echo esc_html( $settings['subtitle'] ); ?>
                        </span>
                    <?php endif; ?>

                    <?php if ( ! empty( $settings['title'] ) ) : ?>
                        <h1 class="gw-image-hero__title">
                            <?php echo wp_kses_post( $settings['title'] ); ?>
                        </h1>
                    <?php endif; ?>

                    <?php if ( ! empty( $settings['description'] ) ) : ?>
                        <p class="gw-image-hero__description">
                            <?php echo esc_html( $settings['description'] ); ?>
                        </p>
                    <?php endif; ?>

                    <?php if ( ! empty( $settings['button_text'] ) ) : ?>
                        <a href="<?php echo esc_url( $settings['button_link']['url'] ?? '#' ); ?>" class="gw-button gw-button--hero">
                            <?php echo esc_html( $settings['button_text'] ); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        <?php
    }
}
