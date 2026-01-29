<?php
/**
 * Contact Info Widget.
 *
 * @package GW_Elements
 */

namespace GW\Elements\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class Contact_Info
 */
class Contact_Info extends Widget_Base_GW {

    /**
     * Widget slug.
     *
     * @var string
     */
    protected string $widget_slug = 'contact-info';

    /**
     * Get widget name.
     *
     * @return string
     */
    public function get_name(): string {
        return 'gw-contact-info';
    }

    /**
     * Get widget title.
     *
     * @return string
     */
    public function get_title(): string {
        return esc_html__( 'GW Contact Info', 'gw-elements' );
    }

    /**
     * Get widget icon.
     *
     * @return string
     */
    public function get_icon(): string {
        return 'eicon-map-pin';
    }

    /**
     * Get widget keywords.
     *
     * @return array
     */
    public function get_keywords(): array {
        return [ 'gw', 'contact', 'info', 'address', 'phone', 'email', 'laviasana' ];
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
                'default' => 'map-pin',
                'options' => $this->get_icon_options(),
            ]
        );

        $repeater->add_control(
            'title',
            [
                'label'       => esc_html__( 'Title', 'gw-elements' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Title', 'gw-elements' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'content',
            [
                'label'       => esc_html__( 'Content', 'gw-elements' ),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => esc_html__( 'Content text', 'gw-elements' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label'       => esc_html__( 'Link', 'gw-elements' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => 'https://example.com',
            ]
        );

        $this->add_control(
            'items',
            [
                'label'       => esc_html__( 'Contact Items', 'gw-elements' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'icon'    => 'map-pin',
                        'title'   => esc_html__( 'Indirizzo', 'gw-elements' ),
                        'content' => esc_html__( "Via del Benessere, 123\n00100 Roma, Italia", 'gw-elements' ),
                    ],
                    [
                        'icon'    => 'phone',
                        'title'   => esc_html__( 'Telefono', 'gw-elements' ),
                        'content' => '+39 06 123 4567',
                        'link'    => [ 'url' => 'tel:+390612345567' ],
                    ],
                    [
                        'icon'    => 'mail',
                        'title'   => esc_html__( 'Email', 'gw-elements' ),
                        'content' => 'contact@laviasana.com',
                        'link'    => [ 'url' => 'mailto:contact@laviasana.com' ],
                    ],
                    [
                        'icon'    => 'clock',
                        'title'   => esc_html__( 'Orari', 'gw-elements' ),
                        'content' => esc_html__( "Lun - Ven: 9:00 - 18:00\nSab: 10:00 - 14:00", 'gw-elements' ),
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
            'icon_bg_color',
            [
                'label'     => esc_html__( 'Icon Background', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'hsl(338 60% 94%)',
                'selectors' => [
                    '{{WRAPPER}} .gw-contact-info__icon' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .gw-contact-info__icon .gw-icon' => 'color: {{VALUE}};',
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

        $this->add_render_attribute( 'wrapper', 'class', 'gw-contact-info' );
        $this->add_animation_wrapper_attributes( $settings );
        ?>
        <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
            <div class="gw-contact-info__items">
                <?php foreach ( $settings['items'] as $item ) :
                    $has_link = ! empty( $item['link']['url'] );
                    $tag = $has_link ? 'a' : 'div';
                    $attrs = $has_link ? ' href="' . esc_url( $item['link']['url'] ) . '"' : '';
                ?>
                    <<?php echo esc_html( $tag ); ?><?php echo $attrs; ?> class="gw-contact-info__item">
                        <div class="gw-contact-info__icon">
                            <?php echo $this->render_icon( $item['icon'], [ 'class' => 'gw-icon' ] ); ?>
                        </div>
                        <div class="gw-contact-info__content">
                            <h4 class="gw-contact-info__title"><?php echo esc_html( $item['title'] ); ?></h4>
                            <p class="gw-contact-info__text"><?php echo nl2br( esc_html( $item['content'] ) ); ?></p>
                        </div>
                    </<?php echo esc_html( $tag ); ?>>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}
