@extends('layouts-side-bar.master')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet"
        href="{{ URL::asset('assets/css/admin-dashboard.css') }}?v={{ @filemtime(public_path('assets/css/admin-dashboard.css')) }}">
@endsection

@section('content')
    @php
        $selectedYear = $payload['filters']['year'];
        $selectedCategory = $payload['filters']['category'];

        // Quick actions: only routes that exist are shown. 'badge' => payload key shown as a count bubble.
        $actions = [
            ['Student approvals', 'fa-circle-check', 'admin.student.approvals', 'Review submissions', 'pending'],
            ['All schools', 'fa-school', 'school.allSchools', 'Profiles & options', null],
            ['Find a student', 'fa-magnifying-glass', 'students.individual.search', 'Search by name or ID', null],
            ['Exam timetable', 'fa-calendar-days', 'students.timetable', 'Papers & dates', null],
            ['Passlips', 'fa-id-card', 'passlip.generate', 'Generate & download', null],
            ['Certificates', 'fa-award', 'school.recognition.index', 'School recognition', null],
        ];
    @endphp

    <div class="iteb-dash" id="iteb-dash" data-url="{{ $dataUrl }}">
        <div class="dash-loadbar" aria-hidden="true"></div>

        {{-- ================= HERO ================= --}}
        <section class="dash-hero mt-4" aria-label="Welcome">
            <div>
                <p class="hero-eyebrow" id="hero-date">&nbsp;</p>
                <h1 class="hero-title"><span id="hero-greeting">Welcome back</span>, {{ $firstName }}</h1>
                <p class="hero-sub">Here is a live view of registrations, enrolment, slots and results across every
                    school registered with ITEB.</p>
                <div class="hero-chips">
                    <span class="chip" id="chip-year"><i class="fa-solid fa-calendar-check"></i> <span>…</span></span>
                    <span class="chip" id="chip-window"><span class="dot"></span> <span>…</span></span>
                </div>
            </div>
            <div class="hero-window" id="hero-window" aria-live="polite"></div>
        </section>

        {{-- ================= TOOLBAR ================= --}}
        <section class="dash-toolbar" aria-label="Dashboard filters">
            <div class="tb-group">
                <label for="f-year">Year</label>
                <select id="f-year" class="tb-select">
                    <option value="all" @selected($selectedYear === 'all')>All years</option>
                    @foreach ($years as $y)
                        <option value="{{ $y }}" @selected($selectedYear === (string) $y)>{{ $y }}</option>
                    @endforeach
                </select>
            </div>

            <div class="seg" id="f-category" role="group" aria-label="Category">
                <button type="button" data-value="all" class="{{ $selectedCategory === 'all' ? 'is-active' : '' }}">All</button>
                <button type="button" data-value="ID" class="{{ $selectedCategory === 'ID' ? 'is-active' : '' }}">Idaad</button>
                <button type="button" data-value="TH" class="{{ $selectedCategory === 'TH' ? 'is-active' : '' }}">Thanawi</button>
            </div>

            <div class="tb-spacer"></div>

            <span class="tb-updated" id="tb-updated"><span class="live-dot"></span><span id="tb-updated-text">Updated now</span></span>

            <label class="switch" title="Refresh the numbers every 2 minutes while this tab is open">
                <input type="checkbox" id="f-auto" checked>
                <span class="track"></span> Auto-refresh
            </label>

            <button type="button" class="btn-dash" id="btn-refresh"><i class="fa-solid fa-rotate"></i> Refresh</button>
            <button type="button" class="btn-dash" id="btn-print"><i class="fa-solid fa-print"></i> Print</button>
        </section>

        <div class="dash-body">
            {{-- ================= ALERTS ================= --}}
            <section class="dash-alerts" id="alerts" aria-label="Needs attention"></section>

            {{-- ================= KPIs ================= --}}
            <section class="kpi-grid" id="kpis" aria-label="Key figures"></section>

            {{-- ================= REGISTRATION ================= --}}
            <h2 class="section-title"><i class="fa-solid fa-clipboard-list"></i> Registration</h2>

            <div class="grid-12">
                <div class="dcard span-8">
                    <div class="dcard-head">
                        <div>
                            <h3 class="dcard-title">Registration activity</h3>
                            <p class="dcard-sub">New registrations and submissions to ITEB</p>
                        </div>
                        <div class="seg seg-sm" id="f-range" role="group" aria-label="Time range">
                            <button type="button" data-value="daily" class="is-active">30 days</button>
                            <button type="button" data-value="monthly">12 months</button>
                        </div>
                    </div>
                    <div class="dcard-body"><div id="chart-trend" class="chart-box"></div></div>
                </div>

                <div class="dcard span-4">
                    <div class="dcard-head">
                        <div>
                            <h3 class="dcard-title">Approval pipeline</h3>
                            <p class="dcard-sub">Where every registration currently sits</p>
                        </div>
                    </div>
                    <div class="dcard-body"><div id="chart-pipeline" class="chart-box"></div></div>
                    <div class="dcard-foot" id="pipeline-foot"></div>
                </div>
            </div>

            <div class="grid-12">
                <div class="dcard span-7">
                    <div class="dcard-head">
                        <div>
                            <h3 class="dcard-title">Top schools by registrations</h3>
                            <p class="dcard-sub">Click a bar to open that school's approvals</p>
                        </div>
                    </div>
                    <div class="dcard-body"><div id="chart-top-schools" class="chart-box"></div></div>
                </div>

                <div class="dcard span-5">
                    <div class="dcard-head">
                        <div>
                            <h3 class="dcard-title">Slot utilisation</h3>
                            <p class="dcard-sub" id="slots-sub">Registration slots used</p>
                        </div>
                    </div>
                    <div class="dcard-body">
                        <div id="chart-slots" class="chart-box" style="min-height:220px"></div>
                        <div id="slot-buckets"></div>
                        <div id="slot-lists"></div>
                    </div>
                </div>
            </div>

            <div class="grid-12">
                <div class="dcard span-7">
                    <div class="dcard-head">
                        <div>
                            <h3 class="dcard-title">Needs your attention</h3>
                            <p class="dcard-sub">Schools with registrations waiting for approval</p>
                        </div>
                        @if (Route::has('admin.student.approvals'))
                            <a class="link-more" href="{{ route('admin.student.approvals') }}">Open approvals →</a>
                        @endif
                    </div>
                    <div class="dcard-body table-wrap" id="attention-table"></div>
                </div>

                <div class="dcard span-5">
                    <div class="dcard-head">
                        <div>
                            <h3 class="dcard-title">Quick actions</h3>
                            <p class="dcard-sub">Jump straight to common tasks</p>
                        </div>
                    </div>
                    <div class="dcard-body">
                        <div class="actions">
                            @foreach ($actions as [$label, $icon, $route, $desc, $badge])
                                @if (Route::has($route))
                                    <a class="action" href="{{ route($route) }}">
                                        <span class="ai"><i class="fa-solid {{ $icon }}"></i></span>
                                        <span class="at"><b>{{ $label }}</b><span>{{ $desc }}</span></span>
                                        @if ($badge)
                                            <span class="badge-count" data-badge="{{ $badge }}"></span>
                                        @endif
                                    </a>
                                @endif
                            @endforeach
                        </div>

                        <p class="mini-head" style="margin-top:20px">Upcoming papers</p>
                        <div id="exam-list"></div>
                    </div>
                </div>
            </div>

            {{-- ================= STUDENTS ================= --}}
            <h2 class="section-title"><i class="fa-solid fa-user-graduate"></i> Students</h2>

            <div class="grid-12">
                <div class="dcard span-6">
                    <div class="dcard-head">
                        <div>
                            <h3 class="dcard-title">Enrolment by admission year</h3>
                            <p class="dcard-sub">Active students, split by Idaad and Thanawi (all years)</p>
                        </div>
                    </div>
                    <div class="dcard-body"><div id="chart-history" class="chart-box"></div></div>
                </div>

                <div class="dcard span-3">
                    <div class="dcard-head">
                        <div>
                            <h3 class="dcard-title">Gender balance</h3>
                            <p class="dcard-sub" id="gender-sub">Enrolled students</p>
                        </div>
                    </div>
                    <div class="dcard-body"><div id="chart-gender" class="chart-box"></div></div>
                </div>

                <div class="dcard span-3">
                    <div class="dcard-head">
                        <div>
                            <h3 class="dcard-title">Photo coverage</h3>
                            <p class="dcard-sub">Needed for passlips & cards</p>
                        </div>
                    </div>
                    <div class="dcard-body">
                        <div id="chart-photos" class="chart-box" style="min-height:190px"></div>
                        <div id="photo-list"></div>
                    </div>
                </div>
            </div>

            <div class="grid-12">
                <div class="dcard span-6">
                    <div class="dcard-head">
                        <div>
                            <h3 class="dcard-title">Where students come from</h3>
                            <p class="dcard-sub">Top districts for the selected filters</p>
                        </div>
                    </div>
                    <div class="dcard-body"><div id="chart-districts" class="chart-box"></div></div>
                </div>

                <div class="dcard span-6">
                    <div class="dcard-head">
                        <div>
                            <h3 class="dcard-title">School recognition</h3>
                            <p class="dcard-sub">Active certificates across all schools</p>
                        </div>
                        @if (Route::has('school.recognition.index'))
                            <a class="link-more" href="{{ route('school.recognition.index') }}">Manage →</a>
                        @endif
                    </div>
                    <div class="dcard-body" id="cert-body"></div>
                </div>
            </div>

            {{-- ================= RESULTS ================= --}}
            <h2 class="section-title"><i class="fa-solid fa-chart-line"></i> Results & performance</h2>

            <div class="grid-12">
                <div class="dcard span-4">
                    <div class="dcard-head">
                        <div>
                            <h3 class="dcard-title">Grade distribution</h3>
                            <p class="dcard-sub" id="grades-sub">Published results</p>
                        </div>
                    </div>
                    <div class="dcard-body"><div id="chart-grades" class="chart-box"></div></div>
                </div>

                <div class="dcard span-4">
                    <div class="dcard-head">
                        <div>
                            <h3 class="dcard-title">Classification</h3>
                            <p class="dcard-sub">Overall result classes</p>
                        </div>
                    </div>
                    <div class="dcard-body"><div id="chart-classes" class="chart-box"></div></div>
                </div>

                <div class="dcard span-4">
                    <div class="dcard-head">
                        <div>
                            <h3 class="dcard-title">Average score by year</h3>
                            <p class="dcard-sub">Idaad vs Thanawi (all years)</p>
                        </div>
                    </div>
                    <div class="dcard-body"><div id="chart-performance" class="chart-box"></div></div>
                </div>
            </div>

            <div class="grid-12">
                <div class="dcard span-5">
                    <div class="dcard-head">
                        <div>
                            <h3 class="dcard-title">Top schools by average score</h3>
                            <p class="dcard-sub">Schools with at least 5 results</p>
                        </div>
                    </div>
                    <div class="dcard-body" id="performers"></div>
                </div>

                <div class="dcard span-7">
                    <div class="dcard-head">
                        <div>
                            <h3 class="dcard-title">Recent activity</h3>
                            <p class="dcard-sub">The latest registrations, approvals and slot changes</p>
                        </div>
                    </div>
                    <div class="dcard-body" id="activity"></div>
                </div>
            </div>
        </div>

        <div class="dash-toast" id="dash-toast" role="status" aria-live="polite"></div>
    </div>  </div>
            </div>
        </div>


    <script type="application/json" id="dash-initial">@json($payload)</script>
@endsection

@section('js')
    {{-- ApexCharts ships with the project (public/assets/js/apexcharts.js) so no CDN is needed for charts. --}}
    <script src="{{ URL::asset('assets/js/apexcharts.js') }}"></script>
    <script
        src="{{ URL::asset('assets/js/admin-dashboard.js') }}?v={{ @filemtime(public_path('assets/js/admin-dashboard.js')) }}">
    </script>
@endsection
