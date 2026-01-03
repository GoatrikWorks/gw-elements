<?php
/**
 * Header Widget.
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
 * Class Header
 */
class Header extends Widget_Base_GW {

    /**
     * Widget slug.
     *
     * @var string
     */
    protected string $widget_slug = 'header';

    /**
     * Get widget name.
     *
     * @return string
     */
    public function get_name(): string {
        return 'gw-header';
    }

    /**
     * Get widget title.
     *
     * @return string
     */
    public function get_title(): string {
        return esc_html__( 'GW Header', 'gw-elements' );
    }

    /**
     * Get widget icon.
     *
     * @return string
     */
    public function get_icon(): string {
        return 'eicon-header';
    }

    /**
     * Get widget keywords.
     *
     * @return array
     */
    public function get_keywords(): array {
        return [ 'gw', 'header', 'navigation', 'menu', 'laviasana' ];
    }

    /**
     * Register widget controls.
     */
    protected function register_controls(): void {
        // Logo Section.
        $this->start_controls_section(
            'section_logo',
            [
                'label' => esc_html__( 'Logo', 'gw-elements' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'logo',
            [
                'label' => esc_html__( 'Logo Image', 'gw-elements' ),
                'type'  => Controls_Manager::MEDIA,
            ]
        );

        $this->add_responsive_control(
            'logo_height',
            [
                'label'      => esc_html__( 'Logo Height', 'gw-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => 20,
                        'max' => 120,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 40,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .gw-header__logo-img' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'logo[url]!' => '',
                ],
            ]
        );

        $this->add_control(
            'logo_text',
            [
                'label'   => esc_html__( 'Logo Text (Fallback)', 'gw-elements' ),
                'type'    => Controls_Manager::TEXT,
                'default' => 'Laviasana',
            ]
        );

        $this->add_control(
            'logo_link',
            [
                'label'       => esc_html__( 'Logo Link', 'gw-elements' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => '/',
                'default'     => [ 'url' => '/' ],
            ]
        );

        $this->end_controls_section();

        // Announcement Bar Section.
        $this->start_controls_section(
            'section_announcement',
            [
                'label' => esc_html__( 'Announcement Bar', 'gw-elements' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_announcement',
            [
                'label'        => esc_html__( 'Show Announcement Bar', 'gw-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'gw-elements' ),
                'label_off'    => esc_html__( 'No', 'gw-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'announcement_text',
            [
                'label'       => esc_html__( 'Announcement Text', 'gw-elements' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'Spedizioni in tutta Italia • Prodotti Certificati e Garantiti',
                'label_block' => true,
                'condition'   => [
                    'show_announcement' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'announcement_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#6e022a',
                'selectors' => [
                    '{{WRAPPER}} .gw-header__announcement' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'show_announcement' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'announcement_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .gw-header__announcement' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_announcement' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'hide_announcement_on_scroll',
            [
                'label'        => esc_html__( 'Hide on Scroll', 'gw-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'gw-elements' ),
                'label_off'    => esc_html__( 'No', 'gw-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => [
                    'show_announcement' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Navigation Section.
        $this->start_controls_section(
            'section_navigation',
            [
                'label' => esc_html__( 'Navigation', 'gw-elements' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'nav_source',
            [
                'label'   => esc_html__( 'Navigation Source', 'gw-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'manual',
                'options' => [
                    'manual' => esc_html__( 'Manual', 'gw-elements' ),
                    'menu'   => esc_html__( 'WordPress Menu', 'gw-elements' ),
                ],
            ]
        );

        // Get registered menus.
        $menus    = wp_get_nav_menus();
        $menu_options = [];
        foreach ( $menus as $menu ) {
            $menu_options[ $menu->slug ] = $menu->name;
        }

        $this->add_control(
            'menu',
            [
                'label'     => esc_html__( 'Menu', 'gw-elements' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => $menu_options,
                'condition' => [
                    'nav_source' => 'menu',
                ],
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'label',
            [
                'label'       => esc_html__( 'Label', 'gw-elements' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Link', 'gw-elements' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label'       => esc_html__( 'Link', 'gw-elements' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => '/page',
            ]
        );

        $this->add_control(
            'nav_items',
            [
                'label'       => esc_html__( 'Navigation Items', 'gw-elements' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [ 'label' => 'Home', 'link' => [ 'url' => '/' ] ],
                    [ 'label' => 'Chi siamo', 'link' => [ 'url' => '/chi-siamo' ] ],
                    [ 'label' => 'Shop', 'link' => [ 'url' => '/shop' ] ],
                    [ 'label' => 'FAQ', 'link' => [ 'url' => '/faq' ] ],
                    [ 'label' => 'Contatti', 'link' => [ 'url' => '/contatti' ] ],
                ],
                'title_field' => '{{{ label }}}',
                'condition'   => [
                    'nav_source' => 'manual',
                ],
            ]
        );

        $this->end_controls_section();

        // Actions Section.
        $this->start_controls_section(
            'section_actions',
            [
                'label' => esc_html__( 'Actions', 'gw-elements' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_search',
            [
                'label'        => esc_html__( 'Show Search', 'gw-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'gw-elements' ),
                'label_off'    => esc_html__( 'No', 'gw-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'show_account',
            [
                'label'        => esc_html__( 'Show Account', 'gw-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'gw-elements' ),
                'label_off'    => esc_html__( 'No', 'gw-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'show_cart',
            [
                'label'        => esc_html__( 'Show Cart', 'gw-elements' ),
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

        $this->add_control(
            'header_sticky',
            [
                'label'        => esc_html__( 'Sticky Header', 'gw-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'gw-elements' ),
                'label_off'    => esc_html__( 'No', 'gw-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'header_fullwidth',
            [
                'label'        => esc_html__( 'Full Width', 'gw-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'gw-elements' ),
                'label_off'    => esc_html__( 'No', 'gw-elements' ),
                'return_value' => 'yes',
                'default'      => '',
            ]
        );

        $this->add_control(
            'header_shrink',
            [
                'label'        => esc_html__( 'Shrink on Scroll', 'gw-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'gw-elements' ),
                'label_off'    => esc_html__( 'No', 'gw-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => [
                    'header_sticky' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'background_color',
            [
                'label'     => esc_html__( 'Background Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .gw-header' => 'background-color: {{VALUE}};',
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

        // Announcement bar settings.
        $show_announcement       = 'yes' === ( $settings['show_announcement'] ?? 'yes' );
        $hide_on_scroll          = 'yes' === ( $settings['hide_announcement_on_scroll'] ?? 'yes' );
        $announcement_text       = $settings['announcement_text'] ?? '';

        $wrapper_class = 'gw-header';
        if ( 'yes' === $settings['header_sticky'] ) {
            $wrapper_class .= ' gw-header--sticky';
        }
        if ( 'yes' === $settings['header_fullwidth'] ) {
            $wrapper_class .= ' gw-header--fullwidth';
        }
        if ( $show_announcement ) {
            $wrapper_class .= ' gw-header--has-announcement';
        }

        $container_class = 'gw-header__container';
        if ( 'yes' === $settings['header_fullwidth'] ) {
            $container_class .= ' gw-header__container--fullwidth';
        } else {
            $container_class .= ' gw-container-wide';
        }

        $this->add_render_attribute( 'wrapper', 'class', $wrapper_class );

        if ( 'yes' === $settings['header_shrink'] && 'yes' === $settings['header_sticky'] ) {
            $this->add_render_attribute( 'wrapper', 'data-shrink', 'true' );
        }

        // Announcement bar classes.
        $announcement_class = 'gw-header__announcement';
        if ( $hide_on_scroll ) {
            $announcement_class .= ' gw-header__announcement--hide-on-scroll';
        }
        if ( 'yes' === $settings['header_sticky'] ) {
            $announcement_class .= ' gw-header__announcement--sticky';
        }
        ?>
        <?php if ( $show_announcement && ! empty( $announcement_text ) ) : ?>
        <div class="<?php echo esc_attr( $announcement_class ); ?>">
            <div class="gw-header__announcement-content">
                <span><?php echo esc_html( $announcement_text ); ?></span>
            </div>
        </div>
        <?php endif; ?>
        <header <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
            <div class="<?php echo esc_attr( $container_class ); ?>">
                <!-- Logo -->
                <a href="<?php echo esc_url( $settings['logo_link']['url'] ?? '/' ); ?>" class="gw-header__logo">
                    <?php if ( ! empty( $settings['logo']['url'] ) ) : ?>
                        <img src="<?php echo esc_url( $settings['logo']['url'] ); ?>" alt="<?php echo esc_attr( $settings['logo_text'] ); ?>" class="gw-header__logo-img">
                    <?php else : ?>
                        <span class="gw-header__logo-text"><?php echo esc_html( $settings['logo_text'] ); ?></span>
                    <?php endif; ?>
                </a>

                <!-- Desktop Navigation -->
                <nav class="gw-header__nav gw-header__nav--desktop">
                    <?php $this->render_navigation( $settings ); ?>
                </nav>

                <!-- Actions -->
                <div class="gw-header__actions">
                    <?php if ( 'yes' === $settings['show_search'] ) : ?>
                        <button type="button" class="gw-header__action gw-header__search-btn gw-search-trigger" data-search-modal-trigger aria-label="<?php esc_attr_e( 'Search', 'gw-elements' ); ?>">
                            <?php echo $this->render_icon( 'search' ); ?>
                        </button>
                    <?php endif; ?>

                    <?php if ( 'yes' === $settings['show_account'] ) : ?>
                        <?php
                        $account_url = function_exists( 'wc_get_account_endpoint_url' )
                            ? wc_get_account_endpoint_url( 'dashboard' )
                            : wp_login_url();
                        ?>
                        <a href="<?php echo esc_url( $account_url ); ?>" class="gw-header__action gw-header__account-btn" aria-label="<?php esc_attr_e( 'Account', 'gw-elements' ); ?>">
                            <?php echo $this->render_icon( 'user' ); ?>
                        </a>
                    <?php endif; ?>

                    <?php if ( 'yes' === $settings['show_cart'] ) : ?>
                        <?php
                        $cart_count = function_exists( 'WC' ) && WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
                        ?>
                        <button type="button" class="gw-header__action gw-header__cart-btn gw-cart-trigger" data-cart-drawer-trigger aria-label="<?php esc_attr_e( 'Cart', 'gw-elements' ); ?>">
                            <?php echo $this->render_icon( 'shopping-bag' ); ?>
                            <span class="gw-cart-count-badge<?php echo $cart_count > 0 ? '' : ' gw-hidden'; ?>"><?php echo esc_html( $cart_count ); ?></span>
                        </button>
                    <?php endif; ?>

                    <!-- Mobile Menu Toggle -->
                    <button type="button" class="gw-header__action gw-header__menu-toggle" aria-label="<?php esc_attr_e( 'Menu', 'gw-elements' ); ?>">
                        <?php echo $this->render_icon( 'menu' ); ?>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <div class="gw-header__mobile-nav" hidden>
                <nav class="gw-header__nav">
                    <?php $this->render_navigation( $settings ); ?>
                </nav>
            </div>
        </header>
        <?php if ( 'yes' === $settings['header_sticky'] ) : ?>
            <div class="gw-header-spacer<?php echo $show_announcement ? ' gw-header-spacer--with-announcement' : ''; ?>"></div>
        <?php endif; ?>
        <?php
    }

    /**
     * Render navigation items.
     *
     * @param array $settings Widget settings.
     */
    private function render_navigation( array $settings ): void {
        if ( 'menu' === $settings['nav_source'] && ! empty( $settings['menu'] ) ) {
            wp_nav_menu( [
                'menu'           => $settings['menu'],
                'container'      => false,
                'menu_class'     => 'gw-header__menu',
                'fallback_cb'    => false,
                'depth'          => 2,
            ] );
        } else {
            echo '<ul class="gw-header__menu">';
            foreach ( $settings['nav_items'] as $item ) {
                $url = $item['link']['url'] ?? '#';
                printf(
                    '<li class="gw-header__menu-item"><a href="%s" class="gw-header__link">%s</a></li>',
                    esc_url( $url ),
                    esc_html( $item['label'] )
                );
            }
            echo '</ul>';
        }
    }
}
