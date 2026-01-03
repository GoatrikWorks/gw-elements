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
            'subtitle',
            [
                'label'       => esc_html__( 'Subtitle', 'gw-elements' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'Inizia Oggi',
                'label_block' => true,
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
            'description',
            [
                'label'       => esc_html__( 'Description', 'gw-elements' ),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => 'Esplora la nostra selezione di prodotti naturali e certificati. Il tuo percorso verso il benessere inizia qui.',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'primary_button_text',
            [
                'label'   => esc_html__( 'Primary Button Text', 'gw-elements' ),
                'type'    => Controls_Manager::TEXT,
                'default' => 'Esplora il Negozio',
            ]
        );

        $this->add_control(
            'primary_button_link',
            [
                'label'       => esc_html__( 'Primary Button Link', 'gw-elements' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => '/shop',
            ]
        );

        $this->add_control(
            'secondary_button_text',
            [
                'label' => esc_html__( 'Secondary Button Text', 'gw-elements' ),
                'type'  => Controls_Manager::TEXT,
            ]
        );

        $this->add_control(
            'secondary_button_link',
            [
                'label'       => esc_html__( 'Secondary Button Link', 'gw-elements' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => '/contact',
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
                    'right'  => '0',
                    'bottom' => '4',
                    'left'   => '0',
                    'unit'   => 'rem',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .gw-cta-section' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    'size' => 32,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .gw-cta-section__description' => 'max-width: {{SIZE}}{{UNIT}};',
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

        $this->end_controls_section();

        // Animation controls.
        $this->add_animation_controls();
    }

    /**
     * Render widget output.
     */
    protected function render(): void {
        $settings = $this->get_settings_for_display();

        $align_class = 'center' === $settings['text_align'] ? 'gw-cta-section--centered' : '';

        $this->add_render_attribute( 'wrapper', 'class', [ 'gw-cta-section', $align_class ] );
        $this->add_animation_wrapper_attributes( $settings );
        ?>
        <section <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
            <div class="gw-cta-section__container gw-container-narrow">
                <?php if ( ! empty( $settings['subtitle'] ) ) : ?>
                    <span class="gw-cta-section__subtitle gw-section-subtitle">
                        <?php echo esc_html( $settings['subtitle'] ); ?>
                    </span>
                <?php endif; ?>

                <?php if ( ! empty( $settings['title'] ) ) : ?>
                    <h2 class="gw-cta-section__title gw-section-title">
                        <?php echo wp_kses_post( $settings['title'] ); ?>
                    </h2>
                <?php endif; ?>

                <?php if ( ! empty( $settings['description'] ) ) : ?>
                    <p class="gw-cta-section__description">
                        <?php echo esc_html( $settings['description'] ); ?>
                    </p>
                <?php endif; ?>

                <?php if ( ! empty( $settings['primary_button_text'] ) || ! empty( $settings['secondary_button_text'] ) ) : ?>
                    <div class="gw-cta-section__buttons">
                        <?php if ( ! empty( $settings['primary_button_text'] ) ) : ?>
                            <a href="<?php echo esc_url( $settings['primary_button_link']['url'] ?? '#' ); ?>" class="gw-button gw-button--hero gw-button--xl">
                                <?php echo esc_html( $settings['primary_button_text'] ); ?>
                            </a>
                        <?php endif; ?>

                        <?php if ( ! empty( $settings['secondary_button_text'] ) ) : ?>
                            <a href="<?php echo esc_url( $settings['secondary_button_link']['url'] ?? '#' ); ?>" class="gw-button gw-button--editorial gw-button--xl">
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
