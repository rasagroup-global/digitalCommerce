
  (function() {
    var root = document.querySelector('[id^="ComingSoon-"]');
    if (!root) return;
    var select = root.querySelector('.rasa-cs__select');
    root.querySelectorAll('.rasa-cs__btn').forEach(function(btn) {
      btn.addEventListener('click', function() {
        if (!select) return;
        var want = 'notify-me,' + btn.dataset.csTag;
        for (var i = 0; i < select.options.length; i++) {
          if (select.options[i].value === want) { select.selectedIndex = i; break; }
        }
        setTimeout(function() {
          var email = root.querySelector('.rasa-cs__input');
          if (email) email.focus({ preventScroll: true });
        }, 400);
      });
    });
  })();
