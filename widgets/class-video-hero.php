<?php
/**
 * Video Hero Widget.
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
 * Class Video_Hero
 */
class Video_Hero extends Widget_Base_GW {

    /**
     * Widget slug.
     *
     * @var string
     */
    protected string $widget_slug = 'video-hero';

    /**
     * Get widget name.
     *
     * @return string
     */
    public function get_name(): string {
        return 'gw-video-hero';
    }

    /**
     * Get widget title.
     *
     * @return string
     */
    public function get_title(): string {
        return esc_html__( 'GW Video Hero', 'gw-elements' );
    }

    /**
     * Get widget icon.
     *
     * @return string
     */
    public function get_icon(): string {
        return 'eicon-youtube';
    }

    /**
     * Get widget keywords.
     *
     * @return array
     */
    public function get_keywords(): array {
        return [ 'gw', 'hero', 'video', 'banner', 'laviasana' ];
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
            'video_source',
            [
                'label'   => esc_html__( 'Video Source', 'gw-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'url',
                'options' => [
                    'url'   => esc_html__( 'URL', 'gw-elements' ),
                    'media' => esc_html__( 'Media Library', 'gw-elements' ),
                ],
            ]
        );

        $this->add_control(
            'video_url',
            [
                'label'       => esc_html__( 'Video URL', 'gw-elements' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://example.com/video.mp4', 'gw-elements' ),
                'condition'   => [
                    'video_source' => 'url',
                ],
            ]
        );

        $this->add_control(
            'video_media',
            [
                'label'      => esc_html__( 'Video File', 'gw-elements' ),
                'type'       => Controls_Manager::MEDIA,
                'media_type' => 'video',
                'condition'  => [
                    'video_source' => 'media',
                ],
            ]
        );

        $this->add_control(
            'poster_image',
            [
                'label' => esc_html__( 'Poster Image', 'gw-elements' ),
                'type'  => Controls_Manager::MEDIA,
            ]
        );

        $this->add_control(
            'subtitle',
            [
                'label'       => esc_html__( 'Subtitle', 'gw-elements' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'Laviasana',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'title',
            [
                'label'       => esc_html__( 'Title', 'gw-elements' ),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => 'Vivi bene, <em>naturalmente</em>',
                'label_block' => true,
                'description' => esc_html__( 'Use <em> tags for italic text', 'gw-elements' ),
            ]
        );

        $this->add_control(
            'description',
            [
                'label'       => esc_html__( 'Description', 'gw-elements' ),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => 'Scopri la nostra selezione di prodotti naturali e certificati per il tuo benessere quotidiano.',
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
                'placeholder' => esc_html__( '/shop', 'gw-elements' ),
            ]
        );

        $this->add_control(
            'secondary_button_text',
            [
                'label'   => esc_html__( 'Secondary Button Text', 'gw-elements' ),
                'type'    => Controls_Manager::TEXT,
                'default' => 'Scopri Chi Siamo',
            ]
        );

        $this->add_control(
            'secondary_button_link',
            [
                'label'       => esc_html__( 'Secondary Button Link', 'gw-elements' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__( '/about', 'gw-elements' ),
            ]
        );

        $this->add_control(
            'show_mute_button',
            [
                'label'        => esc_html__( 'Show Mute Button', 'gw-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'gw-elements' ),
                'label_off'    => esc_html__( 'No', 'gw-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes',
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
            'min_height',
            [
                'label'      => esc_html__( 'Minimum Height', 'gw-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'vh', 'px' ],
                'range'      => [
                    'vh' => [
                        'min' => 30,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 200,
                        'max' => 1200,
                    ],
                ],
                'default'    => [
                    'unit' => 'vh',
                    'size' => 70,
                ],
                'tablet_default' => [
                    'unit' => 'vh',
                    'size' => 80,
                ],
                'mobile_default' => [
                    'unit' => 'vh',
                    'size' => 70,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .gw-video-hero' => 'min-height: {{SIZE}}{{UNIT}};',
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
                    'px' => [
                        'min' => 300,
                        'max' => 1000,
                    ],
                ],
                'default'    => [
                    'unit' => 'rem',
                    'size' => 42,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .gw-video-hero__content' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'overlay_color_start',
            [
                'label'   => esc_html__( 'Overlay Start Color', 'gw-elements' ),
                'type'    => Controls_Manager::COLOR,
                'default' => 'rgba(255, 255, 255, 0.9)',
            ]
        );

        $this->add_control(
            'overlay_color_middle',
            [
                'label'   => esc_html__( 'Overlay Middle Color', 'gw-elements' ),
                'type'    => Controls_Manager::COLOR,
                'default' => 'rgba(255, 255, 255, 0.5)',
            ]
        );

        $this->add_control(
            'overlay_color_end',
            [
                'label'   => esc_html__( 'Overlay End Color', 'gw-elements' ),
                'type'    => Controls_Manager::COLOR,
                'default' => 'transparent',
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

        $this->add_control(
            'subtitle_color',
            [
                'label'     => esc_html__( 'Subtitle Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-video-hero__subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'label'    => esc_html__( 'Title Typography', 'gw-elements' ),
                'selector' => '{{WRAPPER}} .gw-video-hero__title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__( 'Title Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-video-hero__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label'     => esc_html__( 'Description Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-video-hero__description' => 'color: {{VALUE}};',
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

        // Get video URL.
        $video_url = '';
        if ( 'media' === $settings['video_source'] && ! empty( $settings['video_media']['url'] ) ) {
            $video_url = $settings['video_media']['url'];
        } elseif ( ! empty( $settings['video_url']['url'] ) ) {
            $video_url = $settings['video_url']['url'];
        }

        $poster_url = ! empty( $settings['poster_image']['url'] ) ? $settings['poster_image']['url'] : '';

        // Overlay gradient.
        $overlay_style = sprintf(
            'background: linear-gradient(to right, %s, %s, %s);',
            esc_attr( $settings['overlay_color_start'] ),
            esc_attr( $settings['overlay_color_middle'] ),
            esc_attr( $settings['overlay_color_end'] )
        );

        $this->add_render_attribute( 'wrapper', 'class', 'gw-video-hero' );
        $this->add_render_attribute( 'wrapper', 'data-widget', 'video-hero' );
        $this->add_animation_wrapper_attributes( $settings );
        ?>
        <section <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
            <!-- Video Background -->
            <div class="gw-video-hero__background">
                <?php if ( $video_url ) : ?>
                    <video
                        class="gw-video-hero__video"
                        autoplay
                        loop
                        muted
                        playsinline
                        <?php if ( $poster_url ) : ?>poster="<?php echo esc_url( $poster_url ); ?>"<?php endif; ?>
                        preload="metadata"
                    >
                        <source src="<?php echo esc_url( $video_url ); ?>" type="video/mp4">
                    </video>
                <?php elseif ( $poster_url ) : ?>
                    <img src="<?php echo esc_url( $poster_url ); ?>" alt="" class="gw-video-hero__poster">
                <?php endif; ?>
                <div class="gw-video-hero__overlay" style="<?php echo esc_attr( $overlay_style ); ?>"></div>
            </div>

            <?php if ( 'yes' === $settings['show_mute_button'] && $video_url ) : ?>
                <button type="button" class="gw-video-hero__mute" aria-label="<?php esc_attr_e( 'Toggle sound', 'gw-elements' ); ?>">
                    <span class="gw-video-hero__mute-icon gw-video-hero__mute-icon--muted">
                        <?php echo $this->render_icon( 'volume-x', [ 'class' => 'gw-icon gw-icon--sm' ] ); ?>
                    </span>
                    <span class="gw-video-hero__mute-icon gw-video-hero__mute-icon--unmuted" style="display: none;">
                        <?php echo $this->render_icon( 'volume-2', [ 'class' => 'gw-icon gw-icon--sm' ] ); ?>
                    </span>
                </button>
            <?php endif; ?>

            <!-- Content -->
            <div class="gw-video-hero__container gw-container-wide">
                <div class="gw-video-hero__content">
                    <?php if ( ! empty( $settings['subtitle'] ) ) : ?>
                        <span class="gw-video-hero__subtitle gw-section-subtitle">
                            <?php echo esc_html( $settings['subtitle'] ); ?>
                        </span>
                    <?php endif; ?>

                    <?php if ( ! empty( $settings['title'] ) ) : ?>
                        <h1 class="gw-video-hero__title">
                            <?php echo wp_kses_post( $settings['title'] ); ?>
                        </h1>
                    <?php endif; ?>

                    <?php if ( ! empty( $settings['description'] ) ) : ?>
                        <p class="gw-video-hero__description">
                            <?php echo esc_html( $settings['description'] ); ?>
                        </p>
                    <?php endif; ?>

                    <?php if ( ! empty( $settings['primary_button_text'] ) || ! empty( $settings['secondary_button_text'] ) ) : ?>
                        <div class="gw-video-hero__buttons">
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
            </div>
        </section>
        <?php
    }
}
