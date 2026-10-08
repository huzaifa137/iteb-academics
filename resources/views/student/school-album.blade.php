@extends('layouts-side-bar.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card mt-5">

                    <div class="card-header text-white d-flex justify-content-between align-items-center"
                        style="background-color: #253F2D;">
                        <h3 class="card-title">School Album</h3>
                        @if ($portal === 'admin')
                            <a href="{{ url('students/all-students') }}" class="btn btn-sm text-white"
                                style="background-color: #287C44;">
                                <i class="fas fa-users"></i> All Students
                            </a>
                        @endif
                    </div>

                    <div class="card-body">
                        <form method="GET" action="{{ $formUrl }}" class="mb-4" id="albumForm">
                            <div class="row g-3 align-items-end">

                                {{-- School: admin chooses any school, a school only sees its own --}}
                                <div class="col-12 col-md-4">
                                    <label for="house_id" class="form-label">School
                                        @if ($portal === 'admin')<span style="color:red;">(*)</span>@endif
                                    </label>
                                    @if ($portal === 'admin')
                                        <select name="house_id" id="house_id" class="form-control select2" required>
                                            <option value="">-- Select School --</option>
                                            @foreach ($houses as $h)
                                                <option value="{{ $h->ID }}"
                                                    {{ request('house_id') == $h->ID ? 'selected' : '' }}>
                                                    {{ $h->Number }} - {{ $h->House }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input type="text" class="form-control" value="{{ $house->Number }} - {{ $house->House }}"
                                            readonly>
                                    @endif
                                </div>

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

                                <div class="col-12 col-md-2">
                                    <label for="type" class="form-label">Category <span style="color:red;">(*)</span></label>
                                    <select name="type" id="type" class="form-control select2" required>
                                        <option value="">-- Select --</option>
                                        <option value="idaad" {{ request('type') == 'idaad' ? 'selected' : '' }}>Idaad</option>
                                        <option value="thanawi" {{ request('type') == 'thanawi' ? 'selected' : '' }}>Thanawi</option>
                                    </select>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label for="source" class="form-label">Students</label>
                                    <select name="source" id="source" class="form-control">
                                        <option value="approved" {{ request('source', 'approved') == 'approved' ? 'selected' : '' }}>
                                            Approved students
                                        </option>
                                        <option value="registered" {{ request('source') == 'registered' ? 'selected' : '' }}>
                                            All registered (including pending)
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-12 d-flex" style="gap: 0.5rem;">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-filter me-1"></i> Preview
                                    </button>
                                    <a href="{{ $formUrl }}" class="btn btn-secondary">
                                        <i class="fas fa-undo me-1"></i> Reset
                                    </a>
                                    <button type="button" id="albumPrintBtn" class="btn"
                                        style="background-color: #287C44; color: #fff;">
                                        <i class="fas fa-print me-1"></i> Print / Download Album
                                    </button>
                                </div>
                            </div>
                        </form>

                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        @if (request()->filled('year') && request()->filled('type') && $house)
                            <div class="alert mb-3 text-white rounded-0" style="background-color: #287c44;">
                                <strong>{{ $house->House }}</strong>
                                &middot; Year: {{ request('year') }}
                                &middot; Category: {{ ucfirst(request('type')) }}
                                &middot; {{ $records->count() }} student(s)
                                &middot; {{ (int) ceil($records->count() / 15) }} page(s) of 15
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Student ID</th>
                                            <th>Name</th>
                                            <th>Date of Birth</th>
                                            <th>Place of Birth</th>
                                            <th>Nationality</th>
                                            <th>District</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($records as $i => $r)
                                            <tr>
                                                <td>{{ $i + 1 }}</td>
                                                <td>{{ $r['id'] }}</td>
                                                <td>{{ $r['name'] }}</td>
                                                <td>{{ $r['dob'] }}</td>
                                                <td>{{ $r['pob'] }}</td>
                                                <td>{{ $r['nationality'] }}</td>
                                                <td>{{ $r['district'] }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">No students found for this selection.</td>
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
    </div> </div>
        </div>
    </div>


    <script>
        document.getElementById('albumPrintBtn').addEventListener('click', function () {
            var form = document.getElementById('albumForm');
            var houseEl = document.getElementById('house_id');

            if ((houseEl && !houseEl.value) || !document.getElementById('year').value || !document.getElementById('type').value) {
                alert('Please select {{ $portal === 'admin' ? 'a School, ' : '' }}Year and Category before printing.');
                return;
            }

            var params = new URLSearchParams(new FormData(form));
            window.open('{{ $printUrl }}?' + params.toString(), '_blank');
        });
    </script>
@endsection
