@extends('layouts-side-bar.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card mt-5">

                    <div class="card-header text-white d-flex justify-content-between align-items-center"
                        style="background-color: #253F2D;">
                        <h3 class="card-title">Attendance Sheet</h3>
                        <a href="{{ url('students/all-students') }}" class="btn btn-sm text-white"
                            style="background-color: #287C44;">
                            <i class="fas fa-users"></i> All Students
                        </a>
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

                            <!-- Optional exam paper details, used on the printed sheet only -->
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

    <script>
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