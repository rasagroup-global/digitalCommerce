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
})();
