<?php
/**
 * Contact Form Widget.
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
 * Class Contact_Form
 */
class Contact_Form extends Widget_Base_GW {

    /**
     * Widget slug.
     *
     * @var string
     */
    protected string $widget_slug = 'contact-form';

    /**
     * Get widget name.
     *
     * @return string
     */
    public function get_name(): string {
        return 'gw-contact-form';
    }

    /**
     * Get widget title.
     *
     * @return string
     */
    public function get_title(): string {
        return esc_html__( 'GW Contact Form', 'gw-elements' );
    }

    /**
     * Get widget icon.
     *
     * @return string
     */
    public function get_icon(): string {
        return 'eicon-form-horizontal';
    }

    /**
     * Get widget keywords.
     *
     * @return array
     */
    public function get_keywords(): array {
        return [ 'gw', 'contact', 'form', 'laviasana' ];
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
            'form_type',
            [
                'label'   => esc_html__( 'Form Type', 'gw-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'native',
                'options' => [
                    'native'   => esc_html__( 'Native Form', 'gw-elements' ),
                    'cf7'      => esc_html__( 'Contact Form 7', 'gw-elements' ),
                    'shortcode' => esc_html__( 'Custom Shortcode', 'gw-elements' ),
                ],
            ]
        );

        $this->add_control(
            'shortcode',
            [
                'label'       => esc_html__( 'Shortcode', 'gw-elements' ),
                'type'        => Controls_Manager::TEXTAREA,
                'placeholder' => '[contact-form-7 id="123"]',
                'condition'   => [
                    'form_type!' => 'native',
                ],
            ]
        );

        $this->add_control(
            'submit_text',
            [
                'label'     => esc_html__( 'Submit Button Text', 'gw-elements' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => 'Invia Messaggio',
                'condition' => [
                    'form_type' => 'native',
                ],
            ]
        );

        $this->add_control(
            'email_to',
            [
                'label'       => esc_html__( 'Send To Email', 'gw-elements' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => get_option( 'admin_email' ),
                'description' => esc_html__( 'Email address to receive form submissions', 'gw-elements' ),
                'condition'   => [
                    'form_type' => 'native',
                ],
            ]
        );

        $this->end_controls_section();

        // Fields Section.
        $this->start_controls_section(
            'section_fields',
            [
                'label'     => esc_html__( 'Fields', 'gw-elements' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'form_type' => 'native',
                ],
            ]
        );

        $this->add_control(
            'show_name',
            [
                'label'        => esc_html__( 'Show Name Field', 'gw-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'gw-elements' ),
                'label_off'    => esc_html__( 'No', 'gw-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'show_email',
            [
                'label'        => esc_html__( 'Show Email Field', 'gw-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'gw-elements' ),
                'label_off'    => esc_html__( 'No', 'gw-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'show_subject',
            [
                'label'        => esc_html__( 'Show Subject Field', 'gw-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'gw-elements' ),
                'label_off'    => esc_html__( 'No', 'gw-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'show_message',
            [
                'label'        => esc_html__( 'Show Message Field', 'gw-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'gw-elements' ),
                'label_off'    => esc_html__( 'No', 'gw-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes',
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
            'form_width',
            [
                'label'   => esc_html__( 'Form Width', 'gw-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'medium',
                'options' => [
                    'narrow' => esc_html__( 'Narrow (480px)', 'gw-elements' ),
                    'medium' => esc_html__( 'Medium (600px)', 'gw-elements' ),
                    'wide'   => esc_html__( 'Wide (800px)', 'gw-elements' ),
                    'full'   => esc_html__( 'Full Width', 'gw-elements' ),
                ],
            ]
        );

        $this->add_control(
            'form_alignment',
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
                'condition' => [
                    'form_width!' => 'full',
                ],
            ]
        );

        $this->add_control(
            'form_style',
            [
                'label'   => esc_html__( 'Form Style', 'gw-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'bordered',
                'options' => [
                    'bordered' => esc_html__( 'Bordered', 'gw-elements' ),
                    'card'     => esc_html__( 'Card (with shadow)', 'gw-elements' ),
                    'minimal'  => esc_html__( 'Minimal (no border)', 'gw-elements' ),
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section.
        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__( 'Form Style', 'gw-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'form_background',
            [
                'label'     => esc_html__( 'Background Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-contact-form__form' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'form_padding',
            [
                'label'      => esc_html__( 'Padding', 'gw-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'range'      => [
                    'px'  => [
                        'min' => 0,
                        'max' => 80,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 5,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .gw-contact-form__form' => 'padding: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'input_border_radius',
            [
                'label'      => esc_html__( 'Input Border Radius', 'gw-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 20,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .gw-input, {{WRAPPER}} .gw-textarea' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'input_background',
            [
                'label'     => esc_html__( 'Input Background', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-input, {{WRAPPER}} .gw-textarea' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Button Style Section.
        $this->start_controls_section(
            'section_button_style',
            [
                'label' => esc_html__( 'Button', 'gw-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
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
                    '{{WRAPPER}} .gw-contact-form__submit .gw-button' => 'width: 100%;',
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

        $wrapper_classes = [ 'gw-contact-form' ];

        // Width class.
        if ( ! empty( $settings['form_width'] ) ) {
            $wrapper_classes[] = 'gw-contact-form--' . $settings['form_width'];
        }

        // Alignment class.
        if ( ! empty( $settings['form_alignment'] ) && 'full' !== $settings['form_width'] ) {
            $wrapper_classes[] = 'gw-contact-form--align-' . $settings['form_alignment'];
        }

        // Style class.
        if ( ! empty( $settings['form_style'] ) && 'bordered' !== $settings['form_style'] ) {
            $wrapper_classes[] = 'gw-contact-form--' . $settings['form_style'];
        }

        $this->add_render_attribute( 'wrapper', 'class', $wrapper_classes );
        $this->add_animation_wrapper_attributes( $settings );

        if ( 'native' !== $settings['form_type'] && ! empty( $settings['shortcode'] ) ) {
            ?>
            <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
                <?php echo do_shortcode( $settings['shortcode'] ); ?>
            </div>
            <?php
            return;
        }

        $form_id = 'gw-contact-form-' . $this->get_id();
        ?>
        <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
            <form id="<?php echo esc_attr( $form_id ); ?>" class="gw-contact-form__form" method="post" data-ajax="true">
                <?php wp_nonce_field( 'gw_contact_form', 'gw_contact_nonce' ); ?>
                <input type="hidden" name="action" value="gw_contact_form_submit">
                <input type="hidden" name="email_to" value="<?php echo esc_attr( $settings['email_to'] ); ?>">

                <div class="gw-contact-form__fields">
                    <?php if ( 'yes' === $settings['show_name'] ) : ?>
                        <div class="gw-form-group">
                            <label for="<?php echo esc_attr( $form_id ); ?>-name" class="gw-label">
                                <?php esc_html_e( 'Nome *', 'gw-elements' ); ?>
                            </label>
                            <input
                                type="text"
                                id="<?php echo esc_attr( $form_id ); ?>-name"
                                name="name"
                                class="gw-input"
                                required
                                placeholder="<?php esc_attr_e( 'Il tuo nome', 'gw-elements' ); ?>"
                            >
                        </div>
                    <?php endif; ?>

                    <?php if ( 'yes' === $settings['show_email'] ) : ?>
                        <div class="gw-form-group">
                            <label for="<?php echo esc_attr( $form_id ); ?>-email" class="gw-label">
                                <?php esc_html_e( 'Email *', 'gw-elements' ); ?>
                            </label>
                            <input
                                type="email"
                                id="<?php echo esc_attr( $form_id ); ?>-email"
                                name="email"
                                class="gw-input"
                                required
                                placeholder="<?php esc_attr_e( 'La tua email', 'gw-elements' ); ?>"
                            >
                        </div>
                    <?php endif; ?>

                    <?php if ( 'yes' === $settings['show_subject'] ) : ?>
                        <div class="gw-form-group gw-form-group--full">
                            <label for="<?php echo esc_attr( $form_id ); ?>-subject" class="gw-label">
                                <?php esc_html_e( 'Oggetto', 'gw-elements' ); ?>
                            </label>
                            <input
                                type="text"
                                id="<?php echo esc_attr( $form_id ); ?>-subject"
                                name="subject"
                                class="gw-input"
                                placeholder="<?php esc_attr_e( 'Oggetto del messaggio', 'gw-elements' ); ?>"
                            >
                        </div>
                    <?php endif; ?>

                    <?php if ( 'yes' === $settings['show_message'] ) : ?>
                        <div class="gw-form-group gw-form-group--full">
                            <label for="<?php echo esc_attr( $form_id ); ?>-message" class="gw-label">
                                <?php esc_html_e( 'Messaggio *', 'gw-elements' ); ?>
                            </label>
                            <textarea
                                id="<?php echo esc_attr( $form_id ); ?>-message"
                                name="message"
                                class="gw-textarea"
                                required
                                rows="5"
                                placeholder="<?php esc_attr_e( 'Il tuo messaggio', 'gw-elements' ); ?>"
                            ></textarea>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="gw-contact-form__submit">
                    <button type="submit" class="gw-button gw-button--hero">
                        <span class="gw-button__text"><?php echo esc_html( $settings['submit_text'] ); ?></span>
                        <span class="gw-button__loading" style="display: none;">
                            <svg class="gw-spinner" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" fill="none" stroke-linecap="round" stroke-dasharray="31.4 31.4" /></svg>
                        </span>
                    </button>
                </div>

                <div class="gw-contact-form__message" style="display: none;"></div>
            </form>
        </div>
        <?php
    }
}
