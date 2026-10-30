/* ITEB – public contact page: small progressive enhancements. The form works without this file. */
(function () {
    'use strict';

    var form = document.getElementById('contact-form');
    if (!form) { return; }

    var btn = document.getElementById('send-btn');
    var label = btn ? btn.querySelector('.btn-label') : null;
    var area = document.getElementById('f-message');
    var counter = document.getElementById('msg-count');

    function updateCount() {
        if (area && counter) { counter.textContent = String(area.value.length); }
    }
    if (area) { area.addEventListener('input', updateCount); updateCount(); }

    // Fires only after the browser's own validation passes.
    form.addEventListener('submit', function () {
        if (!btn) { return; }
        btn.disabled = true;
        btn.classList.add('is-loading');
        if (label) { label.textContent = 'Sending…'; }
    });

    // Coming back with the browser's back button must not leave the button stuck.
    window.addEventListener('pageshow', function (e) {
        if (e.persisted && btn) {
            btn.disabled = false;
            btn.classList.remove('is-loading');
            if (label) { label.textContent = 'Send message'; }
        }
    });

    // After a failed submit, bring the first problem into view and focus it.
    var firstError = form.querySelector('.has-error .input');
    if (firstError) {
        setTimeout(function () {
            firstError.scrollIntoView({ block: 'center', behavior: 'smooth' });
            try { firstError.focus({ preventScroll: true }); } catch (e) { firstError.focus(); }
        }, 250);
    }
})();
