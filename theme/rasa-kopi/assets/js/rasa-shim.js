/* ------------------------------------------------------------------ *
 * rasa-shim.js — shim globals Shopify untuk JS Dawn di WordPress
 * Dipakai: StandardEvents (parse-time di cart.js), routes, strings.
 * ------------------------------------------------------------------ */
(function () {
	// Dawn import StandardEvents dari CDN Shopify (module). Di Woo tidak ada,
	// jadi kita sediakan class stub yang behavior-nya no-op tapi struktur sama.
	if (!window.StandardEvents) {
		window.StandardEvents = {
			createViewEventElement: function (Element) {
				return Element;
			},
			CartLinesUpdateEvent: function () {},
			CartErrorEvent: function () {},
			CartNoteUpdateEvent: function () {},
		};
	}

	if (typeof window.Shopify === 'undefined') {
		window.Shopify = {};
	}
	window.Shopify.designMode = window.Shopify.designMode || false;
	window.Shopify.bind = window.Shopify.bind || function (el, ev, fn) { el.addEventListener(ev, fn); };
	window.Shopify.addListener = window.Shopify.addListener || function (ev, fn) { document.addEventListener(ev, fn); };
	window.Shopify.postLink = window.Shopify.postLink || function (action, options) {
		var form = document.createElement('form');
		form.method = 'post';
		form.action = action;
		for (var k in (options && options.data || {})) {
			var input = document.createElement('input');
			input.type = 'hidden';
			input.name = k;
			input.value = options.data[k];
			form.appendChild(input);
		}
		document.body.appendChild(form);
		form.submit();
	};
	window.Shopify.loadFeatures = window.Shopify.loadFeatures || function () {};
	window.Shopify.setSelectorByValue = window.Shopify.setSelectorByValue || function (id, value) {
		var el = document.getElementById(id);
		if (el) el.value = value;
	};
	window.Shopify.CountryProvinceSelector = window.Shopify.CountryProvinceSelector || function () {};
	window.Shopify.ModelViewerUI = window.Shopify.ModelViewerUI || {};
	window.Shopify.PaymentButton = window.Shopify.PaymentButton || { init: function () {} };
	window.Shopify.actions = window.Shopify.actions || {};

	// Dawn StickyHeader (on-scroll-up: hide saat scroll down, muncul saat scroll up)
	if (!customElements.get('sticky-header')) {
		customElements.define(
			'sticky-header',
			class StickyHeader extends HTMLElement {
				connectedCallback() {
					this.header = document.getElementById('shopify-section-header');
					this.headerIsAlwaysSticky = this.getAttribute('data-sticky-type') === 'always';
					this.headerBounds = {};
					this.currentScrollTop = 0;
					this.preventReveal = false;

					if (!this.header) return;
					this.setHeaderHeight();
					window.addEventListener('resize', () => this.setHeaderHeight());

					this.observer = new IntersectionObserver((entries) => {
						this.headerBounds = entries[0].intersectionRect;
					});
					this.observer.observe(this.header);

					window.addEventListener('scroll', this.onScroll.bind(this), { passive: true });
				}

				setHeaderHeight() {
					document.documentElement.style.setProperty('--header-height', `${this.header.offsetHeight}px`);
				}

				onScroll() {
					const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
					const topBound = (this.headerBounds && this.headerBounds.bottom) ? this.headerBounds.bottom : 140;

					if (scrollTop > this.currentScrollTop && scrollTop > topBound) {
						if (this.preventHide) { this.currentScrollTop = scrollTop; return; }
						requestAnimationFrame(() => {
							this.header.classList.add('shopify-section-header-hidden', 'shopify-section-header-sticky');
						});
					} else if (scrollTop < this.currentScrollTop && scrollTop > topBound) {
						requestAnimationFrame(() => {
							this.header.classList.add('shopify-section-header-sticky', 'animate');
							this.header.classList.remove('shopify-section-header-hidden');
						});
					} else if (scrollTop <= (this.headerBounds ? this.headerBounds.top : 0)) {
						requestAnimationFrame(() => {
							this.header.classList.remove('shopify-section-header-hidden', 'shopify-section-header-sticky', 'animate');
						});
					}
					this.currentScrollTop = scrollTop;
				}
			}
		);
	}

	// Smooth skeleton shimmer -> fade-in saat image selesai load
	function initImagePreloaders() {
		var containerSel = '.card__media, .rasa-cs__media, .product-spotlight__media, .rasa-pdp-gallery__item, .banner__media';
		var imgs = document.querySelectorAll('.card__media img, .rasa-cs__media img, .product-spotlight__media img, .rasa-pdp-gallery__item img, .banner__media img');
		function markLoaded(img) {
			img.classList.add('is-loaded');
			var box = img.closest(containerSel);
			if (box) box.classList.add('is-loaded');
		}
		imgs.forEach(function (img) {
			if (img.complete && img.naturalWidth > 0) {
				markLoaded(img);
			} else {
				img.addEventListener('load', function () { markLoaded(img); }, { once: true });
				img.addEventListener('error', function () { markLoaded(img); }, { once: true });
			}
		});
	}
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initImagePreloaders);
	} else {
		initImagePreloaders();
	}
})();
