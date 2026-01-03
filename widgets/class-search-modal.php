<?php
/**
 * Search Modal Widget.
 *
 * Full-screen or drawer search modal with AJAX live search.
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
 * Class Search_Modal
 */
class Search_Modal extends Widget_Base_GW {

    /**
     * Widget slug.
     *
     * @var string
     */
    protected string $widget_slug = 'search-modal';

    /**
     * Get widget name.
     *
     * @return string
     */
    public function get_name(): string {
        return 'gw-search-modal';
    }

    /**
     * Get widget title.
     *
     * @return string
     */
    public function get_title(): string {
        return esc_html__( 'GW Search Modal', 'gw-elements' );
    }

    /**
     * Get widget icon.
     *
     * @return string
     */
    public function get_icon(): string {
        return 'eicon-search';
    }

    /**
     * Get widget keywords.
     *
     * @return array
     */
    public function get_keywords(): array {
        return [ 'gw', 'search', 'modal', 'ajax', 'products', 'laviasana' ];
    }

    /**
     * Get script dependencies.
     *
     * @return array
     */
    public function get_script_depends(): array {
        return [ 'gw-elements-frontend', 'gw-widget-search-modal' ];
    }

    /**
     * Get style dependencies.
     *
     * @return array
     */
    public function get_style_depends(): array {
        return [ 'gw-elements-frontend', 'gw-widget-search-modal' ];
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
            'placeholder',
            [
                'label'   => esc_html__( 'Placeholder Text', 'gw-elements' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Cerca prodotti...', 'gw-elements' ),
            ]
        );

        $this->add_control(
            'min_chars',
            [
                'label'   => esc_html__( 'Minimum Characters', 'gw-elements' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 2,
                'min'     => 1,
                'max'     => 5,
            ]
        );

        $this->add_control(
            'results_count',
            [
                'label'   => esc_html__( 'Max Results', 'gw-elements' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 8,
                'min'     => 3,
                'max'     => 20,
            ]
        );

        $this->add_control(
            'modal_style',
            [
                'label'   => esc_html__( 'Modal Style', 'gw-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'fullscreen',
                'options' => [
                    'fullscreen' => esc_html__( 'Full Screen', 'gw-elements' ),
                    'dropdown'   => esc_html__( 'Dropdown', 'gw-elements' ),
                ],
            ]
        );

        $this->add_control(
            'show_categories',
            [
                'label'   => esc_html__( 'Show Categories', 'gw-elements' ),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_prices',
            [
                'label'   => esc_html__( 'Show Prices', 'gw-elements' ),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // Style Section - Modal.
        $this->start_controls_section(
            'section_style_modal',
            [
                'label' => esc_html__( 'Modal', 'gw-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'overlay_color',
            [
                'label'     => esc_html__( 'Overlay Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'rgba(255, 255, 255, 0.98)',
                'selectors' => [
                    '{{WRAPPER}} .gw-search-modal__overlay' => 'background-color: {{VALUE}};',
                ],
                'condition' => [ 'modal_style' => 'fullscreen' ],
            ]
        );

        $this->add_control(
            'dropdown_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .gw-search-modal__dropdown' => 'background-color: {{VALUE}};',
                ],
                'condition' => [ 'modal_style' => 'dropdown' ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Input.
        $this->start_controls_section(
            'section_style_input',
            [
                'label' => esc_html__( 'Search Input', 'gw-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'input_typography',
                'label'    => esc_html__( 'Input Typography', 'gw-elements' ),
                'selector' => '{{WRAPPER}} .gw-search-modal__input',
            ]
        );

        $this->add_control(
            'input_color',
            [
                'label'     => esc_html__( 'Input Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-search-modal__input' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-search-modal__input-wrapper' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Results.
        $this->start_controls_section(
            'section_style_results',
            [
                'label' => esc_html__( 'Results', 'gw-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'result_title_typography',
                'label'    => esc_html__( 'Title Typography', 'gw-elements' ),
                'selector' => '{{WRAPPER}} .gw-search-result__title',
            ]
        );

        $this->add_control(
            'result_hover_bg',
            [
                'label'     => esc_html__( 'Hover Background', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gw-search-result:hover' => 'background-color: {{VALUE}};',
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

        $modal_style    = $settings['modal_style'] ?? 'fullscreen';
        $placeholder    = $settings['placeholder'] ?? __( 'Cerca prodotti...', 'gw-elements' );
        $min_chars      = $settings['min_chars'] ?? 2;
        $results_count  = $settings['results_count'] ?? 8;
        $show_categories = 'yes' === ( $settings['show_categories'] ?? 'yes' );
        $show_prices    = 'yes' === ( $settings['show_prices'] ?? 'yes' );

        $shop_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop' );
        ?>
        <div class="gw-search-modal"
             data-style="<?php echo esc_attr( $modal_style ); ?>"
             data-min-chars="<?php echo esc_attr( $min_chars ); ?>"
             data-max-results="<?php echo esc_attr( $results_count ); ?>"
             data-show-categories="<?php echo $show_categories ? 'true' : 'false'; ?>"
             data-show-prices="<?php echo $show_prices ? 'true' : 'false'; ?>">

            <!-- Modal Container -->
            <div class="gw-search-modal__container" role="dialog" aria-modal="true" aria-labelledby="gw-search-title">
                <!-- Overlay (fullscreen) -->
                <?php if ( 'fullscreen' === $modal_style ) : ?>
                    <div class="gw-search-modal__overlay"></div>
                <?php endif; ?>

                <!-- Content -->
                <div class="gw-search-modal__content">
                    <!-- Header -->
                    <div class="gw-search-modal__header">
                        <div class="gw-search-modal__input-wrapper">
                            <?php echo $this->render_icon( 'search', [ 'width' => '20', 'height' => '20', 'class' => 'gw-icon gw-search-modal__icon' ] ); ?>
                            <input
                                type="search"
                                class="gw-search-modal__input"
                                id="gw-search-input"
                                placeholder="<?php echo esc_attr( $placeholder ); ?>"
                                autocomplete="off"
                                autocapitalize="off"
                                spellcheck="false"
                                aria-label="<?php echo esc_attr( $placeholder ); ?>"
                            >
                            <button type="button" class="gw-search-modal__clear" aria-label="<?php esc_attr_e( 'Clear search', 'gw-elements' ); ?>">
                                <?php echo $this->render_icon( 'x', [ 'width' => '20', 'height' => '20' ] ); ?>
                            </button>
                        </div>
                        <button type="button" class="gw-search-modal__close" aria-label="<?php esc_attr_e( 'Close search', 'gw-elements' ); ?>">
                            <?php echo $this->render_icon( 'x', [ 'width' => '24', 'height' => '24' ] ); ?>
                        </button>
                    </div>

                    <!-- Results -->
                    <div class="gw-search-modal__results" aria-live="polite">
                        <!-- Loading state -->
                        <div class="gw-search-modal__loading" hidden>
                            <svg class="gw-spinner" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" fill="none" stroke-linecap="round" stroke-dasharray="31.4 31.4" />
                            </svg>
                            <span><?php esc_html_e( 'Ricerca in corso...', 'gw-elements' ); ?></span>
                        </div>

                        <!-- No results state -->
                        <div class="gw-search-modal__no-results" hidden>
                            <p><?php esc_html_e( 'Nessun risultato trovato', 'gw-elements' ); ?></p>
                        </div>

                        <!-- Results list -->
                        <div class="gw-search-modal__results-list"></div>

                        <!-- View all link -->
                        <div class="gw-search-modal__view-all" hidden>
                            <a href="<?php echo esc_url( $shop_url ); ?>" class="gw-search-modal__view-all-link">
                                <?php esc_html_e( 'Vedi tutti i risultati', 'gw-elements' ); ?>
                                <?php echo $this->render_icon( 'arrow-right', [ 'width' => '16', 'height' => '16' ] ); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
