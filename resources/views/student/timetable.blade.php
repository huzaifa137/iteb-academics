@extends('layouts-side-bar.master')

@section('content')
    <style>
        .swal2-container { z-index: 20000 !important; }
        .modal { z-index: 10500; }
        .modal-backdrop { z-index: 10400; }
        .flex-nowrap { flex-wrap: nowrap !important; }
.text-nowrap { white-space: nowrap !important; }
    </style>

    <div class="side-app">
        <div class="card mt-5">

            <div class="card-header text-white d-flex justify-content-between align-items-center"
                style="background-color:#253F2D;">
                <h4 class="card-title mb-0"><i class="fas fa-calendar-alt mr-2"></i> Exam Timetable</h4>
                <div>
                    <button type="button" id="importSampleBtn" class="btn btn-sm btn-light mr-1">
                        <i class="fas fa-file-import"></i> Import printed timetable
                    </button>
                    <button type="button" id="addBtn" class="btn btn-sm text-white" style="background-color:#287C44;">
                        <i class="fas fa-plus"></i> Add Entry
                    </button>
                </div>
            </div>

            <div class="card-body bg-light">

                <form method="GET" action="{{ route('students.timetable') }}" class="mb-4">
                    <div class="row align-items-end">
                        <div class="col-md-3">
                            <label>Year</label>
                            <select name="year" class="form-control">
                                <option value="">-- All --</option>
                                @foreach ($years as $y)
                                    <option value="{{ $y->year_en }}" {{ request('year') == $y->year_en ? 'selected' : '' }}>
                                        {{ $y->year_en }} - {{ $y->year_ar }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Category</label>
                            <select name="category" class="form-control">
                                <option value="">-- All --</option>
                                <option value="idaad" {{ request('category') == 'idaad' ? 'selected' : '' }}>Idaad (ع)</option>
                                <option value="thanawi" {{ request('category') == 'thanawi' ? 'selected' : '' }}>Thanawi (ث)</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end flex-nowrap gap-2" style="gap: .5rem;">
    <button type="submit" class="btn btn-primary text-nowrap"><i class="fas fa-filter"></i> Filter</button>
    <a href="{{ route('students.timetable') }}" class="btn btn-secondary text-nowrap"><i class="fas fa-undo"></i> Reset</a>
    <a href="{{ route('students.attendance-sheet') }}" class="btn btn-outline-success text-nowrap">
        <i class="fas fa-clipboard-list"></i> Attendance Sheet
    </a>
</div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped bg-white">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Year</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Category</th>
                                <th>Level</th>
                                <th>Subject (AR)</th>
                                <th>Paper (AR)</th>
                                <th style="width:120px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($entries as $i => $e)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $e->year }}</td>
                                    <td>{{ $e->exam_date->format('d/m/Y') }} <span dir="rtl">({{ $e->day_ar }})</span></td>
                                    <td>{{ $e->start_hm }} - {{ $e->end_hm }}</td>
                                    <td>{{ $e->category === 'idaad' ? 'Idaad (ع)' : 'Thanawi (ث)' }}</td>
                                    <td>{{ $e->class_level }}</td>
                                    <td dir="rtl">{{ $e->subject_ar }}</td>
                                    <td dir="rtl">{{ $e->paper_ar }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info editBtn"
                                            data-entry='@json($e->toPayload())'>
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger deleteBtn"
                                            data-id="{{ $e->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">No timetable entries yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add / Edit modal -->
    <div class="modal fade" id="entryModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header text-white" style="background-color:#253F2D;">
                    <h5 class="modal-title" id="entryModalTitle">Add Timetable Entry</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" data-bs-dismiss="modal"
                        aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="entry_id">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Year <span style="color:red;">(*)</span></label>
                            <select id="f_year" class="form-control">
                                @foreach ($years as $y)
                                    <option value="{{ $y->year_en }}">{{ $y->year_en }} - {{ $y->year_ar }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Category <span style="color:red;">(*)</span></label>
                            <select id="f_category" class="form-control">
                                <option value="idaad">Idaad (ع)</option>
                                <option value="thanawi">Thanawi (ث)</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Level / Class</label>
                            <input type="text" id="f_class_level" class="form-control" placeholder="3">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Subject (Arabic) <span style="color:red;">(*)</span></label>
                            <input type="text" id="f_subject_ar" class="form-control" dir="rtl" placeholder="حفظ">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Paper (Arabic)</label>
                            <input type="text" id="f_paper_ar" class="form-control" dir="rtl" placeholder="تحريري">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Exam Date <span style="color:red;">(*)</span></label>
                            <input type="date" id="f_exam_date" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Start Time <span style="color:red;">(*)</span></label>
                            <input type="time" id="f_start_time" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>End Time <span style="color:red;">(*)</span></label>
                            <input type="time" id="f_end_time" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="saveBtn" class="btn text-white" style="background-color:#287C44;">Save</button>
                </div>
            </div>
        </div>
    </div>
     </div>
        </div>
    </div>

    <script>
        window.addEventListener('load', function () {
            var $ = window.jQuery;
            var csrf = '{{ csrf_token() }}';
            var storeUrl = '{{ route('students.timetable.store') }}';
            var updateUrl = '{{ url('students/timetable/update') }}/';
            var deleteUrl = '{{ url('students/timetable/delete') }}/';
            var importUrl = '{{ route('students.timetable.import-sample') }}';
            var defaultYear = '{{ request('year') }}';

            function showModal() { $('#entryModal').modal('show'); }
            function hideModal() { $('#entryModal').modal('hide'); }

            function errorText(xhr) {
                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                    return Object.values(xhr.responseJSON.errors).map(function (e) { return e[0]; }).join('\n');
                }
                return 'Something went wrong';
            }

            $('#addBtn').on('click', function () {
                $('#entryModalTitle').text('Add Timetable Entry');
                $('#entry_id').val('');
                $('#f_class_level').val('3');
                $('#f_subject_ar, #f_paper_ar, #f_exam_date, #f_start_time, #f_end_time').val('');
                if (defaultYear) { $('#f_year').val(defaultYear); }
                showModal();
            });

            $(document).on('click', '.editBtn', function () {
                var e = $(this).data('entry');
                $('#entryModalTitle').text('Edit Timetable Entry');
                $('#entry_id').val(e.id);
                $('#f_year').val(e.year);
                $('#f_category').val(e.category);
                $('#f_class_level').val(e.class_level || '');
                $('#f_subject_ar').val(e.subject_ar);
                $('#f_paper_ar').val(e.paper_ar || '');
                $('#f_exam_date').val(e.exam_date_iso);
                $('#f_start_time').val(e.start_time);
                $('#f_end_time').val(e.end_time);
                showModal();
            });

            $('#saveBtn').on('click', function () {
                var btn = $(this);
                var id = $('#entry_id').val();

                btn.prop('disabled', true);

                $.ajax({
                    url: id ? updateUrl + id : storeUrl,
                    type: 'POST',
                    data: {
                        _token: csrf,
                        year: $('#f_year').val(),
                        category: $('#f_category').val(),
                        class_level: $('#f_class_level').val(),
                        subject_ar: $('#f_subject_ar').val(),
                        paper_ar: $('#f_paper_ar').val(),
                        exam_date: $('#f_exam_date').val(),
                        start_time: $('#f_start_time').val(),
                        end_time: $('#f_end_time').val()
                    },
                    success: function (res) {
                        hideModal();
                        Swal.fire({ icon: 'success', title: 'Saved!', text: res.message, confirmButtonColor: '#287C44' })
                            .then(function () { location.reload(); });
                    },
                    error: function (xhr) {
                        Swal.fire({ icon: 'error', title: 'Validation Error', text: errorText(xhr) });
                    },
                    complete: function () { btn.prop('disabled', false); }
                });
            });

            $(document).on('click', '.deleteBtn', function () {
                var id = $(this).data('id');

                Swal.fire({
                    icon: 'warning',
                    title: 'Delete this entry?',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete'
                }).then(function (r) {
                    if (!r.isConfirmed) { return; }

                    $.ajax({
                        url: deleteUrl + id,
                        type: 'DELETE',
                        data: { _token: csrf },
                        success: function (res) {
                            Swal.fire({ icon: 'success', title: 'Deleted', text: res.message, confirmButtonColor: '#287C44' })
                                .then(function () { location.reload(); });
                        },
                        error: function (xhr) {
                            Swal.fire({ icon: 'error', title: 'Error', text: errorText(xhr) });
                        }
                    });
                });
            });

            $('#importSampleBtn').on('click', function () {
                var options = {};
                @foreach ($years as $y)
                    options['{{ $y->year_en }}'] = '{{ $y->year_en }} - {{ $y->year_ar }}';
                @endforeach

                Swal.fire({
                    title: 'Import printed timetable (16–20 Nov 2026)',
                    text: 'Choose the academic year to import it into. Existing entries are skipped.',
                    input: 'select',
                    inputOptions: options,
                    inputPlaceholder: 'Select year',
                    showCancelButton: true,
                    confirmButtonColor: '#287C44'
                }).then(function (r) {
                    if (!r.isConfirmed || !r.value) { return; }

                    $.ajax({
                        url: importUrl,
                        type: 'POST',
                        data: { _token: csrf, year: r.value },
                        success: function (res) {
                            Swal.fire({ icon: 'success', title: 'Done', text: res.message, confirmButtonColor: '#287C44' })
                                .then(function () { location.reload(); });
                        },
                        error: function (xhr) {
                            Swal.fire({ icon: 'error', title: 'Error', text: errorText(xhr) });
                        }
                    });
                });
            });
        });
    </script>
@endsection
