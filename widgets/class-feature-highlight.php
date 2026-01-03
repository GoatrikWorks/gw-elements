<?php
/**
 * Feature Highlight Widget.
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
 * Class Feature_Highlight
 */
class Feature_Highlight extends Widget_Base_GW {

    /**
     * Widget slug.
     *
     * @var string
     */
    protected string $widget_slug = 'feature-highlight';

    /**
     * Get widget name.
     *
     * @return string
     */
    public function get_name(): string {
        return 'gw-feature-highlight';
    }

    /**
     * Get widget title.
     *
     * @return string
     */
    public function get_title(): string {
        return esc_html__( 'GW Feature Highlight', 'gw-elements' );
    }

    /**
     * Get widget icon.
     *
     * @return string
     */
    public function get_icon(): string {
        return 'eicon-featured-image';
    }

    /**
     * Get widget keywords.
     *
     * @return array
     */
    public function get_keywords(): array {
        return [ 'gw', 'feature', 'highlight', 'product', 'mattress', 'laviasana' ];
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

        // Visibility Toggles.
        $this->add_control(
            'show_image',
            [
                'label'        => esc_html__( 'Show Image', 'gw-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'gw-elements' ),
                'label_off'    => esc_html__( 'No', 'gw-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'image',
            [
                'label'     => esc_html__( 'Image', 'gw-elements' ),
                'type'      => Controls_Manager::MEDIA,
                'condition' => [
                    'show_image' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'image_position',
            [
                'label'     => esc_html__( 'Image Position', 'gw-elements' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'left',
                'options'   => [
                    'left'  => esc_html__( 'Left', 'gw-elements' ),
                    'right' => esc_html__( 'Right', 'gw-elements' ),
                ],
                'condition' => [
                    'show_image' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'divider_1',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            'show_subtitle',
            [
                'label'        => esc_html__( 'Show Subtitle', 'gw-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'gw-elements' ),
                'label_off'    => esc_html__( 'No', 'gw-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'subtitle',
            [
                'label'       => esc_html__( 'Subtitle', 'gw-elements' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'Nuovo Arrivo',
                'label_block' => true,
                'condition'   => [
                    'show_subtitle' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_title',
            [
                'label'        => esc_html__( 'Show Title', 'gw-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'gw-elements' ),
                'label_off'    => esc_html__( 'No', 'gw-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'title',
            [
                'label'       => esc_html__( 'Title', 'gw-elements' ),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => 'Materasso Naturale Premium',
                'label_block' => true,
                'condition'   => [
                    'show_title' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_description',
            [
                'label'        => esc_html__( 'Show Description', 'gw-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'gw-elements' ),
                'label_off'    => esc_html__( 'No', 'gw-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'description',
            [
                'label'       => esc_html__( 'Description', 'gw-elements' ),
                'type'        => Controls_Manager::WYSIWYG,
                'default'     => '<p>Scopri il comfort naturale con il nostro materasso in lattice 100% organico.</p>',
                'condition'   => [
                    'show_description' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Features Section.
        $this->start_controls_section(
            'section_features',
            [
                'label' => esc_html__( 'Features', 'gw-elements' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'icon',
            [
                'label'   => esc_html__( 'Icon', 'gw-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'leaf',
                'options' => $this->get_icon_options(),
            ]
        );

        $repeater->add_control(
            'title',
            [
                'label'       => esc_html__( 'Title', 'gw-elements' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Feature', 'gw-elements' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'description',
            [
                'label'       => esc_html__( 'Description', 'gw-elements' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Feature description', 'gw-elements' ),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'features',
            [
                'label'       => esc_html__( 'Features', 'gw-elements' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [],
                'title_field' => '{{{ title }}}',
            ]
        );

        $this->end_controls_section();

        // Button Section.
        $this->start_controls_section(
            'section_button',
            [
                'label' => esc_html__( 'Button', 'gw-elements' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_button',
            [
                'label'        => esc_html__( 'Show Button', 'gw-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'gw-elements' ),
                'label_off'    => esc_html__( 'No', 'gw-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label'     => esc_html__( 'Button Text', 'gw-elements' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => 'Scopri il Prodotto',
                'condition' => [
                    'show_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'button_link',
            [
                'label'       => esc_html__( 'Button Link', 'gw-elements' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => '/product/mattress',
                'condition'   => [
                    'show_button' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_width',
            [
                'label'      => esc_html__( 'Button Width', 'gw-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'rem' ],
                'range'      => [
                    'px' => [
                        'min' => 100,
                        'max' => 500,
                    ],
                    '%' => [
                        'min' => 20,
                        'max' => 100,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .gw-button--hero' => 'width: {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'show_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'button_full_width',
            [
                'label'        => esc_html__( 'Full Width Button', 'gw-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'gw-elements' ),
                'label_off'    => esc_html__( 'No', 'gw-elements' ),
                'return_value' => 'yes',
                'default'      => '',
                'selectors'    => [
                    '{{WRAPPER}} .gw-button--hero' => 'width: 100%;',
                ],
                'condition'    => [
                    'show_button' => 'yes',
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
            'background_color',
            [
                'label'     => esc_html__( 'Background Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'hsl(338 60% 94%)',
                'selectors' => [
                    '{{WRAPPER}} .gw-feature-highlight' => 'background-color: {{VALUE}};',
                ],
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
                    '{{WRAPPER}} .gw-feature-highlight' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Text Colors Section.
        $this->start_controls_section(
            'section_text_colors',
            [
                'label' => esc_html__( 'Text Colors', 'gw-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label'     => esc_html__( 'Subtitle Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-section-subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__( 'Title Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-feature-highlight__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label'     => esc_html__( 'Description Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-feature-highlight__description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'heading_features_colors',
            [
                'label'     => esc_html__( 'Features', 'gw-elements' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'feature_icon_color',
            [
                'label'     => esc_html__( 'Icon Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-feature-highlight__feature-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .gw-feature-highlight__feature-icon svg' => 'stroke: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'feature_title_color',
            [
                'label'     => esc_html__( 'Feature Title Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-feature-highlight__feature-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'feature_description_color',
            [
                'label'     => esc_html__( 'Feature Description Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-feature-highlight__feature-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'heading_button_colors',
            [
                'label'     => esc_html__( 'Button', 'gw-elements' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'button_bg_color',
            [
                'label'     => esc_html__( 'Button Background', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-button--hero' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label'     => esc_html__( 'Button Text Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-button--hero' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_bg_color_hover',
            [
                'label'     => esc_html__( 'Button Background (Hover)', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-button--hero:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_text_color_hover',
            [
                'label'     => esc_html__( 'Button Text Color (Hover)', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-button--hero:hover' => 'color: {{VALUE}};',
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

        $show_image       = 'yes' === ( $settings['show_image'] ?? 'yes' );
        $show_subtitle    = 'yes' === ( $settings['show_subtitle'] ?? 'yes' );
        $show_title       = 'yes' === ( $settings['show_title'] ?? 'yes' );
        $show_description = 'yes' === ( $settings['show_description'] ?? 'yes' );
        $show_button      = 'yes' === ( $settings['show_button'] ?? 'yes' );

        $wrapper_class = 'gw-feature-highlight';
        if ( $show_image && 'right' === $settings['image_position'] ) {
            $wrapper_class .= ' gw-feature-highlight--image-right';
        }
        if ( ! $show_image ) {
            $wrapper_class .= ' gw-feature-highlight--no-image';
        }

        $this->add_render_attribute( 'wrapper', 'class', $wrapper_class );
        $this->add_animation_wrapper_attributes( $settings );
        ?>
        <section <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
            <div class="gw-feature-highlight__container gw-container-wide">
                <div class="gw-feature-highlight__grid">
                    <?php if ( $show_image ) : ?>
                        <div class="gw-feature-highlight__image">
                            <?php if ( ! empty( $settings['image']['url'] ) ) : ?>
                                <img src="<?php echo esc_url( $settings['image']['url'] ); ?>" alt="<?php echo esc_attr( $settings['title'] ?? '' ); ?>" loading="lazy" decoding="async">
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div class="gw-feature-highlight__content">
                        <?php if ( $show_subtitle && ! empty( $settings['subtitle'] ) ) : ?>
                            <span class="gw-section-subtitle"><?php echo esc_html( $settings['subtitle'] ); ?></span>
                        <?php endif; ?>

                        <?php if ( $show_title && ! empty( $settings['title'] ) ) : ?>
                            <h2 class="gw-feature-highlight__title"><?php echo wp_kses_post( $settings['title'] ); ?></h2>
                        <?php endif; ?>

                        <?php if ( $show_description && ! empty( $settings['description'] ) ) : ?>
                            <div class="gw-feature-highlight__description"><?php echo wp_kses_post( $settings['description'] ); ?></div>
                        <?php endif; ?>

                        <?php
                        $features = $settings['features'] ?? [];
                        if ( ! empty( $features ) && is_array( $features ) ) :
                        ?>
                            <div class="gw-feature-highlight__features">
                                <?php foreach ( $features as $feature ) :
                                    $icon = $feature['icon'] ?? 'leaf';
                                    $title = $feature['title'] ?? '';
                                    $desc = $feature['description'] ?? '';
                                    if ( empty( $title ) ) continue;
                                ?>
                                    <div class="gw-feature-highlight__feature">
                                        <div class="gw-feature-highlight__feature-icon">
                                            <?php echo $this->render_icon( $icon, [ 'class' => 'gw-icon' ] ); ?>
                                        </div>
                                        <div class="gw-feature-highlight__feature-content">
                                            <h4 class="gw-feature-highlight__feature-title"><?php echo esc_html( $title ); ?></h4>
                                            <?php if ( $desc ) : ?>
                                                <p class="gw-feature-highlight__feature-description"><?php echo esc_html( $desc ); ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ( $show_button && ! empty( $settings['button_text'] ) ) : ?>
                            <a href="<?php echo esc_url( $settings['button_link']['url'] ?? '#' ); ?>" class="gw-button gw-button--hero">
                                <?php echo esc_html( $settings['button_text'] ); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
        <?php
    }
}
