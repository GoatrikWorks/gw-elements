<?php
/**
 * Storytelling Widget.
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
 * Class Storytelling
 */
class Storytelling extends Widget_Base_GW {

    /**
     * Widget slug.
     *
     * @var string
     */
    protected string $widget_slug = 'storytelling';

    /**
     * Get widget name.
     *
     * @return string
     */
    public function get_name(): string {
        return 'gw-storytelling';
    }

    /**
     * Get widget title.
     *
     * @return string
     */
    public function get_title(): string {
        return esc_html__( 'GW Storytelling', 'gw-elements' );
    }

    /**
     * Get widget icon.
     *
     * @return string
     */
    public function get_icon(): string {
        return 'eicon-image-box';
    }

    /**
     * Get widget keywords.
     *
     * @return array
     */
    public function get_keywords(): array {
        return [ 'gw', 'story', 'image', 'text', 'laviasana' ];
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
                'default'     => 'Scopri le nostre',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'title',
            [
                'label'       => esc_html__( 'Title', 'gw-elements' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'Storie di Benessere',
                'label_block' => true,
            ]
        );

        $this->end_controls_section();

        // Stories Section.
        $this->start_controls_section(
            'section_stories',
            [
                'label' => esc_html__( 'Stories', 'gw-elements' ),
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
                'default'     => esc_html__( 'Story Title', 'gw-elements' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'description',
            [
                'label'       => esc_html__( 'Description', 'gw-elements' ),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => esc_html__( 'Story description text here.', 'gw-elements' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label'       => esc_html__( 'Link', 'gw-elements' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => '/stories/article',
            ]
        );

        $this->add_control(
            'stories',
            [
                'label'       => esc_html__( 'Stories', 'gw-elements' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'title'       => esc_html__( 'I Segreti della Nutrizione', 'gw-elements' ),
                        'description' => esc_html__( 'Scopri come una dieta equilibrata può trasformare il tuo benessere quotidiano.', 'gw-elements' ),
                    ],
                    [
                        'title'       => esc_html__( 'L\'Arte dello Yoga', 'gw-elements' ),
                        'description' => esc_html__( 'Il viaggio verso l\'armonia di corpo e mente attraverso pratiche antiche.', 'gw-elements' ),
                    ],
                    [
                        'title'       => esc_html__( 'Skincare Naturale', 'gw-elements' ),
                        'description' => esc_html__( 'I benefici degli ingredienti naturali per una pelle radiosa e sana.', 'gw-elements' ),
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
                    '{{WRAPPER}} .gw-storytelling' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

        $this->add_render_attribute( 'wrapper', 'class', 'gw-storytelling' );
        $this->add_animation_wrapper_attributes( $settings );
        ?>
        <section <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
            <div class="gw-storytelling__container gw-container-wide">
                <?php if ( ! empty( $settings['subtitle'] ) || ! empty( $settings['title'] ) ) : ?>
                    <div class="gw-storytelling__header">
                        <?php if ( ! empty( $settings['subtitle'] ) ) : ?>
                            <span class="gw-section-subtitle"><?php echo esc_html( $settings['subtitle'] ); ?></span>
                        <?php endif; ?>
                        <?php if ( ! empty( $settings['title'] ) ) : ?>
                            <h2 class="gw-section-title"><?php echo esc_html( $settings['title'] ); ?></h2>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <div class="gw-storytelling__grid">
                    <?php foreach ( $settings['stories'] as $index => $story ) :
                        $link = $story['link']['url'] ?? '#';
                        $image_url = $story['image']['url'] ?? '';
                    ?>
                        <article class="gw-story-card gw-story-card--<?php echo esc_attr( $index ); ?>">
                            <a href="<?php echo esc_url( $link ); ?>" class="gw-story-card__inner">
                                <div class="gw-story-card__image gw-image-zoom--lg">
                                    <?php if ( $image_url ) : ?>
                                        <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $story['title'] ); ?>" loading="lazy" decoding="async">
                                    <?php endif; ?>
                                    <div class="gw-story-card__overlay"></div>
                                    <div class="gw-story-card__content">
                                        <h3 class="gw-story-card__title"><?php echo esc_html( $story['title'] ); ?></h3>
                                        <p class="gw-story-card__description"><?php echo esc_html( $story['description'] ); ?></p>
                                        <span class="gw-story-card__link">
                                            <?php esc_html_e( 'Leggi di più', 'gw-elements' ); ?>
                                            <?php echo $this->render_icon( 'arrow-right', [ 'class' => 'gw-icon gw-icon--sm' ] ); ?>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php
    }
}
