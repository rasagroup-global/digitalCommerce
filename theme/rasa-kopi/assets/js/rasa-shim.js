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
	window.Shopify.actions = window.Shopify.actions || {
		openCart: { configure: function () {} },
		updateCart: { configure: function () {} }
	};

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

	// AJAX Add-to-Cart + Slide-out Cart Drawer (no page redirect/reload)
	function initRasaCartDrawer() {
		var drawer = document.getElementById('RasaCartDrawer');
		var bodyEl = document.getElementById('RasaCartDrawerBody');
		var footerEl = document.getElementById('RasaCartDrawerFooter');
		var subtotalEl = document.getElementById('RasaCartSubtotal');
		var checkoutBtn = document.getElementById('RasaCartCheckoutBtn');
		var cfg = window.RasaTheme || {};
		if (!drawer || !bodyEl || !cfg.ajaxUrl) return;

		function esc(s) {
			return String(s || '').replace(/[&<>"']/g, function (c) {
				return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[c];
			});
		}

		function updateHeaderBubble(count) {
			var bubbleWrap = document.getElementById('cart-icon-bubble');
			if (!bubbleWrap) return;
			var bubble = bubbleWrap.querySelector('.cart-count-bubble');
			if (count > 0) {
				if (!bubble) {
					bubble = document.createElement('div');
					bubble.className = 'cart-count-bubble';
					bubble.innerHTML = '<span aria-hidden="true"></span>';
					bubbleWrap.appendChild(bubble);
				}
				var span = bubble.querySelector('span[aria-hidden="true"]');
				if (span) span.textContent = count < 100 ? String(count) : '99+';
			} else if (bubble) {
				bubble.remove();
			}
		}

		function renderCart(data) {
			if (!data) return;
			updateHeaderBubble(data.count || 0);
			if (checkoutBtn && data.checkoutUrl) checkoutBtn.href = data.checkoutUrl;
			var items = data.items || [];
			if (!items.length) {
				bodyEl.innerHTML = '<div class="rasa-cart-drawer__empty">Dein Warenkorb ist leer.</div>';
				if (footerEl) footerEl.hidden = true;
				return;
			}
			bodyEl.innerHTML = items.map(function (it) {
				return '<div class="rasa-cart-drawer__item" data-key="' + esc(it.key) + '">'
					+ (it.image ? '<a href="' + esc(it.url) + '"><img class="rasa-cart-drawer__img" src="' + esc(it.image) + '" alt="' + esc(it.name) + '"></a>' : '<div class="rasa-cart-drawer__img"></div>')
					+ '<div>'
					+ '<a class="rasa-cart-drawer__title" href="' + esc(it.url) + '">' + esc(it.name) + '</a>'
					+ (it.variation ? '<div class="rasa-cart-drawer__meta">' + esc(it.variation) + '</div>' : '')
					+ '<div style="display:flex;align-items:center;">'
					+ '<div class="rasa-cart-drawer__qty">'
					+ '<button type="button" data-rasa-qty="' + (it.qty - 1) + '" aria-label="-">−</button>'
					+ '<span>' + it.qty + '</span>'
					+ '<button type="button" data-rasa-qty="' + (it.qty + 1) + '" aria-label="+">+</button>'
					+ '</div>'
					+ '<button type="button" class="rasa-cart-drawer__remove" data-rasa-qty="0" aria-label="Remove">✕</button>'
					+ '</div>'
					+ '</div>'
					+ '<div class="rasa-cart-drawer__price">' + esc(it.line_total) + '</div>'
					+ '</div>';
			}).join('');
			if (subtotalEl) subtotalEl.textContent = data.subtotal || '';
			if (footerEl) footerEl.hidden = false;
		}

		function openDrawer() {
			drawer.classList.add('active');
			drawer.setAttribute('aria-hidden', 'false');
			document.body.classList.add('overflow-hidden');
		}
		function closeDrawer() {
			drawer.classList.remove('active');
			drawer.setAttribute('aria-hidden', 'true');
			document.body.classList.remove('overflow-hidden');
		}

		drawer.querySelectorAll('[data-rasa-drawer-close]').forEach(function (el) {
			el.addEventListener('click', closeDrawer);
		});
		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && drawer.classList.contains('active')) closeDrawer();
		});

		bodyEl.addEventListener('click', function (e) {
			var btn = e.target.closest('[data-rasa-qty]');
			if (!btn) return;
			var row = btn.closest('[data-key]');
			if (!row) return;
			var fd = new FormData();
			fd.append('action', 'rasa_update_cart_item');
			fd.append('nonce', cfg.nonce || '');
			fd.append('cart_item_key', row.getAttribute('data-key'));
			fd.append('quantity', btn.getAttribute('data-rasa-qty'));
			fetch(cfg.ajaxUrl, { method: 'POST', body: fd, credentials: 'same-origin' })
				.then(function (r) { return r.json(); })
				.then(function (res) { if (res && res.success) renderCart(res.data); });
		});

		var cartIcon = document.getElementById('cart-icon-bubble');
		if (cartIcon) {
			cartIcon.addEventListener('click', function (e) {
				e.preventDefault();
				var fd = new FormData();
				fd.append('action', 'rasa_get_cart');
				fd.append('nonce', cfg.nonce || '');
				fetch(cfg.ajaxUrl, { method: 'POST', body: fd, credentials: 'same-origin' })
					.then(function (r) { return r.json(); })
					.then(function (res) {
						if (res && res.success) renderCart(res.data);
						openDrawer();
					})
					.catch(function () { window.location.href = cartIcon.href; });
			});
		}

		var pdpForm = document.getElementById('RasaPdpForm');
		if (pdpForm) {
			pdpForm.addEventListener('submit', function (e) {
				e.preventDefault();
				var errBox = document.getElementById('RasaPdpError');
				if (errBox) { errBox.hidden = true; errBox.textContent = ''; }
				var submitBtn = pdpForm.querySelector('.product-form__submit');
				var origText = submitBtn ? submitBtn.textContent : '';
				if (submitBtn) { submitBtn.disabled = true; submitBtn.style.opacity = '0.7'; }

				var fd = new FormData(pdpForm);
				fd.append('action', 'rasa_add_to_cart');
				fd.append('nonce', cfg.nonce || '');
				if (!fd.get('product_id') && fd.get('add-to-cart')) {
					fd.set('product_id', fd.get('add-to-cart'));
				}

				fetch(cfg.ajaxUrl, { method: 'POST', body: fd, credentials: 'same-origin' })
					.then(function (r) { return r.json(); })
					.then(function (res) {
						if (res && res.success) {
							renderCart(res.data);
							openDrawer();
						} else {
							var msg = (res && res.data && res.data.message) ? res.data.message : 'Produkt konnte nicht in den Warenkorb gelegt werden.';
							if (errBox) { errBox.textContent = msg; errBox.hidden = false; }
						}
					})
					.catch(function () {
						pdpForm.submit();
					})
					.finally(function () {
						if (submitBtn) { submitBtn.disabled = false; submitBtn.style.opacity = '1'; submitBtn.textContent = origText; }
					});
			});
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initRasaCartDrawer);
	} else {
		initRasaCartDrawer();
	}
})();
