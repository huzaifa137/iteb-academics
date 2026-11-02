/*!
 * ITEB Academics - responsive helpers  (public/assets/js/responsive.js)
 *
 * Plain JavaScript, no dependencies. Loaded from the footer-scripts partials
 * of both layouts. Pairs with public/assets/css/responsive.css.
 *
 *  1. Wraps wide tables in a horizontal scroll container (phones/tablets).
 *     Also handles tables that are injected later (modals, AJAX).
 *  2. Dims the page behind the slide-in menu and lets a tap outside close it.
 *  3. Re-fires "resize" after the menu opens/closes so charts redraw.
 *  4. Dev aid: add ?ra-debug to any URL to outline elements that are wider
 *     than the screen (see the console for the list).
 */
(function () {
    'use strict';

    var BREAKPOINT = '(max-width: 991.98px)';
    var mq = window.matchMedia ? window.matchMedia(BREAKPOINT) : null;

    function isCompact() {
        return mq ? mq.matches : window.innerWidth < 992;
    }

    function onMqChange(fn) {
        if (!mq) { window.addEventListener('resize', fn); return; }
        if (mq.addEventListener) { mq.addEventListener('change', fn); }
        else if (mq.addListener) { mq.addListener(fn); }
    }

    /* ----------------------------------------------------------------------
     * 1. Scrollable tables
     * -------------------------------------------------------------------- */
    var SKIP_ANCESTORS = [
        '.table-responsive',
        '.ra-table-scroll',
        '.dataTables_wrapper',
        '.dataTables_scroll',
        '[data-ra-skip]'
    ].join(',');

    function ownedByDataTables(table) {
        if (/data-?table/i.test(table.id + ' ' + table.className)) { return true; }
        var $ = window.jQuery;
        try {
            return !!($ && $.fn && $.fn.dataTable && $.fn.dataTable.isDataTable(table));
        } catch (e) {
            return false;
        }
    }

    function needsWrap(table) {
        if (!table.parentNode) { return false; }
        if (table.closest(SKIP_ANCESTORS)) { return false; }
        if (table.parentNode.closest && table.parentNode.closest('table')) { return false; } // nested table
        if (ownedByDataTables(table)) { return false; }
        return true;
    }

    function wrapTables(root) {
        if (!isCompact()) { return; }
        var tables = (root || document).querySelectorAll('table');
        for (var i = 0; i < tables.length; i++) {
            var t = tables[i];
            if (!needsWrap(t)) { continue; }
            var box = document.createElement('div');
            box.className = 'ra-table-scroll';
            t.parentNode.insertBefore(box, t);
            box.appendChild(t);
        }
    }

    function watchForNewTables() {
        if (!window.MutationObserver || !document.body) { return; }
        var queued = false;
        var schedule = window.requestAnimationFrame || function (fn) { return setTimeout(fn, 16); };

        function hasTable(node) {
            return node.nodeType === 1 &&
                (node.tagName === 'TABLE' || (node.querySelector && node.querySelector('table')));
        }

        new MutationObserver(function (records) {
            if (queued || !isCompact()) { return; }
            for (var i = 0; i < records.length; i++) {
                var added = records[i].addedNodes;
                for (var j = 0; j < added.length; j++) {
                    if (hasTable(added[j])) {
                        queued = true;
                        schedule(function () { queued = false; wrapTables(document); });
                        return;
                    }
                }
            }
        }).observe(document.body, { childList: true, subtree: true });
    }

    /* ----------------------------------------------------------------------
     * 2 + 3. Slide-in menu: overlay, tap-outside-to-close, chart resize
     * -------------------------------------------------------------------- */
    var sidebars = [];
    var overlay = null;

    function findSidebars() {
        sidebars = Array.prototype.slice.call(document.querySelectorAll('.app-sidebar'));
        return sidebars.length > 0;
    }

    // The menu is "open" when it is really on screen - this does not depend on
    // which CSS class the theme toggles for which screen size.
    function menuIsOpen() {
        if (!isCompact()) { return false; }
        for (var i = 0; i < sidebars.length; i++) {
            var el = sidebars[i];
            var cs = window.getComputedStyle(el);
            if (cs.display === 'none' || cs.visibility === 'hidden') { continue; }
            var r = el.getBoundingClientRect();
            if (r.width > 0 && r.right > 40 && r.left < window.innerWidth - 40) { return true; }
        }
        return false;
    }

    function overlayZIndex() {
        var min = Infinity;
        sidebars.forEach(function (el) {
            var z = parseInt(window.getComputedStyle(el).zIndex, 10);
            if (!isNaN(z) && z < min) { min = z; }
        });
        return isFinite(min) ? String(Math.max(min - 1, 1)) : '';
    }

    function syncOverlay() {
        if (!overlay) { return; }
        var open = menuIsOpen();
        overlay.style.zIndex = overlayZIndex();
        overlay.classList.toggle('is-visible', open);
    }

    function closeMenu() {
        var toggle = document.querySelector('[data-toggle="sidebar"]');
        if (toggle && menuIsOpen()) { toggle.click(); }
        setTimeout(syncOverlay, 60);
        setTimeout(syncOverlay, 400);
    }

    function setupMenu() {
        if (!findSidebars()) { return; }

        overlay = document.createElement('div');
        overlay.className = 'ra-overlay';
        overlay.setAttribute('aria-hidden', 'true');
        overlay.addEventListener('click', closeMenu);
        document.body.appendChild(overlay);

        document.addEventListener('click', function (e) {
            if (!e.target.closest || !e.target.closest('[data-toggle="sidebar"]')) { return; }
            // the theme flips its state first, then the slide animation runs
            setTimeout(syncOverlay, 60);
            setTimeout(syncOverlay, 400);
            // let charts / DataTables re-measure once the layout has settled
            setTimeout(function () {
                try { window.dispatchEvent(new Event('resize')); } catch (err) { /* old browsers */ }
            }, 400);
        }, true);

        sidebars.forEach(function (el) {
            el.addEventListener('transitionend', syncOverlay);
        });

        document.addEventListener('keydown', function (e) {
            if ((e.key === 'Escape' || e.keyCode === 27) && menuIsOpen()) { closeMenu(); }
        });

        window.addEventListener('resize', syncOverlay);
        window.addEventListener('orientationchange', function () { setTimeout(syncOverlay, 300); });
        onMqChange(syncOverlay);
        syncOverlay();
    }

    /* ----------------------------------------------------------------------
     * 4. Dev aid:  https://your-site/any-page?ra-debug
     * -------------------------------------------------------------------- */
    function debugOverflow() {
        if (!/[?&]ra-debug(?:[=&]|$)/.test(window.location.search)) { return; }

        function clipped(el, vw) {
            for (var p = el.parentElement; p && p !== document.body; p = p.parentElement) {
                if (window.getComputedStyle(p).overflowX !== 'visible') {
                    var pr = p.getBoundingClientRect();
                    if (pr.left >= -1 && pr.right <= vw + 1) { return true; }
                }
            }
            return false;
        }

        setTimeout(function () {
            var vw = document.documentElement.clientWidth;
            var all = document.body.querySelectorAll('*');
            var bad = [];
            for (var i = 0; i < all.length; i++) {
                var el = all[i];
                if (el.closest('.app-sidebar, .ra-overlay, .modal:not(.show), .dropdown-menu:not(.show), .select2-container--open, script, style')) { continue; }
                var r = el.getBoundingClientRect();
                if (r.width === 0 || r.height === 0) { continue; }
                if ((r.right > vw + 1 || r.left < -1) && !clipped(el, vw)) {
                    el.style.outline = '2px solid #e11d48';
                    bad.push(el);
                }
            }
            if (window.console) {
                console.warn('[ra-debug] viewport ' + vw + 'px - ' + bad.length + ' element(s) stick out:', bad);
            }
        }, 900);
    }

    /* ---------------------------------------------------------------------- */
    function init() {
        setupMenu();
        debugOverflow();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Wait for "load" so DataTables & friends (initialised on DOM ready) have
    // already built their own wrappers before we decide what still needs one.
    function afterLoad() {
        wrapTables(document);
        watchForNewTables();
        onMqChange(function () { wrapTables(document); });
    }

    if (document.readyState === 'complete') {
        afterLoad();
    } else {
        window.addEventListener('load', afterLoad);
    }
})();
