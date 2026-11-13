<?php
/**
 * woocommerce/single-product.php — Dawn Single Product Page (/product/...)
 * Match 1:1 dengan https://shop.rasakopi.coffee/products/...
 */
if ( ! defined( 'ABSPATH' ) ) exit;

get_header();

while ( have_posts() ) :
	the_post();
	global $product;
	if ( ! ( $product instanceof WC_Product ) ) {
		$product = wc_get_product( get_the_ID() );
	}
	if ( ! $product ) continue;

	$images = array_merge( array( $product->get_image_id() ), $product->get_gallery_image_ids() );
	$images = array_values( array_filter( $images ) );

	$is_variable = $product->is_type( 'variable' );
	$variations  = $is_variable ? $product->get_available_variations() : array();
	$attributes  = $is_variable ? $product->get_variation_attributes() : array();
	$default_attrs = $is_variable ? $product->get_default_attributes() : array();

	$shipping_url = home_url( '/versand-zahlung/' );
	?>
	<section class="page-width section-main-product" style="padding-top: 36px; padding-bottom: 48px;">
		<?php wc_print_notices(); ?>

		<div class="product product--large product--left product--stacked grid grid--1-col grid--2-col-tablet">
			<!-- Left Column: Stacked Gallery (65%) -->
			<div class="grid__item product__media-wrapper" style="flex: 0 0 65%; max-width: 65%;">
				<div class="rasa-pdp-gallery" style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:8px;">
					<?php foreach ( $images as $idx => $img_id ) :
						$src = wp_get_attachment_image_url( $img_id, 'large' ) ?: wp_get_attachment_image_url( $img_id, 'full' );
						$full = wp_get_attachment_image_url( $img_id, 'full' );
						$alt = get_post_meta( $img_id, '_wp_attachment_image_alt', true ) ?: $product->get_name();
						$span = ( 0 === $idx ) ? 'grid-column:1 / -1;' : '';
						$anim_cls = ( 0 === $idx ) ? 'scroll-trigger animate--fade-in' : 'scroll-trigger animate--slide-in';
						$anim_attr = ( 0 === $idx ) ? '' : ' data-cascade';
						?>
						<div class="rasa-pdp-gallery__item <?php echo esc_attr( $anim_cls ); ?>"<?php echo $anim_attr; ?> style="<?php echo esc_attr( $span ); ?>background:#efeee9;overflow:hidden;--animation-order:<?php echo (int) $idx; ?>;">
							<a href="<?php echo esc_url( $full ); ?>" target="_blank" rel="noopener">
								<img src="<?php echo esc_url( $src ); ?>" alt="<?php echo esc_attr( $alt ); ?>" style="width:100%;height:auto;display:block;object-fit:cover;" <?php echo 0 === $idx ? 'fetchpriority="high"' : 'loading="lazy"'; ?>>
							</a>
						</div>
					<?php endforeach; ?>
				</div>
			</div>

			<!-- Right Column: Sticky Product Info (35%) -->
			<div class="product__info-wrapper grid__item" style="flex: 1 1 35%; max-width: 35%; padding-left: 5rem;">
				<section class="product__info-container product__column-sticky scroll-trigger animate--slide-in">
					<p style="margin:0 0 .4rem;font-size:1.2rem;letter-spacing:.12em;text-transform:uppercase;color:rgba(18,18,18,.75);">Rasa Kopi</p>

					<div class="product__title">
						<h1 style="margin:0;font-size:4rem;line-height:5.2rem;font-weight:400;color:#000;"><?php the_title(); ?></h1>
					</div>

					<div class="no-js-hidden" id="price-template" role="status" style="margin:1.2rem 0 .4rem;">
						<div class="price price--large" style="font-size:1.8rem;line-height:2.7rem;letter-spacing:1.3px;color:#000;">
							<?php echo rasa_price_html( $product ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</div>
					</div>

					<div class="rasa-price-note" style="font-size:1.2rem;line-height:1.6;color:rgba(0,0,0,0.75);margin-bottom:2rem;">
						<div><?php echo esc_html( rasa_tr( 'inkl. MwSt., zzgl.', 'incl. VAT, plus' ) ); ?> <a href="<?php echo esc_url( $shipping_url ); ?>" style="color:inherit;text-decoration:underline;text-underline-offset:.3rem;"><?php echo esc_html( rasa_tr( 'Versandkosten', 'shipping' ) ); ?></a></div>
						<div><?php echo esc_html( rasa_tr( 'Lieferzeit: 3–7 Werktage', 'Delivery time: 3–7 business days' ) ); ?></div>
					</div>

					<form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype="multipart/form-data" id="RasaPdpForm">
						<?php if ( $is_variable && ! empty( $attributes ) ) : ?>
							<div class="product-form__variants" style="margin-bottom:2rem;">
								<?php foreach ( $attributes as $attr_name => $options ) :
									$label = wc_attribute_label( $attr_name );
									if ( strtolower( $label ) === 'size' || strtolower( $label ) === 'größe' ) {
										$label = rasa_tr( 'Größe', 'Size' );
									}
									$field_name = 'attribute_' . sanitize_title( $attr_name );
									$selected = $default_attrs[ sanitize_title( $attr_name ) ] ?? ( $options[0] ?? '' );
									?>
									<fieldset class="js product-form__input product-form__input--pill" style="border:0;padding:0;margin:0 0 1.6rem;">
										<legend class="form__label" style="font-size:1.3rem;margin-bottom:.8rem;color:rgba(0,0,0,0.75);"><?php echo esc_html( $label ); ?></legend>
										<div style="display:flex;flex-wrap:wrap;gap:.8rem;">
											<?php foreach ( $options as $opt ) :
												$opt_id = sanitize_title( $attr_name . '-' . $opt );
												$is_checked = ( strtolower( (string) $selected ) === strtolower( (string) $opt ) );
												?>
												<label class="rasa-pill-option" style="cursor:pointer;">
													<input
														type="radio"
														name="<?php echo esc_attr( $field_name ); ?>"
														value="<?php echo esc_attr( $opt ); ?>"
														id="<?php echo esc_attr( $opt_id ); ?>"
														<?php checked( $is_checked ); ?>
														class="rasa-pill-radio visually-hidden"
													>
													<span class="rasa-pill-label" style="display:inline-block;padding:.9rem 2rem;border-radius:4rem;font-size:1.4rem;line-height:1;border:1px solid <?php echo $is_checked ? '#000' : 'rgba(0,0,0,0.55)'; ?>;background:<?php echo $is_checked ? '#000' : '#f3f3f3'; ?>;color:<?php echo $is_checked ? '#f3f3f3' : '#000'; ?>;transition:all .15s ease;">
														<?php echo esc_html( strtoupper( $opt ) ); ?>
													</span>
												</label>
											<?php endforeach; ?>
										</div>
									</fieldset>
								<?php endforeach; ?>
								<input type="hidden" name="variation_id" id="RasaVariationId" value="">
							</div>
						<?php endif; ?>

						<div class="product-form__input product-form__quantity" style="margin-bottom:2.2rem;">
							<label class="form__label" for="RasaQty" style="display:block;font-size:1.3rem;margin-bottom:.8rem;color:rgba(0,0,0,0.75);"><?php echo esc_html( rasa_tr( 'Anzahl', 'Quantity' ) ); ?></label>
							<div class="quantity" style="width:14.2rem;height:4.7rem;display:flex;align-items:center;background:#f3f3f3;float:none;margin:0;">
								<button type="button" class="quantity__button" onclick="var i=document.getElementById('RasaQty');if(i.value>1)i.value--;" aria-label="Decrease quantity" style="width:4.5rem;height:100%;border:0;background:transparent;font-size:1.8rem;cursor:pointer;">−</button>
								<input id="RasaQty" class="quantity__input" type="number" name="quantity" min="1" value="1" style="width:100%;text-align:center;border:0;background:transparent;font-size:1.4rem;font-family:inherit;-moz-appearance:textfield;">
								<button type="button" class="quantity__button" onclick="var i=document.getElementById('RasaQty');i.value++;" aria-label="Increase quantity" style="width:4.5rem;height:100%;border:0;background:transparent;font-size:1.8rem;cursor:pointer;">+</button>
							</div>
						</div>

						<input type="hidden" name="add-to-cart" value="<?php echo (int) $product->get_id(); ?>">
						<?php if ( $is_variable ) : ?>
						<input type="hidden" name="product_id" value="<?php echo (int) $product->get_id(); ?>">
						<?php endif; ?>
						<div id="RasaPdpError" role="alert" hidden style="margin:0 0 1.2rem;padding:1rem 1.4rem;border:1px solid #c62828;background:#fff5f5;color:#b71c1c;font-size:1.3rem;line-height:1.5;"></div>
						<button type="submit" name="add-to-cart" value="<?php echo (int) $product->get_id(); ?>" class="product-form__submit button button--full-width button--primary" style="width:100%;min-height:4.9rem;background:#121212;color:#ffffff;font-size:1.5rem;letter-spacing:.1rem;border:0;cursor:pointer;">
							<?php echo esc_html( rasa_tr( 'In den Warenkorb legen', 'Add to cart' ) ); ?>
						</button>
					</form>

					<!-- EU Statutory Warranty Notice -->
					<div class="rasa-eu-notice" style="margin-top:2.4rem;padding-top:2rem;border-top:1px solid rgba(18,18,18,.12);">
						<p style="margin:0 0 1.2rem;font-size:1.4rem;font-weight:600;color:rgba(0,0,0,0.75);"><?php echo esc_html( rasa_tr( 'Gesetzliche Gewährleistung', 'Statutory Warranty' ) ); ?></p>
						<a href="https://europa.eu/youreurope/garantien" target="_blank" rel="noopener noreferrer" style="display:inline-block;">
							<img src="https://cdn.shopify.com/s/files/1/0901/3388/9407/files/EU-Gewaehrleistung-Hinweis-DE.png?v=1791724559&width=640" alt="Gesetzliche Gewährleistung" style="max-width:320px;width:100%;height:auto;border:1px solid #ddd;display:block;" loading="lazy">
						</a>
						<p style="margin:.8rem 0 0;font-size:1.3rem;color:rgba(0,0,0,0.75);"><?php echo esc_html( rasa_tr( 'Mehr zu Ihren Rechten:', 'More about your rights:' ) ); ?> <a href="https://europa.eu/youreurope/garantien" target="_blank" rel="noopener noreferrer" style="color:inherit;text-decoration:underline;">europa.eu/youreurope/garantien</a></p>
					</div>

					<!-- Product Description -->
					<div class="product__description rte quick-add-hidden" style="margin-top:2.5rem;font-size:1.6rem;line-height:2.88rem;color:rgba(0,0,0,0.75);">
						<?php the_content(); ?>
					</div>

					<!-- Share -->
					<div class="share-button" style="margin-top:2rem;">
						<button type="button" class="share-button__button" onclick="if(navigator.share){navigator.share({title:document.title,url:location.href});}else{navigator.clipboard.writeText(location.href);}" style="background:transparent;border:0;padding:0;display:inline-flex;align-items:center;gap:.6rem;font-size:1.4rem;color:#000;cursor:pointer;font-family:inherit;">
							<?php echo rasa_icon_raw( 'share' ); ?>
							<span><?php echo esc_html( rasa_tr( 'Teilen', 'Share' ) ); ?></span>
						</button>
					</div>
				</section>
			</div>
		</div>
	</section>

	<?php if ( $is_variable && ! empty( $variations ) ) : ?>
	<script>
	(function(){
		var variations = <?php echo wp_json_encode( array_map( function( $v ) {
			return array(
				'id'         => $v['variation_id'],
				'attributes' => $v['attributes'],
				'price_html' => $v['price_html'],
			);
		}, $variations ) ); ?>;
		var form = document.getElementById('RasaPdpForm');
		var varInput = document.getElementById('RasaVariationId');
		if (!form || !varInput) return;

		function sync() {
			var selected = {};
			form.querySelectorAll('.rasa-pill-radio').forEach(function(r) {
				var pill = r.nextElementSibling;
				if (r.checked) {
					selected[r.name] = r.value;
					if (pill) { pill.style.background = '#000'; pill.style.color = '#f3f3f3'; pill.style.borderColor = '#000'; }
				} else {
					if (pill) { pill.style.background = '#f3f3f3'; pill.style.color = '#000'; pill.style.borderColor = 'rgba(0,0,0,0.55)'; }
				}
			});
			var match = variations.find(function(v) {
				return Object.keys(v.attributes).every(function(k) {
					return !v.attributes[k] || (selected[k] && selected[k].toLowerCase() === v.attributes[k].toLowerCase());
				});
			});
			if (match) {
				varInput.value = match.id;
			}
		}
		form.addEventListener('change', sync);
		sync();
	})();
	</script>
	<?php endif; ?>

	<style>
	@media screen and (max-width: 749px) {
		.section-main-product .product { flex-direction: column; }
		.section-main-product .product__media-wrapper,
		.section-main-product .product__info-wrapper { flex: 1 1 100% !important; max-width: 100% !important; padding-left: 0 !important; }
		.section-main-product .product__info-wrapper { margin-top: 2.4rem; }
	}
	</style>
<?php
endwhile;

get_footer();

