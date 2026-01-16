<?php
/**
 * Single Product Content - Custom template override
 *
 * This template overrides WooCommerce's default content-single-product.php
 * to provide a modern, clean product page layout.
 *
 * @package GW_Elements
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product || ! $product instanceof WC_Product ) {
    return;
}

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
    echo get_the_password_form();
    return;
}

$product_id = $product->get_id();
$gallery_ids = $product->get_gallery_image_ids();
$main_image_id = $product->get_image_id();
$categories = get_the_terms( $product_id, 'product_cat' );
$category_name = $categories && ! is_wp_error( $categories ) ? $categories[0]->name : '';
?>

<div id="product-<?php echo esc_attr( $product_id ); ?>" <?php wc_product_class( 'gw-single-product', $product ); ?>>

    <div class="gw-single-product__grid">

        <!-- Product Gallery -->
        <div class="gw-single-product__gallery">
            <?php if ( $main_image_id ) : ?>
                <div class="gw-single-product__main-image">
                    <?php
                    $main_image_url = wp_get_attachment_image_url( $main_image_id, 'large' );
                    $main_image_full = wp_get_attachment_image_url( $main_image_id, 'full' );
                    ?>
                    <a href="<?php echo esc_url( $main_image_full ); ?>" data-lightbox="product-gallery">
                        <img src="<?php echo esc_url( $main_image_url ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>" class="gw-single-product__image">
                    </a>
                </div>

                <?php if ( ! empty( $gallery_ids ) ) : ?>
                    <div class="gw-single-product__thumbnails">
                        <button type="button" class="gw-single-product__thumb is-active" data-image-url="<?php echo esc_url( $main_image_url ); ?>" data-full-url="<?php echo esc_url( $main_image_full ); ?>">
                            <img src="<?php echo esc_url( wp_get_attachment_image_url( $main_image_id, 'thumbnail' ) ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>">
                        </button>
                        <?php foreach ( $gallery_ids as $gallery_id ) :
                            $thumb_url = wp_get_attachment_image_url( $gallery_id, 'thumbnail' );
                            $medium_url = wp_get_attachment_image_url( $gallery_id, 'large' );
                            $full_url = wp_get_attachment_image_url( $gallery_id, 'full' );
                        ?>
                            <button type="button" class="gw-single-product__thumb" data-image-url="<?php echo esc_url( $medium_url ); ?>" data-full-url="<?php echo esc_url( $full_url ); ?>">
                                <img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>">
                            </button>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php else : ?>
                <div class="gw-single-product__main-image gw-single-product__main-image--placeholder">
                    <?php echo wc_placeholder_img( 'large' ); ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Product Info -->
        <div class="gw-single-product__info">

            <?php if ( $category_name ) : ?>
                <span class="gw-single-product__category"><?php echo esc_html( $category_name ); ?></span>
            <?php endif; ?>

            <h1 class="gw-single-product__title"><?php echo esc_html( $product->get_name() ); ?></h1>

            <div class="gw-single-product__price">
                <?php echo $product->get_price_html(); ?>
            </div>

            <?php if ( $product->get_short_description() ) : ?>
                <div class="gw-single-product__short-description">
                    <?php echo wp_kses_post( $product->get_short_description() ); ?>
                </div>
            <?php endif; ?>

            <div class="gw-single-product__add-to-cart">
                <?php woocommerce_template_single_add_to_cart(); ?>
            </div>

            <!-- Trust Badges -->
            <div class="gw-single-product__trust-badges">
                <div class="gw-trust-badge">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 18H3c-.6 0-1-.4-1-1V7c0-.6.4-1 1-1h12c.6 0 1 .4 1 1v2"/>
                        <path d="M11 14h10c.6 0 1 .4 1 1v4c0 .6-.4 1-1 1H11c-.6 0-1-.4-1-1v-4c0-.6.4-1 1-1z"/>
                        <path d="M14 17h4"/>
                    </svg>
                    <span><?php esc_html_e( 'Fri frakt över 500kr', 'gw-elements' ); ?></span>
                </div>
                <div class="gw-trust-badge">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/>
                        <path d="m9 12 2 2 4-4"/>
                    </svg>
                    <span><?php esc_html_e( 'Säker betalning', 'gw-elements' ); ?></span>
                </div>
                <div class="gw-trust-badge">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9Z"/>
                        <path d="m3 9 2.45-4.9A2 2 0 0 1 7.24 3h9.52a2 2 0 0 1 1.8 1.1L21 9"/>
                        <path d="M12 3v6"/>
                    </svg>
                    <span><?php esc_html_e( '14 dagars öppet köp', 'gw-elements' ); ?></span>
                </div>
            </div>

            <!-- Product Meta -->
            <div class="gw-single-product__meta">
                <?php if ( $product->get_sku() ) : ?>
                    <div class="gw-single-product__meta-item">
                        <span class="gw-single-product__meta-label"><?php esc_html_e( 'Artikelnr:', 'gw-elements' ); ?></span>
                        <span class="gw-single-product__meta-value"><?php echo esc_html( $product->get_sku() ); ?></span>
                    </div>
                <?php endif; ?>

                <?php if ( $categories && ! is_wp_error( $categories ) ) : ?>
                    <div class="gw-single-product__meta-item">
                        <span class="gw-single-product__meta-label"><?php esc_html_e( 'Kategori:', 'gw-elements' ); ?></span>
                        <span class="gw-single-product__meta-value">
                            <?php
                            $category_links = array();
                            foreach ( $categories as $category ) {
                                $category_links[] = '<a href="' . esc_url( get_term_link( $category ) ) . '">' . esc_html( $category->name ) . '</a>';
                            }
                            echo implode( ', ', $category_links );
                            ?>
                        </span>
                    </div>
                <?php endif; ?>

                <?php
                $tags = get_the_terms( $product_id, 'product_tag' );
                if ( $tags && ! is_wp_error( $tags ) ) :
                ?>
                    <div class="gw-single-product__meta-item">
                        <span class="gw-single-product__meta-label"><?php esc_html_e( 'Taggar:', 'gw-elements' ); ?></span>
                        <span class="gw-single-product__meta-value">
                            <?php
                            $tag_links = array();
                            foreach ( $tags as $tag ) {
                                $tag_links[] = '<a href="' . esc_url( get_term_link( $tag ) ) . '">' . esc_html( $tag->name ) . '</a>';
                            }
                            echo implode( ', ', $tag_links );
                            ?>
                        </span>
                    </div>
                <?php endif; ?>
            </div>

        </div>

    </div>

    <!-- Product Tabs / Description -->
    <?php if ( $product->get_description() ) : ?>
        <div class="gw-single-product__tabs">
            <div class="gw-single-product__tabs-nav">
                <button type="button" class="gw-single-product__tab-btn is-active" data-tab="description">
                    <?php esc_html_e( 'Beskrivning', 'gw-elements' ); ?>
                </button>
                <?php if ( $product->has_attributes() ) : ?>
                    <button type="button" class="gw-single-product__tab-btn" data-tab="additional">
                        <?php esc_html_e( 'Ytterligare information', 'gw-elements' ); ?>
                    </button>
                <?php endif; ?>
            </div>

            <div class="gw-single-product__tabs-content">
                <div class="gw-single-product__tab-panel is-active" data-tab-content="description">
                    <div class="gw-single-product__description">
                        <?php echo wp_kses_post( $product->get_description() ); ?>
                    </div>
                </div>

                <?php if ( $product->has_attributes() ) : ?>
                    <div class="gw-single-product__tab-panel" data-tab-content="additional">
                        <?php do_action( 'woocommerce_product_additional_information', $product ); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Related Products -->
    <?php
    $related_products = wc_get_related_products( $product_id, 4 );
    if ( ! empty( $related_products ) ) :
    ?>
        <div class="gw-single-product__related">
            <h2 class="gw-single-product__related-title"><?php esc_html_e( 'Relaterade produkter', 'gw-elements' ); ?></h2>
            <div class="gw-single-product__related-grid">
                <?php
                foreach ( $related_products as $related_id ) {
                    $related_product = wc_get_product( $related_id );
                    if ( $related_product ) {
                        echo \GW\Elements\WooCommerce::render_product_card( $related_product );
                    }
                }
                ?>
            </div>
        </div>
    <?php endif; ?>

</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>
