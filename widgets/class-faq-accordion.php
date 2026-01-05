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
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

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
        // Header Section
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
                'default'      => '',
            ]
        );

        $this->add_control(
            'header_title',
            [
                'label'       => esc_html__( 'Title', 'gw-elements' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Domande Frequenti', 'gw-elements' ),
                'label_block' => true,
                'condition'   => [
                    'show_header' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'header_description',
            [
                'label'       => esc_html__( 'Description', 'gw-elements' ),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => '',
                'label_block' => true,
                'condition'   => [
                    'show_header' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Categories Section.
        $this->start_controls_section(
            'section_categories',
            [
                'label' => esc_html__( 'FAQ Categories', 'gw-elements' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_categories',
            [
                'label'        => esc_html__( 'Show Category Titles', 'gw-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'gw-elements' ),
                'label_off'    => esc_html__( 'No', 'gw-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes',
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

        // Layout Section
        $this->start_controls_section(
            'section_layout',
            [
                'label' => esc_html__( 'Layout', 'gw-elements' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'layout_style',
            [
                'label'   => esc_html__( 'Style', 'gw-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'minimal',
                'options' => [
                    'minimal'  => esc_html__( 'Minimal', 'gw-elements' ),
                    'bordered' => esc_html__( 'Bordered', 'gw-elements' ),
                    'filled'   => esc_html__( 'Filled Cards', 'gw-elements' ),
                ],
            ]
        );

        $this->add_control(
            'accordion_behavior',
            [
                'label'   => esc_html__( 'Behavior', 'gw-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'single',
                'options' => [
                    'single'   => esc_html__( 'One at a time', 'gw-elements' ),
                    'multiple' => esc_html__( 'Multiple open', 'gw-elements' ),
                ],
            ]
        );

        $this->add_control(
            'icon_position',
            [
                'label'   => esc_html__( 'Icon Position', 'gw-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'right',
                'options' => [
                    'left'  => esc_html__( 'Left', 'gw-elements' ),
                    'right' => esc_html__( 'Right', 'gw-elements' ),
                ],
            ]
        );

        $this->add_control(
            'icon_type',
            [
                'label'   => esc_html__( 'Icon Type', 'gw-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'chevron',
                'options' => [
                    'chevron' => esc_html__( 'Chevron', 'gw-elements' ),
                    'plus'    => esc_html__( 'Plus/Minus', 'gw-elements' ),
                    'arrow'   => esc_html__( 'Arrow', 'gw-elements' ),
                ],
            ]
        );

        $this->add_responsive_control(
            'items_gap',
            [
                'label'      => esc_html__( 'Items Gap', 'gw-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 40,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 2.5,
                    ],
                ],
                'default'    => [
                    'size' => 0,
                    'unit' => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .gw-accordion' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Header Style Section
        $this->start_controls_section(
            'section_header_style',
            [
                'label'     => esc_html__( 'Header Style', 'gw-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_header' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'header_title_typography',
                'label'    => esc_html__( 'Title Typography', 'gw-elements' ),
                'selector' => '{{WRAPPER}} .gw-faq__header-title',
            ]
        );

        $this->add_control(
            'header_title_color',
            [
                'label'     => esc_html__( 'Title Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-faq__header-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'header_description_color',
            [
                'label'     => esc_html__( 'Description Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-faq__header-description' => 'color: {{VALUE}};',
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
                'selectors' => [
                    '{{WRAPPER}} .gw-faq__header' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Category Style Section
        $this->start_controls_section(
            'section_category_style',
            [
                'label' => esc_html__( 'Category Title Style', 'gw-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_categories' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'category_typography',
                'label'    => esc_html__( 'Typography', 'gw-elements' ),
                'selector' => '{{WRAPPER}} .gw-faq__category-title',
            ]
        );

        $this->add_control(
            'category_title_color',
            [
                'label'     => esc_html__( 'Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-faq__category-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'category_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-faq__category-title' => 'border-bottom-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Item Style Section
        $this->start_controls_section(
            'section_item_style',
            [
                'label' => esc_html__( 'FAQ Item Style', 'gw-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'item_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-accordion__item' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'item_bg_color_hover',
            [
                'label'     => esc_html__( 'Background Color (Hover)', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-accordion__trigger:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'item_bg_color_open',
            [
                'label'     => esc_html__( 'Background Color (Open)', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-accordion__item[data-state="open"]' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'item_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-accordion__item' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_border_width',
            [
                'label'      => esc_html__( 'Border Width', 'gw-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 5,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .gw-accordion__item' => 'border-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_border_radius',
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
                    '{{WRAPPER}} .gw-accordion__item' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_padding',
            [
                'label'      => esc_html__( 'Padding', 'gw-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'rem' ],
                'selectors'  => [
                    '{{WRAPPER}} .gw-accordion__trigger' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Question Style Section
        $this->start_controls_section(
            'section_question_style',
            [
                'label' => esc_html__( 'Question Style', 'gw-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'question_typography',
                'label'    => esc_html__( 'Typography', 'gw-elements' ),
                'selector' => '{{WRAPPER}} .gw-accordion__trigger',
            ]
        );

        $this->add_control(
            'question_color',
            [
                'label'     => esc_html__( 'Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-accordion__trigger' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'question_color_hover',
            [
                'label'     => esc_html__( 'Color (Hover)', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-accordion__trigger:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'question_color_open',
            [
                'label'     => esc_html__( 'Color (Open)', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-accordion__item[data-state="open"] .gw-accordion__trigger' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Answer Style Section
        $this->start_controls_section(
            'section_answer_style',
            [
                'label' => esc_html__( 'Answer Style', 'gw-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'answer_typography',
                'label'    => esc_html__( 'Typography', 'gw-elements' ),
                'selector' => '{{WRAPPER}} .gw-accordion__content-inner',
            ]
        );

        $this->add_control(
            'answer_color',
            [
                'label'     => esc_html__( 'Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-accordion__content-inner' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'answer_padding',
            [
                'label'      => esc_html__( 'Padding', 'gw-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'rem' ],
                'selectors'  => [
                    '{{WRAPPER}} .gw-accordion__content-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Icon Style Section
        $this->start_controls_section(
            'section_icon_style',
            [
                'label' => esc_html__( 'Icon Style', 'gw-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label'     => esc_html__( 'Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-accordion__icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color_open',
            [
                'label'     => esc_html__( 'Color (Open)', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-accordion__item[data-state="open"] .gw-accordion__icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label'      => esc_html__( 'Size', 'gw-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'range'      => [
                    'px' => [
                        'min' => 8,
                        'max' => 32,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .gw-accordion__icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
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

        $layout_class = 'gw-faq--' . $settings['layout_style'];
        $icon_class = 'gw-faq--icon-' . $settings['icon_position'];

        $this->add_render_attribute( 'wrapper', 'class', [
            'gw-faq',
            $layout_class,
            $icon_class,
        ] );
        $this->add_animation_wrapper_attributes( $settings );

        $icon_name = 'chevron-down';
        if ( $settings['icon_type'] === 'plus' ) {
            $icon_name = 'plus';
        } elseif ( $settings['icon_type'] === 'arrow' ) {
            $icon_name = 'arrow-down';
        }
        ?>
        <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>

            <?php if ( 'yes' === $settings['show_header'] ) : ?>
                <div class="gw-faq__header">
                    <?php if ( ! empty( $settings['header_title'] ) ) : ?>
                        <h2 class="gw-faq__header-title"><?php echo esc_html( $settings['header_title'] ); ?></h2>
                    <?php endif; ?>
                    <?php if ( ! empty( $settings['header_description'] ) ) : ?>
                        <p class="gw-faq__header-description"><?php echo esc_html( $settings['header_description'] ); ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php foreach ( $settings['categories'] as $cat_index => $category ) : ?>
                <div class="gw-faq__category">
                    <?php if ( 'yes' === $settings['show_categories'] && ! empty( $category['category_title'] ) ) : ?>
                        <h3 class="gw-faq__category-title">
                            <?php echo esc_html( $category['category_title'] ); ?>
                        </h3>
                    <?php endif; ?>

                    <div class="gw-accordion" data-accordion="<?php echo esc_attr( $settings['accordion_behavior'] ); ?>">
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
                                    <?php if ( $settings['icon_position'] === 'left' ) : ?>
                                        <?php echo $this->render_icon( $icon_name, [ 'class' => 'gw-accordion__icon' ] ); ?>
                                    <?php endif; ?>
                                    <span class="gw-accordion__question"><?php echo esc_html( $item['question'] ); ?></span>
                                    <?php if ( $settings['icon_position'] === 'right' ) : ?>
                                        <?php echo $this->render_icon( $icon_name, [ 'class' => 'gw-accordion__icon' ] ); ?>
                                    <?php endif; ?>
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
