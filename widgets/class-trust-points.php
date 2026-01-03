<?php
/**
 * Trust Points Widget.
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
 * Class Trust_Points
 */
class Trust_Points extends Widget_Base_GW {

    /**
     * Widget slug.
     *
     * @var string
     */
    protected string $widget_slug = 'trust-points';

    /**
     * Get widget name.
     *
     * @return string
     */
    public function get_name(): string {
        return 'gw-trust-points';
    }

    /**
     * Get widget title.
     *
     * @return string
     */
    public function get_title(): string {
        return esc_html__( 'GW Trust Points', 'gw-elements' );
    }

    /**
     * Get widget icon.
     *
     * @return string
     */
    public function get_icon(): string {
        return 'eicon-check-circle';
    }

    /**
     * Get widget keywords.
     *
     * @return array
     */
    public function get_keywords(): array {
        return [ 'gw', 'trust', 'usp', 'features', 'benefits', 'laviasana' ];
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

        $repeater = new Repeater();

        $repeater->add_control(
            'icon',
            [
                'label'   => esc_html__( 'Icon', 'gw-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'truck',
                'options' => $this->get_icon_options(),
            ]
        );

        $repeater->add_control(
            'title',
            [
                'label'       => esc_html__( 'Title', 'gw-elements' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Trust Point', 'gw-elements' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'description',
            [
                'label'       => esc_html__( 'Description', 'gw-elements' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Description text here', 'gw-elements' ),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'items',
            [
                'label'       => esc_html__( 'Trust Points', 'gw-elements' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'icon'        => 'truck',
                        'title'       => esc_html__( 'Spedizioni in Tutta Italia', 'gw-elements' ),
                        'description' => esc_html__( 'Consegna rapida e sicura con tracking', 'gw-elements' ),
                    ],
                    [
                        'icon'        => 'award',
                        'title'       => esc_html__( 'Prodotti Certificati', 'gw-elements' ),
                        'description' => esc_html__( 'Qualità garantita e certificata', 'gw-elements' ),
                    ],
                    [
                        'icon'        => 'headphones',
                        'title'       => esc_html__( 'Assistenza Dedicata', 'gw-elements' ),
                        'description' => esc_html__( 'Supporto telefonico e online', 'gw-elements' ),
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
                    '{{WRAPPER}} .gw-trust-points' => 'background-color: {{VALUE}};',
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
                    'top'    => '1.5',
                    'right'  => '0',
                    'bottom' => '1.5',
                    'left'   => '0',
                    'unit'   => 'rem',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .gw-trust-points' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'show_border',
            [
                'label'        => esc_html__( 'Show Border', 'gw-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'gw-elements' ),
                'label_off'    => esc_html__( 'No', 'gw-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'icon_bg_color',
            [
                'label'     => esc_html__( 'Icon Background', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'hsl(338 60% 94%)',
                'selectors' => [
                    '{{WRAPPER}} .gw-trust-points__icon-wrap' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .gw-trust-points__icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'label'    => esc_html__( 'Title Typography', 'gw-elements' ),
                'selector' => '{{WRAPPER}} .gw-trust-points__title',
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

        $wrapper_class = 'gw-trust-points';
        if ( 'yes' === $settings['show_border'] ) {
            $wrapper_class .= ' gw-trust-points--bordered';
        }

        $this->add_render_attribute( 'wrapper', 'class', $wrapper_class );
        $this->add_animation_wrapper_attributes( $settings );
        ?>
        <section <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
            <div class="gw-trust-points__container gw-container-wide">
                <div class="gw-trust-points__grid">
                    <?php foreach ( $settings['items'] as $index => $item ) : ?>
                        <div class="gw-trust-points__item">
                            <div class="gw-trust-points__icon-wrap">
                                <?php echo $this->render_icon( $item['icon'], [ 'class' => 'gw-trust-points__icon gw-icon' ] ); ?>
                            </div>
                            <div class="gw-trust-points__content">
                                <h3 class="gw-trust-points__title"><?php echo esc_html( $item['title'] ); ?></h3>
                                <p class="gw-trust-points__description"><?php echo esc_html( $item['description'] ); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php
    }
}
