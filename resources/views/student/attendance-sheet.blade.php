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
        <a href="{{ url('students/all-students') }}" class="btn btn-sm text-white"
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
                                </div>
                            </div>
                        </form>

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
@endsection