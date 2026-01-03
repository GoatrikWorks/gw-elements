<?php
/**
 * Upcoming Events Widget.
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
 * Class Upcoming_Events
 */
class Upcoming_Events extends Widget_Base_GW {

    /**
     * Widget slug.
     *
     * @var string
     */
    protected string $widget_slug = 'upcoming-events';

    /**
     * Get widget name.
     *
     * @return string
     */
    public function get_name(): string {
        return 'gw-upcoming-events';
    }

    /**
     * Get widget title.
     *
     * @return string
     */
    public function get_title(): string {
        return esc_html__( 'GW Upcoming Events', 'gw-elements' );
    }

    /**
     * Get widget icon.
     *
     * @return string
     */
    public function get_icon(): string {
        return 'eicon-calendar';
    }

    /**
     * Get widget keywords.
     *
     * @return array
     */
    public function get_keywords(): array {
        return [ 'gw', 'events', 'calendar', 'services', 'laviasana' ];
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
                'default'     => 'Prossimi',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'title',
            [
                'label'       => esc_html__( 'Title', 'gw-elements' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'Eventi in Programma',
                'label_block' => true,
            ]
        );

        $this->end_controls_section();

        // Events Section.
        $this->start_controls_section(
            'section_events',
            [
                'label' => esc_html__( 'Events', 'gw-elements' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

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
                'default'     => esc_html__( 'Event Title', 'gw-elements' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'description',
            [
                'label'       => esc_html__( 'Description', 'gw-elements' ),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => esc_html__( 'Event description', 'gw-elements' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'date',
            [
                'label'   => esc_html__( 'Date', 'gw-elements' ),
                'type'    => Controls_Manager::DATE_TIME,
                'default' => gmdate( 'Y-m-d H:i', strtotime( '+1 week' ) ),
            ]
        );

        $repeater->add_control(
            'location',
            [
                'label'       => esc_html__( 'Location', 'gw-elements' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Rome, Italy', 'gw-elements' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label'       => esc_html__( 'Link', 'gw-elements' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => '/events/event-name',
            ]
        );

        $this->add_control(
            'events',
            [
                'label'       => esc_html__( 'Events', 'gw-elements' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'title'       => esc_html__( 'Ritiro Yoga Weekend', 'gw-elements' ),
                        'description' => esc_html__( 'Un weekend di relax e benessere nella campagna toscana.', 'gw-elements' ),
                        'date'        => gmdate( 'Y-m-d H:i', strtotime( '+2 weeks' ) ),
                        'location'    => 'Toscana',
                    ],
                    [
                        'title'       => esc_html__( 'Workshop Nutrizione', 'gw-elements' ),
                        'description' => esc_html__( 'Impara i segreti di una alimentazione sana e bilanciata.', 'gw-elements' ),
                        'date'        => gmdate( 'Y-m-d H:i', strtotime( '+3 weeks' ) ),
                        'location'    => 'Roma',
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
                    '{{WRAPPER}} .gw-upcoming-events' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

        $this->add_render_attribute( 'wrapper', 'class', 'gw-upcoming-events' );
        $this->add_animation_wrapper_attributes( $settings );
        ?>
        <section <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
            <div class="gw-upcoming-events__container gw-container-wide">
                <?php if ( ! empty( $settings['subtitle'] ) || ! empty( $settings['title'] ) ) : ?>
                    <div class="gw-upcoming-events__header">
                        <?php if ( ! empty( $settings['subtitle'] ) ) : ?>
                            <span class="gw-section-subtitle"><?php echo esc_html( $settings['subtitle'] ); ?></span>
                        <?php endif; ?>
                        <?php if ( ! empty( $settings['title'] ) ) : ?>
                            <h2 class="gw-section-title"><?php echo esc_html( $settings['title'] ); ?></h2>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <div class="gw-upcoming-events__grid">
                    <?php foreach ( $settings['events'] as $event ) :
                        $link = $event['link']['url'] ?? '#';
                        $image_url = $event['image']['url'] ?? '';
                        $date = ! empty( $event['date'] ) ? strtotime( $event['date'] ) : '';
                    ?>
                        <article class="gw-event-card">
                            <?php if ( $image_url ) : ?>
                                <a href="<?php echo esc_url( $link ); ?>" class="gw-event-card__image gw-image-zoom">
                                    <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $event['title'] ); ?>" loading="lazy" decoding="async">
                                </a>
                            <?php endif; ?>
                            <div class="gw-event-card__content">
                                <?php if ( $date ) : ?>
                                    <div class="gw-event-card__meta">
                                        <span class="gw-event-card__date">
                                            <?php echo $this->render_icon( 'calendar', [ 'class' => 'gw-icon gw-icon--sm' ] ); ?>
                                            <?php echo esc_html( wp_date( 'd M Y', $date ) ); ?>
                                        </span>
                                        <?php if ( ! empty( $event['location'] ) ) : ?>
                                            <span class="gw-event-card__location">
                                                <?php echo $this->render_icon( 'map-pin', [ 'class' => 'gw-icon gw-icon--sm' ] ); ?>
                                                <?php echo esc_html( $event['location'] ); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                <a href="<?php echo esc_url( $link ); ?>">
                                    <h3 class="gw-event-card__title"><?php echo esc_html( $event['title'] ); ?></h3>
                                </a>
                                <?php if ( ! empty( $event['description'] ) ) : ?>
                                    <p class="gw-event-card__description"><?php echo esc_html( $event['description'] ); ?></p>
                                <?php endif; ?>
                                <a href="<?php echo esc_url( $link ); ?>" class="gw-event-card__link gw-button gw-button--editorial">
                                    <?php esc_html_e( 'Scopri di più', 'gw-elements' ); ?>
                                </a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php
    }
}
