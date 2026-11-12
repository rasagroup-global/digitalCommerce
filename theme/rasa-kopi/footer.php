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
					<div class="footer__localization-content">
						<h2 class="caption-large text-body" id="FooterLanguageLabel">Sprache</h2>
						<div class="disclosure">
							<button type="button" class="disclosure__button localization-form__select localization-selector link link--text caption-large" aria-expanded="false" aria-label="Deutsch" title="Deutsch">
								<span class="rasa-flag" aria-hidden="true" style="display:inline-flex;align-items:center;vertical-align:middle;line-height:0"><svg viewBox="0 0 5 3" width="20" height="14" preserveAspectRatio="none" style="width:2rem;height:1.4rem;border-radius:2px;box-shadow:0 0 0 1px rgba(0,0,0,.15);display:block"><rect width="5" height="1" fill="#000"/><rect y="1" width="5" height="1" fill="#DD0000"/><rect y="2" width="5" height="1" fill="#FFCE00"/></svg></span>
								<svg class="icon icon-caret" viewBox="0 0 10 6" width="10" height="6"><path fill="currentColor" fill-rule="evenodd" d="M9.354.646a.5.5 0 0 0-.708 0L5 4.293 1.354.646a.5.5 0 0 0-.708.708l4 4a.5.5 0 0 0 .708 0l4-4a.5.5 0 0 0 0-.708" clip-rule="evenodd"/></svg>
							</button>
						</div>
					</div>
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
