@extends('layouts-side-bar.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card mt-5">
<div class="card-header text-white d-flex justify-content-between align-items-center"
    style="background-color: #253F2D;">
    <h3 class="card-title mb-0">Attendance Sheet</h3>
    <div class="d-flex align-items-center" style="gap: 0.5rem;">
        <a href="{{ url('students/all-students') }}" class="btn btn-sm text-white"
            style="background-color: #287C44;">
            <i class="fas fa-users"></i> All Students
        </a>
        <a href="{{ route('students.timetable') }}" class="btn btn-sm text-white"
            style="background-color: #2b7641;">
            <i class="fas fa-calendar-alt"></i> Timetable
        </a>
    </div>
</div>

                    <div class="card-body">
                        <!-- Filter Form -->
                        <form method="GET" action="{{ route('students.attendance-sheet') }}" class="mb-4"
                            id="attendanceSheetForm">
                            <div class="row g-3 align-items-end">

                                <!-- School (House) -->
                                <div class="col-12 col-md-4">
                                    <label for="house_id" class="form-label">School <span
                                            style="color:red;">(*)</span></label>
                                    <select name="house_id" id="house_id" class="form-control select2" required>
                                        <option value="">-- Select School --</option>
                                        @foreach ($houses as $house)
                                            <option value="{{ $house->ID }}"
                                                {{ request('house_id') == $house->ID ? 'selected' : '' }}>
                                                {{ $house->Number }} - {{ $house->House }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Year -->
                                <div class="col-12 col-md-2">
                                    <label for="year" class="form-label">Year <span style="color:red;">(*)</span></label>
                                    <select name="year" id="year" class="form-control select2" required>
                                        <option value="">-- Select Year --</option>
                                        @foreach ($years as $y)
                                            <option value="{{ $y->year_en }}"
                                                {{ request('year') == $y->year_en ? 'selected' : '' }}>
                                                {{ $y->year_en }} - {{ $y->year_ar }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Category -->
                                <div class="col-12 col-md-2">
                                    <label for="type" class="form-label">Category <span
                                            style="color:red;">(*)</span></label>
                                    <select name="type" id="type" class="form-control select2" required>
                                        <option value="">-- Select --</option>
                                        <option value="idaad" {{ request('type') == 'idaad' ? 'selected' : '' }}>
                                            Idaad
                                        </option>
                                        <option value="thanawi" {{ request('type') == 'thanawi' ? 'selected' : '' }}>
                                            Thanawi
                                        </option>
                                    </select>
                                </div>

                                <div class="col-12 col-md-4 d-flex" style="gap: 0.25rem;">
                                    <button type="submit" class="btn btn-primary w-50">
                                        <i class="fas fa-filter me-1"></i> Preview
                                    </button>
                                    <a href="{{ route('students.attendance-sheet') }}" class="btn btn-secondary w-50">
                                        <i class="fas fa-undo me-1"></i> Reset
                                    </a>
                                </div>
                            </div>

                            <hr>

                            <!-- Timetable: pick an entry and the exam details below fill in automatically -->
                            <div class="row g-3 mb-3">
                                <div class="col-12 col-md-8">
                                    <label for="timetable_id" class="form-label">
                                        Exam Timetable Entry
                                        <a href="{{ route('students.timetable') }}" class="small ms-2" target="_blank">
                                            (manage timetable)
                                        </a>
                                    </label>
                                    <select name="timetable_id" id="timetable_id" class="form-control"
                                        data-selected="{{ request('timetable_id') }}">
                                        <option value="">-- Select School/Year/Category first --</option>
                                    </select>
                                    <small id="timetableHint" class="text-muted"></small>
                                </div>
                            </div>

                            <!-- Exam paper details, filled from the timetable (still editable) -->
                            <div class="row g-3">
                                <div class="col-12 col-md-3">
                                    <label for="subject" class="form-label">Subject (Arabic)</label>
                                    <input type="text" name="subject" id="subject" class="form-control"
                                        placeholder="القرآن وعلومه" value="{{ request('subject') }}" dir="rtl">
                                </div>
                                <div class="col-12 col-md-3">
                                    <label for="paper" class="form-label">Paper (Arabic)</label>
                                    <input type="text" name="paper" id="paper" class="form-control"
                                        placeholder="التلاوة والتجويد" value="{{ request('paper') }}" dir="rtl">
                                </div>
                                <div class="col-12 col-md-2">
                                    <label for="exam_date" class="form-label">Exam Date</label>
                                    <input type="text" name="exam_date" id="exam_date" class="form-control"
                                        placeholder="dd/mm/yyyy" value="{{ request('exam_date') }}">
                                </div>
                                <div class="col-12 col-md-2">
                                    <label for="start_time" class="form-label">Start Time</label>
                                    <input type="time" name="start_time" id="start_time" class="form-control"
                                        value="{{ request('start_time') }}">
                                </div>
                                <div class="col-12 col-md-2">
                                    <label for="end_time" class="form-label">End Time</label>
                                    <input type="time" name="end_time" id="end_time" class="form-control"
                                        value="{{ request('end_time') }}">
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-12">
                                    <button type="button" id="exportPdfBtn" class="btn btn-sm"
                                        style="background-color: #287C44; color: #fff;">
                                        <i class="fas fa-file-pdf me-1"></i> Export PDF
                                    </button>
                                    <button type="button" id="exportAllSchoolsBtn" class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-school me-1"></i> All Schools &ndash; Selected Subject
                                    </button>
                                    <button type="button" id="exportAllSubjectsBtn" class="btn btn-sm btn-outline-dark">
                                        <i class="fas fa-layer-group me-1"></i> All Schools &ndash; All Subjects
                                    </button>
                                </div>
                            </div>
                        </form>

                        <!-- ============================================================
                             Bulk generator: pick MANY subjects + a collection of schools
                             ============================================================ -->
                        <style>
                            #bulkPicker .bk-box { border: 1px solid #ced4da; border-radius: 4px; background: #fff; }
                            #bulkPicker .bk-head { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: .25rem; padding: .4rem .6rem; background: #f1f5f2; border-bottom: 1px solid #ced4da; font-weight: 600; }
                            #bulkPicker .bk-body { padding: .5rem .6rem; }
                            #bulkPicker .bk-list { max-height: 260px; overflow-y: auto; border: 1px solid #e3e6e8; border-radius: 3px; }
                            #bulkPicker .bk-row { display: flex; align-items: flex-start; gap: .5rem; margin: 0; padding: .3rem .5rem; border-bottom: 1px solid #f0f0f0; cursor: pointer; font-weight: normal; }
                            #bulkPicker .bk-row:hover { background: #f3faf5; }
                            #bulkPicker .bk-row input { margin-top: .25rem; }
                            #bulkPicker .bk-chips { display: flex; flex-wrap: wrap; gap: .3rem; max-height: 120px; overflow-y: auto; margin-top: .5rem; }
                            #bulkPicker .bk-chip { display: inline-flex; align-items: center; gap: .35rem; background: #287C44; color: #fff; border-radius: 12px; padding: 2px 4px 2px 10px; font-size: 12px; max-width: 100%; }
                            #bulkPicker .bk-chip span { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 230px; }
                            #bulkPicker .bk-chip button { background: rgba(255,255,255,.25); color: #fff; border: 0; border-radius: 50%; width: 18px; height: 18px; line-height: 16px; padding: 0; cursor: pointer; }
                            #bulkPicker .bk-chip button:hover { background: #c0392b; }
                            #bulkPicker .bk-muted { color: #6c757d; font-size: 12px; }
                        </style>

                        <hr>
                        <div id="bulkPicker" class="mt-3 mb-4">
                            <h5 class="mb-1"><i class="fas fa-layer-group me-1"></i> Generate for selected subjects &amp; schools</h5>
                            <p class="bk-muted mb-3">
                                Uses the <b>Year</b> and <b>Category</b> chosen above. Tick the subjects, build your list
                                of schools, then generate every sheet in one print-ready document.
                            </p>

                            <div class="row g-3">
                                <!-- Subjects -->
                                <div class="col-12 col-lg-5 mb-3">
                                    <div class="bk-box">
                                        <div class="bk-head">
                                            <span>1. Subjects <span id="bkSubjCount" class="bk-muted">(0 selected)</span></span>
                                            <span>
                                                <button type="button" id="bkSubjAll" class="btn btn-sm btn-outline-success py-0">Select all</button>
                                                <button type="button" id="bkSubjNone" class="btn btn-sm btn-outline-secondary py-0">Clear</button>
                                            </span>
                                        </div>
                                        <div class="bk-body">
                                            <input type="text" id="bkSubjFilter" class="form-control form-control-sm mb-2" placeholder="Filter subjects...">
                                            <div id="bkSubjList" class="bk-list"></div>
                                            <div id="bkSubjHint" class="bk-muted mt-1"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Schools -->
                                <div class="col-12 col-lg-7 mb-3">
                                    <div class="bk-box">
                                        <div class="bk-head">
                                            <span>2. Schools <span id="bkSchoolCount" class="bk-muted">(0 selected)</span></span>
                                            <span>
                                                <button type="button" id="bkSchoolAll" class="btn btn-sm btn-outline-success py-0" title="Tick every school currently shown in the list">Add all shown</button>
                                                <button type="button" id="bkSchoolNone" class="btn btn-sm btn-outline-secondary py-0" title="Untick every school currently shown in the list">Remove all shown</button>
                                                <button type="button" id="bkSchoolClear" class="btn btn-sm btn-outline-danger py-0">Clear list</button>
                                            </span>
                                        </div>
                                        <div class="bk-body">
                                            <input type="text" id="bkSchoolFilter" class="form-control form-control-sm mb-2" placeholder="Search school by name or number, then tick to add / untick to remove...">
                                            <div id="bkSchoolList" class="bk-list"></div>

                                            <div class="bk-muted mt-2 mb-1">Selected schools (click &times; to remove):</div>
                                            <div id="bkChips" class="bk-chips"></div>

                                            <hr class="my-2">
                                            <div class="row g-2">
                                                <div class="col-12 col-md-6 mb-2">
                                                    <label class="bk-muted mb-1">Add by school numbers (e.g. <code>12, 15, 20-25</code>)</label>
                                                    <textarea id="bkPaste" rows="2" class="form-control form-control-sm" placeholder="Paste school numbers separated by commas, spaces or new lines"></textarea>
                                                    <button type="button" id="bkPasteAdd" class="btn btn-sm btn-outline-success mt-1">Add these schools</button>
                                                    <div id="bkPasteMsg" class="bk-muted mt-1"></div>
                                                </div>
                                                <div class="col-12 col-md-6 mb-2">
                                                    <label class="bk-muted mb-1">Saved school lists (kept in this browser)</label>
                                                    <select id="bkGroups" class="form-control form-control-sm"></select>
                                                    <div class="mt-1">
                                                        <button type="button" id="bkGroupLoad" class="btn btn-sm btn-outline-primary">Load</button>
                                                        <button type="button" id="bkGroupSave" class="btn btn-sm btn-outline-success">Save current list</button>
                                                        <button type="button" id="bkGroupDelete" class="btn btn-sm btn-outline-danger">Delete</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center flex-wrap" style="gap: .75rem;">
                                <select id="bkOrder" class="form-control form-control-sm" style="width: auto;">
                                    <option value="subject">Order: subject by subject</option>
                                    <option value="school">Order: school by school</option>
                                </select>
                                <button type="button" id="bkGenerate" class="btn btn-success">
                                    <i class="fas fa-print me-1"></i> Generate attendance sheets
                                </button>
                                <span id="bkSummary" class="bk-muted"></span>
                            </div>
                        </div>

                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <!-- Preview -->
                        @if (request()->filled('house_id') && request()->filled('year') && request()->filled('type'))
                            <div class="alert mb-3 text-white rounded-0" style="background-color: #287c44;">
                                <strong>{{ $selectedHouse->House ?? 'N/A' }}</strong>
                                &middot; Year: {{ request('year') }}
                                &middot; Category: {{ ucfirst(request('type')) }}
                                &middot; {{ $students->count() }} student(s)
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Reg. No.</th>
                                            <th>Name (EN)</th>
                                            <th>Name (AR)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($students as $i => $student)
                                            <tr>
                                                <td>{{ $i + 1 }}</td>
                                                <td>{{ $student->Student_ID }}</td>
                                                <td>{{ $student->Student_Name }}</td>
                                                <td dir="rtl">{{ $student->Student_Name_AR }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">No students found for this
                                                    selection.</td>
                                            </tr>
                                        @endforelse
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
        </div>
    </div>

    <script>
        // Runs on window "load": the layout loads jQuery again in the footer and initialises select2
        // on that copy, so the change handlers must be bound after everything has loaded.
        window.addEventListener('load', function () {
            var yearEl = document.getElementById('year');
            var typeEl = document.getElementById('type');
            var ttEl = document.getElementById('timetable_id');
            var hintEl = document.getElementById('timetableHint');
            var entries = {};

            function setVal(id, v) { document.getElementById(id).value = v || ''; }

            function loadTimetable(selectedId) {
                entries = {};
                ttEl.innerHTML = '';

                if (!yearEl.value || !typeEl.value) {
                    ttEl.innerHTML = '<option value="">-- Select Year and Category first --</option>';
                    hintEl.textContent = '';
                    return;
                }

                fetch('{{ route('students.timetable.options') }}?year=' + encodeURIComponent(yearEl.value) +
                        '&category=' + encodeURIComponent(typeEl.value), {
                        credentials: 'same-origin',
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(function (r) {
                        if (!r.ok) { throw new Error('HTTP ' + r.status); }
                        return r.json();
                    })
                    .then(function (list) {
                        var first = document.createElement('option');
                        first.value = '';
                        first.textContent = '-- Select timetable entry (or type details below) --';
                        ttEl.appendChild(first);

                        list.forEach(function (e) {
                            entries[e.id] = e;
                            var o = document.createElement('option');
                            o.value = e.id;
                            o.textContent = e.label;
                            ttEl.appendChild(o);
                        });

                        hintEl.textContent = list.length ? '' : 'No timetable entries for this year/category yet.';
                        if (selectedId && entries[selectedId]) { ttEl.value = selectedId; }
                    })
                    .catch(function (err) { hintEl.textContent = 'Could not load the timetable (' + err.message + ').'; });
            }

            function fillFromEntry() {
                var e = entries[ttEl.value];
                if (!e) { return; }
                setVal('subject', e.subject_ar);
                setVal('paper', e.paper_ar);
                setVal('exam_date', e.exam_date);
                setVal('start_time', e.start_time);
                setVal('end_time', e.end_time);
            }

            ttEl.addEventListener('change', fillFromEntry);

            // select2 triggers its change event through jQuery, so listen through jQuery as well
            var $ = window.jQuery;
            if ($) {
                $('#year, #type').on('change', function () { loadTimetable(''); });
            }
            yearEl.addEventListener('change', function () { loadTimetable(''); });
            typeEl.addEventListener('change', function () { loadTimetable(''); });

            loadTimetable(ttEl.getAttribute('data-selected'));
        });

        // Bulk export: every school, for the selected timetable subject or for all subjects.
        function openBulkExport(allSubjects) {
            var year = document.getElementById('year').value;
            var type = document.getElementById('type').value;
            var tt = document.getElementById('timetable_id').value;

            if (!year || !type) {
                alert('Please select a Year and Category first.');
                return;
            }
            if (!allSubjects && !tt) {
                alert('Please select an Exam Timetable Entry first (or use "All Schools - All Subjects").');
                return;
            }

            var params = new URLSearchParams({ year: year, type: type });
            if (!allSubjects) { params.set('timetable_id', tt); }

            window.open('{{ route('students.attendance-sheet.export.bulk') }}?' + params.toString(), '_blank');
        }

        document.getElementById('exportAllSchoolsBtn').addEventListener('click', function () { openBulkExport(false); });
        document.getElementById('exportAllSubjectsBtn').addEventListener('click', function () { openBulkExport(true); });

        document.getElementById('exportPdfBtn').addEventListener('click', function () {
            var houseId = document.getElementById('house_id').value;
            var year = document.getElementById('year').value;
            var type = document.getElementById('type').value;

            if (!houseId || !year || !type) {
                alert('Please select a School, Year and Category before exporting.');
                return;
            }

            var params = new URLSearchParams(new FormData(document.getElementById('attendanceSheetForm')));
            window.open('{{ route('students.attendance-sheet.export.pdf') }}?' + params.toString(), '_blank');
        });
    </script>

    <script>
        // Bulk generator: many subjects x a collection of schools -> one print-ready document.
        window.addEventListener('load', function () {
            var SCHOOLS = {!! json_encode(
                $houses->map(fn ($h) => ['id' => (int) $h->ID, 'number' => (string) $h->Number, 'name' => (string) $h->House])->values(),
                JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE
            ) !!};

            var LS_CURRENT = 'iteb_att_bulk_schools';
            var LS_GROUPS = 'iteb_att_bulk_groups';

            var $id = function (x) { return document.getElementById(x); };
            var yearEl = $id('year'), typeEl = $id('type');

            var entries = [];            // timetable entries for the chosen year/category
            var pickedSubjects = {};     // timetable id -> true
            var picked = {};             // school id -> true
            var rows = {};               // school id -> { row, cb, text }
            var byId = {};
            var byNumber = {};

            SCHOOLS.forEach(function (s) { byId[s.id] = s; byNumber[s.number.toLowerCase()] = s; });

            /* ---------- small helpers ---------- */
            function count(o) { return Object.keys(o).length; }
            function lsGet(key, fallback) {
                try { var v = JSON.parse(localStorage.getItem(key)); return v == null ? fallback : v; } catch (e) { return fallback; }
            }
            function lsSet(key, val) { try { localStorage.setItem(key, JSON.stringify(val)); } catch (e) {} }
            function schoolText(s) { return s.number + ' - ' + s.name; }

            function updateSummary() {
                var subjects = count(pickedSubjects), schools = count(picked);
                $id('bkSubjCount').textContent = '(' + subjects + ' selected)';
                $id('bkSchoolCount').textContent = '(' + schools + ' selected)';
                $id('bkSummary').textContent = (subjects && schools)
                    ? subjects + ' subject(s) \u00d7 ' + schools + ' school(s) = up to ' + (subjects * schools) + ' sheet set(s)'
                    : 'Select at least one subject and one school.';
            }

            /* ---------- subjects ---------- */
            function renderSubjects() {
                var list = $id('bkSubjList');
                var q = $id('bkSubjFilter').value.trim().toLowerCase();
                list.innerHTML = '';

                entries.forEach(function (e) {
                    if (q && e.label.toLowerCase().indexOf(q) === -1) { return; }
                    var label = document.createElement('label');
                    label.className = 'bk-row';
                    var cb = document.createElement('input');
                    cb.type = 'checkbox';
                    cb.checked = !!pickedSubjects[e.id];
                    cb.addEventListener('change', function () {
                        if (cb.checked) { pickedSubjects[e.id] = true; } else { delete pickedSubjects[e.id]; }
                        updateSummary();
                    });
                    var span = document.createElement('span');
                    span.textContent = e.label;
                    label.appendChild(cb);
                    label.appendChild(span);
                    list.appendChild(label);
                });
            }

            var subjReq = 0;   // only the latest request may update the list (change fires twice with select2)
            function loadSubjects() {
                var myReq = ++subjReq;
                entries = [];
                pickedSubjects = {};
                $id('bkSubjList').innerHTML = '';
                var hint = $id('bkSubjHint');

                if (!yearEl.value || !typeEl.value) {
                    hint.textContent = 'Select Year and Category above to load the subjects.';
                    updateSummary();
                    return;
                }
                hint.textContent = 'Loading...';

                fetch('{{ route('students.timetable.options') }}?year=' + encodeURIComponent(yearEl.value) +
                        '&category=' + encodeURIComponent(typeEl.value), {
                        credentials: 'same-origin',
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(function (r) { if (!r.ok) { throw new Error('HTTP ' + r.status); } return r.json(); })
                    .then(function (list) {
                        if (myReq !== subjReq) { return; }
                        entries = list;
                        hint.textContent = list.length ? '' : 'No timetable entries for this year/category yet.';
                        renderSubjects();
                        updateSummary();
                    })
                    .catch(function (err) { hint.textContent = 'Could not load the subjects (' + err.message + ').'; });
            }

            $id('bkSubjFilter').addEventListener('input', renderSubjects);
            $id('bkSubjAll').addEventListener('click', function () {
                var q = $id('bkSubjFilter').value.trim().toLowerCase();
                entries.forEach(function (e) {
                    if (!q || e.label.toLowerCase().indexOf(q) !== -1) { pickedSubjects[e.id] = true; }
                });
                renderSubjects(); updateSummary();
            });
            $id('bkSubjNone').addEventListener('click', function () {
                pickedSubjects = {};
                renderSubjects(); updateSummary();
            });

            /* ---------- schools ---------- */
            function buildSchoolRows() {
                var list = $id('bkSchoolList');
                list.innerHTML = '';
                SCHOOLS.forEach(function (s) {
                    var label = document.createElement('label');
                    label.className = 'bk-row';
                    var cb = document.createElement('input');
                    cb.type = 'checkbox';
                    cb.addEventListener('change', function () { setPicked(s.id, cb.checked); refreshSchools(); });
                    var span = document.createElement('span');
                    span.textContent = schoolText(s);
                    label.appendChild(cb);
                    label.appendChild(span);
                    list.appendChild(label);
                    rows[s.id] = { row: label, cb: cb, text: schoolText(s).toLowerCase() };
                });
            }

            function setPicked(id, on) {
                if (!byId[id]) { return; }
                if (on) { picked[id] = true; } else { delete picked[id]; }
                if (rows[id]) { rows[id].cb.checked = !!on; }
            }

            function shownIds() {
                var q = $id('bkSchoolFilter').value.trim().toLowerCase();
                return SCHOOLS.filter(function (s) { return !q || rows[s.id].text.indexOf(q) !== -1; })
                    .map(function (s) { return s.id; });
            }

            function applySchoolFilter() {
                var shown = {};
                shownIds().forEach(function (id) { shown[id] = true; });
                SCHOOLS.forEach(function (s) { rows[s.id].row.style.display = shown[s.id] ? '' : 'none'; });
            }

            function renderChips() {
                var box = $id('bkChips');
                box.innerHTML = '';
                var any = false;
                SCHOOLS.forEach(function (s) {
                    if (!picked[s.id]) { return; }
                    any = true;
                    var chip = document.createElement('span');
                    chip.className = 'bk-chip';
                    chip.title = schoolText(s);
                    var t = document.createElement('span');
                    t.textContent = schoolText(s);
                    var x = document.createElement('button');
                    x.type = 'button';
                    x.innerHTML = '&times;';
                    x.title = 'Remove';
                    x.addEventListener('click', function () { setPicked(s.id, false); refreshSchools(); });
                    chip.appendChild(t);
                    chip.appendChild(x);
                    box.appendChild(chip);
                });
                if (!any) {
                    var empty = document.createElement('span');
                    empty.className = 'bk-muted';
                    empty.textContent = 'No schools selected yet.';
                    box.appendChild(empty);
                }
            }

            function refreshSchools() {
                renderChips();
                updateSummary();
                lsSet(LS_CURRENT, Object.keys(picked).map(Number));
            }

            $id('bkSchoolFilter').addEventListener('input', applySchoolFilter);
            $id('bkSchoolAll').addEventListener('click', function () {
                shownIds().forEach(function (id) { setPicked(id, true); });
                refreshSchools();
            });
            $id('bkSchoolNone').addEventListener('click', function () {
                shownIds().forEach(function (id) { setPicked(id, false); });
                refreshSchools();
            });
            $id('bkSchoolClear').addEventListener('click', function () {
                Object.keys(picked).forEach(function (id) { setPicked(Number(id), false); });
                refreshSchools();
            });

            /* ---------- add by pasted numbers ---------- */
            $id('bkPasteAdd').addEventListener('click', function () {
                var tokens = $id('bkPaste').value.split(/[\s,;]+/).filter(Boolean);
                var added = 0, missing = [];

                tokens.forEach(function (tok) {
                    var hit = byNumber[tok.toLowerCase()];
                    if (hit) { if (!picked[hit.id]) { added++; } setPicked(hit.id, true); return; }

                    var m = tok.match(/^(\d+)\s*[-\u2013]\s*(\d+)$/);   // range such as 20-25
                    if (m) {
                        var lo = Math.min(+m[1], +m[2]), hi = Math.max(+m[1], +m[2]), found = 0;
                        SCHOOLS.forEach(function (s) {
                            if (/^\d+$/.test(s.number) && +s.number >= lo && +s.number <= hi) {
                                found++; if (!picked[s.id]) { added++; } setPicked(s.id, true);
                            }
                        });
                        if (!found) { missing.push(tok); }
                        return;
                    }
                    missing.push(tok);
                });

                refreshSchools();
                $id('bkPasteMsg').textContent = 'Added ' + added + ' school(s).' +
                    (missing.length ? ' Not found: ' + missing.join(', ') : '');
                if (!missing.length) { $id('bkPaste').value = ''; }
            });

            /* ---------- saved school lists ---------- */
            function renderGroups(selectName) {
                var groups = lsGet(LS_GROUPS, {});
                var sel = $id('bkGroups');
                sel.innerHTML = '';
                var first = document.createElement('option');
                first.value = '';
                first.textContent = '-- Saved lists --';
                sel.appendChild(first);
                Object.keys(groups).sort().forEach(function (name) {
                    var o = document.createElement('option');
                    o.value = name;
                    o.textContent = name + ' (' + groups[name].length + ')';
                    sel.appendChild(o);
                });
                if (selectName) { sel.value = selectName; }
            }

            $id('bkGroupSave').addEventListener('click', function () {
                var ids = Object.keys(picked).map(Number);
                if (!ids.length) { alert('Add at least one school before saving a list.'); return; }
                var name = prompt('Name for this list of schools:', $id('bkGroups').value || '');
                if (!name || !name.trim()) { return; }
                name = name.trim();
                var groups = lsGet(LS_GROUPS, {});
                if (groups[name] && !confirm('A list named "' + name + '" already exists. Replace it?')) { return; }
                groups[name] = ids;
                lsSet(LS_GROUPS, groups);
                renderGroups(name);
            });

            $id('bkGroupLoad').addEventListener('click', function () {
                var name = $id('bkGroups').value;
                var groups = lsGet(LS_GROUPS, {});
                if (!name || !groups[name]) { alert('Choose a saved list first.'); return; }
                Object.keys(picked).forEach(function (id) { setPicked(Number(id), false); });
                groups[name].forEach(function (id) { setPicked(id, true); });
                refreshSchools();
            });

            $id('bkGroupDelete').addEventListener('click', function () {
                var name = $id('bkGroups').value;
                var groups = lsGet(LS_GROUPS, {});
                if (!name || !groups[name]) { alert('Choose a saved list first.'); return; }
                if (!confirm('Delete the saved list "' + name + '"?')) { return; }
                delete groups[name];
                lsSet(LS_GROUPS, groups);
                renderGroups();
            });

            /* ---------- generate ---------- */
            $id('bkGenerate').addEventListener('click', function () {
                if (!yearEl.value || !typeEl.value) { alert('Please select a Year and Category first.'); return; }
                var subjectIds = entries.filter(function (e) { return pickedSubjects[e.id]; }).map(function (e) { return e.id; });
                var schoolIds = Object.keys(picked);
                if (!subjectIds.length) { alert('Please tick at least one subject.'); return; }
                if (!schoolIds.length) { alert('Please add at least one school.'); return; }

                var form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('students.attendance-sheet.export.selected') }}';
                form.target = '_blank';
                form.style.display = 'none';

                function add(name, value) {
                    var i = document.createElement('input');
                    i.type = 'hidden'; i.name = name; i.value = value;
                    form.appendChild(i);
                }
                add('_token', '{{ csrf_token() }}');
                add('year', yearEl.value);
                add('type', typeEl.value);
                add('order', $id('bkOrder').value);
                add('timetable_ids', subjectIds.join(','));
                add('house_ids', schoolIds.join(','));

                document.body.appendChild(form);
                form.submit();
                document.body.removeChild(form);
            });

            /* ---------- init ---------- */
            buildSchoolRows();
            lsGet(LS_CURRENT, []).forEach(function (id) { setPicked(Number(id), true); });   // restore last list
            renderChips();
            renderGroups();
            updateSummary();
            loadSubjects();

            yearEl.addEventListener('change', loadSubjects);
            typeEl.addEventListener('change', loadSubjects);
            if (window.jQuery) { window.jQuery('#year, #type').on('change', loadSubjects); }
        });
    </script>
@endsection