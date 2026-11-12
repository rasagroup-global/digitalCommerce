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
if (!defined('ABSPATH'))
	exit;

get_header();

$sec = RASA_URI . '/assets/img/sections';
$all_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop/');

/* ---------------- data coming-soon (dummy, nanti dari produk Woo "Bald im Shop") ---------------- */
$cs_items = array(
	array('label' => 'Glas', 'title' => 'Glassware 1', 'img' => 'GLASS_2_BLK.jpg', 'tag' => 'notify-glassware-1'),
	array('label' => 'Glas', 'title' => 'Glassware 2', 'img' => 'GLASS_6_BLK.jpg', 'tag' => 'notify-glassware-2'),
	array('label' => 'Glas', 'title' => 'Glassware 4', 'img' => 'GLASS5.png', 'tag' => 'notify-glassware-4'),
	array('label' => 'Glas', 'title' => 'Glassware 5', 'img' => 'GLASS6.png', 'tag' => 'notify-glassware-5'),
	array('label' => 'Glas', 'title' => 'Glassware 6', 'img' => 'GLASS7.png', 'tag' => 'notify-glassware-6'),
	array('label' => 'Partner-Rösterei Meramanis · Filter', 'title' => 'Café de Flores – Filter', 'img' => 'OLSHOP_Thumbnail.jpg', 'tag' => 'notify-cafe-de-flores-filter'),
	array('label' => 'Partner-Rösterei Meramanis · Filter', 'title' => 'Grandpa Bedjo – Filter', 'img' => 'GrandpaBedjo.jpg', 'tag' => 'notify-grandpa-bedjo-filter'),
	array('label' => 'Partner-Rösterei Meramanis · Espresso', 'title' => 'Manis Meriah – Espresso', 'img' => 'OLSHOPThumbnail_6.jpg', 'tag' => 'notify-manis-meriah-espresso'),
);

$ps_items_1 = array(
	array(
		'title' => 'Premium Matcha',
		'img' => 'ChatGPT_Image_Sep_27_2026_03_22_40_PM.png',
		'tag' => 'notify-premium-matcha',
		'description' => 'Fein vermahlener Matcha in Zeremonien-Qualität mit leuchtend grüner Farbe, cremigem Umami und sanft-süßem Abgang – pur als Usucha oder als samtiger Matcha Latte.',
	),
	array(
		'title' => 'Espresso',
		'img' => 'S-PM-01.jpg',
		'tag' => 'notify-espresso',
		'description' => 'Kräftig gerösteter Indonesischer Specialty-Espresso mit dichter Crema, schokoladiger Tiefe und feinen Gewürznoten – entwickelt für Siebträger und Herdkännchen.',
	),
	array(
		'title' => 'Filterkaffee',
		'img' => 'S-PM-02.jpg',
		'tag' => 'notify-filterkaffee',
		'description' => 'Helle bis mittlere Röstung für V60, Chemex und Handfilter – klar in der Tasse, floral-fruchtig im Profil und voller Herkunftscharakter aus dem indonesischen Hochland.',
	),
);

$ps_items_2 = array(
	array(
		'title' => 'Coffee Brew Bag – Sumatra Bhadrika Gayo',
		'img' => 'COFFEEBREW3FRONT.jpg',
		'tag' => 'notify-coffee-brew-bag',
		'description' => 'Specialty Coffee im praktischen Brew Bag: 100 % Arabica aus dem Gayo-Hochland in Sumatra. Einfach heißes Wasser aufgießen, ziehen lassen und unterwegs oder im Büro vollen Ursprungsgeschmack genießen.',
	),
	array(
		'title' => 'Mood Delight',
		'img' => 'S-CB-01.jpg',
		'tag' => 'notify-mood-delight',
		'description' => 'Ausgewogene Specialty-Coffee-Kreation für jeden Moment des Tages – weich im Körper, mit warmen Karamell- und Nussnoten und einem angenehm runden Nachklang.',
	),
	array(
		'title' => 'Kaffeekapseln',
		'img' => 'S-CB-02.png',
		'tag' => 'notify-kaffeekapseln',
		'description' => 'Specialty Coffee auf Knopfdruck: frisch gerösteter indonesischer Kaffee in präzise dosierten Kapseln – volles Aroma, unkompliziert zubereitet.',
	),
);

$render_spotlight = function ($id, $items, $align = 'left') use ($sec) {
	$wrapper_cls = $align === 'right' ? 'product-spotlight__wrapper--right' : 'product-spotlight__wrapper--left';
	$slider_id = 'PsSlider-rasa-spotlight-' . $id;
	$notify_id = 'PsNotify-rasa-spotlight-' . $id;
	$form_id = 'PsNotifyForm-rasa-spotlight-' . $id;
	$email_id = 'PsNotifyEmail-rasa-' . $id;
	?>
	<section id="shopify-section-product_spotlight_<?php echo esc_attr($id); ?>" class="shopify-section section">
		<div class="product-spotlight-section page-width">
			<div class="product-spotlight__wrapper <?php echo esc_attr($wrapper_cls); ?>">
				<div class="product-spotlight__media">
					<div class="ps-slider" id="<?php echo esc_attr($slider_id); ?>" data-autoplay="true" data-speed="5"
						data-sold-out="Ausverkauft" data-add="In den Warenkorb legen">
						<div class="ps-track" tabindex="0" aria-label="<?php echo esc_attr($items[0]['title']); ?>">
							<?php foreach ($items as $i => $it): ?>
								<div class="ps-slide" data-title="<?php echo esc_attr($it['title']); ?>"
									data-description="<?php echo esc_attr($it['description']); ?>" data-price=""
									data-url="#<?php echo esc_attr($notify_id); ?>" data-variant="" data-has-product=""
									data-available="" data-coming="1" data-tag="<?php echo esc_attr($it['tag']); ?>">
									<a href="#<?php echo esc_attr($notify_id); ?>" class="ps-slide__link" tabindex="-1">
										<img src="<?php echo esc_url($sec . '/' . $it['img']); ?>"
											alt="<?php echo esc_attr($it['title']); ?>" loading="lazy"
											class="<?php echo 0 === $i ? 'product-spotlight__image' : 'product-spotlight__slide-image'; ?>">
									</a>
									<span class="ps-badge">Bald verfügbar</span>
								</div>
							<?php endforeach; ?>
						</div>
						<button type="button" class="ps-arrow ps-arrow--prev"
							aria-label="Nach links schieben">&#8249;</button>
						<button type="button" class="ps-arrow ps-arrow--next"
							aria-label="Nach rechts schieben">&#8250;</button>
						<div class="ps-dots">
							<?php foreach ($items as $i => $it): ?>
								<button type="button" class="ps-dot<?php echo 0 === $i ? ' is-active' : ''; ?>"
									data-index="<?php echo (int) $i; ?>"
									aria-label="<?php echo esc_attr(($i + 1) . ' / ' . count($items)); ?>"></button>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
				<div class="product-spotlight__content">
					<span class="product-spotlight__tagline">HIGHLIGHT</span>
					<h2 class="product-spotlight__title"><a
							class="ps-title-link"><?php echo esc_html($items[0]['title']); ?></a></h2>
					<span class="ps-coming-badge">Bald verfügbar</span>
					<p class="product-spotlight__description"><?php echo esc_html($items[0]['description']); ?></p>
					<div class="product-spotlight__price" style="display:none"></div>
					<p class="product-spotlight__subtext ps-buy-only" style="display:none">inkl. MwSt., zzgl. Versandkosten.
						Versandkostenfrei ab 59 € in Deutschland.</p>
					<div class="product-spotlight__swatches">
						<span class="product-spotlight__swatch-label">
							Produkt:
							<?php foreach ($items as $i => $it): ?>
								<span class="ps-name<?php echo 0 === $i ? ' is-active' : ''; ?>"
									data-index="<?php echo (int) $i; ?>"><?php echo esc_html($it['title']); ?></span><?php echo $i < count($items) - 1 ? '/' : ''; ?>
							<?php endforeach; ?>
						</span>
						<div class="product-spotlight__swatch-list">
							<?php foreach ($items as $i => $it): ?>
								<button type="button"
									class="product-spotlight__swatch product-spotlight__swatch--image<?php echo 0 === $i ? ' product-spotlight__swatch--active' : ''; ?> ps-swatch"
									data-index="<?php echo (int) $i; ?>" title="<?php echo esc_attr($it['title']); ?>"
									aria-label="<?php echo esc_attr($it['title']); ?>">
									<img src="<?php echo esc_url($sec . '/' . $it['img']); ?>" alt="" loading="lazy"
										width="32" height="32">
								</button>
							<?php endforeach; ?>
						</div>
					</div>
					<div class="product-spotlight__actions ps-buy-only" style="display:none">
						<a href="#<?php echo esc_attr($notify_id); ?>"
							class="button button--secondary product-spotlight__btn product-spotlight__btn--secondary ps-details-link">Alle
							Details ansehen</a>
					</div>
					<div class="ps-notify" id="<?php echo esc_attr($notify_id); ?>">
						<form method="post" action="<?php echo esc_url(home_url('/kontakt/#' . $form_id)); ?>"
							id="<?php echo esc_attr($form_id); ?>" accept-charset="UTF-8" class="ps-notify__form">
							<?php wp_nonce_field('rasa_notify', 'rasa_notify_nonce'); ?>
							<p class="ps-notify__text">Noch nicht erhältlich. Hinterlass deine E-Mail und wir melden uns,
								sobald es im Shop ist.</p>
							<input type="hidden" name="rasa_notify_tag"
								value="notify-me,<?php echo esc_attr($items[0]['tag']); ?>" class="ps-notify__tags">
							<label class="visually-hidden" for="<?php echo esc_attr($email_id); ?>">E-Mail-Adresse</label>
							<input id="<?php echo esc_attr($email_id); ?>" type="email" name="rasa_notify_email"
								autocomplete="email" placeholder="Deine E-Mail-Adresse" required class="ps-notify__input">
							<label class="ps-notify__consent">
								<input type="checkbox" name="rasa_notify_consent" value="true" required>
								<span>Ich möchte einmalig per E-Mail benachrichtigt werden, sobald dieses Produkt verfügbar
									ist. Weitere Infos in der <a
										href="<?php echo esc_url(home_url('/datenschutz/')); ?>">Datenschutzerklärung</a>.</span>
							</label>
							<button type="submit" class="button button--primary product-spotlight__btn">Benachrichtige
								mich</button>
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
		'desktop' => 'Home_Banner_01.png',
		'mobile' => 'ALL_PRODUCT_POTRAIT.png',
		'heads' => array('Shop Me', 'Like You Do'),
		'text' => 'Entdecke unsere neue Kollektion an Specialty-Coffee-Zubehör und Sensory Cups.',
		'em' => false,
		'alt' => '',
	),
	array(
		'desktop' => 'Home_Banner_02.png',
		'mobile' => 'Home_Banner_02_mobile.jpg',
		'heads' => array('Trag, was', 'du lebst'),
		'text' => 'Aus Indonesien in die Welt – Tasse für Tasse.',
		'em' => true,
		'alt' => 'Rasa Kopi Merchandise – T-Shirt und Hoodie',
	),
	array(
		'desktop' => 'Home_Banner_03.png',
		'mobile' => 'Home_Banner_03_mobile.jpg',
		'heads' => array('Gemacht für', 'Kaffeeliebhaber'),
		'text' => 'Specialty-Coffee-Zubehör für maximale Aromenklarheit.',
		'em' => true,
		'alt' => 'Rasa Kopi Glaskannen und Gläser',
	),
);
?>

<!-- ===================== 1. HERO SLIDESHOW ===================== -->
<section id="shopify-section-hero_slideshow" class="shopify-section section">
	<style data-shopify>
		#Slider-rasa-hero .slideshow__media picture {
			position: absolute;
			inset: 0;
			width: 100%;
			height: 100%;
		}

		#Slider-rasa-hero .slideshow__media picture img {
			width: 100%;
			height: 100%;
			object-fit: cover;
			object-position: center center;
		}

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
			#Slider-rasa-hero .slideshow__box--split {
				align-items: flex-end !important;
			}

			#Slider-rasa-hero .slideshow__box--split .banner__buttons {
				align-self: flex-end !important;
			}
		}

		@media screen and (max-width: 749px) {
			#Slider-rasa-hero .banner__text {
				display: none !important;
			}

			#Slider-rasa-hero .banner__heading {
				display: flex !important;
				flex-direction: column !important;
				align-items: flex-start !important;
				gap: 0.6rem !important;
				background: transparent !important;
				padding: 0 !important;
			}

			#Slider-rasa-hero .banner__heading::before,
			#Slider-rasa-hero .banner__heading::after {
				display: none !important;
			}

			#Slider-rasa-hero .banner__heading p {
				display: block !important;
				font-size: min(3.8rem, 9vw) !important;
				line-height: 1.15 !important;
				padding: 0.35rem 1rem 0.45rem !important;
			}

			#Slider-rasa-hero .slideshow__box--split {
				flex-direction: row !important;
				align-items: flex-end !important;
				justify-content: space-between !important;
				gap: 0.6rem !important;
			}

			#Slider-rasa-hero .slideshow__box--split .slideshow__text-content {
				width: auto !important;
				flex: 1 1 auto;
				min-width: 0;
			}

			#Slider-rasa-hero .slideshow__box--split .banner__buttons {
				margin-top: 0 !important;
				margin-left: auto !important;
				flex-shrink: 0;
			}

			#Slider-rasa-hero .slideshow__box--split .banner__buttons .button {
				display: inline-flex !important;
				align-items: center !important;
				justify-content: center !important;
				text-align: center !important;
				white-space: normal !important;
				line-height: 1.15 !important;
				max-width: 10.5rem;
				min-width: 0 !important;
				min-height: 0 !important;
				height: calc(min(3.8rem, 9vw) * 1.15 + 0.8rem) !important;
				padding: 0 0.9rem !important;
			}

			#Slider-rasa-hero .slideshow__slide {
				min-height: 80svh;
			}
		}
	</style>
	<slideshow-component class="slider-mobile-gutter slideshow--split-layout" role="region"
		aria-roledescription="Karussell" aria-label="Slideshow über unsere Marke" data-infinite-loop="true">
		<button type="button" class="slider-button slider-button--prev" name="previous" aria-label="Vorherige Folie"
			aria-controls="Slider-rasa-hero">
			<span class="svg-wrapper"><svg class="icon icon-caret" viewBox="0 0 10 6">
					<path fill="currentColor" fill-rule="evenodd"
						d="M9.354.646a.5.5 0 0 0-.708 0L5 4.293 1.354.646a.5.5 0 0 0-.708.708l4 4a.5.5 0 0 0 .708 0l4-4a.5.5 0 0 0 0-.708"
						clip-rule="evenodd" />
				</svg></span>
		</button>
		<button type="button" class="slider-button slider-button--next" name="next" aria-label="Nächste Folie"
			aria-controls="Slider-rasa-hero">
			<span class="svg-wrapper"><svg class="icon icon-caret" viewBox="0 0 10 6">
					<path fill="currentColor" fill-rule="evenodd"
						d="M9.354.646a.5.5 0 0 0-.708 0L5 4.293 1.354.646a.5.5 0 0 0-.708.708l4 4a.5.5 0 0 0 .708 0l4-4a.5.5 0 0 0 0-.708"
						clip-rule="evenodd" />
				</svg></span>
		</button>
		<div class="slideshow banner banner--large grid grid--1-col slider slider--everywhere scroll-trigger animate--fade-in"
			id="Slider-rasa-hero" aria-live="polite" aria-atomic="true" data-autoplay="true" data-speed="5">
			<?php foreach ($hero_slides as $i => $slide):
				$n = $i + 1; ?>
				<style>
					#Slide-rasa-hero-<?php echo $n; ?> .banner__media::after {
						opacity: 0.2;
					}
				</style>
				<div class="slideshow__slide grid__item grid--1-col slider__slide" id="Slide-rasa-hero-<?php echo $n; ?>"
					role="group" aria-roledescription="Folie"
					aria-label="<?php echo esc_attr($n . ' von ' . count($hero_slides)); ?>" tabindex="-1">
					<div class="slideshow__media banner__media media">
						<picture>
							<source media="(max-width: 749px)"
								srcset="<?php echo esc_url($sec . '/' . $slide['mobile']); ?>" sizes="100vw">
							<img src="<?php echo esc_url($sec . '/' . $slide['desktop']); ?>"
								alt="<?php echo esc_attr($slide['alt']); ?>" height="1012" sizes="100vw" <?php echo 1 === $n ? 'fetchpriority="high"' : 'loading="lazy"'; ?>>
						</picture>
					</div>
					<div
						class="slideshow__text-wrapper banner__content banner__content--bottom-left page-width banner--desktop-transparent scroll-trigger animate--slide-in">
						<div
							class="slideshow__text banner__box content-container content-container--full-width-mobile color-scheme-3 gradient slideshow__text--left slideshow__text-mobile--left slideshow__box--split">
							<div class="slideshow__text-content">
								<h2 class="banner__heading rte inline-richtext hxl">
									<?php foreach ($slide['heads'] as $h): ?>
										<p><strong><?php echo esc_html($h); ?></strong></p><?php endforeach; ?>
								</h2>
								<div class="banner__text rte">
									<p><?php if ($slide['em']): ?><em><?php echo esc_html($slide['text']); ?></em><?php else: ?><strong><?php echo esc_html($slide['text']); ?></strong><?php endif; ?>
									</p>
								</div>
							</div>
							<div class="banner__buttons">
								<a href="<?php echo esc_url($all_url); ?>" class="button button--primary">Jetzt
									shoppen</a>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<div class="slideshow__controls slider-buttons">
			<div class="slider-counter slider-counter--dots">
				<div class="slideshow__control-wrapper">
					<?php foreach ($hero_slides as $i => $slide):
						$n = $i + 1; ?>
						<button
							class="slider-counter__link slider-counter__link--dots link<?php echo 1 === $n ? ' slider-counter__link--active' : ''; ?>"
							aria-label="Folie laden <?php echo $n; ?> von <?php echo count($hero_slides); ?>"
							aria-controls="Slider-rasa-hero"><span class="dot"></span></button>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</slideshow-component>
</section>

<!-- ===================== 2. FEATURED COLLECTION "Neuheiten" ===================== -->
<section id="shopify-section-featured_collection_1" class="shopify-section section">
	<?php echo rasa_featured_collection(array( // phpcs:ignore WordPress.Security.EscapeOutput
		'section_id' => 'featured_collection_1',
		'collection' => 'neuheiten',
		'title' => 'Neuheiten',
		'products_to_show' => 4,
		'columns_desktop' => 4,
		'columns_mobile' => 2,
		'show_view_all' => true,
		'view_all_label' => 'Alle anzeigen',
		'padding_top' => 44,
		'padding_bottom' => 36,
	)); ?>
</section>

<!-- ===================== 3. PRODUCT SPOTLIGHT 1 & 2 ===================== -->
<?php
$render_spotlight('1', $ps_items_1, 'left');
$render_spotlight('2', $ps_items_2, 'right');
?>

<!-- ===================== 4. COMING SOON "Bald im Shop" ===================== -->
<section id="shopify-section-coming_soon" class="shopify-section section">
	<div class="rasa-cs page-width section-template--31480989090175__coming_soon-padding" id="ComingSoon-rasa">
		<style>
			.section-template--31480989090175__coming_soon-padding {
				padding-top: 33px;
				padding-bottom: 27px;
			}

			@media screen and (min-width: 750px) {
				.section-template--31480989090175__coming_soon-padding {
					padding-top: 44px;
					padding-bottom: 36px;
				}
			}
		</style>
		<div class="rasa-cs__head">
			<h2 class="rasa-cs__heading h2">Bald im Shop</h2>
			<p class="rasa-cs__sub">Neue Gläser und Kaffees sind schon unterwegs. Trag dich ein und erfahre als Erste:r,
				wenn sie da sind.</p>
		</div>
		<ul class="rasa-cs__grid" role="list">
			<?php foreach ($cs_items as $it): ?>
				<li class="rasa-cs__card">
					<div class="rasa-cs__media">
						<img src="<?php echo esc_url($sec . '/' . $it['img']); ?>"
							alt="<?php echo esc_attr($it['title']); ?>" loading="lazy"
							sizes="(min-width: 990px) 25vw, 50vw" class="rasa-cs__img">
						<span class="rasa-cs__badge">Bald verfügbar</span>
					</div>
					<div class="rasa-cs__info">
						<span class="rasa-cs__label"><?php echo esc_html($it['label']); ?></span>
						<h3 class="rasa-cs__title"><?php echo esc_html($it['title']); ?></h3>
						<a href="#Notify-rasa" class="rasa-cs__btn"
							data-cs-tag="<?php echo esc_attr($it['tag']); ?>">Benachrichtige mich</a>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
		<div class="rasa-cs__form-wrap" id="Notify-rasa">
			<form method="post" action="<?php echo esc_url(home_url('/kontakt/#NotifyForm-rasa')); ?>"
				id="NotifyForm-rasa" accept-charset="UTF-8" class="rasa-cs__form">
				<?php wp_nonce_field('rasa_notify', 'rasa_notify_nonce'); ?>
				<div class="rasa-cs__form-text">
					<h3 class="rasa-cs__form-title">Sei als Erste:r dabei</h3>
					<p>Wähle ein Produkt und hinterlass deine E-Mail. Wir melden uns, sobald es verfügbar ist.</p>
				</div>
				<div class="rasa-cs__fields">
					<div class="rasa-cs__field">
						<label for="NotifyProduct-rasa">Produkt</label>
						<select id="NotifyProduct-rasa" name="rasa_notify_tag" class="rasa-cs__select" required>
							<option value="notify-me,notify-all">Alle neuen Produkte (allgemeines Update)</option>
							<?php foreach ($cs_items as $it): ?>
								<option value="notify-me,<?php echo esc_attr($it['tag']); ?>">
									<?php echo esc_html($it['title']); ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="rasa-cs__field">
						<label for="NotifyEmail-rasa">E-Mail-Adresse</label>
						<input id="NotifyEmail-rasa" type="email" name="rasa_notify_email" autocomplete="email" required
							class="rasa-cs__input">
					</div>
					<label class="rasa-cs__consent">
						<input type="checkbox" name="rasa_notify_consent" value="true" required>
						<span>Ja, informiert mich per E-Mail, sobald dieses Produkt verfügbar ist. Abmeldung jederzeit
							möglich. Mehr in der <a
								href="<?php echo esc_url(home_url('/datenschutz/')); ?>">Datenschutzerklärung</a>.</span>
					</label>
					<button type="submit" class="button button--primary rasa-cs__submit">Benachrichtige mich</button>
				</div>
			</form>
		</div>
	</div>
</section>

<!-- ===================== 5. B2B INFO BANNER ===================== -->
<section id="shopify-section-info_banner_1" class="shopify-section section">
	<div class="info-banner-section color-scheme-2 gradient section-template--31480989090175__info_banner_1-padding"
		id="b2b">
		<style>
			.section-template--31480989090175__info_banner_1-padding {
				padding-top: 21px;
				padding-bottom: 21px;
			}

			@media screen and (min-width: 750px) {
				.section-template--31480989090175__info_banner_1-padding {
					padding-top: 28px;
					padding-bottom: 28px;
				}
			}
		</style>
		<div class="page-width">
			<div class="info-banner__container info-banner__container--card">
				<div class="info-banner__content">
					<span class="info-banner__subheading">B2B · WHITE LABEL · PARTNER</span>
					<h2 class="info-banner__heading h2">Lass uns zusammenarbeiten.</h2>
					<div class="info-banner__text rte">
						<p>Für Cafés, Unternehmen, Events und Röstereien:</p>
						<ul>
							<li><strong>Porzellan &amp; Kaffeegläser</strong> mit deinem Logo</li>
							<li><strong>Merch</strong> für dein Team, dein Café oder dein Event</li>
							<li><strong>Specialty-Röstereien:</strong> präsentiere deine Bohnen in unserem Shop</li>
						</ul>
						<p>Erzähl uns, was du vorhast.</p>
					</div>
				</div>
				<div class="info-banner__action">
					<a href="<?php echo esc_url(home_url('/kontakt/')); ?>"
						class="button button--primary info-banner__button">ANFRAGE SENDEN</a>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- ===================== 6. MERCH IMAGE BANNER ===================== -->
<section id="shopify-section-image_banner_2" class="shopify-section section">
	<style data-shopify>
		#Banner-rasa-merch::after {
			opacity: 0.1;
		}
	</style>
	<div id="Banner-rasa-merch"
		class="banner banner--content-align-center banner--content-align-mobile-center banner--medium banner--desktop-transparent scroll-trigger animate--fade-in">
		<div class="banner__media media scroll-trigger animate--fade-in">
			<img src="<?php echo esc_url($sec . '/MERCH.png'); ?>" alt="" width="2500" height="2000" loading="lazy"
				sizes="100vw">
		</div>
		<div class="banner__content banner__content--middle-center page-width scroll-trigger animate--slide-in">
			<div class="banner__box content-container content-container--full-width-mobile color-scheme-3 gradient">
				<div class="banner__buttons">
					<a href="<?php echo esc_url($all_url); ?>" class="button button--primary">Jetzt shoppen</a>
				</div>
			</div>
		</div>
	</div>
	<style>
		#shopify-section-image_banner_2 .banner__buttons .button {
			background-color: #ffffff;
			color: #121212;
			border: 1px solid #ffffff;
			font-weight: 700;
			letter-spacing: 0.06em;
			text-transform: uppercase;
			box-shadow: 0 2px 10px rgba(0, 0, 0, 0.25);
		}

		#shopify-section-image_banner_2 .banner__buttons .button:hover {
			background-color: #f0f0f0;
		}
	</style>
</section>

<!-- ===================== 7. FEATURED COLLECTION "RASA Merchandise" ===================== -->
<section id="shopify-section-featured_collection_3" class="shopify-section section">
	<?php echo rasa_featured_collection(array( // phpcs:ignore WordPress.Security.EscapeOutput
		'section_id' => 'featured_collection_3',
		'collection' => 'rasa-merchandise',
		'title' => 'RASA Merchandise',
		'products_to_show' => 4,
		'columns_desktop' => 4,
		'columns_mobile' => 2,
		'show_view_all' => true,
		'view_all_label' => 'Alle anzeigen',
		'order' => 'ASC',
		'padding_top' => 44,
		'padding_bottom' => 36,
	)); ?>
</section>

<?php get_footer(); ?>