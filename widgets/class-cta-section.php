<?php
/**
 * CTA Section Widget.
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
 * Class CTA_Section
 */
class CTA_Section extends Widget_Base_GW {

    /**
     * Widget slug.
     *
     * @var string
     */
    protected string $widget_slug = 'cta-section';

    /**
     * Get widget name.
     *
     * @return string
     */
    public function get_name(): string {
        return 'gw-cta-section';
    }

    /**
     * Get widget title.
     *
     * @return string
     */
    public function get_title(): string {
        return esc_html__( 'GW CTA Section', 'gw-elements' );
    }

    /**
     * Get widget icon.
     *
     * @return string
     */
    public function get_icon(): string {
        return 'eicon-call-to-action';
    }

    /**
     * Get widget keywords.
     *
     * @return array
     */
    public function get_keywords(): array {
        return [ 'gw', 'cta', 'call to action', 'button', 'laviasana' ];
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
                'default'     => 'Inizia Oggi',
                'label_block' => true,
                'condition'   => [
                    'show_subtitle' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'title',
            [
                'label'       => esc_html__( 'Title', 'gw-elements' ),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => 'Pronto a vivere meglio?',
                'label_block' => true,
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
                'default'     => 'Esplora la nostra selezione di prodotti naturali e certificati. Il tuo percorso verso il benessere inizia qui.',
                'label_block' => true,
                'condition'   => [
                    'show_description' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Buttons Section.
        $this->start_controls_section(
            'section_buttons',
            [
                'label' => esc_html__( 'Buttons', 'gw-elements' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_primary_button',
            [
                'label'        => esc_html__( 'Show Primary Button', 'gw-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'gw-elements' ),
                'label_off'    => esc_html__( 'No', 'gw-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'primary_button_text',
            [
                'label'   => esc_html__( 'Primary Button Text', 'gw-elements' ),
                'type'    => Controls_Manager::TEXT,
                'default' => 'Esplora il Negozio',
                'condition' => [
                    'show_primary_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'primary_button_link',
            [
                'label'       => esc_html__( 'Primary Button Link', 'gw-elements' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => '/shop',
                'condition' => [
                    'show_primary_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_secondary_button',
            [
                'label'        => esc_html__( 'Show Secondary Button', 'gw-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'gw-elements' ),
                'label_off'    => esc_html__( 'No', 'gw-elements' ),
                'return_value' => 'yes',
                'default'      => '',
            ]
        );

        $this->add_control(
            'secondary_button_text',
            [
                'label' => esc_html__( 'Secondary Button Text', 'gw-elements' ),
                'type'  => Controls_Manager::TEXT,
                'default' => 'Contattaci',
                'condition' => [
                    'show_secondary_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'secondary_button_link',
            [
                'label'       => esc_html__( 'Secondary Button Link', 'gw-elements' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => '/contact',
                'condition' => [
                    'show_secondary_button' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Layout Section.
        $this->start_controls_section(
            'section_layout',
            [
                'label' => esc_html__( 'Layout', 'gw-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'text_align',
            [
                'label'   => esc_html__( 'Alignment', 'gw-elements' ),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'left'   => [
                        'title' => esc_html__( 'Left', 'gw-elements' ),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'gw-elements' ),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'  => [
                        'title' => esc_html__( 'Right', 'gw-elements' ),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
            ]
        );

        $this->add_control(
            'background_color',
            [
                'label'     => esc_html__( 'Background Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-cta-section' => 'background-color: {{VALUE}};',
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
                    'top'    => '4',
                    'right'  => '2',
                    'bottom' => '4',
                    'left'   => '2',
                    'unit'   => 'rem',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .gw-cta-section' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'gw-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'rem' ],
                'selectors'  => [
                    '{{WRAPPER}} .gw-cta-section' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'content_max_width',
            [
                'label'      => esc_html__( 'Content Max Width', 'gw-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'rem', 'px' ],
                'range'      => [
                    'rem' => [
                        'min' => 20,
                        'max' => 60,
                    ],
                ],
                'default'    => [
                    'unit' => 'rem',
                    'size' => 40,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .gw-cta-section__container' => 'max-width: {{SIZE}}{{UNIT}};',
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

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'subtitle_typography',
                'label'    => esc_html__( 'Subtitle Typography', 'gw-elements' ),
                'selector' => '{{WRAPPER}} .gw-cta-section__subtitle',
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label'     => esc_html__( 'Subtitle Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-cta-section__subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'label'    => esc_html__( 'Title Typography', 'gw-elements' ),
                'selector' => '{{WRAPPER}} .gw-cta-section__title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__( 'Title Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-cta-section__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'description_typography',
                'label'    => esc_html__( 'Description Typography', 'gw-elements' ),
                'selector' => '{{WRAPPER}} .gw-cta-section__description',
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label'     => esc_html__( 'Description Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-cta-section__description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Button Style Section.
        $this->start_controls_section(
            'section_button_style',
            [
                'label' => esc_html__( 'Button Style', 'gw-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'primary_button_bg_color',
            [
                'label'     => esc_html__( 'Primary Button Background', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-cta-section__primary-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'primary_button_text_color',
            [
                'label'     => esc_html__( 'Primary Button Text', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-cta-section__primary-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'primary_button_hover_bg_color',
            [
                'label'     => esc_html__( 'Primary Button Hover Background', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-cta-section__primary-btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'secondary_button_text_color',
            [
                'label'     => esc_html__( 'Secondary Button Text', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-cta-section__secondary-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'secondary_button_border_color',
            [
                'label'     => esc_html__( 'Secondary Button Border', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-cta-section__secondary-btn' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_spacing',
            [
                'label'      => esc_html__( 'Button Spacing', 'gw-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default'    => [
                    'size' => 1,
                    'unit' => 'rem',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .gw-cta-section__buttons' => 'gap: {{SIZE}}{{UNIT}};',
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

        $wrapper_classes = [ 'gw-cta-section' ];

        // Add alignment class.
        $alignment = $settings['text_align'] ?? 'center';
        $wrapper_classes[] = 'gw-cta-section--align-' . $alignment;

        $this->add_render_attribute( 'wrapper', 'class', $wrapper_classes );
        $this->add_animation_wrapper_attributes( $settings );
        ?>
        <section <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
            <div class="gw-cta-section__container">
                <?php if ( 'yes' === $settings['show_subtitle'] && ! empty( $settings['subtitle'] ) ) : ?>
                    <span class="gw-cta-section__subtitle gw-section-subtitle">
                        <?php echo esc_html( $settings['subtitle'] ); ?>
                    </span>
                <?php endif; ?>

                <?php if ( ! empty( $settings['title'] ) ) : ?>
                    <h2 class="gw-cta-section__title gw-section-title">
                        <?php echo wp_kses_post( $settings['title'] ); ?>
                    </h2>
                <?php endif; ?>

                <?php if ( 'yes' === $settings['show_description'] && ! empty( $settings['description'] ) ) : ?>
                    <div class="gw-cta-section__description">
                        <?php echo wp_kses_post( $settings['description'] ); ?>
                    </div>
                <?php endif; ?>

                <?php
                $show_primary = 'yes' === $settings['show_primary_button'] && ! empty( $settings['primary_button_text'] );
                $show_secondary = 'yes' === $settings['show_secondary_button'] && ! empty( $settings['secondary_button_text'] );
                if ( $show_primary || $show_secondary ) :
                ?>
                    <div class="gw-cta-section__buttons">
                        <?php if ( $show_primary ) : ?>
                            <a href="<?php echo esc_url( $settings['primary_button_link']['url'] ?? '#' ); ?>" class="gw-button gw-button--hero gw-button--xl gw-cta-section__primary-btn">
                                <?php echo esc_html( $settings['primary_button_text'] ); ?>
                            </a>
                        <?php endif; ?>

                        <?php if ( $show_secondary ) : ?>
                            <a href="<?php echo esc_url( $settings['secondary_button_link']['url'] ?? '#' ); ?>" class="gw-button gw-button--editorial gw-button--xl gw-cta-section__secondary-btn">
                                <?php echo esc_html( $settings['secondary_button_text'] ); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        <?php
    }
}
