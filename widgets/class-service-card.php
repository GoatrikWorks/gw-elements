<?php
/**
 * Service Card Widget.
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
 * Class Service_Card
 */
class Service_Card extends Widget_Base_GW {

    /**
     * Widget slug.
     *
     * @var string
     */
    protected string $widget_slug = 'service-card';

    /**
     * Get widget name.
     *
     * @return string
     */
    public function get_name(): string {
        return 'gw-service-card';
    }

    /**
     * Get widget title.
     *
     * @return string
     */
    public function get_title(): string {
        return esc_html__( 'GW Service Card', 'gw-elements' );
    }

    /**
     * Get widget icon.
     *
     * @return string
     */
    public function get_icon(): string {
        return 'eicon-info-box';
    }

    /**
     * Get widget keywords.
     *
     * @return array
     */
    public function get_keywords(): array {
        return [ 'gw', 'service', 'card', 'laviasana' ];
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
                'label' => esc_html__( 'Image', 'gw-elements' ),
                'type'  => Controls_Manager::MEDIA,
            ]
        );

        $this->add_control(
            'title',
            [
                'label'       => esc_html__( 'Title', 'gw-elements' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'Service Name',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'description',
            [
                'label'       => esc_html__( 'Description', 'gw-elements' ),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => 'Service description text here.',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'price',
            [
                'label' => esc_html__( 'Price', 'gw-elements' ),
                'type'  => Controls_Manager::TEXT,
            ]
        );

        $this->add_control(
            'duration',
            [
                'label' => esc_html__( 'Duration', 'gw-elements' ),
                'type'  => Controls_Manager::TEXT,
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label'   => esc_html__( 'Button Text', 'gw-elements' ),
                'type'    => Controls_Manager::TEXT,
                'default' => 'Prenota',
            ]
        );

        $this->add_control(
            'button_link',
            [
                'label'       => esc_html__( 'Button Link', 'gw-elements' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => '/booking',
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
            'feature',
            [
                'label'       => esc_html__( 'Feature', 'gw-elements' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'features',
            [
                'label'       => esc_html__( 'Features', 'gw-elements' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [ 'feature' => 'Feature one' ],
                    [ 'feature' => 'Feature two' ],
                ],
                'title_field' => '{{{ feature }}}',
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
            'card_background',
            [
                'label'     => esc_html__( 'Card Background', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-service-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output.
     */
    protected function render(): void {
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute( 'wrapper', 'class', 'gw-service-card' );
        ?>
        <article <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
            <?php if ( ! empty( $settings['image']['url'] ) ) : ?>
                <div class="gw-service-card__image gw-image-zoom">
                    <img src="<?php echo esc_url( $settings['image']['url'] ); ?>" alt="<?php echo esc_attr( $settings['title'] ); ?>" loading="lazy" decoding="async">
                </div>
            <?php endif; ?>

            <div class="gw-service-card__content">
                <?php if ( ! empty( $settings['title'] ) ) : ?>
                    <h3 class="gw-service-card__title"><?php echo esc_html( $settings['title'] ); ?></h3>
                <?php endif; ?>

                <?php if ( ! empty( $settings['description'] ) ) : ?>
                    <p class="gw-service-card__description"><?php echo esc_html( $settings['description'] ); ?></p>
                <?php endif; ?>

                <?php if ( ! empty( $settings['features'] ) ) : ?>
                    <ul class="gw-service-card__features">
                        <?php foreach ( $settings['features'] as $feature ) : ?>
                            <li>
                                <svg class="gw-icon gw-icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 6 9 17l-5-5"/>
                                </svg>
                                <?php echo esc_html( $feature['feature'] ); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <div class="gw-service-card__footer">
                    <?php if ( ! empty( $settings['price'] ) || ! empty( $settings['duration'] ) ) : ?>
                        <div class="gw-service-card__meta">
                            <?php if ( ! empty( $settings['price'] ) ) : ?>
                                <span class="gw-service-card__price"><?php echo esc_html( $settings['price'] ); ?></span>
                            <?php endif; ?>
                            <?php if ( ! empty( $settings['duration'] ) ) : ?>
                                <span class="gw-service-card__duration"><?php echo esc_html( $settings['duration'] ); ?></span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ( ! empty( $settings['button_text'] ) ) : ?>
                        <a href="<?php echo esc_url( $settings['button_link']['url'] ?? '#' ); ?>" class="gw-service-card__button gw-button gw-button--card">
                            <?php echo esc_html( $settings['button_text'] ); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </article>
        <?php
    }
}
