@extends('layouts-side-bar.master')
@section('content')
    <style>
        :root {
            --forest: #0d4b1f;
            --green: #287C44;
            --green-light: #3da05a;
            --mint: #e8f5e9;
            --amber: #f59e0b;
            --coral: #ef4444;
            --slate: #64748b;
            --radius: 14px;
        }

        .page-hero {
            background: linear-gradient(135deg, #0d4b1f 0%, #287C44 100%);
            color: #fff;
            border-radius: 16px;
            padding: 24px 28px;
            margin-bottom: 24px;
        }

        .tab-bar {
            display: flex;
            gap: 6px;
            margin-bottom: 22px;
            flex-wrap: wrap;
        }

        .tab-btn {
            padding: 9px 20px;
            border-radius: 50px;
            border: 2px solid #dee2e6;
            background: #fff;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            transition: .2s;
        }

        .tab-btn.active {
            background: var(--forest);
            color: #fff;
            border-color: var(--forest);
        }

        .tab-btn-approve-all {
            background: var(--green);
            color: #fff;
            border-color: var(--green);
        }

        .tab-btn-approve-all:hover:not(:disabled) {
            background: var(--forest);
            border-color: var(--forest);
        }

        .tab-btn-approve-all:disabled {
            opacity: .55;
            cursor: not-allowed;
        }

        .tab-btn-submit-ready {
            background: #fff;
            color: var(--forest);
            border-color: var(--green);
        }

        .tab-btn-submit-ready:hover:not(:disabled) {
            background: var(--green);
            color: #fff;
        }

        .tab-btn-submit-ready:disabled {
            opacity: .55;
            cursor: not-allowed;
        }

        .tab-btn-export-ready {
            background: #fff;
            color: #1d4ed8;
            border-color: #1d4ed8;
        }

        .tab-btn-export-ready:hover:not(:disabled) {
            background: #1d4ed8;
            color: #fff;
        }

        .tab-btn-export-ready:disabled {
            opacity: .55;
            cursor: not-allowed;
        }

        .bs-table { width: 100%; font-size: 13px; border-collapse: collapse; margin-top: 10px; text-align: left; }
        .bs-table th, .bs-table td { padding: 6px 8px; border-bottom: 1px solid #e9ecef; }
        .bs-table th { background: #f6f8f7; font-weight: 700; }
        .bs-scroll { max-height: 260px; overflow-y: auto; }
        .bs-skipped { font-size: 12px; color: #6c757d; }
        .bs-table tr.bs-zero td { background: #fdecea; color: #b71c1c; font-weight: 700; }
        .bs-zero-note { font-size: 12.5px; color: #b71c1c; font-weight: 600; margin-top: 6px; }

        .tab-pane {
            display: none;
        }

        .tab-pane.active {
            display: block;
        }

        /* School cards */
        .school-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 18px;
        }

        /* School overview search bar */
        .school-filter-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #fff;
            border-radius: var(--radius);
            padding: 12px 16px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .06);
            margin-bottom: 18px;
        }

        .school-filter-bar i {
            color: var(--slate);
        }

        .school-filter-bar input {
            flex: 1;
            border: none;
            outline: none;
            font-size: 14px;
            background: transparent;
        }

        .school-filter-count {
            font-size: 12px;
            color: var(--slate);
            white-space: nowrap;
        }

        .school-filter-clear {
            border: none;
            background: #f1f5f9;
            color: var(--slate);
            border-radius: 50%;
            width: 24px;
            height: 24px;
            line-height: 24px;
            padding: 0;
            cursor: pointer;
            display: none;
        }

        #schoolNoMatch {
            display: none;
        }

        /* ── Schools overview: filter toolbar ───────────────────────────────── */
        .sf-wrap { background:#fff; border-radius:var(--radius); box-shadow:0 2px 10px rgba(0,0,0,.06); padding:14px 16px; margin-bottom:18px; }
        .sf-toolbar { display:flex; flex-wrap:wrap; gap:10px; align-items:flex-end; }
        .sf-field { display:flex; flex-direction:column; gap:3px; }
        .sf-field label { font-size:10.5px; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:var(--slate); margin:0; }
        .sf-field select, .sf-search { height:38px; border:1px solid #e2e8f0; border-radius:10px; background:#f8fafc; font-size:13.5px; }
        .sf-field select { padding:0 28px 0 10px; min-width:130px; font-weight:600; color:#0f172a; }
        .sf-field select:focus, .sf-search:focus-within { outline:none; border-color:var(--green); background:#fff; box-shadow:0 0 0 3px rgba(40,124,68,.12); }
        .sf-search { flex:1; min-width:200px; display:flex; align-items:center; gap:8px; padding:0 10px; }
        .sf-search i { color:var(--slate); }
        .sf-search input { flex:1; border:none; outline:none; background:transparent; font-size:13.5px; min-width:0; }
        .sf-btn { height:38px; border:1px solid #e2e8f0; background:#fff; border-radius:10px; padding:0 14px; font-size:13px; font-weight:600; color:#334155; cursor:pointer; white-space:nowrap; }
        .sf-btn:hover { border-color:var(--green); color:var(--green); }
        .sf-btn.on { background:#ecf7f0; border-color:var(--green); color:var(--green); }
        .sf-badge { display:none; background:var(--green); color:#fff; border-radius:50px; font-size:10.5px; padding:1px 7px; margin-left:4px; }
        .sf-badge.show { display:inline-block; }

        .sf-quick { display:flex; gap:8px; margin-top:12px; overflow-x:auto; padding-bottom:2px; }
        .sf-chip { border:1px solid #e2e8f0; background:#f8fafc; color:#334155; border-radius:50px; padding:6px 12px; font-size:12.5px; font-weight:600; white-space:nowrap; cursor:pointer; display:inline-flex; align-items:center; gap:7px; transition:.15s; }
        .sf-chip:hover { border-color:var(--green); }
        .sf-chip .n { background:#e2e8f0; color:#475569; border-radius:50px; font-size:11px; padding:0 7px; min-width:22px; text-align:center; }
        .sf-chip.active { background:var(--green); border-color:var(--green); color:#fff; }
        .sf-chip.active .n { background:rgba(255,255,255,.25); color:#fff; }
        .sf-chip.zero:not(.active) { opacity:.45; }

        .sf-panel { display:none; margin-top:14px; padding-top:14px; border-top:1px dashed #e2e8f0; }
        .sf-panel.open { display:grid; grid-template-columns:repeat(auto-fit, minmax(210px, 1fr)); gap:18px 24px; }
        .sf-group h6 { font-size:10.5px; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:var(--slate); margin:0 0 8px; }
        .sf-group .sf-chips { display:flex; flex-wrap:wrap; gap:6px; }

        .sf-results { display:flex; flex-wrap:wrap; align-items:center; gap:8px 14px; margin-top:12px; font-size:12.5px; color:var(--slate); }
        .sf-results strong { color:#0f172a; }
        .sf-totals span { margin-right:10px; white-space:nowrap; }
        .sf-active { display:flex; flex-wrap:wrap; gap:6px; }
        .sf-pill { background:#ecf7f0; color:#166534; border-radius:50px; padding:2px 6px 2px 10px; font-size:11.5px; font-weight:600; cursor:pointer; border:none; }
        .sf-pill:hover { background:#d6efdd; }
        .sf-link { background:none; border:none; color:var(--green); font-weight:700; font-size:12.5px; cursor:pointer; padding:0; }
        .sf-link:hover { text-decoration:underline; }
        .sf-spacer { flex:1; }
        .sc-flags { display:flex; flex-wrap:wrap; gap:5px; margin-top:8px; }
        .sc-flag { font-size:10.5px; font-weight:700; padding:2px 8px; border-radius:50px; background:#f1f5f9; color:#475569; }
        .sc-flag.warn { background:#fef3c7; color:#92400e; }
        .sc-flag.bad { background:#fee2e2; color:#b91c1c; }
        .sc-flag.info { background:#e0f2fe; color:#075985; }

        .school-card {
            border-radius: var(--radius);
            background: #fff;
            overflow: hidden;
            box-shadow: 0 3px 15px rgba(0, 0, 0, .07);
            text-decoration: none;
            color: inherit;
            display: block;
            transition: .25s;
        }

        .school-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 28px rgba(13, 75, 31, .14);
            text-decoration: none;
            color: inherit;
        }

        .sc-head {
            background: linear-gradient(135deg, #0d4b1f, #287C44);
            color: #fff;
            padding: 16px 18px 12px;
        }

        .sc-num {
            font-size: 20px;
            font-weight: 700;
            letter-spacing: .5px;
        }

        .sc-name {
            font-size: 12px;
            opacity: .85;
            margin-top: 2px;
        }

        .sc-body {
            padding: 14px 18px;
        }

        .sc-stat {
            text-align: center;
        }

        .sc-stat .n {
            font-size: 26px;
            font-weight: 700;
            line-height: 1;
        }

        .sc-stat .l {
            font-size: 11px;
            color: var(--slate);
            margin-top: 3px;
        }

        .c-pending {
            color: #e67e22;
        }

        .c-approved {
            color: var(--green);
        }

        .c-total {
            color: #3b82f6;
        }

        .c-slots {
            color: #8b5cf6;
        }

        .slot-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: var(--green);
            border-radius: 50px;
            font-size: 11px;
            font-weight: 600;
            padding: 3px 10px;
            margin-top: 8px;
        }

        .slot-pill.warn {
            background: #fef3c7;
            border-color: #fde68a;
            color: #92400e;
        }

        .slot-pill.danger {
            background: #fee2e2;
            border-color: #fca5a5;
            color: #991b1b;
        }

        .lock-badge {
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 50px;
            background: #dcfce7;
            color: var(--green);
            border: 1px solid #86efac;
            font-weight: 600;
        }

        .lock-badge.closed {
            background: #fee2e2;
            color: #dc2626;
            border-color: #fca5a5;
        }

        /* Slot management */
        .search-box {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
            background: #fff;
            border-radius: var(--radius);
            padding: 18px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .06);
            margin-bottom: 20px;
        }

        .search-box input {
            flex: 1;
            min-width: 200px;
            padding: 10px 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
        }

        .btn-search {
            padding: 10px 22px;
            background: var(--forest);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            font-size: 13px;
        }

        .school-result-row {
            background: #fff;
            border-radius: 10px;
            padding: 16px 18px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .05);
            margin-bottom: 12px;
            border-left: 4px solid var(--green);
        }

        .school-result-row .sname {
            font-weight: 700;
            font-size: 15px;
        }

        .school-result-row .smeta {
            font-size: 12px;
            color: var(--slate);
        }

        /* Period management */
        .period-card {
            background: #fff;
            border-radius: var(--radius);
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .06);
            margin-bottom: 16px;
        }

        .period-active-badge {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
            padding: 3px 10px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 700;
        }

        .period-inactive-badge {
            background: #f1f5f9;
            color: var(--slate);
            border: 1px solid #e2e8f0;
            padding: 3px 10px;
            border-radius: 50px;
            font-size: 11px;
        }

        /* Inputs */
        .form-control-sm2 {
            width: 100%;
            padding: 9px 12px;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            font-size: 13px;
        }

        .btn-green {
            background: var(--green);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 9px 20px;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-green:hover {
            background: var(--forest);
        }

        .btn-outline-green {
            background: #fff;
            color: var(--green);
            border: 1.5px solid var(--green);
            border-radius: 8px;
            padding: 7px 16px;
            font-weight: 600;
            cursor: pointer;
            font-size: 12px;
        }

        .btn-danger2 {
            background: #ef4444;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 7px 14px;
            font-size: 12px;
            cursor: pointer;
        }

        .status-dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }

        .dot-open {
            background: #22c55e;
        }

        .dot-closed {
            background: #ef4444;
        }

        /* Ensure SweetAlert2 always renders above Bootstrap modals/backdrops */
        .swal2-container {
            z-index: 20000 !important;
        }

        .period-closed-badge {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
            padding: 3px 10px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 700;
        }

        .period-archived-badge {
            background: #f1f5f9;
            color: var(--slate);
            border: 1px solid #e2e8f0;
            padding: 3px 10px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 600;
        }
        
        
        .bs-table thead th {
    background-color: #0E4D21 !important;
    color: #ffffff !important;
    font-weight: 700;
}

.bs-table { 
    width: 100%; 
    font-size: 13px; 
    border-collapse: collapse; 
    margin-top: 10px; 
    text-align: left; 
}

.bs-table th, .bs-table td { 
    padding: 6px 8px; 
    border-bottom: 1px solid #e9ecef; 
}

.bs-table th { 
    background: #f6f8f7; 
    font-weight: 700; 
    white-space: nowrap;   /* ← keeps headers on one line */
}

.bs-table td { 
    white-space: nowrap;   /* ← keeps cell values on one line too */
}

/* Allow the school-name column to wrap if names are long */
.bs-table td:first-child,
.bs-table th:first-child {
    white-space: normal;
    min-width: 160px;
}

/* Wide "Submit Ready Students" popup – no sideways scrolling */
.swal2-popup.bs-wide-popup {
    width: min(1200px, 95vw) !important;
    max-width: 95vw !important;
}

.bs-scroll { 
    max-height: 55vh;      /* grows with the screen; only scrolls for very long lists */
    overflow-y: auto; 
    overflow-x: auto;
}

.bs-table th:not(:first-child),
.bs-table td:not(:first-child) {
    text-align: center;
}
    </style>

    <div class="side-app">

        {{-- PAGE HERO --}}
        <div class="page-hero d-flex justify-content-between align-items-start flex-wrap" style="gap:12px;">
            <div>
                <h4 class="mb-1"><i class="fas fa-user-check mr-2"></i>Student Approvals & Registration Management</h4>
                <small style="opacity:.8;">Manage school slots, registration periods, and review submitted students</small>
            </div>
            <div class="text-right">
                <div style="font-size:28px; font-weight:800;">{{ collect($schools)->sum('pending_count') }}</div>
                <small style="opacity:.8;">Total Pending Approval</small>
                <div class="mt-1">
                    @if($globalOpen)
                        <span
                            style="background:rgba(255,255,255,.2); padding:4px 12px; border-radius:50px; font-size:11px; font-weight:700;">
                            <span class="status-dot dot-open"></span> Registration OPEN
                        </span>
                    @else
                        <span
                            style="background:rgba(255,255,255,.15); padding:4px 12px; border-radius:50px; font-size:11px; font-weight:700;">
                            <span class="status-dot dot-closed"></span> Registration CLOSED
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- TAB BAR --}}
        <div class="tab-bar">
            <button class="tab-btn active" data-tab="tab-schools"><i class="fas fa-school mr-1"></i> Schools
                Overview</button>
            <button class="tab-btn" data-tab="tab-slots"><i class="fas fa-ticket-alt mr-1"></i> Manage Slots</button>
            <button class="tab-btn" data-tab="tab-period"><i class="fas fa-calendar-alt mr-1"></i> Registration
                Period</button>
            @php $totalPending = collect($schools)->sum('pending_count'); @endphp
            <button type="button" id="bulkSubmitReadyBtn" class="tab-btn tab-btn-submit-ready"
                title="Send every student whose details are filled and photo attached to the approval queue">
                <i class="fas fa-paper-plane mr-1"></i> Submit Ready Students
            </button>
            <button type="button" id="exportReadyPdfBtn" class="tab-btn tab-btn-export-ready"
                title="Download a PDF listing every student that is ready to be submitted, school by school">
                <i class="fas fa-file-pdf mr-1"></i> Export Ready Students (PDF)
            </button>
            <button type="button" id="approveAllPendingBtn" class="tab-btn tab-btn-approve-all"
                data-pending="{{ $totalPending }}" {{ $totalPending < 1 ? 'disabled' : '' }}>
                <i class="fas fa-check-double mr-1"></i> Approve all Pending
            </button>
        </div>

        {{-- ═══════════════════════ TAB 1: SCHOOLS OVERVIEW ═══════════════════════ --}}
        <div class="tab-pane active" id="tab-schools">
            @if(empty($schools))
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No school registrations found yet.</h5>
                    <p class="text-muted">Once schools start registering students, they'll appear here.</p>
                </div>
            @else
                @php
                    $filterYears = collect($schools)->pluck('admission_year')->unique()->sortDesc()->values();
                    $yearCounts = collect($schools)->countBy('admission_year');
                    $activeYearEn = $activeYear->year_en ?? null;
                @endphp
                <div class="sf-wrap" id="schoolFilters">
                    <div class="sf-toolbar">
                        <div class="sf-field">
                            <label for="sfYear">Year</label>
                            <select id="sfYear">
                                <option value="">All years</option>
                                @foreach($filterYears as $y)
                                    <option value="{{ $y }}">{{ $y }}{{ (string) $y === (string) $activeYearEn ? ' (active)' : '' }} &middot; {{ $yearCounts[$y] ?? 0 }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="sf-search">
                            <i class="fas fa-search"></i>
                            <input type="text" id="schoolFilterInput" placeholder="Search by school name or number (e.g. IT-089)…" autocomplete="off">
                            <button type="button" class="school-filter-clear" id="schoolFilterClear" title="Clear">&times;</button>
                        </div>
                        <div class="sf-field">
                            <label for="sfSort">Sort by</label>
                            <select id="sfSort">
                                <option value="default">Most pending first</option>
                                <option value="name">School name A–Z</option>
                                <option value="number">School number</option>
                                <option value="registered">Most students registered</option>
                                <option value="unsub">Most students not yet submitted</option>
                                <option value="rem">Most slots remaining</option>
                                <option value="last">Latest activity</option>
                            </select>
                        </div>
                        <button type="button" class="sf-btn" id="sfMoreBtn">
                            <i class="fas fa-sliders-h mr-1"></i> More filters <span class="sf-badge" id="sfMoreBadge">0</span>
                        </button>
                    </div>

                    {{-- Quick status chips (always visible) --}}
                    <div class="sf-quick" id="sfQuick"></div>

                    {{-- Advanced filters (collapsed by default) --}}
                    <div class="sf-panel" id="sfPanel">
                        <div class="sf-group"><h6>Needs attention</h6><div class="sf-chips" id="sfGroup-attn"></div></div>
                        <div class="sf-group"><h6>Slots</h6><div class="sf-chips" id="sfGroup-slots"></div></div>
                        <div class="sf-group"><h6>Registration for school</h6><div class="sf-chips" id="sfGroup-win"></div></div>
                        <div class="sf-group">
                            <h6>Last activity</h6>
                            <div class="sf-field" style="margin:0;">
                                <select id="sfAct">
                                    <option value="">Any time</option>
                                    <option value="7">In the last 7 days</option>
                                    <option value="30">In the last 30 days</option>
                                    <option value="old">More than 30 days ago</option>
                                    <option value="never">No activity yet</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="sf-results">
                        <span id="schoolFilterCount"></span>
                        <span class="sf-totals" id="sfTotals"></span>
                        <span class="sf-active" id="sfActive"></span>
                        <span class="sf-spacer"></span>
                        <button type="button" class="sf-link" id="sfClearAll" style="display:none;"><i class="fas fa-undo mr-1"></i>Clear all filters</button>
                        <button type="button" class="sf-link" id="sfCsv" title="Download the schools currently shown as a CSV (opens in Excel)"><i class="fas fa-file-csv mr-1"></i>Export list (CSV)</button>
                    </div>
                </div>

                <div class="text-center py-5" id="schoolNoMatch">
                    <i class="fas fa-search fa-2x text-muted mb-2"></i>
                    <h6 class="text-muted">No schools match your filters.</h6>
                    <button type="button" class="sf-link" id="sfClearAll2">Clear all filters</button>
                </div>

                <div class="school-grid">
                    @foreach($schools as $school)
                        <a href="{{ route('admin.student.approvals.detail', $school['prefix']) }}" class="school-card"
                           data-search="{{ strtolower($school['prefix'] . ' ' . $school['school_name'] . ' ' . $school['admission_year']) }}"
                           data-order="{{ $loop->index }}"
                           data-year="{{ $school['admission_year'] }}"
                           data-prefix="{{ $school['prefix'] }}"
                           data-name="{{ $school['school_name'] }}"
                           data-total="{{ $school['total_registered'] }}"
                           data-pending="{{ $school['pending_count'] }}"
                           data-approved="{{ $school['approved_count'] }}"
                           data-returned="{{ $school['returned_count'] }}"
                           data-nophoto="{{ $school['no_photo_count'] }}"
                           data-unsub="{{ $school['unsubmitted_count'] }}"
                           data-alloc="{{ $school['slots_allocated'] }}"
                           data-rem="{{ $school['slots_remaining'] }}"
                           data-open="{{ $school['school_reg_open'] ? 1 : 0 }}"
                           data-last="{{ $school['latest_submission'] ? \Carbon\Carbon::parse($school['latest_submission'])->timestamp : 0 }}"
                           data-lasttext="{{ $school['latest_submission'] ? \Carbon\Carbon::parse($school['latest_submission'])->format('Y-m-d H:i') : '' }}">
                            <div class="sc-head">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="sc-num">{{ $school['prefix'] }}</div>
                                        <div class="sc-name">{{ $school['school_name'] }}</div>
                                        <div class="sc-name">Year: {{ $school['admission_year'] }}</div>
                                    </div>
                                    @if($school['pending_count'] > 0)
                                        <span
                                            style="background:rgba(255,200,0,.25); color:#fef9c3; border:1px solid rgba(255,200,0,.4); padding:3px 9px; border-radius:50px; font-size:11px; font-weight:700;">
                                            <i class="fas fa-clock mr-1"></i>{{ $school['pending_count'] }} Pending
                                        </span>
                                    @endif
                                </div>
                                {{-- Slot pill --}}
                                @php
                                    $rem = $school['slots_remaining'];
                                    $pillClass = $rem > 5 ? '' : ($rem > 0 ? 'warn' : 'danger');
                                @endphp
                                <div class="slot-pill {{ $pillClass }} mt-2">
                                    <i class="fas fa-ticket-alt"></i>
                                    {{ $school['slots_allocated'] }} allocated &bull;
                                    {{ $rem }} remaining
                                    @if($school['school_reg_open'])
                                        &bull; <span class="status-dot dot-open" style="width:7px;height:7px;"></span>Open
                                    @else
                                        &bull; <span class="status-dot dot-closed" style="width:7px;height:7px;"></span>Closed
                                    @endif
                                </div>
                            </div>
                            <div class="sc-body">
                                <div class="row text-center no-gutters">
                                    <div class="col-3 sc-stat">
                                        <div class="n c-total">{{ $school['total_registered'] }}</div>
                                        <div class="l">Registered</div>
                                    </div>
                                    <div class="col-3 sc-stat">
                                        <div class="n c-pending">{{ $school['pending_count'] }}</div>
                                        <div class="l">Pending</div>
                                    </div>
                                    <div class="col-3 sc-stat">
                                        <div class="n c-approved">{{ $school['approved_count'] }}</div>
                                        <div class="l">Approved</div>
                                    </div>
                                    <div class="col-3 sc-stat">
                                        <div class="n c-slots">{{ $school['slots_allocated'] }}</div>
                                        <div class="l">Slots</div>
                                    </div>
                                </div>
                                @php
                                    $withSchool = $school['no_photo_count'] + $school['unsubmitted_count'];
                                @endphp
                                @if($school['total_registered'] < 1 || $school['returned_count'] > 0 || $withSchool > 0)
                                    <div class="sc-flags">
                                        @if($school['total_registered'] < 1)
                                            <span class="sc-flag bad">No students registered</span>
                                        @endif
                                        @if($school['no_photo_count'] > 0)
                                            <span class="sc-flag warn">{{ $school['no_photo_count'] }} missing photo</span>
                                        @endif
                                        @if($school['unsubmitted_count'] > 0)
                                            <span class="sc-flag info">{{ $school['unsubmitted_count'] }} not submitted</span>
                                        @endif
                                        @if($school['returned_count'] > 0)
                                            <span class="sc-flag bad">{{ $school['returned_count'] }} returned</span>
                                        @endif
                                    </div>
                                @endif
                                <div class="mt-2" style="font-size:11px; color:var(--slate); text-align:right;">
                                    Last activity:
                                    {{ $school['latest_submission'] ? \Carbon\Carbon::parse($school['latest_submission'])->diffForHumans() : 'N/A' }}
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ═══════════════════════ TAB 2: MANAGE SLOTS ═══════════════════════ --}}
        <div class="tab-pane" id="tab-slots">
            <div class="search-box">
                <i class="fas fa-search text-muted"></i>
                <input type="text" id="slotSearchInput" placeholder="Search school by name or number…">
                <select id="slotYearSelect" class="form-control-sm2" style="max-width:160px;">
                    @if($activeYear)
                        <option value="{{ $activeYear->year_en }}">{{ $activeYear->year_en }}</option>
                    @endif
                    @foreach(range(date('Y') - 2, date('Y') + 1) as $y)
                        @if(!$activeYear || $y != $activeYear->year_en)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endif
                    @endforeach
                </select>
                <button class="btn-search" id="slotSearchBtn"><i class="fas fa-search mr-1"></i>Search</button>
            </div>

            <div id="slotResultsContainer"></div>

            {{-- Assign Slots Modal --}}
            <div class="modal fade" id="assignSlotsModal" tabindex="-1">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header" style="background:var(--forest); color:#fff;">
                            <h5 class="modal-title"><i class="fas fa-ticket-alt mr-2"></i>Assign Registration Slots</h5>
                            <button type="button" class="close text-white"
                                data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <p><strong id="assignSchoolName"></strong></p>
                            <input type="hidden" id="assignSchoolId">
                            <input type="hidden" id="assignYear">
                            <div class="mb-3">
                                <label class="font-weight-600" style="font-size:13px;">Current Allocation</label>
                                <div id="currentSlotInfo" class="mt-1" style="font-size:13px; color:var(--slate);"></div>
                            </div>
                            <div class="mb-3">
                                <label class="font-weight-600" style="font-size:13px;">Add Slots <span
                                        style="color:var(--coral);">*</span></label>
                                <input type="number" id="slotsToAdd" class="form-control-sm2 mt-1" placeholder="e.g. 10"
                                    min="1" max="500">
                            </div>
                            <div class="mb-3">
                                <label class="font-weight-600" style="font-size:13px;">Reason (optional)</label>
                                <input type="text" id="slotReason" class="form-control-sm2 mt-1"
                                    placeholder="e.g. Extra payment received">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn-green" id="confirmAssignSlots"><i class="fas fa-check mr-1"></i>Assign
                                Slots</button>
                            <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Edit Slots Modal (adjust the total allocation directly, up or down) --}}
            <div class="modal fade" id="editSlotsModal" tabindex="-1">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header" style="background:#0d4b1f; color:#fff;">
                            <h5 class="modal-title"><i class="fas fa-edit mr-2"></i>Edit Slot Allocation</h5>
                            <button type="button" class="close text-white"
                                data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <p><strong id="editSchoolName"></strong></p>
                            <input type="hidden" id="editSchoolId">
                            <input type="hidden" id="editYear">
                            <div class="mb-3">
                                <label class="font-weight-600" style="font-size:13px;">Current Allocation</label>
                                <div id="editCurrentSlotInfo" class="mt-1" style="font-size:13px; color:var(--slate);"></div>
                            </div>
                            <div class="mb-3">
                                <label class="font-weight-600" style="font-size:13px;">New Total Slots <span
                                        style="color:var(--coral);">*</span></label>
                                <input type="number" id="editSlotsTotal" class="form-control-sm2 mt-1" placeholder="e.g. 25"
                                    min="0" max="5000">
                                <small class="text-muted">This replaces the current total (not added on top). Cannot be set below slots already used.</small>
                            </div>
                            <div class="mb-3">
                                <label class="font-weight-600" style="font-size:13px;">Reason (optional)</label>
                                <input type="text" id="editSlotReason" class="form-control-sm2 mt-1"
                                    placeholder="e.g. Correcting earlier allocation error">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn-green" id="confirmEditSlots"><i class="fas fa-save mr-1"></i>Save
                                Changes</button>
                            <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- History Modal --}}
            <div class="modal fade" id="slotHistoryModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header" style="background:var(--forest); color:#fff;">
                            <h5 class="modal-title"><i class="fas fa-history mr-2"></i>Slot Allocation History</h5>
                            <button type="button" class="close text-white"
                                data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <div class="modal-body p-0">
                            <div id="historyList" style="max-height:400px; overflow-y:auto;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══════════════════════ TAB 3: REGISTRATION PERIOD ═══════════════════════ --}}
        <div class="tab-pane" id="tab-period">

            {{-- Current Status Banner --}}
            <div class="period-card mb-3" style="border-left:4px solid {{ $globalOpen ? '#22c55e' : '#ef4444' }};">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong style="font-size:15px;">
                            <span class="status-dot {{ $globalOpen ? 'dot-open' : 'dot-closed' }}"></span>
                            Global Registration is currently <span
                                style="color:{{ $globalOpen ? '#16a34a' : '#dc2626' }}">{{ $globalOpen ? 'OPEN' : 'CLOSED' }}</span>
                        </strong>
                        @if($globalPeriod)
                            <div style="font-size:12px; color:var(--slate); margin-top:4px;">
                                Period: <strong>{{ $globalPeriod->name ?? 'Unnamed' }}</strong> &bull;
                                Year: {{ $globalPeriod->admission_year }} &bull;
                                @if($globalPeriod->opens_at) Opens:
                                {{ \Carbon\Carbon::parse($globalPeriod->opens_at)->format('d M Y H:i') }} @endif
                                @if($globalPeriod->closes_at) &bull; Closes:
                                {{ \Carbon\Carbon::parse($globalPeriod->closes_at)->format('d M Y H:i') }} @endif
                            </div>
                        @else
                            <div style="font-size:12px; color:var(--slate); margin-top:4px;">No active registration period
                                configured.</div>
                        @endif
                    </div>
                    @if($globalPeriod)
                        <div class="d-flex align-items-center" style="gap:10px;">
                            <button class="btn-danger2 btn-close-period" data-id="{{ $globalPeriod->id }}">
                                <i class="fas fa-lock mr-1"></i>Close Registration
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Create New Period Form --}}
            <div class="period-card">
                <h5 class="mb-3" style="color:var(--forest);"><i class="fas fa-plus-circle mr-2"></i>Create / Activate
                    Registration Period</h5>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label style="font-size:13px; font-weight:600;">Period Name</label>
                        <input type="text" id="newPeriodName" class="form-control-sm2 mt-1"
                            placeholder="e.g. 2026 Admissions Window">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label style="font-size:13px; font-weight:600;">Admission Year <span
                                style="color:var(--coral);">*</span></label>
                        <input type="number" id="newPeriodYear" class="form-control-sm2 mt-1"
                            value="{{ $activeYear ? $activeYear->year_en : date('Y') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label style="font-size:13px; font-weight:600;">Opens At (optional)</label>
                        <input type="datetime-local" id="newPeriodOpens" class="form-control-sm2 mt-1">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label style="font-size:13px; font-weight:600;">Closes At (optional)</label>
                        <input type="datetime-local" id="newPeriodCloses" class="form-control-sm2 mt-1">
                    </div>
                </div>
                <div class="d-flex align-items-center" style="gap:12px;">
                    <button class="btn-green" id="createPeriodBtn">
                        <i class="fas fa-save mr-1"></i>Save & Activate Period
                    </button>
                    <small class="text-muted">Activating a new period automatically deactivates the current one.</small>
                </div>
            </div>

            {{-- All Periods Table --}}
            <div class="period-card">
                <h5 class="mb-3" style="color:var(--forest);"><i class="fas fa-list mr-2"></i>All Registration Periods</h5>
                <small class="text-muted d-block mb-2">Every period ever created stays listed here. Closing a period never deletes it - you can re-activate any year/period again at any time.</small>
                <div id="periodsTableWrap">
                    @if($allPeriods->isEmpty())
                        <div class="text-center py-4 text-muted"><i class="fas fa-calendar-times fa-2x mb-2"></i><br>No registration periods configured yet.</div>
                    @else
                        <div class="table-responsive">
                        <table class="table table-sm table-bordered" style="font-size:13px;">
                            <thead style="background:#0d4b1f; color:#fff;">
                                <tr><th>Name</th><th>Year</th><th>Opens</th><th>Closes</th><th>Status</th><th style="min-width:230px;">Action</th></tr>
                            </thead>
                            <tbody>
                                @foreach($allPeriods as $p)
                                    <tr>
                                        <td>{{ $p->name ?? '—' }}</td>
                                        <td>{{ $p->admission_year }}</td>
                                        <td>{{ $p->opens_at ? \Carbon\Carbon::parse($p->opens_at)->format('d M Y H:i') : '—' }}</td>
                                        <td>{{ $p->closes_at ? \Carbon\Carbon::parse($p->closes_at)->format('d M Y H:i') : '—' }}</td>
                                        <td>
                                            @if($p->status === 'active')
                                                <span class="period-active-badge">ACTIVE</span>
                                            @elseif($p->status === 'archived')
                                                <span class="period-archived-badge">Archived</span>
                                            @else
                                                <span class="period-closed-badge">Closed</span>
                                            @endif
                                        </td>
                                        <td style="white-space:nowrap;">
                                            @if($p->status !== 'active')
                                                <button class="btn-outline-green btn-activate-period mr-1"
                                                    data-id="{{ $p->id }}" data-name="{{ $p->name ?? ('Year ' . $p->admission_year) }}"
                                                    style="color:#16a34a;border-color:#16a34a;">
                                                    <i class="fas fa-play mr-1"></i>Activate
                                                </button>
                                            @else
                                                <button class="btn-danger2 btn-close-period-row mr-1" data-id="{{ $p->id }}" data-name="{{ $p->name ?? ('Year ' . $p->admission_year) }}">
                                                    <i class="fas fa-lock mr-1"></i>Close
                                                </button>
                                            @endif
                                            <button class="btn-outline-green btn-edit-period mr-1"
                                                data-id="{{ $p->id }}"
                                                data-name="{{ $p->name ?? '' }}"
                                                data-year="{{ $p->admission_year }}"
                                                data-opens="{{ $p->opens_at }}"
                                                data-closes="{{ $p->closes_at }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            @if($p->status !== 'active')
                                                <button class="btn-danger2 btn-delete-period" data-id="{{ $p->id }}" data-name="{{ $p->name ?? ('Year ' . $p->admission_year) }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
    </div>
    </div>

    {{-- Edit Period Modal --}}
<div class="modal fade" id="editPeriodModal" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header" style="background:#0d4b1f; color:#fff;">
                <h5 class="modal-title"><i class="fas fa-edit mr-2"></i>Edit Registration Period</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="editPeriodForm">
                <div class="modal-body">
                    <input type="hidden" id="editPeriodId">
                    <div class="mb-3">
                        <label style="font-size:13px; font-weight:600;">Period Name</label>
                        <input type="text" id="editPeriodName" class="form-control-sm2 mt-1" placeholder="e.g. 2026 Admissions Window">
                    </div>
                    <div class="mb-3">
                        <label style="font-size:13px; font-weight:600;">Admission Year <span style="color:#ef4444;">*</span></label>
                        <input type="number" id="editPeriodYear" class="form-control-sm2 mt-1">
                    </div>
                    <div class="mb-3">
                        <label style="font-size:13px; font-weight:600;">Opens At (optional)</label>
                        <input type="datetime-local" id="editPeriodOpens" class="form-control-sm2 mt-1">
                    </div>
                    <div class="mb-3">
                        <label style="font-size:13px; font-weight:600;">Closes At (optional)</label>
                        <input type="datetime-local" id="editPeriodCloses" class="form-control-sm2 mt-1">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn-green" id="editPeriodSaveBtn">
                        <i class="fas fa-save mr-1"></i>Save Changes
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

    </div> {{-- /side-app --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

   <script>
    // ── Tab switching ─────────────────────────────────────────────────────────────
    $(document).on('click', '.tab-btn[data-tab]', function () {
        $('.tab-btn[data-tab]').removeClass('active');
        $('.tab-pane').removeClass('active');
        $(this).addClass('active');
        $('#' + $(this).data('tab')).addClass('active');
    });

    // ── School overview filters ───────────────────────────────────────────────────
    // Groups: options inside a group are OR-ed, different groups (and year / search / activity) are AND-ed.
    // Everything runs in the browser on data-* attributes, so filtering is instant.
    (function () {
        const grid = document.querySelector('.school-grid');
        if (!grid) return;

        const GROUPS = {
            progress: { label: 'Progress', opts: [
                { key: 'pending',   label: 'Pending approval',        test: c => c.pending > 0 },
                { key: 'submitted', label: 'Submitted to admin',      test: c => c.pending + c.approved + c.returned > 0 },
                { key: 'approved',  label: 'Fully approved',          test: c => c.total > 0 && c.approved === c.total },
                { key: 'notsent',   label: 'Nothing submitted yet',   test: c => c.total > 0 && c.pending + c.approved + c.returned === 0 },
                { key: 'none',      label: 'No students registered',  test: c => c.total === 0 }
            ]},
            attn: { label: 'Needs attention', opts: [
                { key: 'unsub',    label: 'Has students not yet submitted', test: c => c.nophoto + c.unsub > 0 },
                { key: 'nophoto',  label: 'Students missing photo',         test: c => c.nophoto > 0 },
                { key: 'ready',    label: 'Photo attached, not submitted',  test: c => c.unsub > 0 },
                { key: 'returned', label: 'Returned to school',             test: c => c.returned > 0 }
            ]},
            slots: { label: 'Slots', opts: [
                { key: 'noslots', label: 'No slots allocated',  test: c => c.alloc === 0 },
                { key: 'avail',   label: 'Slots available',     test: c => c.rem > 0 },
                { key: 'low',     label: 'Low (5 or fewer left)', test: c => c.rem > 0 && c.rem <= 5 },
                { key: 'full',    label: 'All slots used',      test: c => c.alloc > 0 && c.rem === 0 }
            ]},
            win: { label: 'Registration', opts: [
                { key: 'open',   label: 'Open for school',   test: c => c.open === 1 },
                { key: 'closed', label: 'Closed for school', test: c => c.open === 0 }
            ]}
        };
        const GROUP_BOX = { progress: 'sfQuick', attn: 'sfGroup-attn', slots: 'sfGroup-slots', win: 'sfGroup-win' };
        const ACT_LABELS = { '7': 'Active in last 7 days', '30': 'Active in last 30 days', 'old': 'No activity for 30+ days', 'never': 'No activity yet' };
        const DAY = 86400;

        const cards = Array.from(grid.querySelectorAll('.school-card')).map(function (el) {
            const d = el.dataset, n = k => parseInt(d[k], 10) || 0;
            return {
                el: el, order: n('order'), year: d.year, prefix: d.prefix, name: d.name, search: (d.search || '').toLowerCase(),
                total: n('total'), pending: n('pending'), approved: n('approved'), returned: n('returned'),
                nophoto: n('nophoto'), unsub: n('unsub'), alloc: n('alloc'), rem: n('rem'), open: n('open'),
                last: n('last'), lasttext: d.lasttext || ''
            };
        });

        const st = { year: '', q: '', act: '', sort: 'default', sel: { progress: new Set(), attn: new Set(), slots: new Set(), win: new Set() } };
        const $ = id => document.getElementById(id);

        function actTest(c) {
            const now = Date.now() / 1000;
            if (st.act === 'never') return c.last === 0;
            if (c.last === 0) return false;
            if (st.act === '7')  return now - c.last <= 7 * DAY;
            if (st.act === '30') return now - c.last <= 30 * DAY;
            return now - c.last > 30 * DAY;   // 'old'
        }

        // skip = a group (or 'act') to ignore, used to compute the count shown on each chip
        function passes(c, skip) {
            if (st.year && c.year !== st.year) return false;
            if (st.q && c.search.indexOf(st.q) === -1) return false;
            for (const g in GROUPS) {
                if (g === skip || st.sel[g].size === 0) continue;
                if (!GROUPS[g].opts.some(o => st.sel[g].has(o.key) && o.test(c))) return false;
            }
            if (st.act && skip !== 'act' && !actTest(c)) return false;
            return true;
        }

        // ---- build the chips once ----
        function esc(t) { return String(t).replace(/[&<>"]/g, m => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[m])); }

        Object.keys(GROUPS).forEach(function (g) {
            let html = '';
            if (g === 'progress') html += '<button type="button" class="sf-chip" data-g="progress" data-k="">All schools <span class="n">0</span></button>';
            GROUPS[g].opts.forEach(function (o) {
                html += '<button type="button" class="sf-chip" data-g="' + g + '" data-k="' + o.key + '">' + esc(o.label) + ' <span class="n">0</span></button>';
            });
            $(GROUP_BOX[g]).innerHTML = html;
        });

        // ---- URL (so a refresh / shared link keeps the filters) ----
        function writeUrl() {
            const p = new URLSearchParams();
            if (st.year) p.set('y', st.year);
            if (st.q) p.set('q', st.q);
            if (st.act) p.set('act', st.act);
            if (st.sort !== 'default') p.set('sort', st.sort);
            [['progress', 'p'], ['attn', 'a'], ['slots', 's'], ['win', 'w']].forEach(function (m) {
                if (st.sel[m[0]].size) p.set(m[1], Array.from(st.sel[m[0]]).join(','));
            });
            const qs = p.toString();
            history.replaceState(null, '', location.pathname + (qs ? '?' + qs : ''));
        }

        function readUrl() {
            const p = new URLSearchParams(location.search);
            st.year = p.get('y') || '';
            st.q = (p.get('q') || '').toLowerCase();
            st.act = ACT_LABELS[p.get('act')] ? p.get('act') : '';
            st.sort = p.get('sort') || 'default';
            [['progress', 'p'], ['attn', 'a'], ['slots', 's'], ['win', 'w']].forEach(function (m) {
                const valid = GROUPS[m[0]].opts.map(o => o.key);
                (p.get(m[1]) || '').split(',').forEach(function (k) { if (valid.indexOf(k) !== -1) st.sel[m[0]].add(k); });
            });
            if (st.year && !Array.from($('sfYear').options).some(o => o.value === st.year)) st.year = '';
            if (!Array.from($('sfSort').options).some(o => o.value === st.sort)) st.sort = 'default';
            $('sfYear').value = st.year;
            $('sfSort').value = st.sort;
            $('sfAct').value = st.act;
            $('schoolFilterInput').value = st.q;
        }

        // ---- sorting ----
        const SORTS = {
            default:    (a, b) => a.order - b.order,
            name:       (a, b) => a.name.localeCompare(b.name) || b.year - a.year,
            number:     (a, b) => a.prefix.localeCompare(b.prefix, undefined, { numeric: true }) || b.year - a.year,
            registered: (a, b) => b.total - a.total || a.name.localeCompare(b.name),
            unsub:      (a, b) => (b.nophoto + b.unsub) - (a.nophoto + a.unsub) || a.name.localeCompare(b.name),
            rem:        (a, b) => b.rem - a.rem || a.name.localeCompare(b.name),
            last:       (a, b) => b.last - a.last || a.name.localeCompare(b.name)
        };
        function sortCards() {
            const frag = document.createDocumentFragment();
            cards.slice().sort(SORTS[st.sort] || SORTS.default).forEach(c => frag.appendChild(c.el));
            grid.appendChild(frag);
        }

        // ---- apply ----
        function apply() {
            let shown = 0, tot = { total: 0, pending: 0, approved: 0, returned: 0, rem: 0 };

            cards.forEach(function (c) {
                const ok = passes(c, null);
                c.el.style.display = ok ? '' : 'none';
                if (ok) { shown++; tot.total += c.total; tot.pending += c.pending; tot.approved += c.approved; tot.returned += c.returned; tot.rem += c.rem; }
            });

            // chip counts + active state
            document.querySelectorAll('#schoolFilters .sf-chip').forEach(function (chip) {
                const g = chip.dataset.g, k = chip.dataset.k;
                let count, active;
                if (k === '') { count = cards.filter(c => passes(c, g)).length; active = st.sel[g].size === 0; }
                else {
                    const o = GROUPS[g].opts.find(x => x.key === k);
                    count = cards.filter(c => passes(c, g) && o.test(c)).length;
                    active = st.sel[g].has(k);
                }
                chip.classList.toggle('active', active);
                chip.classList.toggle('zero', count === 0);
                chip.querySelector('.n').textContent = count;
            });

            // pills for everything that is switched on
            const pills = [];
            if (st.year) pills.push({ t: 'Year ' + st.year, g: 'year' });
            Object.keys(GROUPS).forEach(g => st.sel[g].forEach(k => pills.push({ t: GROUPS[g].opts.find(o => o.key === k).label, g: g, k: k })));
            if (st.act) pills.push({ t: ACT_LABELS[st.act], g: 'act' });
            $('sfActive').innerHTML = pills.map(p =>
                '<button type="button" class="sf-pill" data-g="' + p.g + '" data-k="' + (p.k || '') + '">' + esc(p.t) + ' &times;</button>').join('');

            const advanced = st.sel.attn.size + st.sel.slots.size + st.sel.win.size + (st.act ? 1 : 0);
            $('sfMoreBadge').textContent = advanced;
            $('sfMoreBadge').classList.toggle('show', advanced > 0);

            const anyFilter = pills.length > 0 || st.q !== '';
            $('sfClearAll').style.display = anyFilter ? '' : 'none';
            $('schoolFilterClear').style.display = st.q ? 'block' : 'none';
            $('schoolFilterCount').innerHTML = anyFilter
                ? '<strong>' + shown + '</strong> of ' + cards.length + ' schools'
                : '<strong>' + cards.length + '</strong> schools';
            $('sfTotals').innerHTML = shown
                ? '<span>Registered <strong>' + tot.total + '</strong></span><span>Pending <strong>' + tot.pending + '</strong></span>'
                  + '<span>Approved <strong>' + tot.approved + '</strong></span><span>Slots left <strong>' + tot.rem + '</strong></span>'
                : '';
            $('schoolNoMatch').style.display = shown === 0 && cards.length > 0 ? 'block' : 'none';
            writeUrl();
        }

        function clearAll() {
            st.year = ''; st.q = ''; st.act = '';
            Object.keys(st.sel).forEach(g => st.sel[g].clear());
            $('sfYear').value = ''; $('sfAct').value = ''; $('schoolFilterInput').value = '';
            apply();
        }

        // ---- events ----
        document.getElementById('schoolFilters').addEventListener('click', function (e) {
            const chip = e.target.closest('.sf-chip');
            if (!chip) return;
            const g = chip.dataset.g, k = chip.dataset.k;
            if (k === '') st.sel[g].clear();
            else if (st.sel[g].has(k)) st.sel[g].delete(k);
            else st.sel[g].add(k);
            apply();
        });
        $('sfActive').addEventListener('click', function (e) {
            const pill = e.target.closest('.sf-pill');
            if (!pill) return;
            const g = pill.dataset.g;
            if (g === 'year') { st.year = ''; $('sfYear').value = ''; }
            else if (g === 'act') { st.act = ''; $('sfAct').value = ''; }
            else st.sel[g].delete(pill.dataset.k);
            apply();
        });
        $('sfYear').addEventListener('change', function () { st.year = this.value; apply(); });
        $('sfAct').addEventListener('change', function () { st.act = this.value; apply(); });
        $('sfSort').addEventListener('change', function () { st.sort = this.value; sortCards(); apply(); });
        $('schoolFilterInput').addEventListener('input', function () { st.q = this.value.toLowerCase().trim(); apply(); });
        $('schoolFilterClear').addEventListener('click', function () { st.q = ''; $('schoolFilterInput').value = ''; $('schoolFilterInput').focus(); apply(); });
        $('sfClearAll').addEventListener('click', clearAll);
        $('sfClearAll2').addEventListener('click', clearAll);
        $('sfMoreBtn').addEventListener('click', function () {
            const open = $('sfPanel').classList.toggle('open');
            this.classList.toggle('on', open);
        });

        // CSV of the schools currently shown (in the order currently shown)
        $('sfCsv').addEventListener('click', function () {
            const rows = [['School No.', 'School', 'Year', 'Registered', 'Pending approval', 'Approved', 'Returned',
                           'Missing photo', 'Photo attached, not submitted', 'Slots allocated', 'Slots remaining', 'Registration open', 'Last activity']];
            const byEl = new Map(cards.map(c => [c.el, c]));
            Array.from(grid.children).forEach(function (el) {
                const c = byEl.get(el);
                if (!c || el.style.display === 'none') return;
                rows.push([c.prefix, c.name, c.year, c.total, c.pending, c.approved, c.returned, c.nophoto, c.unsub, c.alloc, c.rem, c.open ? 'Yes' : 'No', c.lasttext]);
            });
            const csv = '\uFEFF' + rows.map(r => r.map(v => '"' + String(v).replace(/"/g, '""') + '"').join(',')).join('\r\n');
            const a = document.createElement('a');
            a.href = URL.createObjectURL(new Blob([csv], { type: 'text/csv;charset=utf-8;' }));
            a.download = 'schools-' + (st.year || 'all-years') + '-' + new Date().toISOString().slice(0, 10) + '.csv';
            document.body.appendChild(a); a.click(); a.remove();
        });

        // ---- start: default view = exactly as before (no filters, "most pending first") ----
        readUrl();
        sortCards();
        if (st.sel.attn.size + st.sel.slots.size + st.sel.win.size + (st.act ? 1 : 0) > 0) {
            $('sfPanel').classList.add('open'); $('sfMoreBtn').classList.add('on');
        }
        apply();
    })();

    // ── Approve all pending (all schools) ─────────────────────────────────────────
    $('#approveAllPendingBtn').on('click', function () {
        const pending = parseInt($(this).data('pending'), 10) || 0;
        if (pending < 1) {
            Swal.fire({ icon: 'info', title: 'Nothing to approve', text: 'There are no pending students.', confirmButtonColor: '#287C44' });
            return;
        }

        Swal.fire({
            title: 'Approve all ' + pending + ' pending student(s)?',
            text: 'Every student awaiting approval, in all schools, will be approved and added to the main students database.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#287C44',
            confirmButtonText: 'Yes, Approve All'
        }).then(r => {
            if (!r.isConfirmed) return;

            Swal.fire({
                title: 'Approving...', text: 'Please wait, this may take a moment.',
                allowOutsideClick: false, allowEscapeKey: false,
                showConfirmButton: false, didOpen: () => { Swal.showLoading(); }
            });

            $.post('{{ route("admin.approve.all.pending") }}', {
                _token: '{{ csrf_token() }}'
            }, function (res) {
                let msg = res.message;
                if (res.errors && res.errors.length) msg += '\n\nFailed (' + res.errors.length + '):\n' + res.errors.join('\n');
                Swal.fire({
                    icon: (res.errors && res.errors.length) ? 'warning' : 'success',
                    title: 'Done', text: msg, confirmButtonColor: '#287C44'
                }).then(() => location.reload());
            }).fail(xhr => Swal.fire('Error', xhr.responseJSON?.message || 'Something went wrong', 'error'));
        });
    });

    // ── Submit ready students to admin (for schools that never pressed Submit) ───
    function bsEsc(v) {
        return $('<div>').text(v == null ? '' : v).html();
    }

    // ── Export ready students to PDF (read-only, changes nothing) ────────────────
    function bsExportReadyPdf(strict) {
        const url = '{{ route("admin.export.ready.pdf") }}' + '?strict=' + (strict ? 1 : 0);

        Swal.fire({
            title: 'Generating PDF...', text: 'Please wait, this may take a moment for many students.',
            allowOutsideClick: false, allowEscapeKey: false,
            showConfirmButton: false, didOpen: () => { Swal.showLoading(); }
        });

        fetch(url, { credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(async resp => {
                if (!resp.ok) {
                    let msg = 'Could not generate the PDF';
                    try { msg = (await resp.json()).message || msg; } catch (e) {}
                    throw new Error(msg);
                }
                return resp.blob();
            })
            .then(blob => {
                const a = document.createElement('a');
                a.href = URL.createObjectURL(blob);
                a.download = 'ready_students_' + new Date().toISOString().slice(0, 10) + '.pdf';
                document.body.appendChild(a);
                a.click();
                a.remove();
                setTimeout(() => URL.revokeObjectURL(a.href), 10000);
                Swal.fire({ icon: 'success', title: 'PDF ready', text: 'Your download has started.', timer: 1800, showConfirmButton: false });
            })
            .catch(err => Swal.fire('Error', err.message || 'Could not generate the PDF', 'error'));
    }

    $('#exportReadyPdfBtn').on('click', function () { bsExportReadyPdf(true); });

    $('#bulkSubmitReadyBtn').on('click', function () {
        const url = '{{ route("admin.bulk.submit") }}';

        Swal.fire({
            title: 'Checking students...', allowOutsideClick: false, allowEscapeKey: false,
            showConfirmButton: false, didOpen: () => { Swal.showLoading(); }
        });

        $.get(url, function (res) {
            const t = res.totals;

            if (t.ready < 1 && t.scanned < 1 && (res.schools || []).length < 1) {
                Swal.fire({ icon: 'info', title: 'Nothing to submit', text: 'Every registered student has already been submitted.', confirmButtonColor: '#287C44' });
                return;
            }

            let rows = '';
            // Only list schools that have students needing attention (incomplete details or no photo)
            const problemSchools = (res.schools || []).filter(s => (s.incomplete > 0 || s.no_photo > 0));
            problemSchools.forEach(s => {
                const slotCell = (s.slots === null || s.slots === undefined)
                    ? s.students + ' / (–)'
                    : s.students + ' / (' + s.slots + ')';
                rows += '<tr class="' + (s.no_students ? 'bs-zero' : '') + '"><td>' + bsEsc(s.school_name) + '</td>' +
                    '<td title="Students sent to admin + ready now, out of slots allocated">' + slotCell + '</td>' +
                    '<td>' + s.ready + '</td><td>' + s.incomplete + '</td><td>' + s.no_photo + '</td></tr>';
            });

            let skippedHtml = '';
            if (res.skipped_total > 0) {
                skippedHtml = '<div class="bs-skipped mt-2">' + res.skipped_total + ' student(s) will be left with their schools (incomplete details or no photo).</div>';
            }

            let zeroHtml = '';
            if (problemSchools.length < 1) {
                rows = '<tr><td colspan="5" style="text-align:center;">No school has incomplete details or missing photos.</td></tr>';
            }

            const html =
                '<div style="text-align:left;">' +
                '<p style="margin-bottom:4px;"><b>' + t.ready + '</b> ready &middot; ' + t.incomplete + ' incomplete &middot; ' + t.no_photo + ' without photo</p>' +
                '<div class="bs-scroll"><table class="bs-table"><thead><tr><th>School</th><th>Students / (Slots)</th><th>Ready</th><th>Incomplete</th><th>No photo</th></tr></thead><tbody>' + rows + '</tbody></table></div>' +
                skippedHtml +
                zeroHtml +
                '<label class="mt-3" style="font-size:12px;"><input type="checkbox" id="bsLenient"> Also submit students missing optional details (date of birth, nationality, place of birth, district)</label>' +
                '</div>';

            if (t.ready < 1) {
                Swal.fire({ icon: 'info', title: 'No student is ready', html: html, width: '95vw', customClass: { popup: 'bs-wide-popup' }, confirmButtonColor: '#287C44' });
                return;
            }

            Swal.fire({
    title: 'Submit ' + t.ready + ' student(s) to admin?',
    html: html,
    width: '95vw',         // real width is capped by .bs-wide-popup (1200px)
    customClass: { popup: 'bs-wide-popup' },
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#287C44',
    confirmButtonText: 'Yes, Submit Them',
    showDenyButton: true,
    denyButtonText: '<i class="fas fa-file-pdf"></i> Export PDF',
    denyButtonColor: '#1d4ed8',
    preConfirm: () => ({ lenient: $('#bsLenient').is(':checked') }),
    preDeny: () => ({ lenient: $('#bsLenient').is(':checked') })
}).then(r => {
                if (r.isDenied) {
                    bsExportReadyPdf(!r.value.lenient);
                    return;
                }
                if (!r.isConfirmed) return;

                Swal.fire({
                    title: 'Submitting...', text: 'Please wait, this may take a moment.',
                    allowOutsideClick: false, allowEscapeKey: false,
                    showConfirmButton: false, didOpen: () => { Swal.showLoading(); }
                });

                $.post(url, {
                    _token: '{{ csrf_token() }}',
                    run: 1,
                    strict: r.value.lenient ? 0 : 1
                }, function (done) {
                    Swal.fire({
                        icon: 'success', title: 'Submitted',
                        text: done.message + ' They are now waiting under Pending Approval.',
                        confirmButtonColor: '#287C44'
                    }).then(() => location.reload());
                }).fail(xhr => Swal.fire('Error', xhr.responseJSON?.message || 'Something went wrong', 'error'));
            });
        }).fail(xhr => Swal.fire('Error', xhr.responseJSON?.message || 'Could not load the preview', 'error'));
    });

    // ── Slot search ───────────────────────────────────────────────────────────────
    let currentSearchPage = 1;

    $('#slotSearchBtn').on('click', function () { searchSchools(1); });
    $('#slotSearchInput').on('keypress', function (e) { if (e.which === 13) searchSchools(1); });

    function searchSchools(page = 1) {
        
        currentSearchPage = page;
        const q    = $('#slotSearchInput').val().trim();
        const year = $('#slotYearSelect').val();

        if (page === 1) {
            $('#slotResultsContainer').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin"></i> Searching…</div>');
        }

        $.get('{{ route("admin.school.slots.search") }}', { q, year, page }, function (res) {
            if (!res.schools || res.schools.length === 0) {
                $('#slotResultsContainer').html('<div class="text-center py-5 text-muted"><i class="fas fa-search fa-2x mb-2"></i><br>No schools found.</div>');
                return;
            }

            let html = `<div style="font-size:12px; color:var(--slate); margin-bottom:10px;">
                Showing ${(res.page - 1) * res.per_page + 1}–${Math.min(res.page * res.per_page, res.total)} of <strong>${res.total}</strong> schools
            </div>`;

            res.schools.forEach(s => {
                const slot   = s.slot;
                const alloc  = slot ? slot.slots_allocated : 0;
                const used   = slot ? slot.slots_used : 0;
                const rem    = slot ? slot.slots_remaining : 0;
                const pct    = alloc > 0 ? Math.round((used / alloc) * 100) : 0;
                const barClr = pct >= 100 ? '#ef4444' : pct >= 80 ? '#f59e0b' : '#22c55e';

                html += `
                    <div class="school-result-row">
                        <div class="d-flex justify-content-between align-items-start flex-wrap" style="gap:10px;">
                            <div>
                                <div class="sname">${s.House} <span style="font-size:12px; color:#94a3b8;">[${s.Number}]</span></div>
                                <div class="smeta">${s.Location || '—'} &bull; Year: ${res.year}</div>
                                <div class="mt-2" style="font-size:12px;">
                                    <strong>${alloc}</strong> allocated &bull;
                                    <strong>${used}</strong> used &bull;
                                    <strong style="color:${rem > 0 ? '#16a34a' : '#dc2626'}">${rem}</strong> remaining
                                </div>
                                <div style="margin-top:6px; height:6px; background:#e2e8f0; border-radius:50px; width:200px;">
                                    <div style="height:6px; border-radius:50px; background:${barClr}; width:${Math.min(pct, 100)}%;"></div>
                                </div>
                            </div>
                            <div class="d-flex flex-column align-items-end" style="gap:6px;">
                                <button class="btn-green btn-assign-slots"
                                    data-id="${s.ID}" data-name="${s.House}" data-number="${s.Number}"
                                    data-alloc="${alloc}" data-used="${used}" data-rem="${rem}" data-year="${res.year}">
                                    <i class="fas fa-plus mr-1"></i>Add Slots
                                </button>
                                <button class="btn-outline-green btn-edit-slots"
                                    data-id="${s.ID}" data-name="${s.House}" data-number="${s.Number}"
                                    data-alloc="${alloc}" data-used="${used}" data-rem="${rem}" data-year="${res.year}">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </button>
                                <button class="btn-outline-green btn-view-history" data-id="${s.ID}" data-year="${res.year}" data-name="${s.House}">
                                    <i class="fas fa-history mr-1"></i>History
                                </button>
                                ${slot ? `
                                <button class="btn-outline-green btn-toggle-reg" data-id="${s.ID}" data-year="${res.year}"
                                    data-open="${slot.registration_open ? '1' : '0'}"
                                    style="${slot.registration_open ? 'color:#dc2626;border-color:#dc2626;' : 'color:#16a34a;border-color:#16a34a;'}">
                                    <i class="fas fa-${slot.registration_open ? 'lock' : 'unlock'} mr-1"></i>
                                    ${slot.registration_open ? 'Close for School' : 'Open for School'}
                                </button>` : `
                                <button class="btn-outline-green btn-toggle-reg" data-id="${s.ID}" data-year="${res.year}" data-open="0"
                                    style="color:#16a34a;border-color:#16a34a;">
                                    <i class="fas fa-unlock mr-1"></i>Open for School
                                </button>`}
                            </div>
                        </div>
                    </div>`;
            });

            // ── Pagination ────────────────────────────────────────────────────────
            if (res.total_pages > 1) {
                html += `<div class="d-flex justify-content-center align-items-center flex-wrap" style="gap:6px; margin-top:16px;">`;

                html += `<button class="btn-outline-green page-btn" data-page="1"
                    ${res.page === 1 ? 'disabled style="opacity:.4;cursor:default;"' : ''}>«</button>`;
                html += `<button class="btn-outline-green page-btn" data-page="${res.page - 1}"
                    ${res.page === 1 ? 'disabled style="opacity:.4;cursor:default;"' : ''}>‹</button>`;

                const start = Math.max(1, res.page - 2);
                const end   = Math.min(res.total_pages, res.page + 2);
                for (let p = start; p <= end; p++) {
                    html += `<button class="page-btn ${p === res.page ? 'btn-green' : 'btn-outline-green'}"
                        data-page="${p}" ${p === res.page ? 'style="cursor:default;"' : ''}>${p}</button>`;
                }

                html += `<button class="btn-outline-green page-btn" data-page="${res.page + 1}"
                    ${res.page === res.total_pages ? 'disabled style="opacity:.4;cursor:default;"' : ''}>›</button>`;
                html += `<button class="btn-outline-green page-btn" data-page="${res.total_pages}"
                    ${res.page === res.total_pages ? 'disabled style="opacity:.4;cursor:default;"' : ''}>»</button>`;

                html += `<span style="font-size:12px; color:var(--slate); margin-left:8px;">Page ${res.page} of ${res.total_pages}</span>`;
                html += `</div>`;
            }

            $('#slotResultsContainer').html(html);
        });
    }

    // ── Pagination click ──────────────────────────────────────────────────────────
    $(document).on('click', '.page-btn:not([disabled])', function () {
        searchSchools(parseInt($(this).data('page')));
    });

    // ── Assign Slots modal ────────────────────────────────────────────────────────
    $(document).on('click', '.btn-assign-slots', function () {
        const d = $(this).data();
        $('#assignSchoolId').val(d.id);
        $('#assignYear').val(d.year);
        $('#assignSchoolName').text(d.name + ' [' + d.number + ']');
        $('#currentSlotInfo').html(`Allocated: <strong>${d.alloc}</strong> &bull; Used: <strong>${d.used}</strong> &bull; Remaining: <strong>${d.rem}</strong>`);
        $('#slotsToAdd').val('');
        $('#slotReason').val('');
        $('#assignSlotsModal').modal('show');
    });

    $('#confirmAssignSlots').on('click', function () {
        const slots = parseInt($('#slotsToAdd').val());
        if (!slots || slots < 1) { Swal.fire('Error', 'Enter a valid number of slots (min 1)', 'error'); return; }

        const schoolName = $('#assignSchoolName').text();
        const reason = $('#slotReason').val();

        Swal.fire({
            title: 'Assign these slots?',
            html: `Add <strong>${slots}</strong> slot(s) to <strong>${schoolName}</strong>?${reason ? `<br><small class="text-muted">Reason: ${reason}</small>` : ''}`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#287C44',
            confirmButtonText: 'Yes, assign',
            cancelButtonText: 'Cancel'
        }).then(result => {
            if (!result.isConfirmed) return;

            Swal.fire({
                title: 'Assigning slots…',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => { Swal.showLoading(); }
            });

            $.post('{{ route("admin.school.slots.assign") }}', {
                _token: '{{ csrf_token() }}',
                school_id: $('#assignSchoolId').val(),
                admission_year: $('#assignYear').val(),
                slots,
                reason
            }, function (res) {
                $('#assignSlotsModal').modal('hide');
                Swal.fire({ icon: 'success', title: 'Slots Assigned', text: res.message, confirmButtonColor: '#287C44' });
                searchSchools(currentSearchPage);
            }).fail(function (xhr) {
                Swal.fire('Error', xhr.responseJSON?.message || 'Something went wrong', 'error');
            });
        });
    });

    // ── Edit Slots modal ──────────────────────────────────────────────────────────
    $(document).on('click', '.btn-edit-slots', function () {
        const d = $(this).data();
        $('#editSchoolId').val(d.id);
        $('#editYear').val(d.year);
        $('#editSchoolName').text(d.name + ' [' + d.number + ']');
        $('#editCurrentSlotInfo').html(`Allocated: <strong>${d.alloc}</strong> &bull; Used: <strong>${d.used}</strong> &bull; Remaining: <strong>${d.rem}</strong>`);
        $('#editSlotsTotal').val(d.alloc);
        $('#editSlotReason').val('');
        $('#editSlotsModal').modal('show');
    });

    $('#confirmEditSlots').on('click', function () {
        const newTotal = parseInt($('#editSlotsTotal').val());
        if (isNaN(newTotal) || newTotal < 0) { Swal.fire('Error', 'Enter a valid total (0 or more)', 'error'); return; }

        const schoolName = $('#editSchoolName').text();
        const reason = $('#editSlotReason').val();

        Swal.fire({
            title: 'Update slot allocation?',
            html: `Set total allocation for <strong>${schoolName}</strong> to <strong>${newTotal}</strong>?${reason ? `<br><small class="text-muted">Reason: ${reason}</small>` : ''}`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#0d4b1f',
            confirmButtonText: 'Yes, update',
            cancelButtonText: 'Cancel'
        }).then(result => {
            if (!result.isConfirmed) return;

            Swal.fire({
                title: 'Updating allocation…',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => { Swal.showLoading(); }
            });

            $.post('{{ route("admin.school.slots.edit") }}', {
                _token: '{{ csrf_token() }}',
                school_id: $('#editSchoolId').val(),
                admission_year: $('#editYear').val(),
                slots_allocated: newTotal,
                reason
            }, function (res) {
                $('#editSlotsModal').modal('hide');
                Swal.fire({ icon: 'success', title: 'Allocation Updated', text: res.message, confirmButtonColor: '#287C44' });
                searchSchools(currentSearchPage);
            }).fail(function (xhr) {
                Swal.fire('Error', xhr.responseJSON?.message || 'Something went wrong', 'error');
            });
        });
    });

    // ── Toggle per-school registration ────────────────────────────────────────────
    $(document).on('click', '.btn-toggle-reg', function () {
        const id   = $(this).data('id');
        const year = $(this).data('year');
        const open = $(this).data('open') == '1' ? 0 : 1;
        const msg  = open ? 'Open registration for this school?' : 'Close registration for this school?';

        Swal.fire({ title: msg, icon: 'question', showCancelButton: true, confirmButtonColor: '#287C44', confirmButtonText: 'Yes' }).then(r => {
            if (!r.isConfirmed) return;
            $.post('{{ route("admin.school.slots.toggle") }}', {
                _token: '{{ csrf_token() }}', school_id: id, admission_year: year, open
            }, function (res) {
                Swal.fire({ icon: 'success', title: res.message, timer: 1500, showConfirmButton: false });
                searchSchools(currentSearchPage);
            });
        });
    });

    // ── Slot history ──────────────────────────────────────────────────────────────
    $(document).on('click', '.btn-view-history', function () {
        const id   = $(this).data('id');
        const year = $(this).data('year');
        const name = $(this).data('name');
        $('#historyList').html('<div class="text-center p-4"><i class="fas fa-spinner fa-spin"></i></div>');
        $('#slotHistoryModal').modal('show');
        $('.modal-title', '#slotHistoryModal').html('<i class="fas fa-history mr-2"></i>History — ' + name);

        $.get('{{ route("admin.school.slots.history") }}', { school_id: id, admission_year: year }, function (res) {
            if (!res.history || res.history.length === 0) {
                $('#historyList').html('<div class="text-center p-4 text-muted">No history yet.</div>');
                return;
            }
            let h = '';
            res.history.forEach((row, i) => {
                const added = parseInt(row.slots_added);
                const sign = added > 0 ? '+' : '';
                const color = added > 0 ? '#16a34a' : (added < 0 ? '#dc2626' : '#64748b');
                h += `<div style="padding:12px 16px; border-bottom:1px solid #f1f5f9; display:flex; justify-content:space-between; align-items:center;">
                    <div>
                        <strong style="color:${color};">${sign}${added} slots</strong> → Total: ${row.total_after}
                        ${row.reason ? `<br><small class="text-muted">${row.reason}</small>` : ''}
                    </div>
                    <small class="text-muted">${row.created_at}</small>
                </div>`;
            });
            $('#historyList').html(h);
        });
    });

    // ── Create Period ─────────────────────────────────────────────────────────────
    $('#createPeriodBtn').on('click', function () {
        const year = parseInt($('#newPeriodYear').val());
        if (!year) { Swal.fire('Error', 'Admission year is required', 'error'); return; }

        Swal.fire({
            title: 'Activate this registration period?',
            text: 'This will automatically close any currently active period.',
            icon: 'question', showCancelButton: true, confirmButtonColor: '#0d4b1f',
            confirmButtonText: 'Yes, save & activate'
        }).then(r => {
            if (!r.isConfirmed) return;

            Swal.fire({
                title: 'Saving period…', allowOutsideClick: false, allowEscapeKey: false,
                showConfirmButton: false, didOpen: () => { Swal.showLoading(); }
            });

            $.ajax({
                url: '{{ route("admin.registration.period.save") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    name: $('#newPeriodName').val(),
                    admission_year: year,
                    opens_at: $('#newPeriodOpens').val() || null,
                    closes_at: $('#newPeriodCloses').val() || null,
                    is_active: 1,
                },
                success: function (res) {
                    Swal.fire('Success', res.message || 'Period saved & activated!', 'success')
                        .then(() => location.reload());
                },
                error: function (xhr) {
                    Swal.fire('Error', xhr.responseJSON?.message || 'Failed to save period', 'error');
                }
            });
        });
    });

    // ── Close Registration (banner button) ────────────────────────────────────────
    $(document).on('click', '.btn-close-period, .btn-close-period-row', function () {
        const id = $(this).data('id');
        const name = $(this).data('name') || 'this period';
        Swal.fire({
            title: 'Close global registration?',
            html: `Schools will no longer be able to register students under <strong>${name}</strong>. This does not delete the period - you can re-activate it later.`,
            icon: 'warning', showCancelButton: true,
            confirmButtonColor: '#dc2626', confirmButtonText: 'Yes, close it'
        }).then(r => {
            if (!r.isConfirmed) return;

            Swal.fire({
                title: 'Closing…', allowOutsideClick: false, allowEscapeKey: false,
                showConfirmButton: false, didOpen: () => { Swal.showLoading(); }
            });

            $.ajax({
                url: `{{ url('/admin/registration-period') }}/${id}/status`,
                method: 'POST',
                data: { _token: '{{ csrf_token() }}', status: 'closed' },
                success: res => Swal.fire('Closed', res.message, 'success').then(() => location.reload()),
                error: xhr => Swal.fire('Error', xhr.responseJSON?.message || 'Failed', 'error')
            });
        });
    });

    // ── Activate a previous/closed period ─────────────────────────────────────────
    $(document).on('click', '.btn-activate-period', function () {
        const id = $(this).data('id');
        const name = $(this).data('name') || 'this period';
        Swal.fire({
            title: 'Activate this registration period?',
            html: `<strong>${name}</strong> will become the active period. Any currently active period will be closed automatically.`,
            icon: 'question', showCancelButton: true, confirmButtonColor: '#0d4b1f',
            confirmButtonText: 'Yes, activate'
        }).then(r => {
            if (!r.isConfirmed) return;

            Swal.fire({
                title: 'Activating…', allowOutsideClick: false, allowEscapeKey: false,
                showConfirmButton: false, didOpen: () => { Swal.showLoading(); }
            });

            $.ajax({
                url: `{{ url('/admin/registration-period') }}/${id}/status`,
                method: 'POST',
                data: { _token: '{{ csrf_token() }}', status: 'active' },
                success: res => Swal.fire('Activated', res.message, 'success').then(() => location.reload()),
                error: xhr => Swal.fire('Error', xhr.responseJSON?.message || 'Failed', 'error')
            });
        });
    });

    // ── Edit Period modal open ────────────────────────────────────────────────────
    $(document).on('click', '.btn-edit-period', function () {
        const id     = $(this).data('id');
        const name   = $(this).data('name');
        const year   = $(this).data('year');
        const opens  = $(this).data('opens') || '';
        const closes = $(this).data('closes') || '';

        $('#editPeriodId').val(id);
        $('#editPeriodName').val(name);
        $('#editPeriodYear').val(year);
        $('#editPeriodOpens').val(opens ? opens.replace(' ', 'T').substring(0, 16) : '');
        $('#editPeriodCloses').val(closes ? closes.replace(' ', 'T').substring(0, 16) : '');
        $('#editPeriodModal').modal('show');
    });

    // ── Edit Period form submit ───────────────────────────────────────────────────
    $('#editPeriodForm').on('submit', function (e) {
        e.preventDefault();
        const id = $('#editPeriodId').val();

        Swal.fire({
            title: 'Save changes to this period?',
            icon: 'question', showCancelButton: true, confirmButtonColor: '#0d4b1f',
            confirmButtonText: 'Yes, save'
        }).then(r => {
            if (!r.isConfirmed) return;

            Swal.fire({
                title: 'Saving…', allowOutsideClick: false, allowEscapeKey: false,
                showConfirmButton: false, didOpen: () => { Swal.showLoading(); }
            });

            $.ajax({
                url: `{{ url('/admin/registration-period') }}/${id}`,
                method: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    name: $('#editPeriodName').val(),
                    admission_year: $('#editPeriodYear').val(),
                    opens_at: $('#editPeriodOpens').val() || null,
                    closes_at: $('#editPeriodCloses').val() || null,
                    is_active: 1,
                },
                success: function (res) {
                    $('#editPeriodModal').modal('hide');
                    Swal.fire('Updated', res.message || 'Period updated!', 'success')
                        .then(() => location.reload());
                },
                error: function (xhr) {
                    Swal.fire('Error', xhr.responseJSON?.message || 'Failed to update', 'error');
                }
            });
        });
    });

    // ── Delete period (permanent - only available for non-active periods) ────────
    $(document).on('click', '.btn-delete-period', function () {
        const id = $(this).data('id');
        const name = $(this).data('name') || 'this period';
        Swal.fire({
            title: 'Permanently delete this period?',
            html: `This removes <strong>${name}</strong> completely and cannot be undone. Consider using "Close" instead if you may need it again.`,
            icon: 'warning', showCancelButton: true, confirmButtonColor: '#dc2626',
            confirmButtonText: 'Yes, delete permanently'
        }).then(r => {
            if (!r.isConfirmed) return;

            Swal.fire({
                title: 'Deleting…', allowOutsideClick: false, allowEscapeKey: false,
                showConfirmButton: false, didOpen: () => { Swal.showLoading(); }
            });

            $.ajax({
                url: `{{ url('/admin/registration-period') }}/${id}`,
                method: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: res => Swal.fire('Deleted', res.message || 'Period deleted.', 'success').then(() => location.reload()),
                error: xhr => Swal.fire('Error', xhr.responseJSON?.message || 'Failed to delete', 'error')
            });
        });
    });
</script>

@endsection