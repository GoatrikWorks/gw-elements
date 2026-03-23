<?php
/**
 * Internationalization handler for LaViaSana.
 *
 * Provides Italian (default) with English, German and French via URL prefix.
 * Uses output buffering to translate all page content at once.
 *
 * @package GW_Elements
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

final class GW_I18n {

    /** @var GW_I18n|null */
    private static ?GW_I18n $instance = null;

    /** @var string Current language code. */
    private string $lang = 'it';

    /** @var array Translation map (Italian => translated). */
    private array $translations = [];

    /** @var string Original request URI before stripping prefix. */
    private string $original_uri = '';

    /** @var array Supported non-default languages. */
    private const LANG_CODES = [ 'en', 'de', 'fr' ];

    /**
     * Must be called very early (before WordPress parses the request).
     * Strips language prefix from REQUEST_URI so WordPress resolves the correct page.
     */
    public static function init_early(): void {
        if ( ! isset( $_SERVER['REQUEST_URI'] ) ) {
            return;
        }

        if ( str_contains( $_SERVER['REQUEST_URI'], '/wp-admin' ) || str_contains( $_SERVER['REQUEST_URI'], '/wp-json' ) ) {
            return;
        }

        $pattern = '#^/(' . implode( '|', self::LANG_CODES ) . ')(/.*)?$#';
        if ( preg_match( $pattern, $_SERVER['REQUEST_URI'], $m ) ) {
            $_SERVER['GW_ORIGINAL_URI'] = $_SERVER['REQUEST_URI'];
            $_SERVER['GW_LANG']         = $m[1];
            $_SERVER['REQUEST_URI']     = $m[2] ?: '/';
        }
    }

    public static function instance(): GW_I18n {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Get all supported languages including Italian.
     */
    public static function get_all_languages(): array {
        return [
            'it' => 'Italiano',
            'en' => 'English',
            'de' => 'Deutsch',
            'fr' => 'Français',
        ];
    }

    private function __construct() {
        $this->original_uri = $_SERVER['GW_ORIGINAL_URI'] ?? $_SERVER['REQUEST_URI'];

        if ( isset( $_SERVER['GW_LANG'] ) && in_array( $_SERVER['GW_LANG'], self::LANG_CODES, true ) ) {
            $this->lang = $_SERVER['GW_LANG'];
        }

        if ( 'it' !== $this->lang ) {
            $this->load_translations();
            $this->setup_hooks();
        }
    }

    public function is_translated(): bool {
        return 'it' !== $this->lang;
    }

    public function is_english(): bool {
        return 'en' === $this->lang;
    }

    public function get_lang(): string {
        return $this->lang;
    }

    /**
     * Get the URL to switch to a given language.
     */
    public function get_lang_url( string $target_lang ): string {
        // Start from the base path (strip any existing language prefix).
        $base_path = $this->original_uri;
        $pattern   = '#^/(' . implode( '|', self::LANG_CODES ) . ')(?=/|$)#';
        $base_path = preg_replace( $pattern, '', $base_path ) ?: '/';

        if ( 'it' === $target_lang ) {
            return $base_path;
        }

        return '/' . $target_lang . $base_path;
    }

    /**
     * Load translations for the current language.
     */
    private function load_translations(): void {
        // Try DB first (via store), fall back to file.
        if ( class_exists( 'GW_Translation_Store' ) ) {
            $this->translations = GW_Translation_Store::instance()->get_translations( $this->lang );
        } else {
            // Fallback: load directly from file.
            $file = GW_ELEMENTS_PATH . 'includes/translations/' . $this->lang . '.php';
            if ( file_exists( $file ) ) {
                $this->translations = require $file;
                uksort( $this->translations, fn( $a, $b ) => strlen( $b ) - strlen( $a ) );
            }
        }
    }

    private function setup_hooks(): void {
        $locale = $this->get_wp_locale();

        add_filter( 'locale', function ( $current ) use ( $locale ) {
            return is_admin() ? $current : $locale;
        }, 1 );

        add_filter( 'redirect_canonical', '__return_false' );

        add_action( 'template_redirect', function () {
            if ( isset( $_GET['elementor-preview'] ) ) {
                return;
            }
            ob_start( [ $this, 'translate_output' ] );
        }, 0 );

        add_action( 'wp_head', [ $this, 'render_hreflang' ], 1 );
    }

    private function get_wp_locale(): string {
        $map = [ 'en' => 'en_US', 'de' => 'de_DE', 'fr' => 'fr_FR' ];
        return $map[ $this->lang ] ?? 'it_IT';
    }

    /**
     * Output buffer callback — translates the full HTML page.
     */
    public function translate_output( string $html ): string {
        if ( empty( $this->translations ) || empty( $html ) ) {
            return $html;
        }

        // 1. Protect URLs/attributes that must NOT be translated.
        $protected = [];
        $counter   = 0;

        // Protect src/srcset/data-* attributes AND hreflang link tags from translation.
        $html = preg_replace_callback(
            '#(src|srcset|data-image-url|data-full-url|data-src|data-success_message|data-languages)="([^"]*)"#i',
            function ( $m ) use ( &$protected, &$counter ) {
                $key               = '<!--GW_P_' . $counter . '-->';
                $protected[ $key ] = $m[0];
                $counter++;
                return $key;
            },
            $html
        );

        // Protect entire <link rel="alternate" hreflang> tags.
        $html = preg_replace_callback(
            '#<link\s+rel="alternate"\s+hreflang="[^"]*"\s+href="[^"]*"\s*/?\s*>#i',
            function ( $m ) use ( &$protected, &$counter ) {
                $key               = '<!--GW_P_' . $counter . '-->';
                $protected[ $key ] = $m[0];
                $counter++;
                return $key;
            },
            $html
        );

        // Protect <script> blocks — JS translations are handled via wp_localize_script.
        $html = preg_replace_callback(
            '#<script\b[^>]*>.*?</script>#si',
            function ( $m ) use ( &$protected, &$counter ) {
                $key               = '<!--GW_P_' . $counter . '-->';
                $protected[ $key ] = $m[0];
                $counter++;
                return $key;
            },
            $html
        );

        // 2. Replace Italian strings with translations.
        $html = str_replace(
            array_keys( $this->translations ),
            array_values( $this->translations ),
            $html
        );

        // 3. Add language prefix to internal links (before restoring protected strings).
        $html = $this->prefix_internal_urls( $html );

        // 4. Restore protected strings (after URL prefixing so they stay untouched).
        if ( ! empty( $protected ) ) {
            $html = str_replace( array_keys( $protected ), array_values( $protected ), $html );
        }

        return $html;
    }

    private function prefix_internal_urls( string $html ): string {
        $site_url = get_option( 'siteurl' );
        $escaped  = preg_quote( $site_url, '#' );
        $prefix   = '/' . $this->lang;
        $exclude  = implode( '|', array_map( fn( $c ) => $c . '/', self::LANG_CODES ) );

        $html = preg_replace_callback(
            '#(href|action)="(' . $escaped . ')(/(?!' . $exclude . 'wp-content/|wp-admin/|wp-json/|wp-includes/)[^"]*)"#',
            function ( $m ) use ( $prefix ) {
                return $m[1] . '="' . $m[2] . $prefix . $m[3] . '"';
            },
            $html
        );

        $html = str_replace(
            [ 'href="' . $site_url . '"', 'href="' . $site_url . '/"' ],
            [ 'href="' . $site_url . $prefix . '/"', 'href="' . $site_url . $prefix . '/"' ],
            $html
        );

        return $html;
    }

    public function render_hreflang(): void {
        $site_url  = get_option( 'siteurl' );
        $base_path = $_SERVER['REQUEST_URI']; // Already stripped of language prefix.
        $it_url    = $site_url . $base_path;

        echo '<link rel="alternate" hreflang="it" href="' . esc_url( $it_url ) . '" />' . "\n";
        echo '<link rel="alternate" hreflang="x-default" href="' . esc_url( $it_url ) . '" />' . "\n";

        foreach ( self::LANG_CODES as $code ) {
            echo '<link rel="alternate" hreflang="' . esc_attr( $code ) . '" href="' . esc_url( $site_url . '/' . $code . $base_path ) . '" />' . "\n";
        }
    }
}
