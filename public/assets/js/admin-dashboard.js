/* ==========================================================================
   ITEB Academics - Admin dashboard
   Renders the payload produced by App\Services\AdminDashboardService.
   Vanilla JS + the bundled ApexCharts. No other dependencies.
   ========================================================================== */
(function () {
    'use strict';

    var root = document.getElementById('iteb-dash');
    var initialEl = document.getElementById('dash-initial');
    if (!root || !initialEl) { return; }

    // ------------------------------------------------------------------
    //  State & constants
    // ------------------------------------------------------------------
    var DATA_URL = root.getAttribute('data-url');
    var AUTO_MS = 120000;

    var state = {
        payload: JSON.parse(initialEl.textContent),
        year: null,
        category: null,
        range: 'daily',
        charts: {},
        timer: null,
        reqId: 0,
        lastSig: null
    };
    state.year = state.payload.filters.year;
    state.category = state.payload.filters.category;

    var C = {
        green: '#1E7A3D', gold: '#C9A227', amber: '#E0A526', red: '#D64545', slate: '#94A3B8',
        blue: '#3D6FB6', teal: '#0E7C86', purple: '#7C5CBF', rose: '#D6456B', indigo: '#4F5BD5',
        axis: '#64748b', grid: '#eef2ee'
    };
    var TONE_COLOR = {
        green: C.green, amber: '#C98A0B', teal: C.teal, indigo: C.indigo, gold: '#A98410',
        slate: '#55657A', rose: '#C93C68', purple: '#7352B8', red: '#C53B3B', blue: '#2F66B3'
    };
    var REDUCED = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // ------------------------------------------------------------------
    //  Small helpers
    // ------------------------------------------------------------------
    function $(sel, scope) { return (scope || root).querySelector(sel); }
    function $$(sel, scope) { return Array.prototype.slice.call((scope || root).querySelectorAll(sel)); }

    function esc(v) {
        return String(v === null || v === undefined ? '' : v)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;').replace(/'/g, '&#39;');
    }

    function num(n, decimals) {
        if (n === null || n === undefined || isNaN(n)) { return '—'; }
        return Number(n).toLocaleString('en-US', {
            minimumFractionDigits: decimals || 0, maximumFractionDigits: decimals || 0
        });
    }

    function sum(arr) { return (arr || []).reduce(function (a, b) { return a + (Number(b) || 0); }, 0); }
    function plural(n, one, many) { return n + ' ' + (n === 1 ? one : many); }

    function mixHex(h1, h2, t) {
        function parse(h) { return [1, 3, 5].map(function (i) { return parseInt(h.slice(i, i + 2), 16); }); }
        var a = parse(h1), b = parse(h2);
        return '#' + a.map(function (v, i) {
            return Math.round(v + (b[i] - v) * t).toString(16).padStart(2, '0');
        }).join('');
    }

    /** n colours running best (green) to worst (red). */
    function ramp(n) {
        var stops = ['#1E7A3D', '#6DB36B', '#E0C247', '#EE9A3A', '#D64545'];
        if (n <= 1) { return [stops[0]]; }
        var out = [];
        for (var i = 0; i < n; i++) {
            var t = (i / (n - 1)) * (stops.length - 1);
            var a = Math.floor(t), b = Math.min(stops.length - 1, a + 1);
            out.push(mixHex(stops[a], stops[b], t - a));
        }
        return out;
    }

    function setHtml(sel, html) { var el = typeof sel === 'string' ? $(sel) : sel; if (el) { el.innerHTML = html; } }

    function toast(message, type) {
        var el = $('#dash-toast');
        if (!el) { return; }
        el.textContent = message;
        el.className = 'dash-toast is-on' + (type === 'error' ? ' error' : '');
        clearTimeout(toast._t);
        toast._t = setTimeout(function () { el.className = 'dash-toast'; }, 4500);
    }

    // ------------------------------------------------------------------
    //  Chart plumbing
    // ------------------------------------------------------------------
    function destroyChart(key) {
        var c = state.charts[key];
        if (c) { try { c.destroy(); } catch (e) { /* ignore */ } delete state.charts[key]; }
    }

    function destroyChartsWithPrefix(prefix) {
        Object.keys(state.charts).forEach(function (k) { if (k.indexOf(prefix) === 0) { destroyChart(k); } });
    }

    function emptyState(key, el, icon, message) {
        destroyChart(key);
        if (el) {
            el.innerHTML = '<div class="empty"><i class="fa-solid ' + icon + '"></i><div>' + esc(message) + '</div></div>';
            el.removeAttribute('role');
        }
    }

    function mountChart(key, el, options, label) {
        if (!el) { return; }
        destroyChart(key);
        el.innerHTML = '';
        if (typeof window.ApexCharts === 'undefined') {
            emptyState(key, el, 'fa-chart-simple', 'Charts could not be loaded.');
            return;
        }
        try {
            if (label) { el.setAttribute('role', 'img'); el.setAttribute('aria-label', label); }
            var chart = new window.ApexCharts(el, options);
            state.charts[key] = chart;
            chart.render();
        } catch (e) {
            if (window.console) { console.error('Chart "' + key + '" failed', e); }
            emptyState(key, el, 'fa-triangle-exclamation', 'This chart could not be drawn.');
        }
    }

    function baseChart(type, height, extra) {
        var chart = {
            type: type, height: height, fontFamily: 'inherit', foreColor: C.axis,
            toolbar: { show: false },
            animations: { enabled: !REDUCED, easing: 'easeinout', speed: 700 }
        };
        Object.keys(extra || {}).forEach(function (k) { chart[k] = extra[k]; });
        return chart;
    }

    var GRID = { borderColor: C.grid, strokeDashArray: 4, padding: { left: 6, right: 6 } };

    // ------------------------------------------------------------------
    //  Hero, toolbar, alerts
    // ------------------------------------------------------------------
    function renderHero(p) {
        var now = new Date();
        var h = now.getHours();
        setHtml('#hero-greeting', h < 12 ? 'Good morning' : (h < 17 ? 'Good afternoon' : 'Good evening'));
        setHtml('#hero-date', esc(now.toLocaleDateString('en-GB', {
            weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
        })));

        var yearText = state.year === 'all' ? 'All years' : 'Admission year ' + state.year;
        if (state.category !== 'all') { yearText += ' · ' + (state.category === 'ID' ? 'Idaad' : 'Thanawi'); }
        setHtml('#chip-year span', esc(yearText));

        var w = p.window || {};
        var chip = $('#chip-window');
        chip.className = 'chip is-' + (w.state || 'none');
        setHtml('#chip-window span:last-child', esc(w.label || ''));

        var html;
        if (w.state === 'none') {
            html = '<h3>Registration period</h3><div class="win-name">Not configured</div>' +
                '<div class="win-dates"><span>Open a period from Student approvals to let schools register.</span></div>';
        } else {
            var big = '';
            if (w.state === 'open' && w.days_left !== null && w.days_left !== undefined) {
                big = '<div class="win-big">' + num(w.days_left) + '<small>day' + (w.days_left === 1 ? '' : 's') + ' left</small></div>';
            } else if (w.state === 'upcoming') {
                big = '<div class="win-big" style="font-size:20px">Opens ' + esc(w.opens) + '</div>';
            } else {
                big = '<div class="win-big" style="font-size:20px">Closed</div>';
            }
            html = '<h3>Registration period</h3><div class="win-name">' + esc(w.name || 'Current period') + '</div>' +
                '<div class="win-track"><i id="win-bar" style="width:0"></i></div>' +
                '<div class="win-dates"><span>' + esc(w.opens || '—') + '</span><span>' + esc(w.closes || '—') + '</span></div>' + big;
        }
        setHtml('#hero-window', html);
        var bar = $('#win-bar');
        if (bar) { setTimeout(function () { bar.style.width = (w.progress || 0) + '%'; }, 60); }
    }

    function renderUpdated(p) {
        var t = new Date(p.meta.generated_at);
        var text = isNaN(t) ? 'Updated' : 'Updated ' + t.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        setHtml('#tb-updated-text', esc(text));
    }

    function renderAlerts(p) {
        var html = (p.alerts || []).map(function (a, i) {
            var inner = '<span class="a-icon"><i class="fa-solid ' + esc(a.icon) + '"></i></span>' +
                '<span class="a-text">' + esc(a.text) + '</span>' +
                (a.cta ? '<span class="a-cta">' + esc(a.cta) + ' →</span>' : '');
            return a.url
                ? '<a class="alert-item tone-' + esc(a.tone) + '" style="--i:' + i + '" href="' + esc(a.url) + '">' + inner + '</a>'
                : '<div class="alert-item tone-' + esc(a.tone) + '" style="--i:' + i + '">' + inner + '</div>';
        }).join('');
        setHtml('#alerts', html);
    }

    // ------------------------------------------------------------------
    //  KPI cards
    // ------------------------------------------------------------------
    function deltaHtml(d, label) {
        if (!d) { return ''; }
        var icon = d.direction === 'up' ? 'fa-arrow-trend-up' : (d.direction === 'down' ? 'fa-arrow-trend-down' : 'fa-minus');
        var text = d.percent === null ? 'New' : d.percent + '%';
        return '<span class="delta ' + esc(d.direction) + '" title="' + esc(label || '') + '"><i class="fa-solid ' + icon + '"></i>' + esc(text) + '</span>';
    }

    function kpiHtml(k, i) {
        var hasValue = k.value !== null && k.value !== undefined;
        var tag = k.url ? 'a' : 'div';
        var href = k.url ? ' href="' + esc(k.url) + '"' : '';
        return '<' + tag + href + ' class="kpi tone-' + esc(k.tone) + (k.pulse ? ' is-pulse' : '') + '" style="--i:' + i + '">' +
            '<div class="kpi-top"><span class="kpi-icon"><i class="fa-solid ' + esc(k.icon) + '"></i></span>' + deltaHtml(k.delta, k.delta_label) + '</div>' +
            '<div class="kpi-value"><span class="count" data-to="' + (hasValue ? esc(k.value) : '') + '" data-decimals="' + (k.decimals || 0) + '">' +
            (hasValue ? num(k.value, k.decimals) : '—') + '</span>' +
            (hasValue && k.suffix ? '<small>' + esc(k.suffix) + '</small>' : '') + '</div>' +
            '<div class="kpi-label">' + esc(k.label) + '</div>' +
            '<div class="kpi-sub">' + esc(k.sub || '') + '</div>' +
            (k.progress !== undefined && k.progress !== null ? '<div class="kpi-bar"><i data-w="' + esc(k.progress) + '"></i></div>' : '') +
            (k.spark && k.spark.length > 1 ? '<div class="kpi-spark" id="spark-' + esc(k.key) + '"></div>' : '') +
            '</' + tag + '>';
    }

    function animateCounts(scope, animate) {
        $$('.count', scope).forEach(function (el) {
            var raw = el.getAttribute('data-to');
            if (raw === '' || raw === null) { return; }
            var to = parseFloat(raw), dec = parseInt(el.getAttribute('data-decimals'), 10) || 0;
            if (!animate || REDUCED) { el.textContent = num(to, dec); return; }
            var t0 = performance.now(), dur = 900;
            (function step(t) {
                var p = Math.min(1, (t - t0) / dur), e = 1 - Math.pow(1 - p, 3);
                el.textContent = num(to * e, dec);
                if (p < 1) { requestAnimationFrame(step); } else { el.textContent = num(to, dec); }
            })(t0);
        });
    }

    function renderKpis(p, animate) {
        destroyChartsWithPrefix('spark-');
        setHtml('#kpis', (p.kpis || []).map(kpiHtml).join(''));
        var grid = $('#kpis');
        animateCounts(grid, animate);

        $$('.kpi-bar i', grid).forEach(function (bar) {
            setTimeout(function () { bar.style.width = bar.getAttribute('data-w') + '%'; }, 80);
        });

        (p.kpis || []).forEach(function (k) {
            if (!k.spark || k.spark.length < 2) { return; }
            var color = TONE_COLOR[k.tone] || C.green;
            mountChart('spark-' + k.key, $('#spark-' + k.key), {
                chart: baseChart('area', 44, { sparkline: { enabled: true } }),
                series: [{ name: k.label, data: k.spark }],
                colors: [color],
                stroke: { curve: 'smooth', width: 2 },
                fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.35, opacityTo: 0.02, stops: [0, 95, 100] } },
                tooltip: { fixed: { enabled: false }, x: { show: false }, y: { title: { formatter: function () { return ''; } } }, marker: { show: false } }
            });
        });

        var pending = (p.pipeline && p.pipeline.totals) ? p.pipeline.totals.pending : 0;
        $$('[data-badge="pending"]').forEach(function (b) {
            b.textContent = pending > 99 ? '99+' : pending;
            b.className = 'badge-count' + (pending > 0 ? ' is-on' : '');
        });
    }

    // ------------------------------------------------------------------
    //  Registration charts
    // ------------------------------------------------------------------
    function renderTrend() {
        var t = state.payload.trend[state.range];
        var el = $('#chart-trend');
        if (!t || sum(t.registered) + sum(t.submitted) === 0) {
            return emptyState('trend', el, 'fa-chart-area', 'No registration activity in this period.');
        }
        mountChart('trend', el, {
            chart: baseChart('area', 320, {
                toolbar: { show: true, tools: { download: true, selection: false, zoom: false, zoomin: false, zoomout: false, pan: false, reset: false } }
            }),
            series: [{ name: 'Registered', data: t.registered }, { name: 'Submitted to ITEB', data: t.submitted }],
            colors: [C.green, C.gold],
            stroke: { curve: 'smooth', width: 3 },
            fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.32, opacityTo: 0.02, stops: [0, 90, 100] } },
            dataLabels: { enabled: false },
            markers: { size: 0, hover: { size: 5 } },
            xaxis: {
                categories: t.labels,
                labels: {
                    rotate: 0, style: { colors: C.axis, fontSize: '11px' },
                    // ApexCharts 3.10 ignores tickAmount on category axes, so thin the labels ourselves.
                    formatter: function (v) {
                        var step = state.range === 'daily' ? 4 : 2;
                        return t.labels.indexOf(v) % step === 0 ? v : '';
                    }
                },
                axisBorder: { show: false }, axisTicks: { show: false }, tooltip: { enabled: false }
            },
            yaxis: { min: 0, forceNiceScale: true, labels: { formatter: function (v) { return num(Math.round(v)); }, style: { colors: C.axis } } },
            grid: GRID,
            legend: { position: 'top', horizontalAlign: 'right', markers: { radius: 12 } },
            tooltip: { shared: true, intersect: false }
        }, 'Registrations and submissions over time');
    }

    function donutOptions(labels, series, colors, totalLabel, height) {
        return {
            chart: baseChart('donut', height || 290),
            series: series, labels: labels, colors: colors,
            stroke: { width: 3, colors: ['#fff'] },
            dataLabels: { enabled: false },
            legend: { position: 'bottom', fontSize: '12.5px', markers: { radius: 12 }, itemMargin: { horizontal: 8, vertical: 3 } },
            plotOptions: {
                pie: {
                    expandOnClick: false,
                    donut: {
                        size: '70%',
                        labels: {
                            show: true,
                            name: { show: true, fontSize: '13px', color: C.axis },
                            value: { show: true, fontSize: '26px', fontWeight: 800, color: '#0f2418', formatter: function (v) { return num(v); } },
                            total: {
                                show: true, label: totalLabel,
                                formatter: function (w) { return num(w.globals.seriesTotals.reduce(function (a, b) { return a + b; }, 0)); }
                            }
                        }
                    }
                }
            },
            tooltip: { y: { formatter: function (v) { return num(v); } } }
        };
    }

    function renderPipeline(p) {
        var slices = p.pipeline.slices.filter(function (s) { return s.value > 0; });
        var el = $('#chart-pipeline');
        if (!slices.length) {
            emptyState('pipeline', el, 'fa-clipboard-list', 'No registrations for these filters yet.');
        } else {
            mountChart('pipeline', el, donutOptions(
                slices.map(function (s) { return s.label; }),
                slices.map(function (s) { return s.value; }),
                slices.map(function (s) { return s.color; }),
                'Registrations'
            ), 'Registration status breakdown');
        }
        var rate = p.pipeline.approval_rate;
        setHtml('#pipeline-foot',
            '<span>Approval rate <strong>' + (rate === null ? '—' : rate + '%') + '</strong> of decided</span>' +
            '<span>' + num(p.pipeline.total) + ' total</span>');
    }

    function renderTopSchools(p) {
        var rows = p.top_schools || [];
        var el = $('#chart-top-schools');
        if (!rows.length) { return emptyState('top', el, 'fa-school', 'No school has registered students for these filters yet.'); }

        mountChart('top', el, {
            chart: baseChart('bar', Math.max(300, rows.length * 42 + 80), {
                stacked: true,
                events: {
                    dataPointSelection: function (e, ctx, cfg) {
                        var r = rows[cfg.dataPointIndex];
                        if (r && r.url) { window.location.href = r.url; }
                    }
                }
            }),
            plotOptions: { bar: { horizontal: true, barHeight: '66%' } },
            series: [
                { name: 'Approved', data: rows.map(function (r) { return r.approved; }) },
                { name: 'Awaiting approval', data: rows.map(function (r) { return r.pending; }) },
                { name: 'Returned', data: rows.map(function (r) { return r.returned; }) },
                { name: 'Still with school', data: rows.map(function (r) { return r.with_school; }) }
            ],
            colors: [C.green, C.amber, C.red, C.slate],
            dataLabels: {
                enabled: true,
                formatter: function (v) { return v >= 6 ? v : ''; },
                style: { fontSize: '11px', fontWeight: 700, colors: ['#fff'] }
            },
            xaxis: {
                categories: rows.map(function (r) { return r.short; }),
                labels: { formatter: function (v) { return num(Math.round(v)); }, style: { colors: C.axis } },
                axisBorder: { show: false }, axisTicks: { show: false }
            },
            yaxis: { labels: { maxWidth: 190, style: { colors: rows.map(function () { return '#1f3128'; }), fontSize: '12.5px', fontWeight: 600 } } },
            grid: GRID,
            legend: { position: 'top', horizontalAlign: 'left', markers: { radius: 12 } },
            tooltip: {
                shared: false,
                x: { formatter: function (v, o) { return (o && o.dataPointIndex !== undefined && rows[o.dataPointIndex]) ? rows[o.dataPointIndex].name : v; } },
                y: { formatter: function (v) { return num(v); } }
            }
        }, 'Top schools by number of registrations');
    }

    function renderSlots(p) {
        var s = p.slots;
        setHtml('#slots-sub', 'Admission year ' + esc(s.year) + ' · ' + plural(s.schools_with_slots, 'school', 'schools') + ' with slots');
        var el = $('#chart-slots');

        if (!s.allocated) {
            emptyState('slots', el, 'fa-ticket', 'No slots have been allocated for ' + s.year + '.');
            setHtml('#slot-buckets', ''); setHtml('#slot-lists', '');
            return;
        }

        mountChart('slots', el, {
            chart: baseChart('radialBar', 230),
            series: [s.percent], labels: ['Slots used'],
            colors: [C.green],
            fill: { type: 'gradient', gradient: { shade: 'dark', type: 'horizontal', gradientToColors: [C.gold], stops: [0, 100] } },
            stroke: { lineCap: 'round' },
            plotOptions: {
                radialBar: {
                    startAngle: -120, endAngle: 120, hollow: { size: '64%' },
                    track: { background: '#eef2ee', strokeWidth: '100%' },
                    dataLabels: {
                        name: { show: true, offsetY: 24, color: C.axis, fontSize: '13px' },
                        value: { fontSize: '34px', fontWeight: 800, offsetY: -14, color: '#0f2418', formatter: function (v) { return Math.round(v) + '%'; } }
                    }
                }
            }
        }, s.percent + ' percent of slots used');

        var maxB = Math.max.apply(null, Object.keys(s.buckets).map(function (k) { return s.buckets[k]; }).concat([1]));
        var colors = { 'Not started': C.slate, '1–49%': C.blue, '50–89%': C.teal, '90–100%': C.green };
        setHtml('#slot-buckets',
            '<p class="mini-head" style="margin-top:4px">Schools by slot usage</p>' +
            Object.keys(s.buckets).map(function (k) {
                var w = Math.round(s.buckets[k] / maxB * 100);
                return '<div class="bucket-row"><span>' + esc(k) + '</span><span class="b-track"><i data-w="' + w + '" style="background:' + (colors[k] || C.green) + '"></i></span><span class="b-count">' + s.buckets[k] + '</span></div>';
            }).join(''));
        $$('#slot-buckets .b-track i').forEach(function (b) { setTimeout(function () { b.style.width = b.getAttribute('data-w') + '%'; }, 80); });

        var lists = '';
        if (s.nearly_full.length) {
            lists += '<p class="mini-head">Nearly full (90%+)</p><ul class="mini-list">' + s.nearly_full.map(function (r) {
                return '<li><span class="nm" title="' + esc(r.name) + '">' + esc(r.name) + '</span><span class="val">' + num(r.used) + '/' + num(r.allocated) + '</span></li>';
            }).join('') + '</ul>';
        }
        if (s.not_started_count) {
            lists += '<p class="mini-head">Not started (' + s.not_started_count + ')</p><ul class="mini-list">' + s.not_started.slice(0, 3).map(function (r) {
                return '<li><span class="nm" title="' + esc(r.name) + '">' + esc(r.name) + '</span><span class="val">' + num(r.allocated) + ' slots</span></li>';
            }).join('') + '</ul>';
        }
        setHtml('#slot-lists', lists);
    }

    function renderAttention(p) {
        var rows = p.attention.rows || [];
        if (!rows.length) {
            return setHtml('#attention-table', '<div class="empty"><i class="fa-solid fa-circle-check" style="color:#5fbf7f"></i><div>All caught up. No registrations are waiting for approval.</div></div>');
        }
        setHtml('#attention-table',
            '<table class="dtable"><thead><tr><th>School</th><th>Pending</th><th>Waiting</th><th></th></tr></thead><tbody>' +
            rows.map(function (r) {
                var old = r.waiting_days !== null && r.waiting_days >= 7;
                var wait = r.waiting_days === null ? '—' : (r.waiting_days === 0 ? 'Today' : plural(r.waiting_days, 'day', 'days'));
                return '<tr><td><span class="school-name">' + esc(r.name) + '</span><span class="school-code">' + esc(r.number) + '</span></td>' +
                    '<td><span class="num-pill">' + num(r.pending) + '</span></td>' +
                    '<td><span class="wait' + (old ? ' is-old' : '') + '">' + esc(wait) + '</span></td>' +
                    '<td style="text-align:right">' + (r.url ? '<a class="row-link" href="' + esc(r.url) + '">Review →</a>' : '') + '</td></tr>';
            }).join('') +
            '</tbody></table>' +
            (p.attention.schools_with_pending > rows.length
                ? '<p class="wait" style="margin-top:10px">Showing ' + rows.length + ' of ' + p.attention.schools_with_pending + ' schools with pending registrations.</p>' : ''));
    }

    function renderExams(p) {
        var rows = p.upcoming_exams || [];
        if (!rows.length) {
            return setHtml('#exam-list', '<div class="empty" style="min-height:90px"><div>No upcoming papers on the timetable.</div></div>');
        }
        setHtml('#exam-list', '<ul class="exam-list">' + rows.map(function (e) {
            var tag = e.days_until === 0 ? '<span class="tag today">Today</span>'
                : (e.days_until === 1 ? '<span class="tag soon">Tomorrow</span>'
                    : (e.days_until <= 7 ? '<span class="tag soon">In ' + e.days_until + ' days</span>'
                        : '<span class="tag">In ' + e.days_until + ' days</span>'));
            return '<li><div class="exam-date"><b>' + esc(e.day) + '</b><span>' + esc(e.month) + '</span></div>' +
                '<div><div class="exam-subject"><span class="ar" dir="rtl" lang="ar">' + esc(e.subject) + '</span>' + (e.paper ? ' <small style="font-weight:500;color:#64748b">· <span class="ar" dir="rtl" lang="ar">' + esc(e.paper) + '</span></small>' : '') + '</div>' +
                '<div class="exam-meta">' + esc(e.category) + ' · ' + esc(e.time) + '</div></div>' + tag + '</li>';
        }).join('') + '</ul>');
    }

    // ------------------------------------------------------------------
    //  Student charts
    // ------------------------------------------------------------------
    function renderHistory(p) {
        var rows = p.enrolment_history.rows || [];
        var el = $('#chart-history');
        if (!rows.length) { return emptyState('history', el, 'fa-user-graduate', 'No enrolled students yet.'); }
        mountChart('history', el, {
            chart: baseChart('bar', 300, { stacked: true }),
            plotOptions: { bar: { columnWidth: '52%' } },
            series: [
                { name: 'Idaad', data: rows.map(function (r) { return r.ID; }) },
                { name: 'Thanawi', data: rows.map(function (r) { return r.TH; }) }
            ],
            colors: [C.green, C.gold],
            dataLabels: { enabled: false },
            xaxis: { categories: rows.map(function (r) { return r.year; }), axisBorder: { show: false }, axisTicks: { show: false }, labels: { style: { colors: C.axis } } },
            yaxis: { labels: { formatter: function (v) { return num(Math.round(v)); }, style: { colors: C.axis } } },
            grid: GRID,
            legend: { position: 'top', horizontalAlign: 'right', markers: { radius: 12 } },
            tooltip: { shared: true, intersect: false, y: { formatter: function (v) { return num(v); } } }
        }, 'Enrolment by admission year');
    }

    function renderGender(p) {
        var g = p.gender;
        var el = $('#chart-gender');
        setHtml('#gender-sub', g.total ? g.female_percent + '% female · ' + num(g.total) + ' students' : 'Enrolled students');
        if (!g.total) { return emptyState('gender', el, 'fa-venus-mars', 'No enrolled students for these filters.'); }
        var labels = ['Male', 'Female'], series = [g.male, g.female], colors = [C.teal, C.gold];
        if (g.unspecified > 0) { labels.push('Not stated'); series.push(g.unspecified); colors.push(C.slate); }
        var o = donutOptions(labels, series, colors, 'Students', 270);
        o.plotOptions.pie.donut.size = '66%';
        o.plotOptions.pie.donut.labels.value.fontSize = '22px';
        mountChart('gender', el, o, 'Gender balance of enrolled students');
    }

    function renderPhotos(p) {
        var ph = p.photos;
        var el = $('#chart-photos');
        if (!ph.total) {
            emptyState('photos', el, 'fa-camera', 'No enrolled students yet.');
            return setHtml('#photo-list', '');
        }
        mountChart('photos', el, {
            chart: baseChart('radialBar', 200),
            series: [ph.percent], labels: ['With photo'],
            colors: [ph.percent >= 90 ? C.green : (ph.percent >= 60 ? C.amber : C.red)],
            stroke: { lineCap: 'round' },
            plotOptions: {
                radialBar: {
                    hollow: { size: '60%' }, track: { background: '#eef2ee' },
                    dataLabels: {
                        name: { show: true, offsetY: 20, color: C.axis, fontSize: '12px' },
                        value: { fontSize: '28px', fontWeight: 800, offsetY: -12, color: '#0f2418', formatter: function (v) { return Math.round(v) + '%'; } }
                    }
                }
            }
        }, ph.percent + ' percent of students have a photo');

        var html = '<p class="wait" style="text-align:center;margin-top:-6px"><strong>' + num(ph.missing) + '</strong> missing a photo</p>';
        if (ph.worst_schools.length) {
            html += '<p class="mini-head">Most missing</p><ul class="mini-list">' + ph.worst_schools.slice(0, 4).map(function (r) {
                return '<li><span class="nm" title="' + esc(r.name) + '">' + esc(r.name) + '</span><span class="val">' + num(r.missing) + '</span></li>';
            }).join('') + '</ul>';
        }
        setHtml('#photo-list', html);
    }

    function renderDistricts(p) {
        var rows = p.districts || [];
        var el = $('#chart-districts');
        if (!rows.length) { return emptyState('districts', el, 'fa-location-dot', 'No district information recorded for these students.'); }
        mountChart('districts', el, {
            chart: baseChart('bar', Math.max(260, rows.length * 38 + 60)),
            plotOptions: { bar: { horizontal: true, barHeight: '58%', distributed: true } },
            series: [{ name: 'Students', data: rows.map(function (r) { return r.value; }) }],
            colors: rows.map(function (r, i) { return mixHex('#0E7C86', '#4FA9B1', i / Math.max(1, rows.length - 1)); }),
            dataLabels: { enabled: true, style: { fontSize: '12px', fontWeight: 700 }, offsetX: 4 },
            legend: { show: false },
            xaxis: { categories: rows.map(function (r) { return r.name; }), axisBorder: { show: false }, axisTicks: { show: false }, labels: { style: { colors: C.axis } } },
            yaxis: { labels: { style: { colors: rows.map(function () { return '#1f3128'; }), fontSize: '12.5px', fontWeight: 600 } } },
            grid: GRID,
            tooltip: { y: { formatter: function (v) { return num(v) + ' students'; } } }
        }, 'Students by district');
    }

    function renderCerts(p) {
        var c = p.certificates;
        setHtml('#cert-body',
            '<div class="tile-row">' +
            '<div class="tile good"><b>' + num(c.active) + '</b><span>Active</span></div>' +
            '<div class="tile warn"><b>' + num(c.uncovered) + '</b><span>Schools without</span></div>' +
            '<div class="tile bad"><b>' + num(c.revoked) + '</b><span>Revoked</span></div></div>' +
            '<div class="bucket-row" style="grid-template-columns:90px minmax(0,1fr) 42px"><span>Coverage</span>' +
            '<span class="b-track"><i data-w="' + c.coverage + '" style="background:' + C.green + '"></i></span><span class="b-count">' + c.coverage + '%</span></div>' +
            '<p class="wait" style="margin-top:8px">' + plural(c.covered, 'school holds', 'schools hold') + ' an active recognition certificate.</p>' +
            (c.uncovered_list && c.uncovered_list.length
                ? '<p class="mini-head">Without an active certificate' + (c.uncovered > c.uncovered_list.length ? ' (first ' + c.uncovered_list.length + ' of ' + c.uncovered + ')' : '') + '</p><ul class="mini-list">' +
                c.uncovered_list.map(function (r) { return '<li><span class="nm" title="' + esc(r.name) + '">' + esc(r.name) + '</span><span class="val" style="color:#64748b;font-weight:600">' + esc(r.number) + '</span></li>'; }).join('') + '</ul>'
                : ''));
        $$('#cert-body .b-track i').forEach(function (b) { setTimeout(function () { b.style.width = b.getAttribute('data-w') + '%'; }, 80); });
    }

    // ------------------------------------------------------------------
    //  Results
    // ------------------------------------------------------------------
    /** Empty state for result cards, with a shortcut to the latest year that has results. */
    function emptyResults(key, el, icon, message) {
        var latest = state.payload.results.latest_year;
        emptyState(key, el, icon, message);
        if (latest && String(latest) !== String(state.year)) {
            var box = el.querySelector('.empty');
            if (box) { box.insertAdjacentHTML('beforeend', '<button type="button" class="btn-dash" data-set-year="' + esc(latest) + '">View ' + esc(latest) + ' results</button>'); }
        }
    }

    function renderGrades(p) {
        var r = p.results;
        var el = $('#chart-grades');
        setHtml('#grades-sub', r.count ? num(r.count) + ' results · average ' + num(r.average, 1) + '%' : 'Published results');
        if (!r.grades.length) {
            return emptyResults('grades', el, 'fa-chart-column', 'No results published for ' + (state.year === 'all' ? 'these filters' : state.year) + ' yet.');
        }
        mountChart('grades', el, {
            chart: baseChart('bar', 290),
            plotOptions: { bar: { columnWidth: '62%', distributed: true } },
            series: [{ name: 'Students', data: r.grades.map(function (g) { return g.value; }) }],
            colors: ramp(r.grades.length),
            dataLabels: { enabled: true, style: { fontSize: '11px', fontWeight: 700 }, offsetY: -18 },
            legend: { show: false },
            xaxis: { categories: r.grades.map(function (g) { return g.label; }), axisBorder: { show: false }, axisTicks: { show: false }, labels: { style: { colors: C.axis } } },
            yaxis: { labels: { formatter: function (v) { return num(Math.round(v)); }, style: { colors: C.axis } } },
            grid: GRID,
            tooltip: { y: { formatter: function (v) { return num(v) + ' students'; } } }
        }, 'Grade distribution');
    }

    function renderClasses(p) {
        var rows = p.results.classifications || [];
        var el = $('#chart-classes');
        if (!rows.length) { return emptyResults('classes', el, 'fa-award', 'No classifications available for ' + (state.year === 'all' ? 'these filters' : state.year) + ' yet.'); }
        var o = donutOptions(rows.map(function (r) { return r.label; }), rows.map(function (r) { return r.value; }), ramp(rows.length), 'Results', 290);
        mountChart('classes', el, o, 'Result classification breakdown');
    }

    function renderPerformance(p) {
        var rows = p.performance.rows || [];
        var el = $('#chart-performance');
        if (!rows.length) { return emptyState('performance', el, 'fa-chart-line', 'No results recorded yet.'); }
        mountChart('performance', el, {
            chart: baseChart('line', 290),
            series: [
                { name: 'Idaad', data: rows.map(function (r) { return r.ID; }) },
                { name: 'Thanawi', data: rows.map(function (r) { return r.TH; }) }
            ],
            colors: [C.green, C.gold],
            stroke: { curve: 'smooth', width: 3 },
            markers: { size: 5, strokeWidth: 2, strokeColors: '#fff', hover: { size: 7 } },
            dataLabels: { enabled: false },
            xaxis: { categories: rows.map(function (r) { return r.year; }), axisBorder: { show: false }, axisTicks: { show: false }, labels: { style: { colors: C.axis } } },
            yaxis: { min: 0, max: 100, tickAmount: 5, labels: { formatter: function (v) { return Math.round(v) + '%'; }, style: { colors: C.axis } } },
            grid: GRID,
            legend: { position: 'top', horizontalAlign: 'right', markers: { radius: 12 } },
            tooltip: { shared: true, intersect: false, y: { formatter: function (v) { return v === null || v === undefined ? '—' : num(v, 1) + '%'; } } }
        }, 'Average score by year');
    }

    function renderPerformers(p) {
        var rows = p.top_performers || [];
        if (!rows.length) {
            return emptyResults('performers', $('#performers'), 'fa-trophy', 'No school has 5 or more results for these filters yet.');
        }
        setHtml('#performers', '<ol class="rank-list">' + rows.map(function (r, i) {
            return '<li><span class="rank-badge">' + (i + 1) + '</span><div class="rank-info"><div class="rank-name" title="' + esc(r.name) + '">' + esc(r.name) + '</div>' +
                '<div class="rank-meta">' + plural(r.students, 'student', 'students') + '</div></div><span class="rank-score">' + num(r.average, 1) + '%</span></li>';
        }).join('') + '</ol>');
    }

    function renderActivity(p) {
        var items = (p.activity || []).slice(0, 7);
        if (!items.length) {
            return setHtml('#activity', '<div class="empty"><i class="fa-solid fa-clock-rotate-left"></i><div>No activity recorded yet.</div></div>');
        }
        setHtml('#activity', '<ul class="feed">' + items.map(function (a) {
            return '<li class="tone-' + esc(a.tone) + '"><span class="f-icon"><i class="fa-solid ' + esc(a.icon) + '"></i></span>' +
                '<div style="min-width:0"><div class="f-title" title="' + esc(a.title) + '">' + esc(a.title) + '</div><div class="f-meta" title="' + esc(a.meta) + '">' + esc(a.meta) + '</div></div>' +
                '<span class="f-time" title="' + esc(a.iso) + '">' + esc(a.ago) + '</span></li>';
        }).join('') + '</ul>');
    }

    // ------------------------------------------------------------------
    //  Orchestration
    // ------------------------------------------------------------------
    function signature(p) {
        // Ignore values that change on every request (timestamps, "x minutes ago").
        return JSON.stringify({
            f: p.filters, k: p.kpis, a: p.alerts, pl: p.pipeline, t: p.trend, g: p.gender, h: p.enrolment_history,
            ts: p.top_schools, s: p.slots, at: p.attention, r: p.results, pf: p.performance, tp: p.top_performers,
            d: p.districts, ph: p.photos, c: p.certificates, w: p.window, e: p.upcoming_exams,
            act: (p.activity || []).map(function (x) { return x.iso + x.title; })
        });
    }

    function renderAll(force) {
        var p = state.payload;
        renderUpdated(p);

        var sig = signature(p);
        if (!force && sig === state.lastSig) { return; } // nothing changed: avoid a needless redraw/flicker
        var animate = sig !== state.lastSig;
        state.lastSig = sig;

        renderHero(p);
        renderAlerts(p);
        renderKpis(p, animate);
        renderTrend();
        renderPipeline(p);
        renderTopSchools(p);
        renderSlots(p);
        renderAttention(p);
        renderExams(p);
        renderHistory(p);
        renderGender(p);
        renderPhotos(p);
        renderDistricts(p);
        renderCerts(p);
        renderGrades(p);
        renderClasses(p);
        renderPerformance(p);
        renderPerformers(p);
        renderActivity(p);
    }

    function setLoading(on) {
        root.classList.toggle('is-loading', on);
        root.setAttribute('aria-busy', on ? 'true' : 'false');
    }

    function syncUrl() {
        if (!window.history || !window.history.replaceState) { return; }
        var qs = '?year=' + encodeURIComponent(state.year) + '&category=' + encodeURIComponent(state.category);
        try { window.history.replaceState(null, '', window.location.pathname + qs); } catch (e) { /* ignore */ }
    }

    function load(fresh, force) {
        var id = ++state.reqId;
        setLoading(true);

        var qs = 'year=' + encodeURIComponent(state.year) + '&category=' + encodeURIComponent(state.category) + (fresh ? '&fresh=1' : '');

        return fetch(DATA_URL + '?' + qs, {
            credentials: 'same-origin',
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        }).then(function (res) {
            var type = res.headers.get('content-type') || '';
            // An expired session redirects to the login page (HTML): send the user there.
            if (res.redirected || res.status === 401 || res.status === 419 || type.indexOf('json') === -1) {
                if (res.status !== 500) { window.location.href = '/users/login'; }
            }
            if (!res.ok) { throw new Error('HTTP ' + res.status); }
            return res.json();
        }).then(function (data) {
            if (id !== state.reqId) { return; } // a newer request superseded this one
            state.payload = data;
            state.year = data.filters.year;
            state.category = data.filters.category;
            renderAll(force !== false);
            syncUrl();
        }).catch(function (err) {
            if (id !== state.reqId) { return; }
            if (window.console) { console.error(err); }
            toast('Could not refresh the dashboard. Check your connection and try again.', 'error');
        }).then(function () {
            if (id === state.reqId) { setLoading(false); }
        });
    }

    function startAuto() {
        stopAuto();
        state.timer = setInterval(function () {
            if (document.visibilityState === 'visible') { load(false, false); }
        }, AUTO_MS);
        $('#tb-updated .live-dot').classList.remove('is-off');
    }

    function stopAuto() {
        if (state.timer) { clearInterval(state.timer); state.timer = null; }
        var dot = $('#tb-updated .live-dot');
        if (dot) { dot.classList.add('is-off'); }
    }

    function bind() {
        $('#f-year').addEventListener('change', function (e) { state.year = e.target.value; load(false); });

        $$('#f-category button').forEach(function (btn) {
            btn.addEventListener('click', function () {
                state.category = btn.getAttribute('data-value');
                $$('#f-category button').forEach(function (b) { b.classList.toggle('is-active', b === btn); });
                load(false);
            });
        });

        $$('#f-range button').forEach(function (btn) {
            btn.addEventListener('click', function () {
                state.range = btn.getAttribute('data-value');
                $$('#f-range button').forEach(function (b) { b.classList.toggle('is-active', b === btn); });
                renderTrend();
            });
        });

        root.addEventListener('click', function (e) {
            var b = e.target.closest ? e.target.closest('[data-set-year]') : null;
            if (!b) { return; }
            state.year = b.getAttribute('data-set-year');
            $('#f-year').value = state.year;
            load(false);
        });

        $('#btn-refresh').addEventListener('click', function () { load(true); });
        $('#btn-print').addEventListener('click', function () { window.print(); });

        var auto = $('#f-auto');
        var saved = null;
        try { saved = window.localStorage.getItem('iteb.dash.auto'); } catch (e) { /* storage blocked */ }
        if (saved === 'off') { auto.checked = false; }
        auto.addEventListener('change', function () {
            try { window.localStorage.setItem('iteb.dash.auto', auto.checked ? 'on' : 'off'); } catch (e) { /* ignore */ }
            if (auto.checked) { startAuto(); } else { stopAuto(); }
        });

        // Come back to the tab after a while: bring the numbers up to date.
        document.addEventListener('visibilitychange', function () {
            if (document.visibilityState === 'visible' && auto.checked && state.payload) {
                var age = Date.now() - new Date(state.payload.meta.generated_at).getTime();
                if (age > AUTO_MS) { load(false, false); }
            }
        });

        // The sidebar can collapse without a window resize; nudge ApexCharts to re-fit.
        if (window.ResizeObserver) {
            var lastW = root.clientWidth, t;
            new ResizeObserver(function () {
                if (Math.abs(root.clientWidth - lastW) < 8) { return; }
                lastW = root.clientWidth;
                clearTimeout(t);
                t = setTimeout(function () { window.dispatchEvent(new Event('resize')); }, 180);
            }).observe(root);
        }
    }

    // ------------------------------------------------------------------
    //  Go
    // ------------------------------------------------------------------
    bind();
    renderAll(true);
    if ($('#f-auto').checked) { startAuto(); } else { stopAuto(); }
})();
