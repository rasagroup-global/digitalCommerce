
(function () {
function initPsSlider(root) {
	if (!root || root.dataset.ready) return;
	root.dataset.ready = '1';
	var section = root.closest('.product-spotlight-section');
	if (!section) return;
	var track = root.querySelector('.ps-track');
	var slides = root.querySelectorAll('.ps-slide');
	var dots = root.querySelectorAll('.ps-dot');
	var swatches = section.querySelectorAll('.ps-swatch');
	var names = section.querySelectorAll('.ps-name');
	var titleLink = section.querySelector('.ps-title-link');
	var priceEl = section.querySelector('.product-spotlight__price');
	var detailsLink = section.querySelector('.ps-details-link');
	var form = section.querySelector('.product-spotlight__form');
	var idInput = form ? form.querySelector('input[name="id"]') : null;
	var addBtn = form ? form.querySelector('button[name="add"]') : null;
	var buyOnly = section.querySelectorAll('.ps-buy-only');
	var comingBadge = section.querySelector('.ps-coming-badge');
	var notify = section.querySelector('.ps-notify');
	var notifyTags = section.querySelector('.ps-notify__tags');
	var current = 0, timer = null;
	function render() {
		dots.forEach(function (d, n) { d.classList.toggle('is-active', n === current); });
		swatches.forEach(function (s, n) { s.classList.toggle('product-spotlight__swatch--active', n === current); });
		names.forEach(function (s, n) { s.classList.toggle('is-active', n === current); });
		var d = slides[current].dataset;
		var coming = !!d.coming;
		if (titleLink) {
			titleLink.textContent = d.title;
			if (coming) { titleLink.removeAttribute('href'); } else { titleLink.href = d.url; }
		}
		if (priceEl) { priceEl.textContent = d.price || ''; priceEl.style.display = (!coming && d.price) ? '' : 'none'; }
		if (detailsLink) detailsLink.href = d.url;
		buyOnly.forEach(function (el) { el.style.display = coming ? 'none' : ''; });
		if (comingBadge) comingBadge.hidden = !coming;
		if (notify) notify.style.display = coming ? '' : 'none';
		if (notifyTags && d.tag) notifyTags.value = 'notify-me,' + d.tag;
		if (form) {
			form.style.display = d.hasProduct ? '' : 'none';
			if (idInput && d.variant) idInput.value = d.variant;
			if (addBtn) {
				addBtn.disabled = !d.available;
				addBtn.textContent = d.available ? root.dataset.add : root.dataset.soldOut;
			}
		}
	}
	if (slides.length < 2) { render(); return; }
	function go(i) {
		current = (i + slides.length) % slides.length;
		track.scrollTo({ left: current * track.clientWidth, behavior: 'smooth' });
		render();
	}
	var prev = root.querySelector('.ps-arrow--prev');
	var next = root.querySelector('.ps-arrow--next');
	if (prev) prev.addEventListener('click', function () { go(current - 1); restart(); });
	if (next) next.addEventListener('click', function () { go(current + 1); restart(); });
	dots.forEach(function (d) { d.addEventListener('click', function () { go(parseInt(d.dataset.index, 10)); restart(); }); });
	swatches.forEach(function (s) { s.addEventListener('click', function () { go(parseInt(s.dataset.index, 10)); restart(); }); });
	var t;
	track.addEventListener('scroll', function () {
		clearTimeout(t);
		t = setTimeout(function () {
			var i = Math.round(track.scrollLeft / track.clientWidth);
			if (i !== current) { current = i; render(); }
		}, 80);
	});
	var hovering = false;
	function start() {
		if (root.dataset.autoplay !== 'true' || hovering || timer) return;
		var s = (parseInt(root.dataset.speed, 10) || 5) * 1000;
		timer = setInterval(function () { go(current + 1); }, s);
	}
	function stop() { clearInterval(timer); timer = null; }
	function restart() { stop(); start(); }
	[root, section.querySelector('.product-spotlight__swatches'), notify].forEach(function (el) {
		if (!el) return;
		el.addEventListener('mouseenter', function () { hovering = true; stop(); });
		el.addEventListener('mouseleave', function () { hovering = false; start(); });
	});
	if (notify) notify.addEventListener('focusin', function () { hovering = true; stop(); });
	root.addEventListener('touchstart', function () { hovering = true; stop(); }, { passive: true });
	root.psGo = go;
	render();
	if (!(notify && notify.querySelector('.ps-notify__thanks, .ps-notify__error'))) start();
}
function initAll() {
	document.querySelectorAll('[id^="PsSlider-"]').forEach(initPsSlider);
}
if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', initAll);
} else {
	initAll();
}
document.addEventListener('shopify:section:load', initAll);
})();
