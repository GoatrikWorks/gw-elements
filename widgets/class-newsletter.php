<?php
/**
 * Newsletter Widget.
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
 * Class Newsletter
 */
class Newsletter extends Widget_Base_GW {

    /**
     * Widget slug.
     *
     * @var string
     */
    protected string $widget_slug = 'newsletter';

    /**
     * Get widget name.
     *
     * @return string
     */
    public function get_name(): string {
        return 'gw-newsletter';
    }

    /**
     * Get widget title.
     *
     * @return string
     */
    public function get_title(): string {
        return esc_html__( 'GW Newsletter', 'gw-elements' );
    }

    /**
     * Get widget icon.
     *
     * @return string
     */
    public function get_icon(): string {
        return 'eicon-email-field';
    }

    /**
     * Get widget keywords.
     *
     * @return array
     */
    public function get_keywords(): array {
        return [ 'gw', 'newsletter', 'subscribe', 'email', 'laviasana' ];
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
            'title',
            [
                'label'       => esc_html__( 'Title', 'gw-elements' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'Iscriviti alla Newsletter',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'description',
            [
                'label'       => esc_html__( 'Description', 'gw-elements' ),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => 'Ricevi offerte esclusive e aggiornamenti sui nuovi prodotti.',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'placeholder',
            [
                'label'   => esc_html__( 'Input Placeholder', 'gw-elements' ),
                'type'    => Controls_Manager::TEXT,
                'default' => 'La tua email',
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label'   => esc_html__( 'Button Text', 'gw-elements' ),
                'type'    => Controls_Manager::TEXT,
                'default' => 'Iscriviti',
            ]
        );

        $this->add_control(
            'privacy_text',
            [
                'label'       => esc_html__( 'Privacy Text', 'gw-elements' ),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => 'Iscrivendoti, accetti la nostra Privacy Policy.',
                'label_block' => true,
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
            'layout',
            [
                'label'   => esc_html__( 'Layout', 'gw-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'inline',
                'options' => [
                    'inline'  => esc_html__( 'Inline', 'gw-elements' ),
                    'stacked' => esc_html__( 'Stacked', 'gw-elements' ),
                ],
            ]
        );

        $this->add_control(
            'background_color',
            [
                'label'     => esc_html__( 'Background Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-newsletter' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'padding',
            [
                'label'      => esc_html__( 'Padding', 'gw-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'rem' ],
                'selectors'  => [
                    '{{WRAPPER}} .gw-newsletter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

        $layout_class = 'inline' === $settings['layout'] ? 'gw-newsletter--inline' : 'gw-newsletter--stacked';

        $this->add_render_attribute( 'wrapper', 'class', [ 'gw-newsletter', $layout_class ] );
        $this->add_animation_wrapper_attributes( $settings );
        ?>
        <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
            <div class="gw-newsletter__content">
                <?php if ( ! empty( $settings['title'] ) ) : ?>
                    <h3 class="gw-newsletter__title"><?php echo esc_html( $settings['title'] ); ?></h3>
                <?php endif; ?>
                <?php if ( ! empty( $settings['description'] ) ) : ?>
                    <p class="gw-newsletter__description"><?php echo esc_html( $settings['description'] ); ?></p>
                <?php endif; ?>
            </div>

            <form class="gw-newsletter__form" method="post">
                <?php wp_nonce_field( 'gw_newsletter', 'gw_newsletter_nonce' ); ?>
                <div class="gw-newsletter__input-group">
                    <input
                        type="email"
                        name="email"
                        class="gw-newsletter__input gw-input"
                        placeholder="<?php echo esc_attr( $settings['placeholder'] ); ?>"
                        required
                    >
                    <button type="submit" class="gw-newsletter__button gw-button gw-button--hero">
                        <?php echo esc_html( $settings['button_text'] ); ?>
                    </button>
                </div>
                <?php if ( ! empty( $settings['privacy_text'] ) ) : ?>
                    <p class="gw-newsletter__privacy"><?php echo esc_html( $settings['privacy_text'] ); ?></p>
                <?php endif; ?>
            </form>
        </div>
        <?php
    }
}
