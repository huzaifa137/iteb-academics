@extends('layouts-side-bar.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card mt-5">
                    <div class="card-header text-white d-flex justify-content-between align-items-center"
                        style="background-color: #253F2D;">
                        <h3 class="card-title mb-0">Marks Sheet</h3>
                        <div class="d-flex align-items-center" style="gap: 0.5rem;">
                            <a href="{{ route('students.attendance-sheet') }}" class="btn btn-sm text-white"
                                style="background-color: #287C44;">
                                <i class="fas fa-clipboard-list"></i> Attendance Sheet
                            </a>
                            <a href="{{ route('students.timetable') }}" class="btn btn-sm text-white"
                                style="background-color: #2b7641;">
                                <i class="fas fa-calendar-alt"></i> Timetable
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <p class="text-muted mb-3">
                            The sheet sent to each school for recording students' marks per question. Pick the year and
                            category, then print it for <b>one school</b>, or for <b>all schools</b> of that category
                            (all Idaad or all Thanawi).
                        </p>

                        <form method="GET" action="{{ route('students.marks-sheet.print') }}" id="marksSheetForm"
                            target="_blank">
                            <input type="hidden" name="scope" id="scope" value="school">

                            <div class="row g-3 align-items-end">
                                <div class="col-12 col-md-2">
                                    <label for="year" class="form-label">Year <span style="color:red;">(*)</span></label>
                                    <select name="year" id="year" class="form-control select2" required>
                                        <option value="">-- Select Year --</option>
                                        @foreach ($years as $y)
                                            <option value="{{ $y->year_en }}"
                                                {{ old('year') == $y->year_en ? 'selected' : '' }}>
                                                {{ $y->year_en }} - {{ $y->year_ar }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-2">
                                    <label for="type" class="form-label">Category <span
                                            style="color:red;">(*)</span></label>
                                    <select name="type" id="type" class="form-control select2" required>
                                        <option value="">-- Select --</option>
                                        <option value="idaad" {{ old('type') == 'idaad' ? 'selected' : '' }}>Idaad</option>
                                        <option value="thanawi" {{ old('type') == 'thanawi' ? 'selected' : '' }}>Thanawi
                                        </option>
                                    </select>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="house_id" class="form-label">School (only for "One School")</label>
                                    <select name="house_id" id="house_id" class="form-control select2">
                                        <option value="">-- Select School --</option>
                                        @foreach ($houses as $house)
                                            <option value="{{ $house->ID }}"
                                                {{ old('house_id') == $house->ID ? 'selected' : '' }}>
                                                {{ $house->Number }} - {{ $house->House }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-2">
                                    <label for="questions" class="form-label">Question columns</label>
                                    <input type="number" name="questions" id="questions" class="form-control"
                                        min="1" max="20" value="{{ old('questions', 15) }}">
                                </div>
                            </div>

                            <hr>

                            <div class="row g-3 mb-3">
                                <div class="col-12 col-md-8">
                                    <label for="timetable_id" class="form-label">
                                        Exam Timetable Entry (optional)
                                        <a href="{{ route('students.timetable') }}" class="small ms-2" target="_blank">
                                            (manage timetable)
                                        </a>
                                    </label>
                                    <select name="timetable_id" id="timetable_id" class="form-control">
                                        <option value="">-- Select Year and Category first --</option>
                                    </select>
                                    <small id="timetableHint" class="text-muted"></small>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-12 col-md-3">
                                    <label for="subject" class="form-label">Subject (Arabic)</label>
                                    <input type="text" name="subject" id="subject" class="form-control"
                                        placeholder="القرآن وعلومه" value="{{ old('subject') }}" dir="rtl">
                                </div>
                                <div class="col-12 col-md-3">
                                    <label for="paper" class="form-label">Paper (Arabic)</label>
                                    <input type="text" name="paper" id="paper" class="form-control"
                                        placeholder="التلاوة والتجويد" value="{{ old('paper') }}" dir="rtl">
                                </div>
                                <div class="col-12 col-md-2">
                                    <label for="exam_date" class="form-label">Exam Date</label>
                                    <input type="text" name="exam_date" id="exam_date" class="form-control"
                                        placeholder="dd/mm/yyyy" value="{{ old('exam_date') }}">
                                </div>
                                <div class="col-12 col-md-2">
                                    <label for="start_time" class="form-label">Start Time</label>
                                    <input type="time" name="start_time" id="start_time" class="form-control"
                                        value="{{ old('start_time') }}">
                                </div>
                                <div class="col-12 col-md-2">
                                    <label for="end_time" class="form-label">End Time</label>
                                    <input type="time" name="end_time" id="end_time" class="form-control"
                                        value="{{ old('end_time') }}">
                                </div>
                            </div>

                            <small class="text-muted d-block mt-2">
                                Leave Subject / Paper empty to print the default <b>القرآن وعلومه &ndash; التلاوة والتجويد</b>.
                            </small>

                            <div class="row mt-3">
                                <div class="col-12 d-flex flex-wrap" style="gap: .5rem;">
                                    <button type="button" class="btn btn-sm" id="btnSchool"
                                        style="background-color: #287C44; color: #fff;">
                                        <i class="fas fa-school me-1"></i> One School
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-success" id="btnAllSelected">
                                        <i class="fas fa-print me-1"></i> All Schools &ndash; <span id="allLabel">Selected
                                            Category</span>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-dark" id="btnAllIdaad">
                                        <i class="fas fa-layer-group me-1"></i> All Idaad Schools
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-dark" id="btnAllThanawi">
                                        <i class="fas fa-layer-group me-1"></i> All Thanawi Schools
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div></div>
        </div>
    </div>

    <script>
        // Runs on window "load": the layout re-initialises select2 in the footer, so the change
        // handlers must be bound after everything has loaded (same as the Attendance Sheet page).
        window.addEventListener('load', function () {
            var $id = function (x) { return document.getElementById(x); };
            var form = $id('marksSheetForm');
            var yearEl = $id('year'), typeEl = $id('type'), ttEl = $id('timetable_id'), hintEl = $id('timetableHint');
            var entries = {};
            var reqNo = 0;

            function setVal(id, v) { $id(id).value = v || ''; }

            function loadTimetable() {
                var myReq = ++reqNo;
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
                    .then(function (r) { if (!r.ok) { throw new Error('HTTP ' + r.status); } return r.json(); })
                    .then(function (list) {
                        if (myReq !== reqNo) { return; }
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

            function updateLabel() {
                var t = typeEl.value;
                $id('allLabel').textContent = t === 'idaad' ? 'Idaad' : (t === 'thanawi' ? 'Thanawi' : 'Selected Category');
            }

            ttEl.addEventListener('change', fillFromEntry);
            ['year', 'type'].forEach(function (id) {
                $id(id).addEventListener('change', function () { loadTimetable(); updateLabel(); });
            });
            if (window.jQuery) {
                window.jQuery('#year, #type').on('change', function () { loadTimetable(); updateLabel(); });
            }
            loadTimetable();
            updateLabel();

            // scope: 'school' | 'all'. forcedType overrides the Category dropdown (all Idaad / all Thanawi).
            function generate(scope, forcedType) {
                if (!yearEl.value) { alert('Please select a Year first.'); return; }
                if (!forcedType && !typeEl.value) { alert('Please select a Category first.'); return; }
                if (scope === 'school' && !$id('house_id').value) { alert('Please select a School first.'); return; }

                $id('scope').value = scope;
                var params = new URLSearchParams(new FormData(form));
                if (forcedType) { params.set('type', forcedType); }
                if (scope === 'all') { params.delete('house_id'); }
                // The timetable entry belongs to one category: drop it if it would not match.
                if (forcedType && forcedType !== typeEl.value) { params.delete('timetable_id'); }
                window.open('{{ route('students.marks-sheet.print') }}?' + params.toString(), '_blank');
            }

            $id('btnSchool').addEventListener('click', function () { generate('school', null); });
            $id('btnAllSelected').addEventListener('click', function () { generate('all', null); });
            $id('btnAllIdaad').addEventListener('click', function () { generate('all', 'idaad'); });
            $id('btnAllThanawi').addEventListener('click', function () { generate('all', 'thanawi'); });
        });
    </script>
@endsection
