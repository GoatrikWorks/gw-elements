<?php
/**
 * Footer Widget.
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
 * Class Footer
 */
class Footer extends Widget_Base_GW {

    /**
     * Widget slug.
     *
     * @var string
     */
    protected string $widget_slug = 'footer';

    /**
     * Get widget name.
     *
     * @return string
     */
    public function get_name(): string {
        return 'gw-footer';
    }

    /**
     * Get widget title.
     *
     * @return string
     */
    public function get_title(): string {
        return esc_html__( 'GW Footer', 'gw-elements' );
    }

    /**
     * Get widget icon.
     *
     * @return string
     */
    public function get_icon(): string {
        return 'eicon-footer';
    }

    /**
     * Get widget keywords.
     *
     * @return array
     */
    public function get_keywords(): array {
        return [ 'gw', 'footer', 'laviasana' ];
    }

    /**
     * Register widget controls.
     */
    protected function register_controls(): void {
        // Brand Section.
        $this->start_controls_section(
            'section_brand',
            [
                'label' => esc_html__( 'Brand', 'gw-elements' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'logo',
            [
                'label' => esc_html__( 'Logo', 'gw-elements' ),
                'type'  => Controls_Manager::MEDIA,
            ]
        );

        $this->add_control(
            'logo_invert',
            [
                'label'        => esc_html__( 'Invert Logo to White', 'gw-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'gw-elements' ),
                'label_off'    => esc_html__( 'No', 'gw-elements' ),
                'return_value' => 'yes',
                'default'      => '',
                'description'  => esc_html__( 'Enable to make dark logos appear white on the dark footer background.', 'gw-elements' ),
            ]
        );

        $this->add_responsive_control(
            'logo_height',
            [
                'label'      => esc_html__( 'Logo Height', 'gw-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'range'      => [
                    'px'  => [ 'min' => 20, 'max' => 150 ],
                    'rem' => [ 'min' => 1, 'max' => 10 ],
                ],
                'default'    => [
                    'size' => 2,
                    'unit' => 'rem',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .gw-footer__logo' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'tagline',
            [
                'label'   => esc_html__( 'Tagline', 'gw-elements' ),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => 'Prodotti naturali e certificati per il tuo benessere quotidiano.',
            ]
        );

        $this->end_controls_section();

        // Links Column 1.
        $this->start_controls_section(
            'section_links_1',
            [
                'label' => esc_html__( 'Links Column 1', 'gw-elements' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'column_1_title',
            [
                'label'   => esc_html__( 'Title', 'gw-elements' ),
                'type'    => Controls_Manager::TEXT,
                'default' => 'Shop',
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'label',
            [
                'label'       => esc_html__( 'Label', 'gw-elements' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label' => esc_html__( 'Link', 'gw-elements' ),
                'type'  => Controls_Manager::URL,
            ]
        );

        $this->add_control(
            'column_1_links',
            [
                'label'       => esc_html__( 'Links', 'gw-elements' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [ 'label' => 'Tutti i Prodotti', 'link' => [ 'url' => '/shop' ] ],
                    [ 'label' => 'Integratori', 'link' => [ 'url' => '/shop?category=integratori' ] ],
                    [ 'label' => 'Vini Bio', 'link' => [ 'url' => '/shop?category=vini' ] ],
                ],
                'title_field' => '{{{ label }}}',
            ]
        );

        $this->end_controls_section();

        // Links Column 2.
        $this->start_controls_section(
            'section_links_2',
            [
                'label' => esc_html__( 'Links Column 2', 'gw-elements' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'column_2_title',
            [
                'label'   => esc_html__( 'Title', 'gw-elements' ),
                'type'    => Controls_Manager::TEXT,
                'default' => 'Azienda',
            ]
        );

        $this->add_control(
            'column_2_links',
            [
                'label'       => esc_html__( 'Links', 'gw-elements' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [ 'label' => 'Chi Siamo', 'link' => [ 'url' => '/about' ] ],
                    [ 'label' => 'Stories', 'link' => [ 'url' => '/stories' ] ],
                    [ 'label' => 'Contatti', 'link' => [ 'url' => '/contact' ] ],
                ],
                'title_field' => '{{{ label }}}',
            ]
        );

        $this->end_controls_section();

        // Links Column 3.
        $this->start_controls_section(
            'section_links_3',
            [
                'label' => esc_html__( 'Links Column 3', 'gw-elements' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'column_3_title',
            [
                'label'   => esc_html__( 'Title', 'gw-elements' ),
                'type'    => Controls_Manager::TEXT,
                'default' => 'Supporto',
            ]
        );

        $this->add_control(
            'column_3_links',
            [
                'label'       => esc_html__( 'Links', 'gw-elements' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [ 'label' => 'FAQ', 'link' => [ 'url' => '/faq' ] ],
                    [ 'label' => 'Spedizioni', 'link' => [ 'url' => '/shipping' ] ],
                    [ 'label' => 'Resi', 'link' => [ 'url' => '/returns' ] ],
                ],
                'title_field' => '{{{ label }}}',
            ]
        );

        $this->end_controls_section();

        // Social Section.
        $this->start_controls_section(
            'section_social',
            [
                'label' => esc_html__( 'Social Links', 'gw-elements' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'instagram_url',
            [
                'label'       => esc_html__( 'Instagram URL', 'gw-elements' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => 'https://instagram.com/...',
            ]
        );

        $this->add_control(
            'facebook_url',
            [
                'label'       => esc_html__( 'Facebook URL', 'gw-elements' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => 'https://facebook.com/...',
            ]
        );

        $this->end_controls_section();

        // Bottom Section.
        $this->start_controls_section(
            'section_bottom',
            [
                'label' => esc_html__( 'Bottom Bar', 'gw-elements' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'copyright_text',
            [
                'label'   => esc_html__( 'Copyright Text', 'gw-elements' ),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => '© 2024 Laviasana. Tutti i diritti riservati.',
            ]
        );

        $this->add_control(
            'bottom_links',
            [
                'label'       => esc_html__( 'Bottom Links', 'gw-elements' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [ 'label' => 'Privacy Policy', 'link' => [ 'url' => '/privacy' ] ],
                    [ 'label' => 'Termini di Servizio', 'link' => [ 'url' => '/terms' ] ],
                    [ 'label' => 'Cookie Policy', 'link' => [ 'url' => '/cookies' ] ],
                ],
                'title_field' => '{{{ label }}}',
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
            'footer_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .gw-footer' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'footer_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .gw-footer' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .gw-footer__column-title' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .gw-footer__social-link' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'footer_link_color',
            [
                'label'     => esc_html__( 'Link Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .gw-footer__link' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .gw-footer__tagline' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .gw-footer__copyright' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .gw-footer__bottom-links a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'footer_link_hover_color',
            [
                'label'     => esc_html__( 'Link Hover Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .gw-footer__link:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .gw-footer__bottom-links a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'footer_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'gw-elements' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .gw-footer__main' => 'border-bottom-color: {{VALUE}};',
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

        $this->add_render_attribute( 'wrapper', 'class', 'gw-footer' );
        $this->add_animation_wrapper_attributes( $settings );
        ?>
        <footer <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
            <div class="gw-footer__container gw-container-wide">
                <div class="gw-footer__main">
                    <!-- Brand Column -->
                    <div class="gw-footer__brand">
                        <?php if ( ! empty( $settings['logo']['url'] ) ) :
                            $logo_class = 'gw-footer__logo';
                            if ( 'yes' === $settings['logo_invert'] ) {
                                $logo_class .= ' gw-footer__logo--invert';
                            }
                        ?>
                            <img src="<?php echo esc_url( $settings['logo']['url'] ); ?>" alt="" class="<?php echo esc_attr( $logo_class ); ?>">
                        <?php endif; ?>
                        <?php if ( ! empty( $settings['tagline'] ) ) : ?>
                            <p class="gw-footer__tagline"><?php echo esc_html( $settings['tagline'] ); ?></p>
                        <?php endif; ?>

                        <div class="gw-footer__social">
                            <?php if ( ! empty( $settings['instagram_url']['url'] ) ) : ?>
                                <a href="<?php echo esc_url( $settings['instagram_url']['url'] ); ?>" class="gw-footer__social-link" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                                    <?php echo $this->render_icon( 'instagram' ); ?>
                                </a>
                            <?php endif; ?>
                            <?php if ( ! empty( $settings['facebook_url']['url'] ) ) : ?>
                                <a href="<?php echo esc_url( $settings['facebook_url']['url'] ); ?>" class="gw-footer__social-link" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                                    <?php echo $this->render_icon( 'facebook' ); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Link Columns -->
                    <?php for ( $i = 1; $i <= 3; $i++ ) :
                        $title_key = "column_{$i}_title";
                        $links_key = "column_{$i}_links";
                    ?>
                        <?php if ( ! empty( $settings[ $title_key ] ) || ! empty( $settings[ $links_key ] ) ) : ?>
                            <div class="gw-footer__column">
                                <?php if ( ! empty( $settings[ $title_key ] ) ) : ?>
                                    <h4 class="gw-footer__column-title"><?php echo esc_html( $settings[ $title_key ] ); ?></h4>
                                <?php endif; ?>
                                <?php if ( ! empty( $settings[ $links_key ] ) ) : ?>
                                    <ul class="gw-footer__links">
                                        <?php foreach ( $settings[ $links_key ] as $link ) : ?>
                                            <li>
                                                <a href="<?php echo esc_url( $link['link']['url'] ?? '#' ); ?>" class="gw-footer__link">
                                                    <?php echo esc_html( $link['label'] ); ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>

                <!-- Bottom Bar -->
                <div class="gw-footer__bottom">
                    <p class="gw-footer__copyright"><?php echo esc_html( $settings['copyright_text'] ); ?></p>
                    <?php if ( ! empty( $settings['bottom_links'] ) ) : ?>
                        <ul class="gw-footer__bottom-links">
                            <?php foreach ( $settings['bottom_links'] as $link ) : ?>
                                <li>
                                    <a href="<?php echo esc_url( $link['link']['url'] ?? '#' ); ?>">
                                        <?php echo esc_html( $link['label'] ); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </footer>
        <?php
    }
}
