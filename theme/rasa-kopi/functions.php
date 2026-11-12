<?php
/**
 * Rasa Kopi theme bootstrap.
 *
 * Port dari theme Shopify Dawn-based "Rasa Commerce Revisi".
 * Seluruh CSS dari Shopify di-reuse apa adanya (pure, tanpa Liquid).
 * Markup PHP mengikuti class convention Dawn agar CSS menempel.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'RASA_VERSION', '1.0.2' );
define( 'RASA_DIR', get_template_directory() );
define( 'RASA_URI', get_template_directory_uri() );

/* ------------------------------------------------------------------ *
 * Language helper (cookie rasa_lang + integrasi TranslatePress bila aktif)
 * ------------------------------------------------------------------ */
function rasa_current_lang() {
	if ( isset( $_COOKIE['rasa_lang'] ) && in_array( $_COOKIE['rasa_lang'], array( 'de', 'en' ), true ) ) {
		return $_COOKIE['rasa_lang'];
	}
	$req = isset( $_SERVER['REQUEST_URI'] ) ? (string) wp_unslash( $_SERVER['REQUEST_URI'] ) : '';
	if ( strpos( $req, '/en/' ) === 0 || $req === '/en' ) {
		return 'en';
	}
	return 'de';
}

function rasa_handle_locale_switch() {
	if ( $_SERVER['REQUEST_METHOD'] !== 'POST' || empty( $_POST['locale_code'] ) ) {
		return;
	}
	$lang = sanitize_text_field( wp_unslash( $_POST['locale_code'] ) );
	if ( ! in_array( $lang, array( 'de', 'en' ), true ) ) {
		return;
	}
	setcookie( 'rasa_lang', $lang, time() + 30 * DAY_IN_SECONDS, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, is_ssl(), false );
	$_COOKIE['rasa_lang'] = $lang;

	$referer = wp_get_referer() ?: home_url( '/' );
	// Jika TranslatePress aktif, arahkan ke prefix /en/ atau root /
	if ( class_exists( 'TRP_Translate_Press' ) ) {
		$path = wp_parse_url( $referer, PHP_URL_PATH ) ?: '/';
		$path = preg_replace( '#^/en(/|$)#', '/', $path );
		if ( $lang === 'en' ) {
			$referer = home_url( '/en' . ( $path === '/' ? '/' : $path ) );
		} else {
			$referer = home_url( $path );
		}
	}
	wp_safe_redirect( $referer );
	exit;
}
add_action( 'init', 'rasa_handle_locale_switch' );

/* ------------------------------------------------------------------ *
 * Setup
 * ------------------------------------------------------------------ */
function rasa_setup() {
	load_theme_textdomain( 'rasa-kopi', RASA_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'custom-logo', array(
		'height'      => 80,
		'width'       => 300,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );

	// WooCommerce
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	// Content width 1200 (page_width setting dari Shopify)
	$GLOBALS['content_width'] = 1200;

	// Menu
	register_nav_menus( array(
		'primary'      => __( 'Menu utama (Header)', 'rasa-kopi' ),
		'footer-rasa'  => __( 'Footer: Rasa Kopi', 'rasa-kopi' ),
		'footer-legal' => __( 'Footer: Rechtliches (Legal)', 'rasa-kopi' ),
	) );

	// Woo gallery
	add_theme_support( 'wc-product-gallery-lightbox' );

	// Image sizes (Shopify card uses 533/940 widths)
	add_image_size( 'rasa-card', 600, 600, false );
	add_image_size( 'rasa-card-large', 940, 940, false );
}
add_action( 'after_setup_theme', 'rasa_setup' );

/* ------------------------------------------------------------------ *
 * Enqueue: semua CSS Shopify + fonts + tokens
 * ------------------------------------------------------------------ */
function rasa_assets() {
	$css = glob( RASA_DIR . '/assets/css/*.css' );
	usort( $css, function ( $a, $b ) {
		// base.css duluan, sisanya alfabetis
		$rank = function ( $f ) {
			$n = basename( $f );
			if ( $n === 'base.css' ) return 0;
			if ( strpos( $n, 'component-' ) === 0 ) return 1;
			if ( strpos( $n, 'quick-order' ) === 0 ) return 1;
			return 2;
		};
		return [ $rank( $a ), $a ] <=> [ $rank( $b ), $b ];
	} );

	$base = RASA_URI . '/assets/css/';

	// 1. Design tokens (CSS variables scheme + :root spacing)
	wp_enqueue_style( 'rasa-tokens', RASA_URI . '/inc/tokens.css', array(), RASA_VERSION );

	// 2. Font faces (self-hosted woff2: Bai Jamjuree, Cinzel, Cormorant Garamond)
	wp_enqueue_style( 'rasa-fonts', RASA_URI . '/inc/fonts.css', array( 'rasa-tokens' ), RASA_VERSION );

	// 3. Seluruh CSS komponen Shopify (pure CSS, faithful)
	//    SKIP template-giftcard.css — di Dawn asli cuma load di halaman giftcard;
	//    isinya `body{max-width:var(--page-width);margin:0 auto}` + reset `:empty`
	//    yang bocor global & bikin layout "kayak iframe" (container 1200px centered).
	$deps = array( 'rasa-fonts' );
	$i    = 0;
	foreach ( $css as $f ) {
		if ( basename( $f ) === 'template-giftcard.css' ) {
			continue;
		}
		$handle = 'rasa-css-' . str_replace( array( '.css', '-' ), array( '', '-' ), basename( $f ) );
		wp_enqueue_style( $handle, $base . basename( $f ), $deps, RASA_VERSION );
		$deps = array( $handle );
		$i++;
	}

	// 4. Overlays & overrides theme rasa (marquee, legal, menu fixes)
	wp_enqueue_style( 'rasa-overrides', RASA_URI . '/assets/css/rasa-overrides.css', $deps, RASA_VERSION );

	// 5. Theme base (style.css, wajib di-enqueue utk WP)
	wp_enqueue_style( 'rasa-style', get_stylesheet_uri(), array( 'rasa-overrides' ), RASA_VERSION );

	// 6. JS — rantai sesuai urutan theme.liquid Dawn (defer via in_footer)
	$base_js  = RASA_URI . '/assets/js/';
	$js_order = array(
		'rasa-shim',                 // shim Shopify/StandardEvents + routes/strings
		'constants',
		'pubsub',
		'global',
		'cart-disclosure-modal',
		'cart-disclosure-tooltip',
		'details-disclosure',
		'details-modal',
		'search-form',
		'standard-actions-override',
		'animations',
		'localization-form',
		'predictive-search',
		'cart-drawer',
		'cart',
		'quantity-popover',
		'product-form',
		'product-info',
		'product-model',
		'media-gallery',
		'magnify',
		'quick-add',
		'show-more',
		'product-spotlight',
		'rasa-coming-soon',
	);
	$dep = array();
	foreach ( $js_order as $i => $file ) {
		$handle = 'rasa-js-' . $file;
		wp_enqueue_script( $handle, $base_js . $file . '.js', $dep, RASA_VERSION, true );
		$dep = array( $handle );
	}

	// 7. Localize: URL penting utk JS
	wp_localize_script( 'rasa-js-global', 'RasaTheme', array(
		'homeUrl'      => home_url( '/' ),
		'ajaxUrl'      => admin_url( 'admin-ajax.php' ),
		'cartUrl'      => function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : '#',
		'checkoutUrl'  => function_exists( 'wc_get_checkout_url' ) ? wc_get_checkout_url() : '#',
		'nonce'        => wp_create_nonce( 'rasa-cart' ),
		'i18n'         => array(
			'addedToCart' => __( 'Ditambahkan ke keranjang', 'rasa-kopi' ),
			'soldOut'     => __( 'Sold out', 'rasa-kopi' ),
			'viewCart'    => __( 'Lihat keranjang', 'rasa-kopi' ),
		),
	) );
}
add_action( 'wp_enqueue_scripts', 'rasa_assets' );

/* ------------------------------------------------------------------ *
 * Strings + routes global (persis blok <script> di theme.liquid, DE)
 * ------------------------------------------------------------------ */
function rasa_global_strings() {
	if ( is_admin() ) return;
	$home = esc_url( home_url( '/' ) );
	?>
<script>
	window.shopUrl = '<?php echo esc_js( home_url() ); ?>';
	window.routes = {
		cart_add_url: '<?php echo esc_js( home_url( '/cart/add' ) ); ?>',
		cart_change_url: '<?php echo esc_js( home_url( '/cart/change' ) ); ?>',
		cart_update_url: '<?php echo esc_js( home_url( '/cart/update' ) ); ?>',
		cart_url: '<?php echo esc_js( wc_get_cart_url() ); ?>',
		predictive_search_url: '<?php echo esc_js( home_url( '/search/suggest' ) ); ?>',
	};

	window.cartStrings = {
		error: `Beim Aktualisieren deines Warenkorbs ist ein Fehler aufgetreten. Bitte versuche es erneut.`,
		quantityError: `Du kannst deinem Warenkorb nur [quantity] Stück dieses Artikels hinzufügen.`,
	};

	window.variantStrings = {
		addToCart: `In den Warenkorb legen`,
		soldOut: `Ausverkauft`,
		unavailable: `Nicht verfügbar`,
		unavailable_with_option: `Die Auswahl [value] ist nicht verfügbar.`,
	};

	window.quickOrderListStrings = {
		itemsAdded: `[quantity] Artikel wurden zum Warenkorb hinzugefügt.`,
		itemAdded: `1 Artikel wurde zum Warenkorb hinzugefügt.`,
		itemsRemoved: `[quantity] Artikel wurden aus dem Warenkorb entfernt.`,
		itemRemoved: `1 Artikel wurde aus dem Warenkorb entfernt.`,
		viewCart: `Warenkorb ansehen`,
		each: `[money] / Artikel`,
		min_error: `Mindestens [min] Stück dieses Artikels erforderlich.`,
		max_error: `Höchstens [max] Stück dieses Artikels erlaubt.`,
		step_error: `Du kannst diesen Artikel nur in Abstufungen von [step] hinzufügen.`,
	};

	window.accessibilityStrings = {
		imageAvailable: `Bild [index] ist nun in der Galerieansicht verfügbar`,
		shareSuccess: `Link in die Zwischenablage kopiert`,
		pauseSlideshow: `Slideshow pausieren`,
		playSlideshow: `Slideshow abspielen`,
		recipientFormExpanded: `Empfänger-Formular erweitert`,
		recipientFormCollapsed: `Empfänger-Formular zusammengeklappt`,
		countrySelectorSearchCount: `[count] Länder gefunden`,
	};
</script>
	<?php
}
add_action( 'wp_head', 'rasa_global_strings', 5 );

/* ------------------------------------------------------------------ *
 * Helper: CSS class color-scheme (Dawn convention)
 * ------------------------------------------------------------------ */
function rasa_scheme_class( $scheme = 'scheme-1' ) {
	return 'color-' . sanitize_html_class( $scheme ) . ' gradient';
}

/* ------------------------------------------------------------------ *
 * Helper: price format
 * ------------------------------------------------------------------ */
function rasa_price( $price, $echo = true ) {
	$out = wc_price( $price );
	return $echo ? $out : $out;
}

/* ------------------------------------------------------------------ *
 * Announcement bar: lihat rasa_announcements() di inc/template-tags.php
 * ------------------------------------------------------------------ */

/* ------------------------------------------------------------------ *
 * Menu utama (fallback statis: header-group.json = hauptmenue-partner)
 * ------------------------------------------------------------------ */
function rasa_main_menu() {
	return array(
		array( 'label' => 'Kaffee',       'url' => home_url( '/shop/kaffee/' ) ),
		array( 'label' => 'Zubehör',      'url' => home_url( '/shop/zubehoer/' ) ),
		array( 'label' => 'Alle Produkte','url' => home_url( '/shop/' ) ),
		array( 'label' => 'Für Unternehmen','url' => home_url( '/' ) . '#b2b' ),
	);
}

/* ------------------------------------------------------------------ *
 * Breadcrumbs ringan
 * ------------------------------------------------------------------ */
function rasa_breadcrumbs() {
	if ( is_front_page() ) return;
	echo '<nav class="breadcrumbs" aria-label="Breadcrumb"><div class="page-width"><div class="list-breadcrumbs">';
	echo '<span class="h6">' . esc_html__( 'Zurück', 'rasa-kopi' ) . '</span>';
	echo '</div></div></nav>';
}

/* ------------------------------------------------------------------ *
 * Cart count
 * ------------------------------------------------------------------ */
function rasa_cart_count() {
	if ( ! function_exists( 'WC' ) ) return 0;
	return WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
}

/* ------------------------------------------------------------------ *
 * AJAX: add to cart (bridge utk rasa-theme.js)
 * ------------------------------------------------------------------ */
add_action( 'wp_ajax_rasa_add_to_cart', 'rasa_ajax_add_to_cart' );
add_action( 'wp_ajax_nopriv_rasa_add_to_cart', 'rasa_ajax_add_to_cart' );
function rasa_ajax_add_to_cart() {
	check_ajax_referer( 'rasa-cart', 'nonce' );
	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		wp_send_json_error( array( 'message' => 'WooCommerce tidak aktif.' ) );
	}
	$product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
	$qty        = isset( $_POST['quantity'] ) ? max( 1, absint( $_POST['quantity'] ) ) : 1;
	$variation  = isset( $_POST['variation'] ) ? wp_unslash( $_POST['variation'] ) : array();

	$added = WC()->cart->add_to_cart( $product_id, $qty, 0, $variation );
	if ( ! $added ) {
		wp_send_json_error( array( 'message' => 'Produk tidak bisa ditambahkan.' ) );
	}
	wp_send_json_success( array(
		'count'  => WC()->cart->get_cart_contents_count(),
		'total'  => WC()->cart->get_cart_subtotal(),
		'cartUrl'=> wc_get_cart_url(),
	) );
}

/* ------------------------------------------------------------------ *
 * Body class
 * ------------------------------------------------------------------ */
function rasa_body_class( $classes ) {
	$classes[] = 'color-scheme-1';
	$classes[] = 'page-width-constrain';
	return $classes;
}
add_filter( 'body_class', 'rasa_body_class' );

/* ------------------------------------------------------------------ *
 * Include inc/
 * ------------------------------------------------------------------ */
require_once RASA_DIR . '/inc/woo-compat.php';
require_once RASA_DIR . '/inc/template-tags.php';
