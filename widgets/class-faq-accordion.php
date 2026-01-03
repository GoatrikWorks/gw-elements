<?php
/**
 * FAQ Accordion Widget.
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
 * Class FAQ_Accordion
 */
class FAQ_Accordion extends Widget_Base_GW {

    /**
     * Widget slug.
     *
     * @var string
     */
    protected string $widget_slug = 'faq-accordion';

    /**
     * Get widget name.
     *
     * @return string
     */
    public function get_name(): string {
        return 'gw-faq-accordion';
    }

    /**
     * Get widget title.
     *
     * @return string
     */
    public function get_title(): string {
        return esc_html__( 'GW FAQ Accordion', 'gw-elements' );
    }

    /**
     * Get widget icon.
     *
     * @return string
     */
    public function get_icon(): string {
        return 'eicon-accordion';
    }

    /**
     * Get widget keywords.
     *
     * @return array
     */
    public function get_keywords(): array {
        return [ 'gw', 'faq', 'accordion', 'questions', 'laviasana' ];
    }

    /**
     * Register widget controls.
     */
    protected function register_controls(): void {
        // Categories Section.
        $this->start_controls_section(
            'section_categories',
            [
                'label' => esc_html__( 'FAQ Categories', 'gw-elements' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $category_repeater = new Repeater();

        $category_repeater->add_control(
            'category_title',
            [
                'label'       => esc_html__( 'Category Title', 'gw-elements' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Category', 'gw-elements' ),
                'label_block' => true,
            ]
        );

        $item_repeater = new Repeater();

        $item_repeater->add_control(
            'question',
            [
                'label'       => esc_html__( 'Question', 'gw-elements' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Question text?', 'gw-elements' ),
                'label_block' => true,
            ]
        );

        $item_repeater->add_control(
            'answer',
            [
                'label'       => esc_html__( 'Answer', 'gw-elements' ),
                'type'        => Controls_Manager::WYSIWYG,
                'default'     => esc_html__( 'Answer text here.', 'gw-elements' ),
            ]
        );

        $category_repeater->add_control(
            'items',
            [
                'label'       => esc_html__( 'FAQ Items', 'gw-elements' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $item_repeater->get_controls(),
                'default'     => [
                    [
                        'question' => esc_html__( 'Sample question?', 'gw-elements' ),
                        'answer'   => esc_html__( 'Sample answer text.', 'gw-elements' ),
                    ],
                ],
                'title_field' => '{{{ question }}}',
            ]
        );

        $this->add_control(
            'categories',
            [
                'label'       => esc_html__( 'Categories', 'gw-elements' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $category_repeater->get_controls(),
                'default'     => [
                    [
                        'category_title' => esc_html__( 'Ordini e Spedizioni', 'gw-elements' ),
                        'items'          => [
                            [
                                'question' => esc_html__( 'Quanto tempo ci vuole per ricevere il mio ordine?', 'gw-elements' ),
                                'answer'   => esc_html__( 'Gli ordini vengono elaborati entro 1-2 giorni lavorativi. La spedizione standard in Italia richiede 3-5 giorni lavorativi.', 'gw-elements' ),
                            ],
                            [
                                'question' => esc_html__( 'Quali sono i costi di spedizione?', 'gw-elements' ),
                                'answer'   => esc_html__( 'La spedizione è gratuita per ordini superiori a €50. Per ordini inferiori, il costo di spedizione è di €5,90.', 'gw-elements' ),
                            ],
                        ],
                    ],
                ],
                'title_field' => '{{{ category_title }}}',
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

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'category_typography',
                'label'    => esc_html__( 'Category Title Typography', 'gw-elements' ),
                'selector' => '{{WRAPPER}} .gw-faq__category-title',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'question_typography',
                'label'    => esc_html__( 'Question Typography', 'gw-elements' ),
                'selector' => '{{WRAPPER}} .gw-accordion__trigger',
            ]
        );

        $this->add_control(
            'item_border_color',
            [
                'label'     => esc_html__( 'Item Border Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-accordion__item' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'item_open_bg',
            [
                'label'     => esc_html__( 'Open Item Background', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'hsl(30 10% 96% / 0.5)',
                'selectors' => [
                    '{{WRAPPER}} .gw-accordion__item[data-state="open"]' => 'background-color: {{VALUE}};',
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

        $this->add_render_attribute( 'wrapper', 'class', 'gw-faq' );
        $this->add_animation_wrapper_attributes( $settings );
        ?>
        <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
            <?php foreach ( $settings['categories'] as $cat_index => $category ) : ?>
                <div class="gw-faq__category">
                    <h2 class="gw-faq__category-title">
                        <?php echo esc_html( $category['category_title'] ); ?>
                    </h2>

                    <div class="gw-accordion" data-accordion="single">
                        <?php foreach ( $category['items'] as $item_index => $item ) :
                            $item_id = 'faq-' . $cat_index . '-' . $item_index;
                        ?>
                            <div class="gw-accordion__item" data-state="closed">
                                <button
                                    type="button"
                                    class="gw-accordion__trigger"
                                    aria-expanded="false"
                                    aria-controls="<?php echo esc_attr( $item_id ); ?>-content"
                                >
                                    <span><?php echo esc_html( $item['question'] ); ?></span>
                                    <?php echo $this->render_icon( 'chevron-down', [ 'class' => 'gw-accordion__icon' ] ); ?>
                                </button>
                                <div
                                    id="<?php echo esc_attr( $item_id ); ?>-content"
                                    class="gw-accordion__content"
                                    role="region"
                                    hidden
                                >
                                    <div class="gw-accordion__content-inner">
                                        <?php echo wp_kses_post( $item['answer'] ); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
}
