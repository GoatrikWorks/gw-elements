<?php
/**
 * Translation data store.
 *
 * Reads/writes translations from wp_options (primary) with file fallback.
 * Each language stored as: gw_translations_{lang} => [ 'italian' => 'translated', ... ]
 * Group metadata stored as: gw_translation_groups => [ 'Section' => ['key1', ...], ... ]
 *
 * @package GW_Elements
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

final class GW_Translation_Store {

    private static ?GW_Translation_Store $instance = null;

    /** @var array Cached translations per language. */
    private array $cache = [];

    /** @var array Supported languages (code => label). */
    private const LANGUAGES = [
        'en' => 'English',
        'de' => 'Deutsch',
        'fr' => 'Français',
    ];

    /** @var array Language code to WP locale mapping. */
    private const LOCALE_MAP = [
        'en' => 'en_US',
        'de' => 'de_DE',
        'fr' => 'fr_FR',
    ];

    public static function instance(): self {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Get supported languages (excludes Italian which is the default).
     */
    public function get_languages(): array {
        return self::LANGUAGES;
    }

    /**
     * Get WP locale for a language code.
     */
    public function get_locale( string $lang ): string {
        return self::LOCALE_MAP[ $lang ] ?? 'it_IT';
    }

    /**
     * Get translations for a language. Returns sorted array (longest key first).
     */
    public function get_translations( string $lang ): array {
        if ( isset( $this->cache[ $lang ] ) ) {
            return $this->cache[ $lang ];
        }

        $option_name = 'gw_translations_' . $lang;
        $translations = get_option( $option_name, null );
        $file_translations = $this->load_from_file( $lang );

        if ( null === $translations || ! is_array( $translations ) ) {
            // First load: import everything from file.
            $translations = $file_translations;
            if ( ! empty( $translations ) ) {
                update_option( $option_name, $translations, true );
                $this->build_groups_from_file( $lang );
            }
        } elseif ( ! empty( $file_translations ) ) {
            // Merge: add new keys from file that aren't in DB yet.
            $new_keys = array_diff_key( $file_translations, $translations );
            if ( ! empty( $new_keys ) ) {
                $translations = array_merge( $translations, $new_keys );
                update_option( $option_name, $translations, true );
                $this->build_groups_from_file( $lang );
            }
        }

        // Sort by key length descending to prevent partial-match issues.
        if ( ! empty( $translations ) ) {
            uksort( $translations, function ( $a, $b ) {
                return strlen( $b ) - strlen( $a );
            } );
        }

        $this->cache[ $lang ] = $translations;
        return $translations;
    }

    /**
     * Get all source strings (Italian keys) with their groups.
     */
    public function get_grouped_sources(): array {
        $groups = get_option( 'gw_translation_groups', null );

        if ( null === $groups || ! is_array( $groups ) ) {
            $this->build_groups_from_file( 'en' );
            $groups = get_option( 'gw_translation_groups', [] );
        }

        return $groups;
    }

    /**
     * Save a single translation.
     */
    public function save_single( string $lang, string $key, string $value ): bool {
        if ( ! isset( self::LANGUAGES[ $lang ] ) ) {
            return false;
        }

        $option_name = 'gw_translations_' . $lang;
        $translations = get_option( $option_name, [] );
        if ( ! is_array( $translations ) ) {
            $translations = [];
        }

        if ( '' === $value ) {
            unset( $translations[ $key ] );
        } else {
            $translations[ $key ] = $value;
        }

        unset( $this->cache[ $lang ] );
        return update_option( $option_name, $translations, true );
    }

    /**
     * Bulk save translations.
     */
    public function save_bulk( string $lang, array $pairs ): int {
        if ( ! isset( self::LANGUAGES[ $lang ] ) ) {
            return 0;
        }

        $option_name = 'gw_translations_' . $lang;
        $translations = get_option( $option_name, [] );
        if ( ! is_array( $translations ) ) {
            $translations = [];
        }

        $count = 0;
        foreach ( $pairs as $key => $value ) {
            if ( '' === $value ) {
                unset( $translations[ $key ] );
            } else {
                $translations[ $key ] = $value;
            }
            $count++;
        }

        unset( $this->cache[ $lang ] );
        update_option( $option_name, $translations, true );
        return $count;
    }

    /**
     * Load translations from a PHP file.
     */
    private function load_from_file( string $lang ): array {
        $file = GW_ELEMENTS_PATH . 'includes/translations/' . $lang . '.php';
        if ( file_exists( $file ) ) {
            $data = require $file;
            return is_array( $data ) ? $data : [];
        }
        return [];
    }

    /**
     * Build group metadata from the actual translation array keys.
     * Uses en.php file comments for section headers, and the loaded array for keys.
     */
    private function build_groups_from_file( string $lang ): void {
        $file = GW_ELEMENTS_PATH . 'includes/translations/en.php';
        if ( ! file_exists( $file ) ) {
            return;
        }

        // Get the actual PHP array keys (properly decoded, no duplicates).
        $translations = require $file;
        if ( ! is_array( $translations ) ) {
            return;
        }
        $all_keys = array_keys( $translations );

        // Parse file source to extract section headers and map keys to sections.
        $source = file_get_contents( $file );
        $lines  = explode( "\n", $source );

        $section_ranges = [];
        $current_section = 'General';

        foreach ( $lines as $i => $line ) {
            $trimmed = trim( $line );
            if ( preg_match( '#^//\s*([A-Z][^=]+)$#', $trimmed, $m ) ) {
                $name = trim( $m[1] );
                if ( ! preg_match( '#^[=\-]+$#', $name ) ) {
                    $current_section = $name;
                }
            }
            // When we find a key line, record which section it belongs to.
            if ( preg_match( "#^['\"](.+?)['\"]\s*=>#", $trimmed ) ) {
                $section_ranges[ $i ] = $current_section;
            }
        }

        // Map each actual key to a section based on order of appearance.
        $section_list = array_values( $section_ranges );
        $groups = [];
        $seen = [];

        foreach ( $all_keys as $idx => $key ) {
            if ( isset( $seen[ $key ] ) ) {
                continue; // Skip duplicates.
            }
            $seen[ $key ] = true;
            $section = $section_list[ $idx ] ?? 'General';
            $groups[ $section ][] = $key;
        }

        if ( ! empty( $groups ) ) {
            update_option( 'gw_translation_groups', $groups, true );
        }
    }
}
