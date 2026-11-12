<?php
/**
 * WooCommerce compatibility.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* ------------------------------------------------------------------ *
 * Cart / checkout / account URLs (mimic Shopify paths /cart /checkout)
 * ------------------------------------------------------------------ */
add_filter( 'woocommerce_store_api_transients', '__return_true' );

remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

/* ------------------------------------------------------------------ *
 * Product loop — pakai card rasa sendiri
 * ------------------------------------------------------------------ */
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_price', 10 );
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_show_product_loop_sale_flash', 10 );
add_action( 'woocommerce_shop_loop_item', function () {
	global $product;
	echo rasa_product_card( $product ); // phpcs:ignore WordPress.Security.EscapeOutput
}, 5 );

/* ------------------------------------------------------------------ *
 * Breadcrumbs off (rasa pakai markup sendiri)
 * ------------------------------------------------------------------ */
add_filter( 'woocommerce_enable_breadcrumbs', '__return_false' );

/* ------------------------------------------------------------------ *
 * Quantity fields — pakai markup rasa di single product
 * ------------------------------------------------------------------ */
add_filter( 'woocommerce_quantity_input_class', function () {
	return array( 'quantity-input__input', 'field__input' );
} );

/* ------------------------------------------------------------------ *
 * Pagination
 * ------------------------------------------------------------------ */
add_filter( 'woocommerce_pagination', function ( $html ) {
	return preg_replace( '/woocommerce-pagination[^"]*/', 'pagination', $html );
} );

/* ------------------------------------------------------------------ *
 * Add-to-cart AJAX redirect — tetap di halaman (cart drawer)
 * ------------------------------------------------------------------ */
add_filter( 'woocommerce_add_to_cart_redirect', function () {
	return wp_get_referer() ? wp_get_referer() : home_url( '/' );
} );

/* ------------------------------------------------------------------ *
 * Cart page
 * ------------------------------------------------------------------ */
add_filter( 'woocommerce_cart_is_empty', function ( $empty ) {
	return $empty;
} );
