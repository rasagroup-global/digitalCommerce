<?php
/**
 * front-page.php — Homepage Rasa Kopi (urutan section persis live Shopify)
 * 1. Hero slideshow (3 slides)
 * 2. Featured collection "Neuheiten"
 * 3. Product spotlight "Premium Matcha" (coming soon)
 * 4. Coming soon "Bald im Shop" + notify form
 * 5. B2B info banner
 * 6. Merch image banner
 * 7. Featured collection "RASA Merchandise"
 */
if ( ! defined( 'ABSPATH' ) ) exit;

get_header();

$sec = RASA_URI . '/assets/img/sections';
$all_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );

/* ---------------- data coming-soon (dummy, nanti dari produk Woo "Bald im Shop") ---------------- */
$cs_items = array(
	array( 'label' => 'Glas', 'title' => 'Glassware 1', 'img' => 'GLASS_2_BLK.webp', 'tag' => 'notify-glassware-1' ),
	array( 'label' => 'Glas', 'title' => 'Glassware 2', 'img' => 'GLASS_6_BLK.webp', 'tag' => 'notify-glassware-2' ),
	array( 'label' => 'Glas', 'title' => 'Glassware 4', 'img' => 'GLASS5.webp',      'tag' => 'notify-glassware-4' ),
	array( 'label' => 'Glas', 'title' => 'Glassware 5', 'img' => 'GLASS6.webp',      'tag' => 'notify-glassware-5' ),
	array( 'label' => 'Glas', 'title' => 'Glassware 6', 'img' => 'GLASS7.webp',      'tag' => 'notify-glassware-6' ),
	array( 'label' => 'Partner-Rösterei Meramanis · Filter',   'title' => 'Café de Flores – Filter',  'img' => 'OLSHOP_Thumbnail.webp',  'tag' => 'notify-cafe-de-flores-filter' ),
	array( 'label' => 'Partner-Rösterei Meramanis · Filter',   'title' => 'Grandpa Bedjo – Filter',   'img' => 'GrandpaBedjo.webp',      'tag' => 'notify-grandpa-bedjo-filter' ),
	array( 'label' => 'Partner-Rösterei Meramanis · Espresso', 'title' => 'Manis Meriah – Espresso',  'img' => 'OLSHOPThumbnail_6.webp', 'tag' => 'notify-manis-meriah-espresso' ),
);

$ps_items_1 = array(
	array(
		'title'       => 'Premium Matcha',
		'img'         => 'ChatGPT_Image_Sep_27_2026_03_22_40_PM.webp',
		'tag'         => 'notify-premium-matcha',
		'description' => rasa_tr(
			'Fein vermahlener Matcha in Zeremonien-Qualität mit leuchtend grüner Farbe, cremigem Umami und sanft-süßem Abgang – pur als Usucha oder als samtiger Matcha Latte.',
			'Finely stone-ground ceremonial-grade matcha with vibrant green color, creamy umami, and a smooth sweet finish — pure as Usucha or as a velvety Matcha Latte.'
		),
	),
	array(
		'title'       => 'Espresso',
		'img'         => 'S-PM-01.webp',
		'tag'         => 'notify-espresso',
		'description' => rasa_tr(
			'Kräftig gerösteter Indonesischer Specialty-Espresso mit dichter Crema, schokoladiger Tiefe und feinen Gewürznoten – entwickelt für Siebträger und Herdkännchen.',
			'Richly roasted Indonesian specialty espresso with dense crema, chocolatey depth, and subtle spice notes — crafted for portafilters and moka pots.'
		),
	),
	array(
		'title'       => rasa_tr( 'Filterkaffee', 'Filter Coffee' ),
		'img'         => 'S-PM-02.webp',
		'tag'         => 'notify-filterkaffee',
		'description' => rasa_tr(
			'Helle bis mittlere Röstung für V60, Chemex und Handfilter – klar in der Tasse, floral-fruchtig im Profil und voller Herkunftscharakter aus dem indonesischen Hochland.',
			'Light to medium roast for V60, Chemex, and pour-over — clean in the cup, floral-fruity in profile, and full of origin character from the Indonesian highlands.'
		),
	),
);

$ps_items_2 = array(
	array(
		'title'       => 'Coffee Brew Bag – Sumatra Bhadrika Gayo',
		'img'         => 'COFFEEBREW3FRONT.webp',
		'tag'         => 'notify-coffee-brew-bag',
		'description' => rasa_tr(
			'Specialty Coffee im praktischen Brew Bag: 100 % Arabica aus dem Gayo-Hochland in Sumatra. Einfach heißes Wasser aufgießen, ziehen lassen und unterwegs oder im Büro vollen Ursprungsgeschmack genießen.',
			'Specialty coffee in a convenient brew bag: 100% Arabica from the Gayo Highlands of Sumatra. Simply pour hot water, steep, and enjoy full origin flavor on the go or at the office.'
		),
	),
	array(
		'title'       => 'Mood Delight',
		'img'         => 'S-CB-01.webp',
		'tag'         => 'notify-mood-delight',
		'description' => rasa_tr(
			'Ausgewogene Specialty-Coffee-Kreation für jeden Moment des Tages – weich im Körper, mit warmen Karamell- und Nussnoten und einem angenehm runden Nachklang.',
			'Balanced specialty coffee creation for any time of day — smooth body with warm caramel and nut notes and a pleasantly round finish.'
		),
	),
	array(
		'title'       => rasa_tr( 'Kaffeekapseln', 'Coffee Capsules' ),
		'img'         => 'S-CB-02.webp',
		'tag'         => 'notify-kaffeekapseln',
		'description' => rasa_tr(
			'Specialty Coffee auf Knopfdruck: frisch gerösteter indonesischer Kaffee in präzise dosierten Kapseln – volles Aroma, unkompliziert zubereitet.',
			'Specialty coffee at the touch of a button: freshly roasted Indonesian coffee in precision-dosed capsules — full aroma, effortlessly brewed.'
		),
	),
);

$render_spotlight = function ( $id, $items, $align = 'left' ) use ( $sec ) {
	$wrapper_cls = $align === 'right' ? 'product-spotlight__wrapper--right' : 'product-spotlight__wrapper--left';
	$slider_id   = 'PsSlider-rasa-spotlight-' . $id;
	$notify_id   = 'PsNotify-rasa-spotlight-' . $id;
	$form_id     = 'PsNotifyForm-rasa-spotlight-' . $id;
	$email_id    = 'PsNotifyEmail-rasa-' . $id;
	$coming_lbl  = rasa_tr( 'Bald verfügbar', 'Coming soon' );
	$notify_btn  = rasa_tr( 'Benachrichtige mich', 'Notify me' );
	?>
	<section id="shopify-section-product_spotlight_<?php echo esc_attr( $id ); ?>" class="shopify-section section">
	<div class="product-spotlight-section page-width">
		<div class="product-spotlight__wrapper <?php echo esc_attr( $wrapper_cls ); ?> scroll-trigger animate--slide-in">
			<div class="product-spotlight__media">
				<div class="ps-slider" id="<?php echo esc_attr( $slider_id ); ?>" data-autoplay="true" data-speed="5" data-sold-out="<?php echo esc_attr( rasa_tr( 'Ausverkauft', 'Sold out' ) ); ?>" data-add="<?php echo esc_attr( rasa_tr( 'In den Warenkorb legen', 'Add to cart' ) ); ?>">
					<div class="ps-track" tabindex="0" aria-label="<?php echo esc_attr( $items[0]['title'] ); ?>">
						<?php foreach ( $items as $i => $it ) : ?>
						<div class="ps-slide"
							data-title="<?php echo esc_attr( $it['title'] ); ?>"
							data-description="<?php echo esc_attr( $it['description'] ); ?>"
							data-price="" data-url="#<?php echo esc_attr( $notify_id ); ?>" data-variant=""
							data-has-product="" data-available="" data-coming="1" data-tag="<?php echo esc_attr( $it['tag'] ); ?>">
							<a href="#<?php echo esc_attr( $notify_id ); ?>" class="ps-slide__link" tabindex="-1">
								<img src="<?php echo esc_url( $sec . '/' . $it['img'] ); ?>" alt="<?php echo esc_attr( $it['title'] ); ?>" loading="lazy" class="<?php echo 0 === $i ? 'product-spotlight__image' : 'product-spotlight__slide-image'; ?>">
							</a>
							<span class="ps-badge"><?php echo esc_html( $coming_lbl ); ?></span>
						</div>
						<?php endforeach; ?>
					</div>
					<button type="button" class="ps-arrow ps-arrow--prev" aria-label="Previous">&#8249;</button>
					<button type="button" class="ps-arrow ps-arrow--next" aria-label="Next">&#8250;</button>
					<div class="ps-dots">
						<?php foreach ( $items as $i => $it ) : ?>
						<button type="button" class="ps-dot<?php echo 0 === $i ? ' is-active' : ''; ?>" data-index="<?php echo (int) $i; ?>" aria-label="<?php echo esc_attr( ( $i + 1 ) . ' / ' . count( $items ) ); ?>"></button>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
			<div class="product-spotlight__content">
				<span class="product-spotlight__tagline">HIGHLIGHT</span>
				<h2 class="product-spotlight__title"><a class="ps-title-link"><?php echo esc_html( $items[0]['title'] ); ?></a></h2>
				<span class="ps-coming-badge"><?php echo esc_html( $coming_lbl ); ?></span>
				<p class="product-spotlight__description"><?php echo esc_html( $items[0]['description'] ); ?></p>
				<div class="product-spotlight__price" style="display:none"></div>
				<p class="product-spotlight__subtext ps-buy-only" style="display:none"><?php echo esc_html( rasa_tr( 'inkl. MwSt., zzgl. Versandkosten. Versandkostenfrei ab 59 € in Deutschland.', 'incl. VAT, plus shipping. Free shipping over €59 in Germany.' ) ); ?></p>
				<div class="product-spotlight__swatches">
					<span class="product-spotlight__swatch-label">
						<?php echo esc_html( rasa_tr( 'Produkt:', 'Product:' ) ); ?>
						<?php foreach ( $items as $i => $it ) : ?>
						<span class="ps-name<?php echo 0 === $i ? ' is-active' : ''; ?>" data-index="<?php echo (int) $i; ?>"><?php echo esc_html( $it['title'] ); ?></span><?php echo $i < count( $items ) - 1 ? '/' : ''; ?>
						<?php endforeach; ?>
					</span>
					<div class="product-spotlight__swatch-list">
						<?php foreach ( $items as $i => $it ) : ?>
						<button type="button" class="product-spotlight__swatch product-spotlight__swatch--image<?php echo 0 === $i ? ' product-spotlight__swatch--active' : ''; ?> ps-swatch" data-index="<?php echo (int) $i; ?>" title="<?php echo esc_attr( $it['title'] ); ?>" aria-label="<?php echo esc_attr( $it['title'] ); ?>">
							<img src="<?php echo esc_url( $sec . '/' . $it['img'] ); ?>" alt="" loading="lazy" width="32" height="32">
						</button>
						<?php endforeach; ?>
					</div>
				</div>
				<div class="product-spotlight__actions ps-buy-only" style="display:none">
					<a href="#<?php echo esc_attr( $notify_id ); ?>" class="button button--secondary product-spotlight__btn product-spotlight__btn--secondary ps-details-link"><?php echo esc_html( rasa_tr( 'Alle Details ansehen', 'View full details' ) ); ?></a>
				</div>
				<div class="ps-notify" id="<?php echo esc_attr( $notify_id ); ?>">
					<form method="post" action="<?php echo esc_url( home_url( '/kontakt/#' . $form_id ) ); ?>" id="<?php echo esc_attr( $form_id ); ?>" accept-charset="UTF-8" class="ps-notify__form">
						<?php wp_nonce_field( 'rasa_notify', 'rasa_notify_nonce' ); ?>
						<p class="ps-notify__text"><?php echo esc_html( rasa_tr( 'Noch nicht erhältlich. Hinterlass deine E-Mail und wir melden uns, sobald es im Shop ist.', 'Not yet available. Leave your email and we will notify you as soon as it is in the shop.' ) ); ?></p>
						<input type="hidden" name="rasa_notify_tag" value="notify-me,<?php echo esc_attr( $items[0]['tag'] ); ?>" class="ps-notify__tags">
						<label class="visually-hidden" for="<?php echo esc_attr( $email_id ); ?>"><?php echo esc_html( rasa_tr( 'E-Mail-Adresse', 'Email address' ) ); ?></label>
						<input id="<?php echo esc_attr( $email_id ); ?>" type="email" name="rasa_notify_email" autocomplete="email" placeholder="<?php echo esc_attr( rasa_tr( 'Deine E-Mail-Adresse', 'Your email address' ) ); ?>" required class="ps-notify__input">
						<label class="ps-notify__consent">
							<input type="checkbox" name="rasa_notify_consent" value="true" required>
							<span><?php echo esc_html( rasa_tr( 'Ich möchte einmalig per E-Mail benachrichtigt werden, sobald dieses Produkt verfügbar ist. Weitere Infos in der', 'I would like to receive a one-time email notification when this product becomes available. More info in our' ) ); ?> <a href="<?php echo esc_url( home_url( '/datenschutz/' ) ); ?>"><?php echo esc_html( rasa_tr( 'Datenschutzerklärung', 'Privacy Policy' ) ); ?></a>.</span>
						</label>
						<button type="submit" class="button button--primary product-spotlight__btn"><?php echo esc_html( $notify_btn ); ?></button>
					</form>
				</div>
			</div>
		</div>
	</div>
	</section>
	<?php
};

$hero_slides = array(
	array(
		'desktop' => 'Home_Banner_01.webp',
		'mobile'  => 'ALL_PRODUCT_POTRAIT.webp',
		'heads'   => array( 'Shop Me', 'Like You Do' ),
		'text'    => rasa_tr( 'Entdecke unsere neue Kollektion an Specialty-Coffee-Zubehör und Sensory Cups.', 'Discover our new collection of specialty coffee gear and sensory cups.' ),
		'em'      => false,
		'alt'     => '',
	),
	array(
		'desktop' => 'Home_Banner_02.webp',
		'mobile'  => 'Home_Banner_02_mobile.webp',
		'heads'   => array( rasa_tr( 'Trag, was', 'Wear what' ), rasa_tr( 'du lebst', 'you live' ) ),
		'text'    => rasa_tr( 'Aus Indonesien in die Welt – Tasse für Tasse.', 'From Indonesia to the world — cup by cup.' ),
		'em'      => true,
		'alt'     => 'Rasa Kopi Merchandise – T-Shirt und Hoodie',
	),
	array(
		'desktop' => 'Home_Banner_03.webp',
		'mobile'  => 'Home_Banner_03_mobile.webp',
		'heads'   => array( rasa_tr( 'Gemacht für', 'Made for' ), rasa_tr( 'Kaffeeliebhaber', 'coffee lovers' ) ),
		'text'    => rasa_tr( 'Specialty-Coffee-Zubehör für maximale Aromenklarheit.', 'Specialty coffee gear for maximum flavor clarity.' ),
		'em'      => true,
		'alt'     => 'Rasa Kopi Glaskannen und Gläser',
	),
);
?>

<!-- ===================== 1. HERO SLIDESHOW ===================== -->
<section id="shopify-section-hero_slideshow" class="shopify-section section">
<style data-shopify>
#Slider-rasa-hero .slideshow__media picture { position: absolute; inset: 0; width: 100%; height: 100%; }
#Slider-rasa-hero .slideshow__media picture img { width: 100%; height: 100%; object-fit: cover; object-position: center center; }
#Slider-rasa-hero .banner__text p,
#Slider-rasa-hero .banner__text p strong,
#Slider-rasa-hero .banner__text p em {
	font-family: 'Bai Jamjuree', sans-serif !important;
	font-weight: 400 !important;
	font-style: italic !important;
}
#Slider-rasa-hero .slideshow__text.banner__box {
	background: transparent !important;
	padding: 4rem 0 2rem;
}
#Slider-rasa-hero .banner__heading p {
	display: table;
	margin: 0 0 0.4rem;
	padding: 0.2rem 1.4rem;
	background: rgba(18, 18, 18, 0.85);
	color: #ffffff;
}
#Slider-rasa-hero .banner__text p {
	display: table;
	margin: 0.8rem 0 0;
	padding: 0.8rem 1.4rem;
	background: rgba(18, 18, 18, 0.85);
	color: rgba(255, 255, 255, 0.85);
}
#Slider-rasa-hero .banner__buttons .button--primary {
	background-color: #ffffff;
	color: #121212;
	border: 1px solid #ffffff;
	font-weight: 700;
	font-size: 1.5rem;
	letter-spacing: 0.12em;
	text-transform: uppercase;
	min-height: 4.9rem;
	padding: 0 3rem;
	box-shadow: 0 2px 10px rgba(0, 0, 0, 0.25);
}
#Slider-rasa-hero .banner__buttons .button--primary:hover {
	background-color: #f0f0f0;
}
@media screen and (min-width: 750px) {
	#Slider-rasa-hero .slideshow__box--split { align-items: flex-end !important; }
	#Slider-rasa-hero .slideshow__box--split .banner__buttons { align-self: flex-end !important; }
}
@media screen and (max-width: 749px) {
	#Slider-rasa-hero .banner__text { display: none !important; }
	#Slider-rasa-hero .banner__heading {
		display: flex !important; flex-direction: column !important; align-items: flex-start !important;
		gap: 0.6rem !important; background: transparent !important; padding: 0 !important;
	}
	#Slider-rasa-hero .banner__heading::before, #Slider-rasa-hero .banner__heading::after { display: none !important; }
	#Slider-rasa-hero .banner__heading p {
		display: block !important; font-size: min(3.8rem, 9vw) !important; line-height: 1.15 !important;
		padding: 0.35rem 1rem 0.45rem !important;
	}
	#Slider-rasa-hero .slideshow__box--split {
		flex-direction: row !important; align-items: flex-end !important; justify-content: space-between !important; gap: 0.6rem !important;
	}
	#Slider-rasa-hero .slideshow__box--split .slideshow__text-content { width: auto !important; flex: 1 1 auto; min-width: 0; }
	#Slider-rasa-hero .slideshow__box--split .banner__buttons { margin-top: 0 !important; margin-left: auto !important; flex-shrink: 0; }
	#Slider-rasa-hero .slideshow__box--split .banner__buttons .button {
		display: inline-flex !important; align-items: center !important; justify-content: center !important;
		text-align: center !important; white-space: normal !important; line-height: 1.15 !important;
		max-width: 10.5rem; min-width: 0 !important; min-height: 0 !important;
		height: calc(min(3.8rem, 9vw) * 1.15 + 0.8rem) !important; padding: 0 0.9rem !important;
	}
	#Slider-rasa-hero .slideshow__slide { min-height: 80svh; }
}
</style>
<slideshow-component class="slider-mobile-gutter slideshow--split-layout" role="region" aria-roledescription="Karussell" aria-label="Slideshow über unsere Marke" data-infinite-loop="true">
	<button type="button" class="slider-button slider-button--prev" name="previous" aria-label="Vorherige Folie" aria-controls="Slider-rasa-hero">
		<span class="svg-wrapper"><svg class="icon icon-caret" viewBox="0 0 10 6"><path fill="currentColor" fill-rule="evenodd" d="M9.354.646a.5.5 0 0 0-.708 0L5 4.293 1.354.646a.5.5 0 0 0-.708.708l4 4a.5.5 0 0 0 .708 0l4-4a.5.5 0 0 0 0-.708" clip-rule="evenodd"/></svg></span>
	</button>
	<button type="button" class="slider-button slider-button--next" name="next" aria-label="Nächste Folie" aria-controls="Slider-rasa-hero">
		<span class="svg-wrapper"><svg class="icon icon-caret" viewBox="0 0 10 6"><path fill="currentColor" fill-rule="evenodd" d="M9.354.646a.5.5 0 0 0-.708 0L5 4.293 1.354.646a.5.5 0 0 0-.708.708l4 4a.5.5 0 0 0 .708 0l4-4a.5.5 0 0 0 0-.708" clip-rule="evenodd"/></svg></span>
	</button>
	<div class="slideshow banner banner--large grid grid--1-col slider slider--everywhere scroll-trigger animate--fade-in" id="Slider-rasa-hero" aria-live="polite" aria-atomic="true" data-autoplay="true" data-speed="5">
		<?php foreach ( $hero_slides as $i => $slide ) : $n = $i + 1; ?>
		<style>#Slide-rasa-hero-<?php echo $n; ?> .banner__media::after { opacity: 0.2; }</style>
		<div class="slideshow__slide grid__item grid--1-col slider__slide" id="Slide-rasa-hero-<?php echo $n; ?>" role="group" aria-roledescription="Folie" aria-label="<?php echo esc_attr( $n . ' von ' . count( $hero_slides ) ); ?>" tabindex="-1">
			<div class="slideshow__media banner__media media">
				<picture>
					<source media="(max-width: 749px)" srcset="<?php echo esc_url( $sec . '/' . $slide['mobile'] ); ?>" sizes="100vw">
					<img src="<?php echo esc_url( $sec . '/' . $slide['desktop'] ); ?>" alt="<?php echo esc_attr( $slide['alt'] ); ?>" height="1012" sizes="100vw" <?php echo 1 === $n ? 'fetchpriority="high"' : 'loading="lazy"'; ?>>
				</picture>
			</div>
			<div class="slideshow__text-wrapper banner__content banner__content--bottom-left page-width banner--desktop-transparent scroll-trigger animate--slide-in">
				<div class="slideshow__text banner__box content-container content-container--full-width-mobile color-scheme-3 gradient slideshow__text--left slideshow__text-mobile--left slideshow__box--split">
					<div class="slideshow__text-content">
						<h2 class="banner__heading rte inline-richtext hxl">
							<?php foreach ( $slide['heads'] as $h ) : ?><p><strong><?php echo esc_html( $h ); ?></strong></p><?php endforeach; ?>
						</h2>
						<div class="banner__text rte">
							<p><?php if ( $slide['em'] ) : ?><em><?php echo esc_html( $slide['text'] ); ?></em><?php else : ?><strong><?php echo esc_html( $slide['text'] ); ?></strong><?php endif; ?></p>
						</div>
					</div>
					<div class="banner__buttons">
						<a href="<?php echo esc_url( $all_url ); ?>" class="button button--primary"><?php echo esc_html( rasa_tr( 'Jetzt shoppen', 'Shop now' ) ); ?></a>
					</div>
				</div>
			</div>
		</div>
		<?php endforeach; ?>
	</div>
	<div class="slideshow__controls slider-buttons">
		<div class="slider-counter slider-counter--dots">
			<div class="slideshow__control-wrapper">
				<?php foreach ( $hero_slides as $i => $slide ) : $n = $i + 1; ?>
				<button class="slider-counter__link slider-counter__link--dots link<?php echo 1 === $n ? ' slider-counter__link--active' : ''; ?>" aria-label="Folie laden <?php echo $n; ?> von <?php echo count( $hero_slides ); ?>" aria-controls="Slider-rasa-hero"><span class="dot"></span></button>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</slideshow-component>
</section>

<!-- ===================== 2. FEATURED COLLECTION "Neuheiten" ===================== -->
<section id="shopify-section-featured_collection_1" class="shopify-section section">
<?php echo rasa_featured_collection( array( // phpcs:ignore WordPress.Security.EscapeOutput
	'section_id'       => 'featured_collection_1',
	'collection'       => 'neuheiten',
	'title'            => rasa_tr( 'Neuheiten', 'New Arrivals' ),
	'products_to_show' => 4,
	'columns_desktop'  => 4,
	'columns_mobile'   => 2,
	'show_view_all'    => true,
	'view_all_label'   => rasa_tr( 'Alle anzeigen', 'View all' ),
	'padding_top'      => 44,
	'padding_bottom'   => 36,
) ); ?>
</section>

<!-- ===================== 3. PRODUCT SPOTLIGHT 1 & 2 ===================== -->
<?php
$render_spotlight( '1', $ps_items_1, 'left' );
$render_spotlight( '2', $ps_items_2, 'right' );
?>

<!-- ===================== 4. COMING SOON "Bald im Shop" ===================== -->
<section id="shopify-section-coming_soon" class="shopify-section section">
<div class="rasa-cs page-width section-template--31480989090175__coming_soon-padding" id="ComingSoon-rasa">
	<style>
		.section-template--31480989090175__coming_soon-padding { padding-top: 33px; padding-bottom: 27px; }
		@media screen and (min-width: 750px) {
			.section-template--31480989090175__coming_soon-padding { padding-top: 44px; padding-bottom: 36px; }
		}
	</style>
	<div class="rasa-cs__head scroll-trigger animate--slide-in">
		<h2 class="rasa-cs__heading h2"><?php echo esc_html( rasa_tr( 'Bald im Shop', 'Coming soon' ) ); ?></h2>
		<p class="rasa-cs__sub"><?php echo esc_html( rasa_tr( 'Neue Gläser und Kaffees sind schon unterwegs. Trag dich ein und erfahre als Erste:r, wenn sie da sind.', 'New glassware and coffees are already on the way. Sign up to be the first to know when they arrive.' ) ); ?></p>
	</div>
	<ul class="rasa-cs__grid" role="list">
		<?php foreach ( $cs_items as $cs_idx => $it ) : ?>
		<li class="rasa-cs__card scroll-trigger animate--slide-in" data-cascade style="--animation-order: <?php echo (int) ( $cs_idx + 1 ); ?>;">
			<div class="rasa-cs__media">
				<img src="<?php echo esc_url( $sec . '/' . $it['img'] ); ?>" alt="<?php echo esc_attr( $it['title'] ); ?>" loading="lazy" sizes="(min-width: 990px) 25vw, 50vw" class="rasa-cs__img">
				<span class="rasa-cs__badge"><?php echo esc_html( rasa_tr( 'Bald verfügbar', 'Coming soon' ) ); ?></span>
			</div>
			<div class="rasa-cs__info">
				<span class="rasa-cs__label"><?php echo esc_html( $it['label'] ); ?></span>
				<h3 class="rasa-cs__title"><?php echo esc_html( $it['title'] ); ?></h3>
				<a href="#Notify-rasa" class="rasa-cs__btn" data-cs-tag="<?php echo esc_attr( $it['tag'] ); ?>"><?php echo esc_html( rasa_tr( 'Benachrichtige mich', 'Notify me' ) ); ?></a>
			</div>
		</li>
		<?php endforeach; ?>
	</ul>
	<div class="rasa-cs__form-wrap" id="Notify-rasa">
		<form method="post" action="<?php echo esc_url( home_url( '/kontakt/#NotifyForm-rasa' ) ); ?>" id="NotifyForm-rasa" accept-charset="UTF-8" class="rasa-cs__form">
			<?php wp_nonce_field( 'rasa_notify', 'rasa_notify_nonce' ); ?>
			<div class="rasa-cs__form-text">
				<h3 class="rasa-cs__form-title"><?php echo esc_html( rasa_tr( 'Sei als Erste:r dabei', 'Be the first to know' ) ); ?></h3>
				<p><?php echo esc_html( rasa_tr( 'Wähle ein Produkt und hinterlass deine E-Mail. Wir melden uns, sobald es verfügbar ist.', 'Choose a product and leave your email. We will let you know as soon as it is available.' ) ); ?></p>
			</div>
			<div class="rasa-cs__fields">
				<div class="rasa-cs__field">
					<label for="NotifyProduct-rasa"><?php echo esc_html( rasa_tr( 'Produkt', 'Product' ) ); ?></label>
					<select id="NotifyProduct-rasa" name="rasa_notify_tag" class="rasa-cs__select" required>
						<option value="notify-me,notify-all"><?php echo esc_html( rasa_tr( 'Alle neuen Produkte (allgemeines Update)', 'All new products (general update)' ) ); ?></option>
						<?php foreach ( $cs_items as $it ) : ?>
						<option value="notify-me,<?php echo esc_attr( $it['tag'] ); ?>"><?php echo esc_html( $it['title'] ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="rasa-cs__field">
					<label for="NotifyEmail-rasa"><?php echo esc_html( rasa_tr( 'E-Mail-Adresse', 'Email address' ) ); ?></label>
					<input id="NotifyEmail-rasa" type="email" name="rasa_notify_email" autocomplete="email" placeholder="<?php echo esc_attr( rasa_tr( 'deine@email.de', 'your@email.com' ) ); ?>" required class="rasa-cs__input">
				</div>
				<label class="rasa-cs__consent">
					<input type="checkbox" name="rasa_notify_consent" value="true" required>
					<span><?php echo esc_html( rasa_tr( 'Ich möchte einmalig per E-Mail informiert werden, sobald das gewählte Produkt verfügbar ist. Hinweise zur Verarbeitung findest du in unserer', 'I would like to be notified once by email when the selected product becomes available. Details can be found in our' ) ); ?> <a href="<?php echo esc_url( home_url( '/datenschutz/' ) ); ?>"><?php echo esc_html( rasa_tr( 'Datenschutzerklärung', 'Privacy Policy' ) ); ?></a>.</span>
				</label>
				<button type="submit" class="button button--primary rasa-cs__submit"><?php echo esc_html( rasa_tr( 'Benachrichtige mich', 'Notify me' ) ); ?></button>
			</div>
		</form>
	</div>
</div>
</section>

<!-- ===================== 5. B2B INFO BANNER ===================== -->
<section id="shopify-section-info_banner_1" class="shopify-section section">
<div class="info-banner-section color-scheme-2 gradient section-template--31480989090175__info_banner_1-padding" id="b2b">
	<style>
		.section-template--31480989090175__info_banner_1-padding { padding-top: 21px; padding-bottom: 21px; }
		@media screen and (min-width: 750px) {
			.section-template--31480989090175__info_banner_1-padding { padding-top: 28px; padding-bottom: 28px; }
		}
	</style>
	<div class="page-width">
		<div class="info-banner__container info-banner__container--card scroll-trigger animate--slide-in">
			<div class="info-banner__content">
				<span class="info-banner__subheading">B2B · WHITE LABEL · PARTNER</span>
				<h2 class="info-banner__heading h2"><?php echo esc_html( rasa_tr( 'Lass uns zusammenarbeiten.', "Let's work together." ) ); ?></h2>
				<div class="info-banner__text rte">
					<p><?php echo esc_html( rasa_tr( 'Für Cafés, Unternehmen, Events und Röstereien:', 'For cafés, businesses, events, and roasteries:' ) ); ?></p>
					<ul>
						<li><strong><?php echo esc_html( rasa_tr( 'Porzellan & Kaffeegläser', 'Porcelain & Coffee Glassware' ) ); ?></strong> <?php echo esc_html( rasa_tr( 'mit deinem Logo', 'with your logo' ) ); ?></li>
						<li><strong>Merch</strong> <?php echo esc_html( rasa_tr( 'für dein Team, dein Café oder dein Event', 'for your team, your café, or your event' ) ); ?></li>
						<li><strong><?php echo esc_html( rasa_tr( 'Specialty-Röstereien:', 'Specialty Roasteries:' ) ); ?></strong> <?php echo esc_html( rasa_tr( 'präsentiere deine Bohnen in unserem Shop', 'showcase your beans in our shop' ) ); ?></li>
					</ul>
					<p><?php echo esc_html( rasa_tr( 'Erzähl uns, was du vorhast.', 'Tell us what you have in mind.' ) ); ?></p>
				</div>
			</div>
			<div class="info-banner__action">
				<a href="<?php echo esc_url( home_url( '/kontakt/' ) ); ?>" class="button button--primary info-banner__button"><?php echo esc_html( rasa_tr( 'ANFRAGE SENDEN', 'SEND INQUIRY' ) ); ?></a>
			</div>
		</div>
	</div>
</div>
</section>

<!-- ===================== 6. MERCH IMAGE BANNER ===================== -->
<section id="shopify-section-image_banner_2" class="shopify-section section">
<style data-shopify>#Banner-rasa-merch::after { opacity: 0.1; }</style>
<div id="Banner-rasa-merch" class="banner banner--content-align-center banner--content-align-mobile-center banner--medium banner--desktop-transparent scroll-trigger animate--fade-in">
	<div class="banner__media media scroll-trigger animate--fade-in">
		<img src="<?php echo esc_url( $sec . '/MERCH.webp' ); ?>" alt="" width="2500" height="2000" loading="lazy" decoding="async" sizes="100vw">
	</div>
	<div class="banner__content banner__content--middle-center page-width scroll-trigger animate--slide-in">
		<div class="banner__box content-container content-container--full-width-mobile color-scheme-3 gradient">
			<div class="banner__buttons">
				<a href="<?php echo esc_url( $all_url ); ?>" class="button button--primary"><?php echo esc_html( rasa_tr( 'Jetzt shoppen', 'Shop now' ) ); ?></a>
			</div>
		</div>
	</div>
</div>
<style>#shopify-section-image_banner_2 .banner__buttons .button { background-color: #ffffff; color: #121212; border: 1px solid #ffffff; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase; box-shadow: 0 2px 10px rgba(0,0,0,0.25); } #shopify-section-image_banner_2 .banner__buttons .button:hover { background-color: #f0f0f0; }</style>
</section>

<!-- ===================== 7. FEATURED COLLECTION "RASA Merchandise" ===================== -->
<section id="shopify-section-featured_collection_3" class="shopify-section section">
<?php echo rasa_featured_collection( array( // phpcs:ignore WordPress.Security.EscapeOutput
	'section_id'       => 'featured_collection_3',
	'collection'       => 'rasa-merchandise',
	'title'            => 'RASA Merchandise',
	'products_to_show' => 4,
	'columns_desktop'  => 4,
	'columns_mobile'   => 2,
	'show_view_all'    => true,
	'view_all_label'   => rasa_tr( 'Alle anzeigen', 'View all' ),
	'order'            => 'ASC',
	'padding_top'      => 44,
	'padding_bottom'   => 36,
) ); ?>
</section>

<?php get_footer(); ?>
