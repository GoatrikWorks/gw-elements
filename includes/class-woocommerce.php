<?php
/**
 * WooCommerce integration class.
 *
 * Compatible with WooCommerce 10.4.3+
 * Uses modern WC APIs and HPOS-compatible methods.
 *
 * @package GW_Elements
 * @see https://woocommerce.github.io/code-reference/
 * @see https://woocommerce.com/documentation/woocommerce/
 */

namespace GW\Elements;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class WooCommerce
 *
 * Handles WooCommerce integration for product widgets.
 */
class WooCommerce {

    /**
     * Singleton instance.
     *
     * @var WooCommerce|null
     */
    private static ?WooCommerce $instance = null;

    /**
     * AJAX nonce action.
     */
    const NONCE_ACTION = 'gw_elements_nonce';

    /**
     * Get singleton instance.
     *
     * @return WooCommerce
     */
    public static function instance(): WooCommerce {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor.
     */
    private function __construct() {
        // Declare HPOS compatibility.
        add_action( 'before_woocommerce_init', [ $this, 'declare_hpos_compatibility' ] );

        // Single product page customizations.
        add_action( 'woocommerce_single_product_summary', [ $this, 'add_category_above_title' ], 4 );
        add_action( 'woocommerce_single_product_summary', [ $this, 'add_trust_badges' ], 35 );
        add_action( 'woocommerce_before_single_product', [ $this, 'add_back_to_shop_link' ], 5 );
        add_action( 'woocommerce_after_add_to_cart_button', [ $this, 'add_wishlist_button' ], 5 );
        add_filter( 'woocommerce_quantity_input_args', [ $this, 'quantity_input_args' ], 10, 2 );

        // AJAX handlers for cart operations.
        add_action( 'wp_ajax_gw_add_to_cart', [ $this, 'ajax_add_to_cart' ] );
        add_action( 'wp_ajax_nopriv_gw_add_to_cart', [ $this, 'ajax_add_to_cart' ] );

        add_action( 'wp_ajax_gw_update_cart_item', [ $this, 'ajax_update_cart_item' ] );
        add_action( 'wp_ajax_nopriv_gw_update_cart_item', [ $this, 'ajax_update_cart_item' ] );

        add_action( 'wp_ajax_gw_remove_cart_item', [ $this, 'ajax_remove_cart_item' ] );
        add_action( 'wp_ajax_nopriv_gw_remove_cart_item', [ $this, 'ajax_remove_cart_item' ] );

        add_action( 'wp_ajax_gw_get_cart', [ $this, 'ajax_get_cart' ] );
        add_action( 'wp_ajax_nopriv_gw_get_cart', [ $this, 'ajax_get_cart' ] );

        add_action( 'wp_ajax_gw_get_cart_count', [ $this, 'ajax_get_cart_count' ] );
        add_action( 'wp_ajax_nopriv_gw_get_cart_count', [ $this, 'ajax_get_cart_count' ] );

        // AJAX handlers for products.
        add_action( 'wp_ajax_gw_filter_products', [ $this, 'ajax_filter_products' ] );
        add_action( 'wp_ajax_nopriv_gw_filter_products', [ $this, 'ajax_filter_products' ] );

        add_action( 'wp_ajax_gw_search_products', [ $this, 'ajax_search_products' ] );
        add_action( 'wp_ajax_nopriv_gw_search_products', [ $this, 'ajax_search_products' ] );

        // Cart fragments for AJAX cart update.
        add_filter( 'woocommerce_add_to_cart_fragments', [ $this, 'cart_fragments' ] );

        // Add to wishlist (simple implementation).
        add_action( 'wp_ajax_gw_toggle_wishlist', [ $this, 'ajax_toggle_wishlist' ] );
        add_action( 'wp_ajax_nopriv_gw_toggle_wishlist', [ $this, 'ajax_toggle_wishlist' ] );

        // Localize script data.
        add_action( 'wp_enqueue_scripts', [ $this, 'localize_scripts' ], 20 );
    }

    /**
     * Declare HPOS (High-Performance Order Storage) compatibility.
     *
     * @see https://woocommerce.com/document/high-performance-order-storage/
     */
    public function declare_hpos_compatibility(): void {
        if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
                'custom_order_tables',
                GW_ELEMENTS_FILE,
                true
            );
        }
    }

    /**
     * Add back to shop link before product.
     */
    public function add_back_to_shop_link(): void {
        $shop_url = wc_get_page_permalink( 'shop' );
        ?>
        <div class="gw-back-to-shop">
            <a href="<?php echo esc_url( $shop_url ); ?>" class="gw-back-to-shop__link">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m12 19-7-7 7-7"/><path d="M19 12H5"/>
                </svg>
                <?php esc_html_e( 'Back to Shop', 'gw-elements' ); ?>
            </a>
        </div>
        <?php
    }

    /**
     * Add category label above product title.
     */
    public function add_category_above_title(): void {
        global $product;

        if ( ! $product ) {
            return;
        }

        $terms = get_the_terms( $product->get_id(), 'product_cat' );

        if ( $terms && ! is_wp_error( $terms ) ) {
            $category = $terms[0];
            ?>
            <span class="gw-product-category-label">
                <?php echo esc_html( $category->name ); ?>
            </span>
            <?php
        }
    }

    /**
     * Add trust badges after add to cart form.
     */
    public function add_trust_badges(): void {
        ?>
        <div class="gw-trust-badges">
            <div class="gw-trust-badge">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/>
                    <path d="M15 18H9"/>
                    <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"/>
                    <circle cx="17" cy="18" r="2"/>
                    <circle cx="7" cy="18" r="2"/>
                </svg>
                <span><?php esc_html_e( 'Free shipping over €50', 'gw-elements' ); ?></span>
            </div>
            <div class="gw-trust-badge">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/>
                </svg>
                <span><?php esc_html_e( 'Secure checkout', 'gw-elements' ); ?></span>
            </div>
            <div class="gw-trust-badge">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/>
                    <path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>
                </svg>
                <span><?php esc_html_e( 'Certified organic', 'gw-elements' ); ?></span>
            </div>
        </div>
        <?php
    }

    /**
     * Add wishlist button after add to cart.
     */
    public function add_wishlist_button(): void {
        global $product;
        if ( ! $product ) {
            return;
        }
        ?>
        <button type="button" class="gw-wishlist-btn" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>" aria-label="<?php esc_attr_e( 'Add to wishlist', 'gw-elements' ); ?>">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
            </svg>
        </button>
        <?php
    }

    /**
     * Modify quantity input args.
     */
    public function quantity_input_args( array $args, $product ): array {
        $args['min_value'] = 1;
        $args['max_value'] = $product->get_max_purchase_quantity();
        $args['step'] = 1;
        return $args;
    }

    /**
     * Localize scripts with WooCommerce data.
     */
    public function localize_scripts(): void {
        if ( ! function_exists( 'WC' ) ) {
            return;
        }

        $cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
        $cart_total = WC()->cart ? WC()->cart->get_cart_total() : wc_price( 0 );

        wp_localize_script( 'gw-elements-frontend', 'gwWooCommerce', [
            'ajaxUrl'       => admin_url( 'admin-ajax.php' ),
            'nonce'         => wp_create_nonce( self::NONCE_ACTION ),
            'cartUrl'       => wc_get_cart_url(),
            'checkoutUrl'   => wc_get_checkout_url(),
            'shopUrl'       => wc_get_page_permalink( 'shop' ),
            'cartCount'     => $cart_count,
            'cartTotal'     => $cart_total,
            'currency'      => get_woocommerce_currency_symbol(),
            'priceFormat'   => get_woocommerce_price_format(),
            'priceDecimals' => wc_get_price_decimals(),
            'i18n'          => [
                'addToCart'      => __( 'Aggiungi al carrello', 'gw-elements' ),
                'addedToCart'    => __( 'Aggiunto al carrello', 'gw-elements' ),
                'viewCart'       => __( 'Vedi carrello', 'gw-elements' ),
                'checkout'       => __( 'Procedi al checkout', 'gw-elements' ),
                'continueShopping' => __( 'Continua lo shopping', 'gw-elements' ),
                'emptyCart'      => __( 'Il tuo carrello è vuoto', 'gw-elements' ),
                'emptyCartDesc'  => __( 'Aggiungi qualcosa per iniziare lo shopping!', 'gw-elements' ),
                'subtotal'       => __( 'Subtotale', 'gw-elements' ),
                'shippingNote'   => __( 'Spedizione calcolata al checkout', 'gw-elements' ),
                'remove'         => __( 'Rimuovi', 'gw-elements' ),
                'searching'      => __( 'Ricerca in corso...', 'gw-elements' ),
                'noResults'      => __( 'Nessun risultato trovato', 'gw-elements' ),
                'searchPlaceholder' => __( 'Cerca prodotti...', 'gw-elements' ),
            ],
        ] );
    }

    /**
     * AJAX add to cart handler.
     */
    public function ajax_add_to_cart(): void {
        check_ajax_referer( self::NONCE_ACTION, 'nonce' );

        $product_id   = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
        $quantity     = isset( $_POST['quantity'] ) ? absint( $_POST['quantity'] ) : 1;
        $variation_id = isset( $_POST['variation_id'] ) ? absint( $_POST['variation_id'] ) : 0;
        $variations   = isset( $_POST['variations'] ) ? array_map( 'sanitize_text_field', (array) $_POST['variations'] ) : [];

        if ( ! $product_id ) {
            wp_send_json_error( [ 'message' => __( 'Prodotto non valido.', 'gw-elements' ) ] );
        }

        $product = wc_get_product( $product_id );

        if ( ! $product ) {
            wp_send_json_error( [ 'message' => __( 'Prodotto non trovato.', 'gw-elements' ) ] );
        }

        // Handle variable products.
        if ( $product->is_type( 'variable' ) && ! $variation_id ) {
            wp_send_json_error( [ 'message' => __( 'Seleziona le opzioni del prodotto.', 'gw-elements' ) ] );
        }

        // Add to cart.
        $cart_item_key = WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variations );

        if ( $cart_item_key ) {
            // Get updated cart data.
            $cart_data = $this->get_cart_data();

            wp_send_json_success( [
                'message'       => sprintf( __( '%s aggiunto al carrello.', 'gw-elements' ), $product->get_name() ),
                'cartItemKey'   => $cart_item_key,
                'cartCount'     => $cart_data['count'],
                'cartTotal'     => $cart_data['total'],
                'cartSubtotal'  => $cart_data['subtotal'],
                'cartHtml'      => $this->render_mini_cart(),
                'fragments'     => $this->get_cart_fragments(),
            ] );
        } else {
            wp_send_json_error( [ 'message' => __( 'Impossibile aggiungere al carrello.', 'gw-elements' ) ] );
        }
    }

    /**
     * AJAX update cart item quantity.
     */
    public function ajax_update_cart_item(): void {
        check_ajax_referer( self::NONCE_ACTION, 'nonce' );

        $cart_item_key = isset( $_POST['cart_item_key'] ) ? sanitize_text_field( $_POST['cart_item_key'] ) : '';
        $quantity      = isset( $_POST['quantity'] ) ? absint( $_POST['quantity'] ) : 1;

        if ( ! $cart_item_key ) {
            wp_send_json_error( [ 'message' => __( 'Articolo non valido.', 'gw-elements' ) ] );
        }

        $cart = WC()->cart;

        if ( $quantity > 0 ) {
            $cart->set_quantity( $cart_item_key, $quantity, true );
        } else {
            $cart->remove_cart_item( $cart_item_key );
        }

        $cart_data = $this->get_cart_data();

        wp_send_json_success( [
            'cartCount'    => $cart_data['count'],
            'cartTotal'    => $cart_data['total'],
            'cartSubtotal' => $cart_data['subtotal'],
            'cartHtml'     => $this->render_mini_cart(),
            'fragments'    => $this->get_cart_fragments(),
        ] );
    }

    /**
     * AJAX remove cart item.
     */
    public function ajax_remove_cart_item(): void {
        check_ajax_referer( self::NONCE_ACTION, 'nonce' );

        $cart_item_key = isset( $_POST['cart_item_key'] ) ? sanitize_text_field( $_POST['cart_item_key'] ) : '';

        if ( ! $cart_item_key ) {
            wp_send_json_error( [ 'message' => __( 'Articolo non valido.', 'gw-elements' ) ] );
        }

        $removed = WC()->cart->remove_cart_item( $cart_item_key );

        if ( $removed ) {
            $cart_data = $this->get_cart_data();

            wp_send_json_success( [
                'cartCount'    => $cart_data['count'],
                'cartTotal'    => $cart_data['total'],
                'cartSubtotal' => $cart_data['subtotal'],
                'cartHtml'     => $this->render_mini_cart(),
                'fragments'    => $this->get_cart_fragments(),
            ] );
        } else {
            wp_send_json_error( [ 'message' => __( 'Impossibile rimuovere l\'articolo.', 'gw-elements' ) ] );
        }
    }

    /**
     * AJAX get full cart data.
     */
    public function ajax_get_cart(): void {
        $cart_data = $this->get_cart_data();

        wp_send_json_success( [
            'items'        => $cart_data['items'],
            'count'        => $cart_data['count'],
            'total'        => $cart_data['total'],
            'subtotal'     => $cart_data['subtotal'],
            'cartHtml'     => $this->render_mini_cart(),
        ] );
    }

    /**
     * AJAX get cart count handler.
     */
    public function ajax_get_cart_count(): void {
        $cart_data = $this->get_cart_data();

        wp_send_json_success( [
            'count'    => $cart_data['count'],
            'total'    => $cart_data['total'],
            'subtotal' => $cart_data['subtotal'],
        ] );
    }

    /**
     * AJAX filter products handler.
     */
    public function ajax_filter_products(): void {
        check_ajax_referer( self::NONCE_ACTION, 'nonce' );

        $category = isset( $_POST['category'] ) ? sanitize_text_field( $_POST['category'] ) : '';
        $orderby  = isset( $_POST['orderby'] ) ? sanitize_text_field( $_POST['orderby'] ) : 'date';
        $order    = isset( $_POST['order'] ) ? sanitize_text_field( $_POST['order'] ) : 'DESC';
        $per_page = isset( $_POST['per_page'] ) ? absint( $_POST['per_page'] ) : 12;
        $paged    = isset( $_POST['paged'] ) ? absint( $_POST['paged'] ) : 1;
        $search   = isset( $_POST['search'] ) ? sanitize_text_field( $_POST['search'] ) : '';

        $args = [
            'status'   => 'publish',
            'limit'    => $per_page,
            'page'     => $paged,
            'orderby'  => $orderby,
            'order'    => $order,
            'return'   => 'objects',
        ];

        if ( $category && 'all' !== $category ) {
            $args['category'] = [ $category ];
        }

        if ( $search ) {
            $args['s'] = $search;
        }

        $products = wc_get_products( $args );
        $html     = '';

        if ( ! empty( $products ) ) {
            foreach ( $products as $product ) {
                $html .= self::render_product_card( $product );
            }
        }

        // Count total products for pagination.
        $count_args          = $args;
        $count_args['limit'] = -1;
        $count_args['return'] = 'ids';
        $total_products      = count( wc_get_products( $count_args ) );
        $total_pages         = ceil( $total_products / $per_page );

        wp_send_json_success( [
            'html'        => $html,
            'count'       => $total_products,
            'totalPages'  => $total_pages,
            'currentPage' => $paged,
        ] );
    }

    /**
     * AJAX live search products.
     */
    public function ajax_search_products(): void {
        $search   = isset( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';
        $per_page = isset( $_GET['per_page'] ) ? absint( $_GET['per_page'] ) : 8;

        if ( strlen( $search ) < 2 ) {
            wp_send_json_success( [ 'results' => [], 'count' => 0 ] );
        }

        $args = [
            'status'  => 'publish',
            'limit'   => $per_page,
            's'       => $search,
            'return'  => 'objects',
            'orderby' => 'relevance',
        ];

        $products = wc_get_products( $args );
        $results  = [];

        foreach ( $products as $product ) {
            $image_id  = $product->get_image_id();
            $image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'thumbnail' ) : wc_placeholder_img_src( 'thumbnail' );
            $terms     = get_the_terms( $product->get_id(), 'product_cat' );
            $category  = $terms && ! is_wp_error( $terms ) ? $terms[0]->name : '';

            $results[] = [
                'id'        => $product->get_id(),
                'name'      => $product->get_name(),
                'url'       => $product->get_permalink(),
                'image'     => $image_url,
                'price'     => $product->get_price_html(),
                'priceRaw'  => $product->get_price(),
                'category'  => $category,
            ];
        }

        // Count total for "view all" link.
        $count_args          = $args;
        $count_args['limit'] = -1;
        $count_args['return'] = 'ids';
        $total               = count( wc_get_products( $count_args ) );

        wp_send_json_success( [
            'results'   => $results,
            'count'     => $total,
            'searchUrl' => add_query_arg( 's', urlencode( $search ), wc_get_page_permalink( 'shop' ) ),
        ] );
    }

    /**
     * AJAX toggle wishlist item.
     */
    public function ajax_toggle_wishlist(): void {
        check_ajax_referer( self::NONCE_ACTION, 'nonce' );

        $product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;

        if ( ! $product_id ) {
            wp_send_json_error( [ 'message' => __( 'Prodotto non valido.', 'gw-elements' ) ] );
        }

        // Get current wishlist from user meta or cookie.
        $wishlist = $this->get_wishlist();

        if ( in_array( $product_id, $wishlist, true ) ) {
            // Remove from wishlist.
            $wishlist = array_diff( $wishlist, [ $product_id ] );
            $added    = false;
            $message  = __( 'Rimosso dai preferiti.', 'gw-elements' );
        } else {
            // Add to wishlist.
            $wishlist[] = $product_id;
            $added      = true;
            $message    = __( 'Aggiunto ai preferiti.', 'gw-elements' );
        }

        $this->save_wishlist( $wishlist );

        wp_send_json_success( [
            'added'    => $added,
            'message'  => $message,
            'count'    => count( $wishlist ),
            'wishlist' => $wishlist,
        ] );
    }

    /**
     * Get wishlist items.
     *
     * @return array
     */
    private function get_wishlist(): array {
        if ( is_user_logged_in() ) {
            $wishlist = get_user_meta( get_current_user_id(), '_gw_wishlist', true );
            return is_array( $wishlist ) ? $wishlist : [];
        }

        // For guests, use cookie.
        $cookie = isset( $_COOKIE['gw_wishlist'] ) ? sanitize_text_field( $_COOKIE['gw_wishlist'] ) : '';
        if ( $cookie ) {
            $wishlist = json_decode( stripslashes( $cookie ), true );
            return is_array( $wishlist ) ? array_map( 'absint', $wishlist ) : [];
        }

        return [];
    }

    /**
     * Save wishlist items.
     *
     * @param array $wishlist Wishlist product IDs.
     */
    private function save_wishlist( array $wishlist ): void {
        $wishlist = array_values( array_unique( array_filter( array_map( 'absint', $wishlist ) ) ) );

        if ( is_user_logged_in() ) {
            update_user_meta( get_current_user_id(), '_gw_wishlist', $wishlist );
        } else {
            // Set cookie for 30 days.
            setcookie( 'gw_wishlist', wp_json_encode( $wishlist ), time() + ( 30 * DAY_IN_SECONDS ), COOKIEPATH, COOKIE_DOMAIN, is_ssl(), true );
        }
    }

    /**
     * Cart fragments for AJAX cart refresh.
     *
     * @param array $fragments Cart fragments.
     * @return array
     */
    public function cart_fragments( array $fragments ): array {
        $cart_data = $this->get_cart_data();

        // Cart count badge.
        $fragments['.gw-cart-count'] = sprintf(
            '<span class="gw-cart-count">%d</span>',
            $cart_data['count']
        );

        // Cart count with visibility class.
        $fragments['.gw-cart-count-badge'] = sprintf(
            '<span class="gw-cart-count-badge%s">%d</span>',
            $cart_data['count'] > 0 ? '' : ' gw-hidden',
            $cart_data['count']
        );

        // Mini cart HTML.
        $fragments['.gw-mini-cart-content'] = '<div class="gw-mini-cart-content">' . $this->render_mini_cart() . '</div>';

        return $fragments;
    }

    /**
     * Get cart fragments as array.
     *
     * @return array
     */
    private function get_cart_fragments(): array {
        return apply_filters( 'woocommerce_add_to_cart_fragments', [] );
    }

    /**
     * Get cart data.
     *
     * @return array
     */
    private function get_cart_data(): array {
        $cart = WC()->cart;

        if ( ! $cart ) {
            return [
                'items'    => [],
                'count'    => 0,
                'total'    => wc_price( 0 ),
                'subtotal' => wc_price( 0 ),
            ];
        }

        $items = [];

        foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {
            $product   = $cart_item['data'];
            $image_id  = $product->get_image_id();
            $image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'thumbnail' ) : wc_placeholder_img_src( 'thumbnail' );

            $items[] = [
                'key'         => $cart_item_key,
                'productId'   => $cart_item['product_id'],
                'variationId' => $cart_item['variation_id'] ?? 0,
                'name'        => $product->get_name(),
                'url'         => $product->get_permalink(),
                'image'       => $image_url,
                'price'       => $product->get_price(),
                'priceHtml'   => $product->get_price_html(),
                'quantity'    => $cart_item['quantity'],
                'subtotal'    => WC()->cart->get_product_subtotal( $product, $cart_item['quantity'] ),
            ];
        }

        return [
            'items'    => $items,
            'count'    => $cart->get_cart_contents_count(),
            'total'    => $cart->get_cart_total(),
            'subtotal' => $cart->get_cart_subtotal(),
        ];
    }

    /**
     * Render mini cart HTML.
     *
     * @return string
     */
    public function render_mini_cart(): string {
        $cart = WC()->cart;

        if ( ! $cart || $cart->is_empty() ) {
            ob_start();
            ?>
            <div class="gw-cart-drawer__empty">
                <?php echo $this->get_icon_svg( 'shopping-bag', 64 ); ?>
                <h3 class="gw-cart-drawer__empty-title"><?php esc_html_e( 'Il tuo carrello è vuoto', 'gw-elements' ); ?></h3>
                <p class="gw-cart-drawer__empty-text"><?php esc_html_e( 'Aggiungi qualcosa per iniziare lo shopping!', 'gw-elements' ); ?></p>
                <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="gw-button gw-button--primary">
                    <?php esc_html_e( 'Esplora i prodotti', 'gw-elements' ); ?>
                </a>
            </div>
            <?php
            return ob_get_clean();
        }

        ob_start();
        ?>
        <div class="gw-cart-drawer__items">
            <?php foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) :
                $product   = $cart_item['data'];
                $image_id  = $product->get_image_id();
                $image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'thumbnail' ) : wc_placeholder_img_src( 'thumbnail' );
            ?>
                <div class="gw-cart-item" data-cart-item-key="<?php echo esc_attr( $cart_item_key ); ?>">
                    <a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="gw-cart-item__image">
                        <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>" loading="lazy">
                    </a>
                    <div class="gw-cart-item__content">
                        <a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="gw-cart-item__title">
                            <?php echo esc_html( $product->get_name() ); ?>
                        </a>
                        <span class="gw-cart-item__price">
                            <?php echo wp_kses_post( $product->get_price_html() ); ?>
                        </span>
                        <div class="gw-cart-item__quantity">
                            <button type="button" class="gw-cart-item__qty-btn gw-cart-item__qty-btn--minus" data-action="decrease">
                                <?php echo $this->get_icon_svg( 'minus', 14 ); ?>
                            </button>
                            <span class="gw-cart-item__qty-value"><?php echo esc_html( $cart_item['quantity'] ); ?></span>
                            <button type="button" class="gw-cart-item__qty-btn gw-cart-item__qty-btn--plus" data-action="increase">
                                <?php echo $this->get_icon_svg( 'plus', 14 ); ?>
                            </button>
                            <button type="button" class="gw-cart-item__remove" data-action="remove" title="<?php esc_attr_e( 'Rimuovi', 'gw-elements' ); ?>">
                                <?php echo $this->get_icon_svg( 'trash-2', 16 ); ?>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="gw-cart-drawer__footer">
            <div class="gw-cart-drawer__subtotal">
                <span><?php esc_html_e( 'Subtotale', 'gw-elements' ); ?></span>
                <span class="gw-cart-drawer__subtotal-value"><?php echo wp_kses_post( $cart->get_cart_subtotal() ); ?></span>
            </div>
            <p class="gw-cart-drawer__shipping-note"><?php esc_html_e( 'Spedizione calcolata al checkout', 'gw-elements' ); ?></p>
            <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="gw-button gw-button--primary gw-button--full">
                <?php esc_html_e( 'Procedi al checkout', 'gw-elements' ); ?>
            </a>
            <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="gw-button gw-button--outline gw-button--full">
                <?php esc_html_e( 'Continua lo shopping', 'gw-elements' ); ?>
            </a>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Get SVG icon.
     *
     * @param string $icon Icon name.
     * @param int    $size Icon size.
     * @return string
     */
    private function get_icon_svg( string $icon, int $size = 24 ): string {
        $icons = [
            'shopping-bag' => '<path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>',
            'minus'        => '<path d="M5 12h14"/>',
            'plus'         => '<path d="M5 12h14"/><path d="M12 5v14"/>',
            'trash-2'      => '<path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/>',
            'x'            => '<path d="M18 6 6 18"/><path d="m6 6 12 12"/>',
            'search'       => '<circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>',
            'loader'       => '<path d="M21 12a9 9 0 1 1-6.219-8.56"/>',
        ];

        if ( ! isset( $icons[ $icon ] ) ) {
            return '';
        }

        return sprintf(
            '<svg class="gw-icon gw-icon--%s" width="%d" height="%d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">%s</svg>',
            esc_attr( $icon ),
            $size,
            $size,
            $icons[ $icon ]
        );
    }

    /**
     * Get products with query args.
     *
     * @param array $args Query arguments.
     * @return array
     */
    public static function get_products( array $args = [] ): array {
        $defaults = [
            'status'  => 'publish',
            'limit'   => 12,
            'orderby' => 'date',
            'order'   => 'DESC',
            'return'  => 'objects',
        ];

        $args = wp_parse_args( $args, $defaults );

        return wc_get_products( $args );
    }

    /**
     * Get product categories.
     *
     * @param array $args Query arguments.
     * @return array
     */
    public static function get_categories( array $args = [] ): array {
        $defaults = [
            'taxonomy'   => 'product_cat',
            'orderby'    => 'name',
            'order'      => 'ASC',
            'hide_empty' => true,
        ];

        $args = wp_parse_args( $args, $defaults );

        return get_terms( $args );
    }

    /**
     * Render a product card HTML.
     *
     * @param \WC_Product $product Product object.
     * @return string
     */
    public static function render_product_card( \WC_Product $product ): string {
        $image_id  = $product->get_image_id();
        $image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'medium_large' ) : wc_placeholder_img_src( 'medium_large' );
        $terms     = get_the_terms( $product->get_id(), 'product_cat' );
        $category  = $terms && ! is_wp_error( $terms ) ? $terms[0]->name : '';

        ob_start();
        ?>
        <article class="gw-product-card" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
            <a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="gw-product-card__image-link">
                <div class="gw-product-card__image">
                    <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>" loading="lazy" decoding="async">
                </div>
            </a>
            <div class="gw-product-card__content">
                <?php if ( $category ) : ?>
                    <span class="gw-product-card__category"><?php echo esc_html( $category ); ?></span>
                <?php endif; ?>
                <a href="<?php echo esc_url( $product->get_permalink() ); ?>">
                    <h3 class="gw-product-card__title"><?php echo esc_html( $product->get_name() ); ?></h3>
                </a>
                <span class="gw-product-card__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
                <?php if ( $product->is_purchasable() && $product->is_in_stock() ) : ?>
                    <button type="button" class="gw-product-card__button gw-button gw-button--card gw-add-to-cart-btn" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
                        <span class="gw-button__text"><?php esc_html_e( 'Aggiungi al carrello', 'gw-elements' ); ?></span>
                        <span class="gw-button__loading">
                            <svg class="gw-spinner" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" fill="none" stroke-linecap="round" stroke-dasharray="31.4 31.4" /></svg>
                        </span>
                    </button>
                <?php endif; ?>
            </div>
        </article>
        <?php
        return ob_get_clean();
    }

    /**
     * Check if product is in wishlist.
     *
     * @param int $product_id Product ID.
     * @return bool
     */
    public function is_in_wishlist( int $product_id ): bool {
        $wishlist = $this->get_wishlist();
        return in_array( $product_id, $wishlist, true );
    }

    /**
     * Get wishlist count.
     *
     * @return int
     */
    public function get_wishlist_count(): int {
        return count( $this->get_wishlist() );
    }
}
