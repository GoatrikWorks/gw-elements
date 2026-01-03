<?php
/**
 * Values Grid Widget.
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
 * Class Values_Grid
 */
class Values_Grid extends Widget_Base_GW {

    /**
     * Widget slug.
     *
     * @var string
     */
    protected string $widget_slug = 'values-grid';

    /**
     * Get widget name.
     *
     * @return string
     */
    public function get_name(): string {
        return 'gw-values-grid';
    }

    /**
     * Get widget title.
     *
     * @return string
     */
    public function get_title(): string {
        return esc_html__( 'GW Values Grid', 'gw-elements' );
    }

    /**
     * Get widget icon.
     *
     * @return string
     */
    public function get_icon(): string {
        return 'eicon-gallery-grid';
    }

    /**
     * Get widget keywords.
     *
     * @return array
     */
    public function get_keywords(): array {
        return [ 'gw', 'values', 'features', 'grid', 'icons', 'laviasana' ];
    }

    /**
     * Register widget controls.
     */
    protected function register_controls(): void {
        // Content Section.
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
                'default'     => 'Perché Laviasana',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'title',
            [
                'label'       => esc_html__( 'Title', 'gw-elements' ),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => 'Qualità che fa la differenza',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'description',
            [
                'label'       => esc_html__( 'Description', 'gw-elements' ),
                'type'        => Controls_Manager::WYSIWYG,
                'default'     => '',
            ]
        );

        $this->end_controls_section();

        // Values Section.
        $this->start_controls_section(
            'section_values',
            [
                'label' => esc_html__( 'Values', 'gw-elements' ),
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
                'default'     => esc_html__( 'Value Title', 'gw-elements' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'description',
            [
                'label'       => esc_html__( 'Description', 'gw-elements' ),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => esc_html__( 'Description of this value proposition.', 'gw-elements' ),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'values',
            [
                'label'       => esc_html__( 'Values', 'gw-elements' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'icon'        => 'leaf',
                        'title'       => esc_html__( '100% Naturale', 'gw-elements' ),
                        'description' => esc_html__( 'Ingredienti selezionati dalla natura per il tuo benessere quotidiano.', 'gw-elements' ),
                    ],
                    [
                        'icon'        => 'heart',
                        'title'       => esc_html__( 'Fatto con Cura', 'gw-elements' ),
                        'description' => esc_html__( 'Ogni prodotto è scelto con attenzione per garantire la massima qualità.', 'gw-elements' ),
                    ],
                    [
                        'icon'        => 'shield',
                        'title'       => esc_html__( 'Qualità Garantita', 'gw-elements' ),
                        'description' => esc_html__( 'Certificazioni e standard elevati per la tua tranquillità.', 'gw-elements' ),
                    ],
                ],
                'title_field' => '{{{ title }}}',
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
                'default'   => 'hsl(30 10% 96%)',
                'selectors' => [
                    '{{WRAPPER}} .gw-values-grid' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label'   => esc_html__( 'Columns', 'gw-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => '3',
                'options' => [
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ],
            ]
        );

        $this->add_control(
            'icon_bg_color',
            [
                'label'     => esc_html__( 'Icon Background', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'hsl(338 60% 94%)',
                'selectors' => [
                    '{{WRAPPER}} .gw-values-grid__icon-wrap' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label'     => esc_html__( 'Icon Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'hsl(338 96% 22%)',
                'selectors' => [
                    '{{WRAPPER}} .gw-values-grid__icon' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .gw-values-grid' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

        $columns = $settings['columns'] ?? '3';

        $this->add_render_attribute( 'wrapper', 'class', 'gw-values-grid' );
        $this->add_render_attribute( 'grid', 'class', [ 'gw-values-grid__items', 'gw-values-grid__items--cols-' . $columns ] );
        $this->add_animation_wrapper_attributes( $settings );
        ?>
        <section <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
            <div class="gw-values-grid__container gw-container-wide">
                <?php if ( ! empty( $settings['subtitle'] ) || ! empty( $settings['title'] ) || ! empty( $settings['description'] ) ) : ?>
                    <div class="gw-values-grid__header">
                        <?php if ( ! empty( $settings['subtitle'] ) ) : ?>
                            <span class="gw-values-grid__subtitle gw-section-subtitle">
                                <?php echo esc_html( $settings['subtitle'] ); ?>
                            </span>
                        <?php endif; ?>

                        <?php if ( ! empty( $settings['title'] ) ) : ?>
                            <h2 class="gw-values-grid__title gw-section-title">
                                <?php echo wp_kses_post( $settings['title'] ); ?>
                            </h2>
                        <?php endif; ?>

                        <?php if ( ! empty( $settings['description'] ) ) : ?>
                            <div class="gw-values-grid__description">
                                <?php echo wp_kses_post( $settings['description'] ); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <div <?php $this->print_render_attribute_string( 'grid' ); ?>>
                    <?php foreach ( $settings['values'] as $index => $value ) : ?>
                        <div class="gw-values-grid__item">
                            <div class="gw-values-grid__icon-wrap">
                                <?php echo $this->render_icon( $value['icon'], [ 'class' => 'gw-values-grid__icon gw-icon gw-icon--xl' ] ); ?>
                            </div>
                            <h3 class="gw-values-grid__item-title"><?php echo esc_html( $value['title'] ); ?></h3>
                            <p class="gw-values-grid__item-description"><?php echo esc_html( $value['description'] ); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php
    }
}
