<?php
/**
 * Base widget class.
 *
 * @package GW_Elements
 */

namespace GW\Elements\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use GW\Elements\Assets;
use GW\Elements\Elementor;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Abstract Class Widget_Base_GW
 *
 * Base class for all GW Elements widgets.
 */
abstract class Widget_Base_GW extends Widget_Base {

    /**
     * Widget slug for asset loading.
     *
     * @var string
     */
    protected string $widget_slug = '';

    /**
     * Whether the widget requires the carousel library.
     *
     * @var bool
     */
    protected bool $requires_carousel = false;

    /**
     * Whether the widget supports scroll animations.
     *
     * @var bool
     */
    protected bool $supports_animation = true;

    /**
     * Get widget categories.
     *
     * @return array
     */
    public function get_categories(): array {
        return [ Elementor::get_category() ];
    }

    /**
     * Get widget keywords.
     *
     * @return array
     */
    public function get_keywords(): array {
        return [ 'gw', 'laviasana' ];
    }

    /**
     * Enqueue widget-specific styles.
     */
    public function get_style_depends(): array {
        $styles = [ 'gw-elements-frontend' ];

        if ( $this->widget_slug ) {
            $styles[] = 'gw-widget-' . $this->widget_slug;
        }

        if ( $this->requires_carousel ) {
            $styles[] = 'splide';
        }

        return $styles;
    }

    /**
     * Enqueue widget-specific scripts.
     */
    public function get_script_depends(): array {
        $scripts = [ 'gw-elements-frontend' ];

        if ( $this->widget_slug ) {
            $handle = 'gw-widget-' . $this->widget_slug;
            if ( wp_script_is( $handle, 'registered' ) ) {
                $scripts[] = $handle;
            }
        }

        if ( $this->requires_carousel ) {
            $scripts[] = 'splide';
        }

        return $scripts;
    }

    /**
     * Add common Content tab controls.
     */
    protected function add_content_section(): void {
        // Override in child classes.
    }

    /**
     * Add common Style tab controls.
     */
    protected function add_style_section(): void {
        // Override in child classes.
    }

    /**
     * Add animation controls to the Advanced tab.
     * Call this at the end of register_controls() in child widgets.
     */
    protected function add_animation_controls(): void {
        if ( ! $this->supports_animation ) {
            return;
        }

        $this->start_controls_section(
            'section_gw_animation',
            [
                'label' => esc_html__( 'Entrance Animation', 'gw-elements' ),
                'tab'   => Controls_Manager::TAB_ADVANCED,
            ]
        );

        $this->add_control(
            'gw_animation_enable',
            [
                'label'        => esc_html__( 'Enable Animation', 'gw-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'gw-elements' ),
                'label_off'    => esc_html__( 'No', 'gw-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'gw_animation_type',
            [
                'label'     => esc_html__( 'Animation Type', 'gw-elements' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'slide-up',
                'options'   => [
                    'fade'        => esc_html__( 'Fade', 'gw-elements' ),
                    'slide-up'    => esc_html__( 'Slide Up', 'gw-elements' ),
                    'slide-left'  => esc_html__( 'Slide from Right', 'gw-elements' ),
                    'slide-right' => esc_html__( 'Slide from Left', 'gw-elements' ),
                    'scale'       => esc_html__( 'Scale', 'gw-elements' ),
                ],
                'condition' => [
                    'gw_animation_enable' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'gw_animation_stagger',
            [
                'label'        => esc_html__( 'Stagger Children', 'gw-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'gw-elements' ),
                'label_off'    => esc_html__( 'No', 'gw-elements' ),
                'return_value' => 'yes',
                'default'      => '',
                'description'  => esc_html__( 'Animate child elements with staggered delay', 'gw-elements' ),
                'condition'    => [
                    'gw_animation_enable' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Add animation classes to wrapper render attributes.
     * Call this after setting up wrapper attributes in render().
     *
     * @param array $settings Widget settings.
     */
    protected function add_animation_wrapper_attributes( array $settings ): void {
        if ( ! $this->supports_animation ) {
            return;
        }

        if ( 'yes' !== ( $settings['gw_animation_enable'] ?? 'yes' ) ) {
            return;
        }

        $animation_type = $settings['gw_animation_type'] ?? 'slide-up';
        $classes        = [ 'gw-scroll-animate' ];

        if ( 'slide-up' !== $animation_type ) {
            $classes[] = 'gw-scroll-animate--' . $animation_type;
        }

        if ( 'yes' === ( $settings['gw_animation_stagger'] ?? '' ) ) {
            $classes[] = 'gw-stagger-children';
        }

        $this->add_render_attribute( 'wrapper', 'class', $classes );
    }

    /**
     * Render Lucide icon SVG.
     *
     * @param string $icon Icon name.
     * @param array  $attrs Additional attributes.
     * @return string
     */
    protected function render_icon( string $icon, array $attrs = [] ): string {
        $icons = $this->get_lucide_icons();

        if ( ! isset( $icons[ $icon ] ) ) {
            return '';
        }

        $default_attrs = [
            'class'       => 'gw-icon',
            'width'       => '24',
            'height'      => '24',
            'fill'        => 'none',
            'stroke'      => 'currentColor',
            'stroke-width' => '2',
            'stroke-linecap' => 'round',
            'stroke-linejoin' => 'round',
            'viewBox'     => '0 0 24 24',
        ];

        $attrs = array_merge( $default_attrs, $attrs );
        $attr_string = '';

        foreach ( $attrs as $key => $value ) {
            $attr_string .= sprintf( ' %s="%s"', esc_attr( $key ), esc_attr( $value ) );
        }

        return sprintf( '<svg%s>%s</svg>', $attr_string, $icons[ $icon ] );
    }

    /**
     * Get Lucide icons paths.
     *
     * @return array
     */
    protected function get_lucide_icons(): array {
        return [
            'arrow-right'    => '<path d="m9 18 6-6-6-6"/>',
            'arrow-left'     => '<path d="m15 18-6-6 6-6"/>',
            'chevron-right'  => '<path d="m9 18 6-6-6-6"/>',
            'chevron-left'   => '<path d="m15 18-6-6 6-6"/>',
            'chevron-down'   => '<path d="m6 9 6 6 6-6"/>',
            'search'         => '<circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>',
            'shopping-bag'   => '<path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>',
            'user'           => '<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
            'menu'           => '<line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/>',
            'x'              => '<path d="M18 6 6 18"/><path d="m6 6 12 12"/>',
            'heart'          => '<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>',
            'truck'          => '<path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/><path d="M15 18H9"/><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"/><circle cx="17" cy="18" r="2"/><circle cx="7" cy="18" r="2"/>',
            'award'          => '<circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/>',
            'headphones'     => '<path d="M3 14h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-7a9 9 0 0 1 18 0v7a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3"/>',
            'leaf'           => '<path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>',
            'shield'         => '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/>',
            'mail'           => '<rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>',
            'phone'          => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>',
            'map-pin'        => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>',
            'clock'          => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
            'instagram'      => '<rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/>',
            'facebook'       => '<path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>',
            'twitter'        => '<path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/>',
            'youtube'        => '<path d="M2.5 17a24.12 24.12 0 0 1 0-10 2 2 0 0 1 1.4-1.4 49.56 49.56 0 0 1 16.2 0A2 2 0 0 1 21.5 7a24.12 24.12 0 0 1 0 10 2 2 0 0 1-1.4 1.4 49.55 49.55 0 0 1-16.2 0A2 2 0 0 1 2.5 17"/><path d="m10 15 5-3-5-3z"/>',
            'linkedin'       => '<path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect width="4" height="12" x="2" y="9"/><circle cx="4" cy="4" r="2"/>',
            'minus'          => '<path d="M5 12h14"/>',
            'plus'           => '<path d="M5 12h14"/><path d="M12 5v14"/>',
            'trash-2'        => '<path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/>',
            'calendar'       => '<path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/>',
            'users'          => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
            'volume-2'       => '<polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/><path d="M15.54 8.46a5 5 0 0 1 0 7.07"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14"/>',
            'volume-x'       => '<polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/><line x1="22" x2="16" y1="9" y2="15"/><line x1="16" x2="22" y1="9" y2="15"/>',
            'filter'         => '<polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>',
            'grid'           => '<rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/>',
            'list'           => '<line x1="8" x2="21" y1="6" y2="6"/><line x1="8" x2="21" y1="12" y2="12"/><line x1="8" x2="21" y1="18" y2="18"/><line x1="3" x2="3.01" y1="6" y2="6"/><line x1="3" x2="3.01" y1="12" y2="12"/><line x1="3" x2="3.01" y1="18" y2="18"/>',
            'loader-2'       => '<path d="M21 12a9 9 0 1 1-6.219-8.56"/>',
            'package'        => '<path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/>',
        ];
    }

    /**
     * Get available icon options for Elementor control.
     *
     * @return array
     */
    protected function get_icon_options(): array {
        return [
            'arrow-right'   => __( 'Arrow Right', 'gw-elements' ),
            'truck'         => __( 'Truck', 'gw-elements' ),
            'award'         => __( 'Award', 'gw-elements' ),
            'headphones'    => __( 'Headphones', 'gw-elements' ),
            'leaf'          => __( 'Leaf', 'gw-elements' ),
            'heart'         => __( 'Heart', 'gw-elements' ),
            'shield'        => __( 'Shield', 'gw-elements' ),
            'mail'          => __( 'Mail', 'gw-elements' ),
            'phone'         => __( 'Phone', 'gw-elements' ),
            'map-pin'       => __( 'Map Pin', 'gw-elements' ),
            'clock'         => __( 'Clock', 'gw-elements' ),
            'instagram'     => __( 'Instagram', 'gw-elements' ),
            'facebook'      => __( 'Facebook', 'gw-elements' ),
            'twitter'       => __( 'Twitter/X', 'gw-elements' ),
            'youtube'       => __( 'YouTube', 'gw-elements' ),
            'linkedin'      => __( 'LinkedIn', 'gw-elements' ),
            'calendar'      => __( 'Calendar', 'gw-elements' ),
            'users'         => __( 'Users', 'gw-elements' ),
            'package'       => __( 'Package', 'gw-elements' ),
        ];
    }
}
