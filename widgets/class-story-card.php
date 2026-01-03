<?php
/**
 * Story Card Widget.
 *
 * @package GW_Elements
 */

namespace GW\Elements\Widgets;

use Elementor\Controls_Manager;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class Story_Card
 */
class Story_Card extends Widget_Base_GW {

    /**
     * Widget slug.
     *
     * @var string
     */
    protected string $widget_slug = 'story-card';

    /**
     * Get widget name.
     *
     * @return string
     */
    public function get_name(): string {
        return 'gw-story-card';
    }

    /**
     * Get widget title.
     *
     * @return string
     */
    public function get_title(): string {
        return esc_html__( 'GW Story Card', 'gw-elements' );
    }

    /**
     * Get widget icon.
     *
     * @return string
     */
    public function get_icon(): string {
        return 'eicon-post';
    }

    /**
     * Get widget keywords.
     *
     * @return array
     */
    public function get_keywords(): array {
        return [ 'gw', 'story', 'article', 'blog', 'post', 'laviasana' ];
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
            'source',
            [
                'label'   => esc_html__( 'Source', 'gw-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'manual',
                'options' => [
                    'manual' => esc_html__( 'Manual', 'gw-elements' ),
                    'post'   => esc_html__( 'WordPress Post', 'gw-elements' ),
                ],
            ]
        );

        // Get posts for selection.
        $posts = get_posts( [
            'post_type'      => 'post',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
        ] );

        $post_options = [];
        foreach ( $posts as $post ) {
            $post_options[ $post->ID ] = $post->post_title;
        }

        $this->add_control(
            'post_id',
            [
                'label'     => esc_html__( 'Post', 'gw-elements' ),
                'type'      => Controls_Manager::SELECT2,
                'options'   => $post_options,
                'condition' => [
                    'source' => 'post',
                ],
            ]
        );

        $this->add_control(
            'image',
            [
                'label'     => esc_html__( 'Image', 'gw-elements' ),
                'type'      => Controls_Manager::MEDIA,
                'condition' => [
                    'source' => 'manual',
                ],
            ]
        );

        $this->add_control(
            'category',
            [
                'label'     => esc_html__( 'Category', 'gw-elements' ),
                'type'      => Controls_Manager::TEXT,
                'condition' => [
                    'source' => 'manual',
                ],
            ]
        );

        $this->add_control(
            'title',
            [
                'label'       => esc_html__( 'Title', 'gw-elements' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'Story Title',
                'label_block' => true,
                'condition'   => [
                    'source' => 'manual',
                ],
            ]
        );

        $this->add_control(
            'excerpt',
            [
                'label'       => esc_html__( 'Excerpt', 'gw-elements' ),
                'type'        => Controls_Manager::TEXTAREA,
                'label_block' => true,
                'condition'   => [
                    'source' => 'manual',
                ],
            ]
        );

        $this->add_control(
            'date',
            [
                'label'     => esc_html__( 'Date', 'gw-elements' ),
                'type'      => Controls_Manager::DATE_TIME,
                'condition' => [
                    'source' => 'manual',
                ],
            ]
        );

        $this->add_control(
            'link',
            [
                'label'     => esc_html__( 'Link', 'gw-elements' ),
                'type'      => Controls_Manager::URL,
                'condition' => [
                    'source' => 'manual',
                ],
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
            'image_aspect',
            [
                'label'   => esc_html__( 'Image Aspect Ratio', 'gw-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => '16-10',
                'options' => [
                    '16-9'  => '16:9',
                    '16-10' => '16:10',
                    '4-3'   => '4:3',
                    'square' => '1:1',
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

        $data = $this->get_story_data( $settings );

        if ( empty( $data['title'] ) ) {
            return;
        }

        $aspect_class = 'gw-aspect-' . str_replace( '-', '-', $settings['image_aspect'] );

        $this->add_render_attribute( 'wrapper', 'class', 'gw-story-card' );
        ?>
        <article <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
            <?php if ( ! empty( $data['image'] ) ) : ?>
                <a href="<?php echo esc_url( $data['link'] ); ?>" class="gw-story-card__image <?php echo esc_attr( $aspect_class ); ?> gw-image-zoom">
                    <img src="<?php echo esc_url( $data['image'] ); ?>" alt="<?php echo esc_attr( $data['title'] ); ?>" loading="lazy" decoding="async">
                </a>
            <?php endif; ?>

            <div class="gw-story-card__content">
                <div class="gw-story-card__meta">
                    <?php if ( ! empty( $data['category'] ) ) : ?>
                        <span class="gw-story-card__category"><?php echo esc_html( $data['category'] ); ?></span>
                    <?php endif; ?>
                    <?php if ( ! empty( $data['date'] ) ) : ?>
                        <span class="gw-story-card__date"><?php echo esc_html( $data['date'] ); ?></span>
                    <?php endif; ?>
                </div>

                <a href="<?php echo esc_url( $data['link'] ); ?>">
                    <h3 class="gw-story-card__title"><?php echo esc_html( $data['title'] ); ?></h3>
                </a>

                <?php if ( ! empty( $data['excerpt'] ) ) : ?>
                    <p class="gw-story-card__excerpt"><?php echo esc_html( $data['excerpt'] ); ?></p>
                <?php endif; ?>

                <a href="<?php echo esc_url( $data['link'] ); ?>" class="gw-story-card__link gw-button gw-button--editorial">
                    <?php esc_html_e( 'Leggi di più', 'gw-elements' ); ?>
                </a>
            </div>
        </article>
        <?php
    }

    /**
     * Get story data from settings.
     *
     * @param array $settings Widget settings.
     * @return array
     */
    private function get_story_data( array $settings ): array {
        if ( 'post' === $settings['source'] && ! empty( $settings['post_id'] ) ) {
            $post = get_post( $settings['post_id'] );

            if ( ! $post ) {
                return [];
            }

            $categories = get_the_category( $post->ID );
            $category   = ! empty( $categories ) ? $categories[0]->name : '';

            return [
                'image'    => get_the_post_thumbnail_url( $post->ID, 'medium_large' ),
                'category' => $category,
                'title'    => $post->post_title,
                'excerpt'  => wp_trim_words( $post->post_excerpt ?: $post->post_content, 20 ),
                'date'     => get_the_date( '', $post->ID ),
                'link'     => get_permalink( $post->ID ),
            ];
        }

        $date = '';
        if ( ! empty( $settings['date'] ) ) {
            $date = wp_date( 'd M Y', strtotime( $settings['date'] ) );
        }

        return [
            'image'    => $settings['image']['url'] ?? '',
            'category' => $settings['category'] ?? '',
            'title'    => $settings['title'] ?? '',
            'excerpt'  => $settings['excerpt'] ?? '',
            'date'     => $date,
            'link'     => $settings['link']['url'] ?? '#',
        ];
    }
}
