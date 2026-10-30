/* ==========================================================================
   ITEB – public landing page
   Vanilla JS, no dependencies. The page is fully readable without it; this
   adds the navigation behaviour, counters, filtering and reveal animations.
   ========================================================================== */
(function () {
    'use strict';

    var doc = document;
    var reduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var hasIO = 'IntersectionObserver' in window;

    function $(s, c) { return (c || doc).querySelector(s); }
    function $$(s, c) { return Array.prototype.slice.call((c || doc).querySelectorAll(s)); }

    // ------------------------------------------------------------------
    //  Navigation: scrolled state, mobile menu, active section
    // ------------------------------------------------------------------
    var nav = $('#nav');
    var toggle = $('#nav-toggle');
    var toTop = $('#to-top');

    function onScroll() {
        var y = window.pageYOffset || doc.documentElement.scrollTop;
        if (nav) { nav.classList.toggle('is-light', y > 24); }
        if (toTop) { toTop.classList.toggle('is-visible', y > 700); }
        // Above the first section nothing should look "active".
        if (y < 300) { $$('#nav-links a.is-active').forEach(function (a) { a.classList.remove('is-active'); }); }
    }
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();

    function setMenu(open) {
        if (!nav || !toggle) { return; }
        nav.classList.toggle('is-open', open);
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        toggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
    }

    if (toggle) {
        toggle.addEventListener('click', function () { setMenu(!nav.classList.contains('is-open')); });
        doc.addEventListener('keydown', function (e) { if (e.key === 'Escape') { setMenu(false); toggle.focus(); } });
        doc.addEventListener('click', function (e) { if (nav.classList.contains('is-open') && !nav.contains(e.target)) { setMenu(false); } });
        $$('#nav-links a').forEach(function (a) { a.addEventListener('click', function () { setMenu(false); }); });
        window.addEventListener('resize', function () { if (window.innerWidth > 960) { setMenu(false); } });
    }

    if (toTop) {
        toTop.addEventListener('click', function () { window.scrollTo({ top: 0, behavior: reduced ? 'auto' : 'smooth' }); });
    }

    // Highlight the nav link of the section currently in view.
    var links = $$('#nav-links a[href^="#"]');
    var map = {};
    links.forEach(function (a) { map[a.getAttribute('href').slice(1)] = a; });

    if (hasIO && links.length) {
        var spy = new IntersectionObserver(function (entries) {
            entries.forEach(function (en) {
                if (!en.isIntersecting) { return; }
                links.forEach(function (a) { a.classList.remove('is-active'); });
                var link = map[en.target.id];
                if (link) { link.classList.add('is-active'); }
            });
        }, { rootMargin: '-45% 0px -50% 0px', threshold: 0 });

        Object.keys(map).forEach(function (id) { var el = doc.getElementById(id); if (el) { spy.observe(el); } });
    }

    // Keep anchor targets clear of the sticky header.
    doc.documentElement.style.scrollPaddingTop = '84px';

    // ------------------------------------------------------------------
    //  Reveal on scroll
    // ------------------------------------------------------------------
    var revealEls = $$('[data-reveal]');
    if (hasIO && !reduced) {
        var rev = new IntersectionObserver(function (entries) {
            entries.forEach(function (en) {
                if (en.isIntersecting) { en.target.classList.add('is-in'); rev.unobserve(en.target); }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
        revealEls.forEach(function (el) { rev.observe(el); });
    } else {
        revealEls.forEach(function (el) { el.classList.add('is-in'); });
    }

    // ------------------------------------------------------------------
    //  Animated counters
    // ------------------------------------------------------------------
    function fmt(n) { return Math.round(n).toLocaleString('en-US'); }

    function runCount(el) {
        var to = parseFloat(el.getAttribute('data-to'));
        if (isNaN(to)) { return; }
        if (reduced) { el.textContent = fmt(to); return; }
        var t0 = performance.now(), dur = 1600;
        (function step(t) {
            var p = Math.min(1, (t - t0) / dur);
            var e = 1 - Math.pow(1 - p, 4);
            el.textContent = fmt(to * e);
            if (p < 1) { requestAnimationFrame(step); } else { el.textContent = fmt(to); }
        })(t0);
    }

    var counts = $$('.count[data-to]');
    if (hasIO) {
        var co = new IntersectionObserver(function (entries) {
            entries.forEach(function (en) {
                if (en.isIntersecting) { runCount(en.target); co.unobserve(en.target); }
            });
        }, { threshold: 0.4 });
        counts.forEach(function (el) { el.textContent = '0'; co.observe(el); });
    }

    // ------------------------------------------------------------------
    //  Registration progress ring
    // ------------------------------------------------------------------
    $$('.hc-ring').forEach(function (ring) {
        var bar = $('.ring-bar', ring);
        if (!bar) { return; }
        var pct = Math.max(0, Math.min(100, parseFloat(ring.getAttribute('data-progress')) || 0));
        var C = 2 * Math.PI * 52;
        setTimeout(function () { bar.style.strokeDashoffset = String(C * (1 - pct / 100)); }, reduced ? 0 : 450);
    });

    // ------------------------------------------------------------------
    //  Subject filter
    // ------------------------------------------------------------------
    var chips = $$('.chip[data-filter]');
    var cards = $$('#subjects-grid .subject');

    function applyFilter(f) {
        chips.forEach(function (c) {
            var on = c.getAttribute('data-filter') === f;
            c.classList.toggle('is-active', on);
            c.setAttribute('aria-pressed', on ? 'true' : 'false');
        });
        var n = 0;
        cards.forEach(function (card) {
            var lvl = card.getAttribute('data-level');
            var show = f === 'all' || lvl === f || lvl === 'both';
            card.hidden = !show;
            if (show) {
                // restart the entrance animation
                card.style.animation = 'none';
                void card.offsetWidth;
                card.style.animation = '';
                card.style.animationDelay = (n++ * 35) + 'ms';
            }
        });
    }
    chips.forEach(function (c) { c.addEventListener('click', function () { applyFilter(c.getAttribute('data-filter')); }); });

    // ------------------------------------------------------------------
    //  FAQ: one open at a time
    // ------------------------------------------------------------------
    var faqs = $$('#faq-list details');
    faqs.forEach(function (d) {
        d.addEventListener('toggle', function () {
            if (d.open) { faqs.forEach(function (o) { if (o !== d) { o.open = false; } }); }
        });
    });
})();
