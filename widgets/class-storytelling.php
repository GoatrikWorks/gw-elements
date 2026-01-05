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
            'show_header',
            [
                'label'        => esc_html__( 'Show Header', 'gw-elements' ),
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
                'default'     => 'Scopri le nostre',
                'label_block' => true,
                'condition'   => [
                    'show_header' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'title',
            [
                'label'       => esc_html__( 'Title', 'gw-elements' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'Storie di Benessere',
                'label_block' => true,
                'condition'   => [
                    'show_header' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'header_align',
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
                    'show_header' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .gw-storytelling__header' => 'text-align: {{VALUE}};',
                ],
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
            'link_text',
            [
                'label'   => esc_html__( 'Link Text', 'gw-elements' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Leggi di più', 'gw-elements' ),
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

        // Layout Section.
        $this->start_controls_section(
            'section_layout',
            [
                'label' => esc_html__( 'Layout', 'gw-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'background_color',
            [
                'label'     => esc_html__( 'Background Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-storytelling' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .gw-storytelling' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ],
                'selectors' => [
                    '{{WRAPPER}} .gw-storytelling__grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
                ],
            ]
        );

        $this->add_responsive_control(
            'gap',
            [
                'label'      => esc_html__( 'Gap', 'gw-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default'    => [
                    'size' => 1.5,
                    'unit' => 'rem',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .gw-storytelling__grid' => 'gap: {{SIZE}}{{UNIT}};',
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
                'selector' => '{{WRAPPER}} .gw-section-subtitle',
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label'     => esc_html__( 'Subtitle Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-section-subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'label'    => esc_html__( 'Title Typography', 'gw-elements' ),
                'selector' => '{{WRAPPER}} .gw-section-title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__( 'Title Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-section-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Card Style Section.
        $this->start_controls_section(
            'section_card_style',
            [
                'label' => esc_html__( 'Card Style', 'gw-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'card_height',
            [
                'label'      => esc_html__( 'Card Height', 'gw-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem', 'vh' ],
                'range'      => [
                    'px' => [
                        'min' => 200,
                        'max' => 600,
                    ],
                    'vh' => [
                        'min' => 20,
                        'max' => 80,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .gw-story-card__image' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'gw-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 30,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .gw-story-card__image' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'card_title_typography',
                'label'    => esc_html__( 'Card Title Typography', 'gw-elements' ),
                'selector' => '{{WRAPPER}} .gw-story-card__title',
            ]
        );

        $this->add_control(
            'card_title_color',
            [
                'label'     => esc_html__( 'Card Title Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-story-card__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'card_description_color',
            [
                'label'     => esc_html__( 'Card Description Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-story-card__description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'card_link_color',
            [
                'label'     => esc_html__( 'Card Link Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-story-card__link' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'overlay_color',
            [
                'label'     => esc_html__( 'Overlay Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-story-card__overlay' => 'background: linear-gradient(to top, {{VALUE}} 0%, transparent 100%);',
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
                <?php if ( 'yes' === $settings['show_header'] && ( ! empty( $settings['subtitle'] ) || ! empty( $settings['title'] ) ) ) : ?>
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
                        $link_text = $story['link_text'] ?? esc_html__( 'Leggi di più', 'gw-elements' );
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
                                        <?php if ( ! empty( $link_text ) ) : ?>
                                            <span class="gw-story-card__link">
                                                <?php echo esc_html( $link_text ); ?>
                                                <?php echo $this->render_icon( 'arrow-right', [ 'class' => 'gw-icon gw-icon--sm' ] ); ?>
                                            </span>
                                        <?php endif; ?>
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
