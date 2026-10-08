@extends('layouts-side-bar.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card mt-5">

                    <div class="card-header text-white d-flex justify-content-between align-items-center"
                        style="background-color: #253F2D;">
                        <h3 class="card-title">Examination Cards</h3>
                        <a href="{{ url('students/all-students') }}" class="btn btn-sm text-white"
                            style="background-color: #287C44;">
                            <i class="fas fa-users"></i> All Students
                        </a>
                    </div>

                    <div class="card-body">
                        <!-- Filter Form -->
                        <form method="GET" action="{{ route('students.examination-cards') }}" class="mb-4"
                            id="examCardsForm">
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
                                    <a href="{{ route('students.examination-cards') }}" class="btn btn-secondary w-50">
                                        <i class="fas fa-undo me-1"></i> Reset
                                    </a>
                                </div>
                            </div>

                            <div class="row g-3 mt-1">
                                <div class="col-12 col-md-3">
                                    <label for="card_start" class="form-label">First Card No.</label>
                                    <input type="number" min="0" name="card_start" id="card_start" class="form-control"
                                        value="{{ request('card_start', 1) }}">
                                </div>
                                <div class="col-12 col-md-3">
                                    <label for="card_step" class="form-label">Card No. Step</label>
                                    <input type="number" min="1" name="card_step" id="card_step" class="form-control"
                                        value="{{ request('card_step', 1) }}">
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-12">
                                    <button type="button" id="exportPdfBtn" class="btn btn-sm"
                                        style="background-color: #287C44; color: #fff;">
                                        <i class="fas fa-print me-1"></i> Print / Download Cards
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
        document.getElementById('exportPdfBtn').addEventListener('click', function () {
            var houseId = document.getElementById('house_id').value;
            var year = document.getElementById('year').value;
            var type = document.getElementById('type').value;

            if (!houseId || !year || !type) {
                alert('Please select a School, Year and Category before exporting.');
                return;
            }

            var params = new URLSearchParams(new FormData(document.getElementById('examCardsForm')));
            window.open('{{ route('students.examination-cards.print') }}?' + params.toString(), '_blank');
        });
    </script>
@endsection