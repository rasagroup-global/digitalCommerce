<?php
/**
 * header.php — announcement marquee + header (logo center, nav, search, cart)
 * Markup faithful dengan theme Shopify Rasa.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$cart_count = rasa_cart_count();
$announcements = rasa_announcements();
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" type="image/png" href="<?php echo esc_url( RASA_URI . '/assets/img/sections/rasa-kopi-favicon.png' ); ?>">
<link rel="apple-touch-icon" href="<?php echo esc_url( RASA_URI . '/assets/img/sections/rasa-kopi-favicon.png' ); ?>">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-to-content-link button visually-hidden" href="#MainContent"><?php esc_html_e( 'Direkt zum Inhalt', 'rasa-kopi' ); ?></a>

<!-- ===================== ANNOUNCEMENT BAR (marquee) ===================== -->
<div class="utility-bar color-scheme-6 gradient utility-bar--bottom-border">
	<div class="page-width utility-bar__grid">
		<div class="rasa-marquee" role="region" aria-label="<?php esc_attr_e( 'Ankündigung', 'rasa-kopi' ); ?>" style="--rasa-marquee-duration: 55s;">
			<div class="rasa-marquee__track">
				<?php for ( $g = 1; $g <= 2; $g++ ) : ?>
				<ul class="rasa-marquee__group list-unstyled"<?php echo 2 === $g ? ' aria-hidden="true"' : ''; ?>>
					<?php foreach ( $announcements as $a ) : ?>
					<li class="rasa-marquee__item announcement-bar__message h5">
						<?php if ( ! empty( $a['link'] ) ) : ?>
							<a href="<?php echo esc_url( $a['link'] ); ?>" class="rasa-marquee__link link link--text">
								<?php echo esc_html( $a['text'] ); ?>
							</a>
						<?php else : ?>
							<span><?php echo esc_html( $a['text'] ); ?></span>
						<?php endif; ?>
					</li>
					<?php endforeach; ?>
				</ul>
				<?php endfor; ?>
			</div>
		</div>
	</div>
</div>

<style>
	/* RASA: black bar + rose gold text (persis live shop.rasakopi.coffee) */
	.utility-bar {
		background: #000000 !important;
		--color-foreground: 237, 192, 174;
		color: #edc0ae;
		border-bottom-color: rgba(237, 192, 174, 0.25);
	}
	.rasa-marquee__item,
	.rasa-marquee__link {
		color: #edc0ae !important;
	}
	.rasa-marquee {
		grid-column: 1 / -1;
		overflow: hidden;
		width: 100%;
		padding: 1rem 0;
		-webkit-mask-image: linear-gradient(to right, transparent, #000 4%, #000 96%, transparent);
		mask-image: linear-gradient(to right, transparent, #000 4%, #000 96%, transparent);
	}
	.rasa-marquee__track {
		display: flex;
		width: max-content;
		animation: rasa-marquee-scroll var(--rasa-marquee-duration, 45s) linear infinite;
	}
	.rasa-marquee:hover .rasa-marquee__track,
	.rasa-marquee:focus-within .rasa-marquee__track {
		animation-play-state: paused;
	}
	.rasa-marquee__group {
		display: flex;
		flex-shrink: 0;
		align-items: center;
		margin: 0;
		padding: 0;
	}
	.rasa-marquee__item {
		display: flex;
		align-items: center;
		margin: 0;
		white-space: nowrap;
		text-transform: uppercase;
		letter-spacing: 0.1rem;
	}
	.rasa-marquee__item::after {
		content: '\2022';
		margin: 0 2.4rem;
		opacity: 0.6;
	}
	.rasa-marquee__link {
		color: inherit;
		text-decoration: none;
	}
	.rasa-marquee__link:hover {
		text-decoration: underline;
		text-underline-offset: 0.3rem;
	}
	@keyframes rasa-marquee-scroll {
		from { transform: translateX(0); }
		to { transform: translateX(-50%); }
	}
	@media (prefers-reduced-motion: reduce) {
		.rasa-marquee__track { animation: none; }
	}

	/* Header (persis inline CSS live header-group) */
	.header {
		padding: 10px 3rem 10px 3rem;
	}
	.section-header {
		position: sticky; /* fix z-index Safari (Dawn PR #2147) */
		margin-bottom: 0px;
		z-index: 4;
	}
	.header-wrapper {
		position: relative;
		z-index: 4;
	}
	@media screen and (min-width: 750px) {
		.section-header { margin-bottom: 0px; }
	}
	@media screen and (min-width: 990px) {
		.header {
			padding-top: 20px;
			padding-bottom: 20px;
		}
	}
	header-drawer {
		justify-self: start;
		margin-left: -1.2rem;
	}
	@media screen and (min-width: 990px) {
		header-drawer { display: none; }
	}
	.menu-drawer-container { display: flex; }
	.list-menu { list-style: none; padding: 0; margin: 0; }
	.list-menu--inline { display: inline-flex; flex-wrap: wrap; }
	summary.list-menu__item { padding-right: 2.7rem; }
	.list-menu__item { display: flex; align-items: center; line-height: calc(1 + 0.3 / var(--font-body-scale)); }
	.list-menu__item--link {
		text-decoration: none;
		padding-bottom: 1rem;
		padding-top: 1rem;
		line-height: calc(1 + 0.8 / var(--font-body-scale));
	}
	@media screen and (min-width: 750px) {
		.list-menu__item--link { padding-bottom: 0.5rem; padding-top: 0.5rem; }
	}
</style>

<!-- ===================== HEADER ===================== -->
<div id="shopify-section-header" class="shopify-section section-header">
<sticky-header data-sticky-type="on-scroll-up" class="header-wrapper color-scheme-2 gradient header-wrapper--border-bottom">
	<header class="header header--middle-center header--mobile-center page-width header--has-menu header--has-account header--has-localizations">

		<!-- Mobile Drawer (< 990px) -->
		<header-drawer data-breakpoint="tablet">
			<details id="Details-menu-drawer-container" class="menu-drawer-container">
				<summary class="header__icon header__icon--menu header__icon--summary link focus-inset" aria-label="<?php esc_attr_e( 'Menü', 'rasa-kopi' ); ?>">
					<span>
						<?php echo rasa_icon_raw( 'hamburger' ); ?>
						<?php echo rasa_icon_raw( 'close' ); ?>
					</span>
				</summary>
				<div id="menu-drawer" class="gradient menu-drawer motion-reduce color-scheme-2">
					<div class="menu-drawer__inner-container">
						<div class="menu-drawer__navigation-container">
							<nav class="menu-drawer__navigation">
								<ul class="menu-drawer__menu has-submenu list-menu" role="list">
									<?php foreach ( rasa_main_menu_items() as $link ) : ?>
									<li>
										<a href="<?php echo esc_url( $link['url'] ); ?>" class="menu-drawer__menu-item list-menu__item link link--text focus-inset<?php echo ! empty( $link['current'] ) ? ' menu-drawer__menu-item--active' : ''; ?>">
											<?php echo esc_html( $link['title'] ); ?>
										</a>
									</li>
									<?php endforeach; ?>
								</ul>
							</nav>
						</div>
					</div>
				</div>
			</details>
		</header-drawer>

		<!-- Logo (middle-center) -->
		<h1 class="header__heading">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="header__heading-link link link--text focus-inset">
				<div class="header__heading-logo-wrapper">
					<img
						src="<?php echo esc_url( RASA_URI . '/assets/img/logo.png' ); ?>"
						srcset="<?php echo esc_url( RASA_URI . '/assets/img/logo.png' ); ?> 190w, <?php echo esc_url( RASA_URI . '/assets/img/logo.png' ); ?> 285w, <?php echo esc_url( RASA_URI . '/assets/img/logo.png' ); ?> 380w"
						alt="Rasa Kopi"
						class="header__heading-logo"
						width="190"
						height="45"
						loading="eager"
						sizes="(min-width: 750px) 190px, 50vw"
					>
				</div>
			</a>
		</h1>

		<!-- Nav (dropdown menu) -->
		<nav class="header__inline-menu">
			<ul class="list-menu list-menu--inline" role="list">
				<?php foreach ( rasa_main_menu_items() as $link ) : ?>
					<?php if ( ! empty( $link['children'] ) ) : ?>
					<header-menu>
						<details id="Details-HeaderMenu-<?php echo esc_attr( sanitize_title( $link['title'] ) ); ?>">
							<summary id="HeaderMenu-<?php echo esc_attr( sanitize_title( $link['title'] ) ); ?>" class="header__menu-item list-menu__item link focus-inset">
								<span<?php echo $link['current'] ? ' class="header__active-menu-item"' : ''; ?>><?php echo esc_html( $link['title'] ); ?></span>
								<?php echo rasa_icon( 'caret', 'svg-wrapper' ); ?>
							</summary>
							<ul class="header__submenu list-menu list-menu--disclosure color-scheme-2 gradient caption-large motion-reduce global-settings-popup" role="list" tabindex="-1">
								<?php foreach ( $link['children'] as $child ) : ?>
								<li>
									<a href="<?php echo esc_url( $child['url'] ); ?>" class="header__menu-item list-menu__item link link--text focus-inset caption-large<?php echo ! empty( $child['current'] ) ? ' list-menu__item--active' : ''; ?>">
										<?php echo esc_html( $child['title'] ); ?>
									</a>
								</li>
								<?php endforeach; ?>
							</ul>
						</details>
					</header-menu>
					<?php else : ?>
					<li>
						<a id="HeaderMenu-<?php echo esc_attr( sanitize_title( $link['title'] ) ); ?>" href="<?php echo esc_url( $link['url'] ); ?>" class="header__menu-item list-menu__item link link--text focus-inset" <?php echo ! empty( $link['current'] ) ? 'aria-current="page"' : ''; ?>>
							<span<?php echo ! empty( $link['current'] ) ? ' class="header__active-menu-item"' : ''; ?>><?php echo esc_html( $link['title'] ); ?></span>
						</a>
					</li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		</nav>

		<!-- Icons: language + search + account + cart -->
		<?php $cur_lang = function_exists( 'rasa_current_lang' ) ? rasa_current_lang() : 'de'; ?>
		<div class="header__icons header__icons--localization header-localization">
			<!-- Language (markup persis live desktop HeaderLanguageForm) -->
			<div class="desktop-localization-wrapper">
				<localization-form class="small-hide medium-hide" data-prevent-hide>
					<form method="post" action="<?php echo esc_url( home_url( '/' ) ); ?>" id="HeaderLanguageForm" class="localization-form">
						<input type="hidden" name="locale_code" value="<?php echo esc_attr( $cur_lang ); ?>" />
						<div>
							<h2 class="visually-hidden" id="HeaderLanguageLabel"><?php esc_html_e( 'Sprache', 'rasa-kopi' ); ?></h2>
							<div class="disclosure">
								<button
									type="button"
									class="disclosure__button localization-form__select localization-selector link link--text caption-large"
									aria-expanded="false"
									aria-controls="HeaderLanguageList"
									aria-describedby="HeaderLanguageLabel"
									aria-label="<?php echo $cur_lang === 'en' ? 'English' : 'Deutsch'; ?>"
									title="<?php echo $cur_lang === 'en' ? 'English' : 'Deutsch'; ?>"
								><span>
								<?php if ( $cur_lang === 'en' ) : ?>
									<span class="rasa-flag" aria-hidden="true" style="display:inline-flex;align-items:center;vertical-align:middle;line-height:0"><svg viewBox="0 0 60 30" width="20" height="14" preserveAspectRatio="none" style="width:2rem;height:1.4rem;border-radius:2px;box-shadow:0 0 0 1px rgba(0,0,0,.15);display:block"><clipPath id="rf-s-cur-en"><path d="M0,0 v30 h60 v-30 z"/></clipPath><clipPath id="rf-t-cur-en"><path d="M30,15 h30 v15 z v15 h-30 z h-30 v-15 z v-15 h30 z"/></clipPath><g clip-path="url(#rf-s-cur-en)"><path d="M0,0 v30 h60 v-30 z" fill="#012169"/><path d="M0,0 L60,30 M60,0 L0,30" stroke="#fff" stroke-width="6"/><path d="M0,0 L60,30 M60,0 L0,30" clip-path="url(#rf-t-cur-en)" stroke="#C8102E" stroke-width="4"/><path d="M30,0 v30 M0,15 h60" stroke="#fff" stroke-width="10"/><path d="M30,0 v30 M0,15 h60" stroke="#C8102E" stroke-width="6"/></g></svg></span>
								<?php else : ?>
									<span class="rasa-flag" aria-hidden="true" style="display:inline-flex;align-items:center;vertical-align:middle;line-height:0"><svg viewBox="0 0 5 3" width="20" height="14" preserveAspectRatio="none" style="width:2rem;height:1.4rem;border-radius:2px;box-shadow:0 0 0 1px rgba(0,0,0,.15);display:block"><rect width="5" height="1" fill="#000"/><rect y="1" width="5" height="1" fill="#DD0000"/><rect y="2" width="5" height="1" fill="#FFCE00"/></svg></span>
								<?php endif; ?>
								</span>
									<?php echo rasa_icon_raw( 'caret' ); ?>
								</button>
								<div class="disclosure__list-wrapper language-selector" hidden>
									<ul id="HeaderLanguageList" role="list" class="disclosure__list list-unstyled">
										<li class="disclosure__item" tabindex="-1">
											<a class="link link--text disclosure__link caption-large focus-inset" href="#" hreflang="de" lang="de" <?php echo $cur_lang === 'de' ? 'aria-current="true"' : ''; ?> data-value="de" aria-label="Deutsch" title="Deutsch">
												<span<?php echo $cur_lang !== 'de' ? ' class="visibility-hidden"' : ''; ?>><svg xmlns="http://www.w3.org/2000/svg" fill="none" class="icon icon-checkmark" viewBox="0 0 12 9"><path fill="currentColor" fill-rule="evenodd" d="M11.35.643a.5.5 0 0 1 .006.707l-6.77 6.886a.5.5 0 0 1-.719-.006L.638 4.845a.5.5 0 1 1 .724-.69l2.872 3.011 6.41-6.517a.5.5 0 0 1 .707-.006z" clip-rule="evenodd"/></svg></span><span style="display:inline-flex;align-items:center;gap:0.8rem">
													<span class="rasa-flag" aria-hidden="true" style="display:inline-flex;align-items:center;vertical-align:middle;line-height:0"><svg viewBox="0 0 5 3" width="20" height="14" preserveAspectRatio="none" style="width:2rem;height:1.4rem;border-radius:2px;box-shadow:0 0 0 1px rgba(0,0,0,.15);display:block"><rect width="5" height="1" fill="#000"/><rect y="1" width="5" height="1" fill="#DD0000"/><rect y="2" width="5" height="1" fill="#FFCE00"/></svg></span>
													Deutsch
												</span>
											</a>
										</li>
										<li class="disclosure__item" tabindex="-1">
											<a class="link link--text disclosure__link caption-large focus-inset" href="#" hreflang="en" lang="en" <?php echo $cur_lang === 'en' ? 'aria-current="true"' : ''; ?> data-value="en" aria-label="English" title="English">
												<span<?php echo $cur_lang !== 'en' ? ' class="visibility-hidden"' : ''; ?>><svg xmlns="http://www.w3.org/2000/svg" fill="none" class="icon icon-checkmark" viewBox="0 0 12 9"><path fill="currentColor" fill-rule="evenodd" d="M11.35.643a.5.5 0 0 1 .006.707l-6.77 6.886a.5.5 0 0 1-.719-.006L.638 4.845a.5.5 0 1 1 .724-.69l2.872 3.011 6.41-6.517a.5.5 0 0 1 .707-.006z" clip-rule="evenodd"/></svg></span><span style="display:inline-flex;align-items:center;gap:0.8rem">
													<span class="rasa-flag" aria-hidden="true" style="display:inline-flex;align-items:center;vertical-align:middle;line-height:0"><svg viewBox="0 0 60 30" width="20" height="14" preserveAspectRatio="none" style="width:2rem;height:1.4rem;border-radius:2px;box-shadow:0 0 0 1px rgba(0,0,0,.15);display:block"><clipPath id="rf-s-headerlanguage-lang-en-gb"><path d="M0,0 v30 h60 v-30 z"/></clipPath><clipPath id="rf-t-headerlanguage-lang-en-gb"><path d="M30,15 h30 v15 z v15 h-30 z h-30 v-15 z v-15 h30 z"/></clipPath><g clip-path="url(#rf-s-headerlanguage-lang-en-gb)"><path d="M0,0 v30 h60 v-30 z" fill="#012169"/><path d="M0,0 L60,30 M60,0 L0,30" stroke="#fff" stroke-width="6"/><path d="M0,0 L60,30 M60,0 L0,30" clip-path="url(#rf-t-headerlanguage-lang-en-gb)" stroke="#C8102E" stroke-width="4"/><path d="M30,0 v30 M0,15 h60" stroke="#fff" stroke-width="10"/><path d="M30,0 v30 M0,15 h60" stroke="#C8102E" stroke-width="6"/></g></svg></span>
													English
												</span>
											</a>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</form>
				</localization-form>
			</div>
			<!-- Search modal -->
			<details-modal class="header__search">
				<details>
					<summary
						class="header__icon header__icon--search header__icon--summary link focus-inset modal__toggle"
						aria-haspopup="dialog"
					>
						<span class="svg-wrapper"><?php echo rasa_icon_raw( 'search' ); ?></span>
						<span class="svg-wrapper header__icon-close"><?php echo rasa_icon_raw( 'close-small' ); ?></span>
						<span class="visually-hidden"><?php esc_html_e( 'Suche', 'rasa-kopi' ); ?></span>
					</summary>
					<div class="search-modal modal__content gradient" id="SearchModal" role="dialog" aria-modal="true" aria-labelledby="SearchModalLabel" tabindex="-1">
						<div class="modal-overlay"></div>
						<div class="search-modal__content search-modal__content-bottom">
							<span id="SearchModalLabel" class="visually-hidden"><?php esc_html_e( 'Suche', 'rasa-kopi' ); ?></span>
							<form action="<?php echo esc_url( home_url( '/?s=' ) ); ?>" method="get" role="search" class="search search-modal__form">
								<div class="field">
									<input
										id="Search-In-Modal"
										class="search__input field__input"
										type="search"
										name="s"
										aria-label="<?php esc_attr_e( 'Suche', 'rasa-kopi' ); ?>"
										autocapitalize="off"
										autocomplete="off"
										autocorrect="off"
										placeholder="<?php esc_attr_e( 'Produkte durchsuchen…', 'rasa-kopi' ); ?>"
										required
									>
									<label class="field__label" for="Search-In-Modal"><?php esc_html_e( 'Suche', 'rasa-kopi' ); ?></label>
									<button class="search__button field__button" aria-label="<?php esc_attr_e( 'Suche', 'rasa-kopi' ); ?>">
										<span class="svg-wrapper"><?php echo rasa_icon_raw( 'search' ); ?></span>
									</button>
								</div>
							</form>
							<button type="button" class="search-modal__close-button modal__close-button link link--text focus-inset" aria-label="<?php esc_attr_e( 'Schließen', 'rasa-kopi' ); ?>">
								<span class="svg-wrapper"><?php echo rasa_icon_raw( 'close' ); ?></span>
							</button>
						</div>
					</div>
				</details>
			</details-modal>

			<!-- Account -->
			<a href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : home_url( '/my-account/' ) ); ?>" class="header__icon header__icon--account link focus-inset">
				<span class="svg-wrapper"><?php echo rasa_icon_raw( 'account' ); ?></span>
				<span class="visually-hidden"><?php esc_html_e( 'Konto', 'rasa-kopi' ); ?></span>
			</a>

			<!-- Cart -->
			<a href="<?php echo esc_url( function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/cart/' ) ); ?>" class="header__icon header__icon--cart link focus-inset" id="cart-icon-bubble">
				<?php if ( $cart_count > 0 ) : ?>
					<span class="svg-wrapper"><?php echo rasa_icon_raw( 'cart' ); ?></span>
					<div class="cart-count-bubble">
						<?php if ( $cart_count < 100 ) : ?>
							<span aria-hidden="true"><?php echo (int) $cart_count; ?></span>
						<?php endif; ?>
						<span class="visually-hidden"><?php printf( esc_html__( '%s Artikel im Warenkorb', 'rasa-kopi' ), (int) $cart_count ); ?></span>
					</div>
				<?php else : ?>
					<span class="svg-wrapper"><?php echo rasa_icon_raw( 'cart-empty' ); ?></span>
				<?php endif; ?>
				<span class="visually-hidden"><?php esc_html_e( 'Warenkorb', 'rasa-kopi' ); ?></span>
			</a>
		</div>
	</header>
</sticky-header>
</div>

<main id="MainContent" class="content" role="main" tabindex="-1">
