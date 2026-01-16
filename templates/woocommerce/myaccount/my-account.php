<?php
/**
 * My Account page - Custom template override
 *
 * This template overrides WooCommerce's default my-account.php
 * to provide a modern grid layout with sidebar navigation.
 *
 * @package GW_Elements
 */

defined( 'ABSPATH' ) || exit;

$current_user = wp_get_current_user();
?>

<div class="gw-my-account">
    <nav class="gw-my-account__navigation" aria-label="<?php esc_attr_e( 'Account navigation', 'gw-elements' ); ?>">
        <?php do_action( 'woocommerce_account_navigation' ); ?>
    </nav>

    <main class="gw-my-account__content">
        <?php
            /**
             * My Account content.
             */
            do_action( 'woocommerce_account_content' );
        ?>
    </main>
</div>
