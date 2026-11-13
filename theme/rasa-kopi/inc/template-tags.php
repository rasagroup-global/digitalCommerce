<?php
/**
 * Template tags — menghasilkan markup HTML yang FAITHFUL dengan theme Shopify
 * (class convention Dawn) dari data WordPress/WooCommerce.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* ------------------------------------------------------------------ *
 * Icons (inline SVG dari assets)
 * ------------------------------------------------------------------ */
function rasa_icon( $name, $class = 'svg-wrapper' ) {
	$svg = rasa_icon_raw( $name );
	if ( '' === $svg ) return '';
	return '<span class="' . esc_attr( $class ) . '">' . $svg . '</span>';
}

function rasa_icon_raw( $name ) {
	$file = RASA_DIR . '/assets/img/icon-' . $name . '.svg';
	if ( ! file_exists( $file ) ) return '';
	$svg = file_get_contents( $file );
	$svg = preg_replace( '/^\s*<!--.*?-->/s', '', $svg );
	// pastikan ada class svg-icon di svg root (jika belum ada)
	if ( ! preg_match( '/class\s*=\s*"/', $svg ) ) {
		$svg = preg_replace( '/<svg/', '<svg class="svg-icon" aria-hidden="true" focusable="false"', $svg, 1 );
	}
	return $svg;
}

/**
 * Announcement bar items (live Shopify, DE).
 */
function rasa_announcements() {
	// Persis live shop.rasakopi.coffee (rasa-marquee, 3 pesan)
	$items = array(
		array( 'text' => rasa_tr( 'Kostenloser Versand ab 59 € in Deutschland', 'Free shipping over €59 in Germany' ), 'link' => home_url( '/versand-zahlung/' ) ),
		array( 'text' => rasa_tr( 'Lieferung in 3–7 Werktagen', 'Delivery in 3–7 business days' ), 'link' => home_url( '/versand-zahlung/' ) ),
		array( 'text' => rasa_tr( 'Sicher bezahlen mit PayPal, Klarna, Apple Pay & Google Pay', 'Secure payment with PayPal, Klarna, Apple Pay & Google Pay' ), 'link' => '' ),
	);
	// override dari customizer
	$custom = get_theme_mod( 'rasa_announcements', array() );
	if ( is_array( $custom ) && ! empty( $custom ) ) return $custom;
	return $items;
}

/* ------------------------------------------------------------------ *
 * Price (markup Dawn price.liquid — output live persis)
 * ------------------------------------------------------------------ */
function rasa_price_html( $product, $args = array() ) {
	if ( ! ( $product instanceof WC_Product ) ) return '';
	$show_compare  = ! empty( $args['show_compare_at_price'] );
	$on_sale       = $product->is_on_sale();

	// Format harga persis Shopify live: "€79,00 EUR"
	$fmt = function ( $v ) {
		return '<span class="woocommerce-Price-amount amount"><bdi>&euro;' . number_format( (float) $v, 2, ',', '.' ) . ' EUR</bdi></span>';
	};

	// Variable product: parent price kosong — pakai "Von" (min variation price), persis Dawn
	// NOTE: num_variations() tidak ada di Woo 11.x — pakai get_children()
	$price_html = '';
	if ( $product->is_type( 'variable' ) && count( $product->get_children() ) > 0 ) {
		$min = (float) $product->get_variation_price( 'min' );
		$max = (float) $product->get_variation_price( 'max' );
		if ( $min > 0 && $min === $max ) {
			$price_html = $fmt( $min );
		} elseif ( $min > 0 ) {
			$price_html = esc_html( rasa_tr( 'Von ', 'From ' ) ) . $fmt( $min );
		} else {
			$price_html = '';
		}
	} else {
		$regular = (float) $product->get_regular_price();
		$sale    = $product->get_sale_price() !== '' ? (float) $product->get_sale_price() : 0;
		$price   = $sale > 0 ? $sale : $regular;
		if ( $on_sale && $show_compare ) {
			$price_html = '<span class="visually-hidden visually-hidden--inline">' . esc_html( rasa_tr( 'Normaler Preis', 'Regular price' ) ) . '</span>'
				. '<span><s class="price-item price-item--regular">' . wp_kses_post( $fmt( $regular ) ) . '</s></span>'
				. '<span class="visually-hidden visually-hidden--inline">' . esc_html( rasa_tr( 'Verkaufspreis', 'Sale price' ) ) . '</span>'
				. '<span class="price-item price-item--sale price-item--last">' . wp_kses_post( $fmt( $sale ) ) . '</span>';
		} else {
			$price_html = '<span class="visually-hidden visually-hidden--inline">' . esc_html( rasa_tr( 'Normaler Preis', 'Regular price' ) ) . '</span>'
				. '<span class="price-item price-item--regular price-item--last">' . wp_kses_post( $fmt( $price ) ) . '</span>';
		}
	}

	if ( $price_html === '' ) return '';

	ob_start();
	?>
	<div class="price">
		<div class="price__container">
			<div class="price__regular">
				<span class="price-item price-item--regular">
					<?php echo wp_kses_post( $price_html ); ?>
				</span>
			</div>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

/* ------------------------------------------------------------------ *
 * Product card (markup card-product.liquid — output live persis)
 * ------------------------------------------------------------------ */
function rasa_product_card( $product, $args = array() ) {
	if ( ! ( $product instanceof WC_Product ) ) return '';
	$a = wp_parse_args( $args, array(
		'section_id' => 'template',
	) );

	$images   = array_merge( array( $product->get_image_id() ), $product->get_gallery_image_ids() );
	$images   = array_values( array_filter( $images ) );
	$title    = $product->get_name();
	$url      = $product->get_permalink();
	$on_sale  = $product->is_on_sale();
	$out_of_stock = ! $product->is_in_stock();
	$sizes    = '(min-width: 1200px) 267px, (min-width: 990px) calc((100vw - 130px) / 4), (min-width: 750px) calc((100vw - 120px) / 3), calc((100vw - 35px) / 2)';

	// ratio dari gambar pertama (w/h). wp_get_attachment_image_src => [0]=url,[1]=w,[2]=h,[3]=is_intermediate
	$w = 1300; $h = 1600;
	if ( $images ) {
		$src = wp_get_attachment_image_src( $images[0], 'full' );
		if ( $src && ! empty( $src[1] ) ) {
			$w = (int) $src[1];
			$h = (int) $src[2];
		}
	}
	$w = max( 1, $w ); $h = max( 1, $h );
	$ratio_pct = ( $h / $w * 100 );

	$badge_class = '';
	if ( $out_of_stock )      $badge_class = ' color-scheme-3';
	elseif ( $on_sale )       $badge_class = ' color-scheme-4';

	$badge_text = '';
	if ( $out_of_stock )      $badge_text = 'Ausverkauft';
	elseif ( $on_sale )       $badge_text = 'Sale';

	/**
	 * Helper: srcset + src untuk 1 attachment id.
	 */
	$srcset_for = function ( $img_id ) {
		if ( empty( $img_id ) ) return array( 'srcset' => '', 'src' => '', 'alt' => '', 'w' => 600, 'h' => 738 );
		$card = wp_get_attachment_image_src( $img_id, 'rasa-card' );
		$full = wp_get_attachment_image_src( $img_id, 'full' );
		$primary = $card ?: $full;
		$srcset = wp_get_attachment_image_srcset( $img_id, 'rasa-card' );
		if ( ! $srcset && $primary ) {
			$srcset = $primary[0] . ' ' . (int) $primary[1] . 'w';
		}
		return array(
			'srcset' => (string) $srcset,
			'src'    => $primary ? $primary[0] : '',
			'alt'    => trim( (string) wp_get_attachment_caption( $img_id ) ) ?: get_post_meta( $img_id, '_wp_attachment_image_alt', true ),
			'w'      => $primary ? (int) $primary[1] : 600,
			'h'      => $primary ? (int) $primary[2] : 738,
		);
	};

	ob_start();
	echo '<div class="card-wrapper product-card-wrapper underline-links-hover">';
	echo '<div class="card card--card card--media color-scheme-2 gradient" style="--ratio-percent: ' . esc_attr( $ratio_pct ) . '%;">';
	echo '<div class="card__inner ratio" style="--ratio-percent: ' . esc_attr( $ratio_pct ) . '%;">';
		echo '<div class="card__media">';
		if ( count( $images ) > 1 ) {
			echo '<card-slider class="card-slider">';
			echo '<a href="' . esc_url( $url ) . '" class="card-slider__link" tabindex="-1" aria-hidden="true">';
			echo '<div class="card-slider__track">';
			foreach ( array_slice( $images, 0, 8 ) as $i => $img_id ) {
				$img = $srcset_for( $img_id );
				echo '<div class="card-slider__slide" data-index="' . $i . '">';
				echo '<img srcset="' . esc_attr( $img['srcset'] ) . '" src="' . esc_url( $img['src'] ) . '" sizes="' . esc_attr( $sizes ) . '" alt="' . esc_attr( $img['alt'] ) . '" class="motion-reduce" loading="lazy" width="' . (int) $img['w'] . '" height="' . (int) $img['h'] . '">';
				echo '</div>';
			}
			echo '</div></a>';
			echo '<button type="button" class="card-slider__btn card-slider__btn--prev" aria-label="Vorheriges Bild"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg></button>';
			echo '<button type="button" class="card-slider__btn card-slider__btn--next" aria-label="Nächstes Bild"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg></button>';
			echo '</card-slider>';
		} else {
			$img = isset( $images[0] ) ? $srcset_for( $images[0] ) : null;
			echo '<div class="media media--transparent media--hover-effect">';
			if ( $img ) {
				echo '<img srcset="' . esc_attr( $img['srcset'] ) . '" src="' . esc_url( $img['src'] ) . '" sizes="' . esc_attr( $sizes ) . '" alt="' . esc_attr( $img['alt'] ) . '" class="motion-reduce" loading="lazy" width="' . (int) $img['w'] . '" height="' . (int) $img['h'] . '">';
			} else {
				echo '<div class="card__media-placeholder" aria-hidden="true"></div>';
			}
			echo '</div>';
		}
		echo '</div>';

	// card__content #1 (heading + badge, versi no-media)
	echo '<div class="card__content">';
	echo '<div class="card__information">';
	echo '<h3 class="card__heading">';
	echo '<a href="' . esc_url( $url ) . '" class="full-unstyled-link">' . esc_html( $title ) . '</a>';
	echo '</h3></div>';
	echo '<div class="card__badge bottom left"></div>';
	echo '</div>'; // .card__content #1
	echo '</div>'; // .card__inner

	// card__content #2 (heading + info + price — sibling dari .card__inner)
	echo '<div class="card__content">';
	echo '<div class="card__information">';
	echo '<h3 class="card__heading h5">';
	echo '<a href="' . esc_url( $url ) . '" class="full-unstyled-link">' . esc_html( $title ) . '</a>';
	echo '</h3>';
	echo '<div class="card-information"><span class="caption-large light"></span>';
	echo rasa_price_html( $product, array( 'show_compare_at_price' => true ) );
	echo '</div></div>';
	echo '<div class="card__badge bottom left"></div>';
	echo '</div>'; // .card__content #2

	echo '</div></div>'; // .card + .card-wrapper
	return ob_get_clean();
}

/* ------------------------------------------------------------------ *
 * Featured collection section (markup featured-collection.liquid)
 * ------------------------------------------------------------------ */
function rasa_featured_collection( $args = array() ) {
	$a = wp_parse_args( $args, array(
		'section_id'        => 'featured_collection',
		'collection'        => '',
		'title'             => '',
		'products_to_show'  => 4,
		'columns_desktop'   => 4,
		'columns_mobile'    => 2,
		'show_view_all'     => true,
		'view_all_label'    => 'Alle ansehen',
		'padding_top'       => 44,
		'padding_bottom'    => 36,
		'color_scheme'      => 'scheme-2',
		'order'             => 'DESC',
	) );

	if ( ! $a['collection'] ) return;
	$cat = get_term_by( 'slug', $a['collection'], 'product_cat' );
	$cat_id = $cat ? $cat->term_id : 0;
	$products = $cat_id ? rasa_get_products_by_cat( $cat_id, $a['products_to_show'], $a['order'] ) : array();

	ob_start();
	?>
	<div class="color-<?php echo esc_attr( $a['color_scheme'] ); ?> isolate gradient">
		<div class="collection section-<?php echo esc_attr( $a['section_id'] ); ?>-padding">
			<style>
				.section-<?php echo esc_attr( $a['section_id'] ); ?>-padding {
					padding-top: <?php echo (int) round( $a['padding_top'] * 0.75 ); ?>px;
					padding-bottom: <?php echo (int) round( $a['padding_bottom'] * 0.75 ); ?>px;
				}
				@media screen and (min-width: 750px) {
					.section-<?php echo esc_attr( $a['section_id'] ); ?>-padding {
						padding-top: <?php echo (int) $a['padding_top']; ?>px;
						padding-bottom: <?php echo (int) $a['padding_bottom']; ?>px;
					}
				}
			</style>
			<div class="collection__title title-wrapper title-wrapper--no-top-margin page-width">
				<div class="collection__title-text scroll-trigger animate--slide-in">
					<h2 class="title inline-richtext h2"><?php echo wp_kses_post( $a['title'] ); ?></h2>
				</div>
				<?php if ( $a['show_view_all'] && $cat_id > 0 ) : ?>
				<div class="collection__view-all-top">
					<a href="<?php echo esc_url( get_term_link( $cat_id, 'product_cat' ) ); ?>" class="link underlined-link">
						<?php echo esc_html( $a['view_all_label'] ); ?>
						<?php echo rasa_icon( 'arrow', 'icon-wrap' ); ?>
					</a>
				</div>
				<?php endif; ?>
			</div>
			<div class="page-width-desktop">
				<div class="grid product-grid contains-card contains-card--product grid--<?php echo (int) $a['columns_desktop']; ?>-col-desktop grid--<?php echo (int) $a['columns_mobile']; ?>-col-tablet-down">
					<?php if ( $products ) : ?>
						<?php foreach ( $products as $idx => $p ) : ?>
							<div class="grid__item scroll-trigger animate--slide-in" data-cascade style="--animation-order: <?php echo (int) ( $idx + 1 ); ?>;">
								<?php echo rasa_product_card( $p, array( 'section_id' => $a['section_id'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							</div>
						<?php endforeach; ?>
					<?php else : ?>
						<?php for ( $i = 0; $i < $a['products_to_show']; $i++ ) : ?>
							<div class="grid__item scroll-trigger animate--slide-in" data-cascade style="--animation-order: <?php echo (int) ( $i + 1 ); ?>;">
								<?php echo rasa_product_card_placeholder(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							</div>
						<?php endfor; ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Placeholder card (produk belum diimport) — tampil seperti card Shopify.
 */
function rasa_product_card_placeholder() {
	ob_start();
	?>
	<div class="card-wrapper product-card-wrapper underline-links-hover">
		<div class="card card--card card--media color-scheme-2 gradient" style="--ratio-percent: 100%;">
			<div class="card__inner ratio" style="--ratio-percent: 100%;">
				<div class="card__media">
					<div class="media media--transparent">
						<div class="placeholder-svg placeholder" style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:rgba(var(--color-foreground),0.04);">
							<span class="visually-hidden">Bald verfügbar</span>
						</div>
					</div>
				</div>
				<div class="card__content">
					<div class="card__information">
						<h3 class="card__heading"><a class="full-unstyled-link" href="#">Bald im Shop</a></h3>
					</div>
					<div class="card__badge bottom left"></div>
				</div>
			</div>
			<div class="card__content">
				<div class="card__information">
					<h3 class="card__heading h5"><a class="full-unstyled-link" href="#">Bald im Shop</a></h3>
					<div class="card-information"><span class="caption-large light"></span></div>
				</div>
				<div class="card__badge bottom left"></div>
			</div>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Ambil produk dari category (limit). $cat bisa slug atau term_id.
 * NOTE: wc_get_products 'category' param = SLUG (bukan term_id).
 */
function rasa_get_products_by_cat( $cat, $limit = 4, $order = 'DESC' ) {
	if ( ! function_exists( 'WC' ) ) return array();
	$slug = is_numeric( $cat ) ? get_term_field( 'slug', (int) $cat, 'product_cat' ) : $cat;
	$loop = wc_get_products( array(
		'category'  => (string) $slug,
		'status'    => 'publish',
		'limit'     => (int) $limit,
		'orderby'   => 'date',
		'order'     => strtoupper( $order ) === 'ASC' ? 'ASC' : 'DESC',
		'return'    => 'objects',
	) );
	return is_array( $loop ) ? $loop : array();
}

/* ------------------------------------------------------------------ *
 * Free shipping progress bar (rasa-free-shipping.liquid)
 * Threshold DE 59,00 € / AT 89,00 €
 * ------------------------------------------------------------------ */
function rasa_free_shipping_bar( $cart_total = null, $country = 'DE' ) {
	if ( null === $cart_total && function_exists( 'WC' ) && WC()->cart ) {
		$cart_total = (float) WC()->cart->get_subtotal_excluding_tax();
	}
	if ( $cart_total <= 0 ) return;
	$threshold = ( $country === 'AT' ) ? 89.0 : 59.0;
	$missing   = $threshold - $cart_total;
	$pct       = min( 100, $cart_total / $threshold * 100 );

	$currency = get_woocommerce_currency();
	$money = function ( $v ) use ( $currency ) {
		return wc_price( $v ) . ' ' . $currency;
	};

	ob_start();
	?>
	<div class="rasa-ship" role="status">
		<p class="rasa-ship__text">
			<?php if ( $missing > 0 ) : ?>
				Noch <strong><?php echo wp_kses_post( $money( $missing ) ); ?></strong> bis zum <strong>kostenlosen Versand</strong>
			<?php else : ?>
				✓ Dein Versand ist <strong>kostenlos</strong>
			<?php endif; ?>
		</p>
		<div class="rasa-ship__track" aria-hidden="true">
			<div class="rasa-ship__fill<?php echo $missing <= 0 ? ' rasa-ship__fill--done' : ''; ?>" style="width: <?php echo esc_attr( $pct ); ?>%"></div>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

/* ------------------------------------------------------------------ *
 * Menus — dari WP nav menu, fallback statis (menu live Shopify)
 * ------------------------------------------------------------------ */
function rasa_main_menu_items() {
	$items = wp_get_nav_menu_items( 'primary' );
	if ( $items ) {
		$out = array();
		foreach ( $items as $it ) {
			$out[] = array(
				'title' => $it->title,
				'url'   => $it->url,
				'children' => rasa_child_menu_items( $it ),
				'current' => rasa_is_current( $it ),
			);
		}
		return $out;
	}
	// Fallback: menu live Shopify
	return array(
		array( 'title' => rasa_tr( 'Startseite', 'Home' ), 'url' => home_url( '/' ), 'children' => array(), 'current' => is_front_page() ),
		array( 'title' => rasa_tr( 'Produkte', 'Products' ), 'url' => home_url( '/shop/' ), 'children' => array(), 'current' => function_exists( 'is_shop' ) && is_shop() || is_product() || is_product_category() ),
		array( 'title' => rasa_tr( 'Kontakt', 'Contact' ), 'url' => home_url( '/kontakt/' ), 'children' => array(), 'current' => is_page( 'kontakt' ) ),
		array( 'title' => 'B2B',        'url' => home_url( '/#b2b' ), 'children' => array(), 'current' => false ),
	);
}

function rasa_child_menu_items( $parent ) {
	$all = wp_get_nav_menu_items( 'primary' );
	$children = array();
	if ( $all ) {
		foreach ( $all as $it ) {
			if ( $it->menu_item_parent == $parent->ID && $it->type == 'custom' ) {
				$children[] = array( 'title' => $it->title, 'url' => $it->url, 'current' => rasa_is_current( $it ) );
			}
		}
	}
	return $children;
}

function rasa_is_current( $item ) {
	if ( $item->type == 'post_type' && $item->object_id && is_singular( array( $item->object ) ) && (int) $item->object_id === (int) get_the_ID() ) return true;
	if ( $item->type == 'custom' && $item->url && $item->url === home_url( add_query_arg( array(), $GLOBALS['wp']->request ?? '' ) ) ) return true;
	return false;
}

function rasa_footer_menu_items( $location ) {
	$items = wp_get_nav_menu_items( $location );
	if ( $items ) {
		$out = array();
		foreach ( $items as $it ) {
			$out[] = array( 'title' => $it->title, 'url' => $it->url );
		}
		return $out;
	}
	if ( $location === 'footer-rasa' ) {
		return array(
			array( 'title' => rasa_tr( 'Partner-Röstereien', 'Partner Roasteries' ), 'url' => home_url( '/partner-roestereien/' ) ),
			array( 'title' => 'B2B', 'url' => home_url( '/#b2b' ) ),
		);
	}
	// footer-legal fallback (menu live)
	return array(
		array( 'title' => rasa_tr( 'Impressum', 'Imprint' ), 'url' => home_url( '/impressum/' ) ),
		array( 'title' => rasa_tr( 'AGB', 'Terms & Conditions' ), 'url' => home_url( '/agb/' ) ),
		array( 'title' => rasa_tr( 'Widerrufsbelehrung', 'Cancellation Policy' ), 'url' => home_url( '/widerrufsbelehrung/' ) ),
		array( 'title' => rasa_tr( 'Datenschutz', 'Privacy Policy' ), 'url' => home_url( '/datenschutz/' ) ),
		array( 'title' => rasa_tr( 'Versand & Zahlung', 'Shipping & Payment' ), 'url' => home_url( '/versand-zahlung/' ) ),
		array( 'title' => rasa_tr( 'Kontakt', 'Contact' ), 'url' => home_url( '/kontakt/' ) ),
		array( 'title' => 'FAQ', 'url' => home_url( '/faq/' ) ),
		array( 'title' => rasa_tr( 'Cookie-Einstellungen', 'Cookie Settings' ), 'url' => '#cookie-einstellungen' ),
	);
}
