<?php
/**
 * Admin translations page.
 *
 * Provides a WP admin interface for managing all site translations
 * across English, German and French.
 *
 * @package GW_Elements
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

final class GW_Admin {

    private static ?GW_Admin $instance = null;

    public static function instance(): self {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action( 'admin_menu', [ $this, 'register_menu' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_assets' ] );
        add_action( 'wp_ajax_gw_save_translation', [ $this, 'ajax_save_translation' ] );
        add_action( 'wp_ajax_gw_bulk_save', [ $this, 'ajax_bulk_save' ] );
    }

    /**
     * Register the admin menu page.
     */
    public function register_menu(): void {
        add_menu_page(
            'Translations — LaViaSana',
            'Translations',
            'manage_options',
            'gw-translations',
            [ $this, 'render_page' ],
            'dashicons-translation',
            30
        );
    }

    /**
     * Enqueue admin assets only on our page.
     */
    public function enqueue_assets( string $hook ): void {
        if ( 'toplevel_page_gw-translations' !== $hook ) {
            return;
        }

        wp_enqueue_style(
            'gw-admin-translations',
            GW_ELEMENTS_ASSETS_URL . 'css/admin-translations.css',
            [],
            GW_ELEMENTS_VERSION
        );

        wp_enqueue_script(
            'gw-admin-translations',
            GW_ELEMENTS_ASSETS_URL . 'js/admin-translations.js',
            [],
            GW_ELEMENTS_VERSION,
            true
        );

        wp_localize_script( 'gw-admin-translations', 'gwTranslations', [
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'gw_translations' ),
        ] );
    }

    /**
     * AJAX: Save a single translation.
     */
    public function ajax_save_translation(): void {
        check_ajax_referer( 'gw_translations', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( 'Unauthorized' );
        }

        $lang  = sanitize_text_field( $_POST['lang'] ?? '' );
        $key   = wp_unslash( $_POST['key'] ?? '' );
        $value = wp_unslash( $_POST['value'] ?? '' );

        if ( empty( $lang ) || empty( $key ) ) {
            wp_send_json_error( 'Missing parameters' );
        }

        $store  = GW_Translation_Store::instance();
        $result = $store->save_single( $lang, $key, $value );

        wp_send_json_success( [ 'saved' => $result ] );
    }

    /**
     * AJAX: Bulk save translations.
     */
    public function ajax_bulk_save(): void {
        check_ajax_referer( 'gw_translations', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( 'Unauthorized' );
        }

        $lang = sanitize_text_field( $_POST['lang'] ?? '' );
        $data = json_decode( wp_unslash( $_POST['translations'] ?? '{}' ), true );

        if ( empty( $lang ) || ! is_array( $data ) ) {
            wp_send_json_error( 'Invalid data' );
        }

        $store = GW_Translation_Store::instance();
        $count = $store->save_bulk( $lang, $data );

        wp_send_json_success( [ 'count' => $count ] );
    }

    /**
     * Render the admin page.
     */
    public function render_page(): void {
        $store     = GW_Translation_Store::instance();
        $languages = $store->get_languages();
        $groups    = $store->get_grouped_sources();

        // Load all translations.
        $translations = [];
        foreach ( $languages as $code => $label ) {
            $translations[ $code ] = $store->get_translations( $code );
        }

        // Collect all unique Italian keys.
        $all_keys = [];
        foreach ( $groups as $section => $keys ) {
            foreach ( $keys as $key ) {
                $all_keys[ $key ] = $section;
            }
        }

        // Count stats.
        $total_keys = count( $all_keys );
        $stats = [];
        foreach ( $languages as $code => $label ) {
            $filled = 0;
            foreach ( $all_keys as $key => $section ) {
                if ( ! empty( $translations[ $code ][ $key ] ) ) {
                    $filled++;
                }
            }
            $stats[ $code ] = $filled;
        }
        ?>
        <div class="wrap gw-translations-wrap">
            <h1 class="gw-translations-title">
                <span class="dashicons dashicons-translation"></span>
                Translations — LaViaSana
            </h1>

            <div class="gw-translations-stats">
                <?php foreach ( $languages as $code => $label ) :
                    $pct = $total_keys > 0 ? round( ( $stats[ $code ] / $total_keys ) * 100 ) : 0;
                ?>
                    <div class="gw-stat-card">
                        <div class="gw-stat-label"><?php echo esc_html( strtoupper( $code ) ); ?> — <?php echo esc_html( $label ); ?></div>
                        <div class="gw-stat-bar">
                            <div class="gw-stat-bar__fill" style="width: <?php echo esc_attr( $pct ); ?>%"></div>
                        </div>
                        <div class="gw-stat-number"><?php echo esc_html( $stats[ $code ] ); ?> / <?php echo esc_html( $total_keys ); ?> (<?php echo esc_html( $pct ); ?>%)</div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="gw-translations-toolbar">
                <input type="text" id="gw-search" class="gw-search-input" placeholder="Search translations...">
                <select id="gw-section-filter" class="gw-section-filter">
                    <option value="">All Sections</option>
                    <?php foreach ( array_keys( $groups ) as $section ) : ?>
                        <option value="<?php echo esc_attr( $section ); ?>"><?php echo esc_html( $section ); ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="button" id="gw-save-all" class="button button-primary">Save All Changes</button>
                <span id="gw-save-status" class="gw-save-status"></span>
            </div>

            <div class="gw-translations-table-wrap">
                <table class="gw-translations-table" id="gw-translations-table">
                    <thead>
                        <tr>
                            <th class="gw-col-source">Italian (source)</th>
                            <?php foreach ( $languages as $code => $label ) : ?>
                                <th class="gw-col-lang"><?php echo esc_html( strtoupper( $code ) ); ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ( $groups as $section => $keys ) : ?>
                            <tr class="gw-section-header" data-section="<?php echo esc_attr( $section ); ?>">
                                <td colspan="<?php echo count( $languages ) + 1; ?>">
                                    <strong><?php echo esc_html( $section ); ?></strong>
                                    <span class="gw-section-count"><?php echo count( $keys ); ?> strings</span>
                                </td>
                            </tr>
                            <?php foreach ( $keys as $key ) : ?>
                                <tr class="gw-translation-row" data-section="<?php echo esc_attr( $section ); ?>" data-key="<?php echo esc_attr( $key ); ?>">
                                    <td class="gw-col-source">
                                        <div class="gw-source-text"><?php echo esc_html( mb_strimwidth( $key, 0, 120, '...' ) ); ?></div>
                                        <?php if ( mb_strlen( $key ) > 120 ) : ?>
                                            <button type="button" class="gw-expand-btn" title="Show full text">...</button>
                                        <?php endif; ?>
                                    </td>
                                    <?php foreach ( $languages as $code => $label ) :
                                        $value = $translations[ $code ][ $key ] ?? '';
                                        $is_empty = '' === $value;
                                    ?>
                                        <td class="gw-col-lang <?php echo $is_empty ? 'gw-empty' : ''; ?>">
                                            <textarea
                                                class="gw-translation-input"
                                                data-lang="<?php echo esc_attr( $code ); ?>"
                                                data-key="<?php echo esc_attr( $key ); ?>"
                                                data-original="<?php echo esc_attr( $value ); ?>"
                                                rows="1"
                                            ><?php echo esc_textarea( $value ); ?></textarea>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    }
}
