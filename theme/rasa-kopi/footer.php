<?php
/**
 * footer.php — footer 2 kolom (Rasa Kopi menu + Rechtliches) + localization + payment + copyright
 * Markup faithful dengan theme Shopify Rasa.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$rasa_menu   = rasa_footer_menu_items( 'footer-rasa' );
$legal_menu  = rasa_footer_menu_items( 'footer-legal' );
?>
	</main><!-- #MainContent -->

<footer class="footer color-scheme-2 gradient section-footer-padding">
	<style>
		.section-footer-padding {
			padding-top: 36px;
			padding-bottom: 24px;
		}
		@media screen and (min-width: 750px) {
			.section-footer-padding {
				padding-top: 48px;
				padding-bottom: 32px;
			}
		}
		.footer__blocks-wrapper {
			margin-top: 2.4rem;
		}
		.footer-block__heading {
			margin-bottom: 1.2rem;
		}
		.footer-block__details-content .list-menu__item {
			padding-bottom: 0.55rem;
		}
	</style>

	<div class="footer__content-top page-width">
		<div class="footer__blocks-wrapper grid grid--1-col grid--2-col grid--4-col-tablet" data-cascade>
			<div class="footer-block grid__item footer-block--menu scroll-trigger animate--slide-in" data-cascade style="--animation-order: 1;">
				<h2 class="footer-block__heading inline-richtext">Rasa Kopi</h2>
				<ul class="footer-block__details-content list-unstyled">
					<?php foreach ( $rasa_menu as $item ) : ?>
					<li>
						<a href="<?php echo esc_url( $item['url'] ); ?>" class="link link--text list-menu__item list-menu__item--link">
							<?php echo esc_html( $item['title'] ); ?>
						</a>
					</li>
					<?php endforeach; ?>
				</ul>
			</div>
			<div class="footer-block grid__item footer-block--menu scroll-trigger animate--slide-in" data-cascade style="--animation-order: 2;">
				<h2 class="footer-block__heading inline-richtext">Rechtliches</h2>
				<ul class="footer-block__details-content list-unstyled">
					<?php foreach ( $legal_menu as $item ) : ?>
					<li>
						<a href="<?php echo esc_url( $item['url'] ); ?>" class="link link--text list-menu__item list-menu__item--link">
							<?php echo esc_html( $item['title'] ); ?>
						</a>
					</li>
					<?php endforeach; ?>
				</ul>
			</div>
			<div class="footer-block--newsletter scroll-trigger animate--slide-in" data-cascade></div>
		</div>

		<div class="footer__content-bottom scroll-trigger animate--slide-in" data-cascade>
			<div class="footer__content-bottom-wrapper page-width">
				<div class="footer__column footer__localization isolate">
					<?php $cur_lang = function_exists( 'rasa_current_lang' ) ? rasa_current_lang() : 'de'; ?>
					<localization-form>
						<form method="post" action="<?php echo esc_url( home_url( '/' ) ); ?>" id="FooterLanguageForm" class="localization-form">
							<input type="hidden" name="locale_code" value="<?php echo esc_attr( $cur_lang ); ?>" />
							<div class="no-js-hidden">
								<h2 class="caption-large text-body" id="FooterLanguageLabel">Sprache</h2>
								<div class="disclosure">
									<button type="button" class="disclosure__button localization-form__select localization-selector link link--text caption-large" aria-expanded="false" aria-controls="FooterLanguageList" aria-describedby="FooterLanguageLabel" aria-label="<?php echo $cur_lang === 'en' ? 'English' : 'Deutsch'; ?>">
										<?php if ( $cur_lang === 'en' ) : ?>
											<span class="rasa-flag" aria-hidden="true" style="display:inline-flex;align-items:center;vertical-align:middle;line-height:0"><svg viewBox="0 0 60 30" width="20" height="14" preserveAspectRatio="none" style="width:2rem;height:1.4rem;border-radius:2px;box-shadow:0 0 0 1px rgba(0,0,0,.15);display:block"><clipPath id="rf-s-footer-en"><path d="M0,0 v30 h60 v-30 z"/></clipPath><clipPath id="rf-t-footer-en"><path d="M30,15 h30 v15 z v15 h-30 z h-30 v-15 z v-15 h30 z"/></clipPath><g clip-path="url(#rf-s-footer-en)"><path d="M0,0 v30 h60 v-30 z" fill="#012169"/><path d="M0,0 L60,30 M60,0 L0,30" stroke="#fff" stroke-width="6"/><path d="M0,0 L60,30 M60,0 L0,30" clip-path="url(#rf-t-footer-en)" stroke="#C8102E" stroke-width="4"/><path d="M30,0 v30 M0,15 h60" stroke="#fff" stroke-width="10"/><path d="M30,0 v30 M0,15 h60" stroke="#C8102E" stroke-width="6"/></g></svg></span>
										<?php else : ?>
											<span class="rasa-flag" aria-hidden="true" style="display:inline-flex;align-items:center;vertical-align:middle;line-height:0"><svg viewBox="0 0 5 3" width="20" height="14" preserveAspectRatio="none" style="width:2rem;height:1.4rem;border-radius:2px;box-shadow:0 0 0 1px rgba(0,0,0,.15);display:block"><rect width="5" height="1" fill="#000"/><rect y="1" width="5" height="1" fill="#DD0000"/><rect y="2" width="5" height="1" fill="#FFCE00"/></svg></span>
										<?php endif; ?>
										<svg class="icon icon-caret" viewBox="0 0 10 6" width="10" height="6"><path fill="currentColor" fill-rule="evenodd" d="M9.354.646a.5.5 0 0 0-.708 0L5 4.293 1.354.646a.5.5 0 0 0-.708.708l4 4a.5.5 0 0 0 .708 0l4-4a.5.5 0 0 0 0-.708" clip-rule="evenodd"/></svg>
									</button>
									<div class="disclosure__list-wrapper language-selector" hidden>
										<ul id="FooterLanguageList" role="list" class="disclosure__list list-unstyled">
											<li class="disclosure__item" tabindex="-1">
												<a class="link link--text disclosure__link caption-large focus-inset" href="#" hreflang="de" lang="de" data-value="de">Deutsch</a>
											</li>
											<li class="disclosure__item" tabindex="-1">
												<a class="link link--text disclosure__link caption-large focus-inset" href="#" hreflang="en" lang="en" data-value="en">English</a>
											</li>
										</ul>
									</div>
								</div>
							</div>
						</form>
					</localization-form>
				</div>

				<div class="footer__column footer__column--info">
					<div class="footer__payment">
						<span class="visually-hidden">Zahlungsmethoden</span>
						<ul class="list list-payment" role="list">
							<?php $payment_icons = require RASA_DIR . '/inc/payment-icons.php'; ?>
							<?php foreach ( $payment_icons as $svg ) : ?>
							<li class="list-payment__item"><?php echo $svg; // phpcs:ignore WordPress.Security.EscapeOutput ?></li>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
			</div>

			<div class="footer__copyright caption">
				<small class="copyright__content">
					&copy; <?php echo esc_html( date_i18n( 'Y' ) ); ?>, <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="">Rasa Kopi</a>
				</small>
			</div>
		</div>
	</div>
</footer>

<div class="rasa-legal-bar color-scheme-2 gradient">
	<div class="rasa-legal-bar__inner page-width">
		<a
			href="https://connect.haendlerbund.de/widerrufsformular/shop-rasakopi-coffee/01M4VA27MEKAAG52YF6EFSKX7K"
			target="_blank"
			rel="noopener noreferrer"
			class="rasa-legal-bar__button"
		>Vertrag widerrufen</a>
	</div>
</div>

<?php wp_footer(); ?>
</body>
</html>
