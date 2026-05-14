<?php use App\Http\Controllers\Helper; ?>
@extends('layouts-side-bar.master')
@section('content')

    <style>
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
        }

        .badge-pending-approval {
            background-color: #ffc107;
            color: #000;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-approved {
            background-color: #28a745;
            color: #fff;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        .swal2-container {
            z-index: 9999 !important;
        }

        .swal2-popup {
            z-index: 10000 !important;
        }

        table thead th {
            background-color: #0d4b1f;
            color: white;
            padding: 12px 15px;
            font-weight: 600;
            font-size: 14px;
        }

        table tbody td {
            padding: 10px 15px;
            vertical-align: middle;
            border-bottom: 1px solid #e0e0e0;
        }

        table tbody tr:hover {
            background-color: #f5f9f5 !important;
        }

        .action-bar {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px 20px;
            margin-bottom: 20px;
            border: 1px solid #e0e0e0;
        }
    </style>

    <div class="side-app">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header text-white d-flex justify-content-between align-items-center"
                        style="background-color: #0d4b1f; border-radius: 12px 12px 0 0;">
                        <div>
                            <h4 class="card-title mb-0">
                                <i class="fas fa-user-check mr-2"></i>
                                Student Approvals — {{ $schoolPrefix }}
                            </h4>
                            <small style="opacity:0.85;">{{ $schoolName }}</small>
                        </div>
                        <a href="{{ route('admin.student.approvals') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left mr-1"></i> Back
                        </a>
                    </div>

                    <div class="card-body">

                        @if($registrations->isEmpty())
                            <div class="text-center py-5 text-muted">
                                <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                No pending approvals for this school.
                            </div>
                        @else

                            <!-- Action Bar -->
                            <div class="action-bar d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="custom-control custom-checkbox mr-3">
                                        <input type="checkbox" class="custom-control-input" id="checkAll">
                                        <label class="custom-control-label font-weight-600" for="checkAll">
                                            Select All ({{ $registrations->count() }} students)
                                        </label>
                                    </div>
                                    <span id="selectedCount" class="text-muted" style="font-size:13px;">0 selected</span>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button" id="approveBtn" class="btn text-white mr-2"
                                        style="background-color:#287C44;">
                                        <i class="fas fa-check mr-1"></i> Approve Selected
                                    </button>
                                    <button type="button" id="rejectBtn" class="btn btn-warning">
                                        <i class="fas fa-undo mr-1"></i> Send Back Selected
                                    </button>
                                </div>
                            </div>

                            <!-- Table -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="approvalTable">
                                    <thead>
                                        <tr>
                                            <th style="width:40px;"></th>
                                            <th>#</th>
                                            <th>Photo</th>
                                            <th>Student ID</th>
                                            <th>Name</th>
                                            <th>Category</th>
                                            <th>Class</th>
                                            <th>Year</th>
                                            <th>Gender</th>
                                            <th>Date of Birth</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($registrations as $index => $reg)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="approval-checkbox" value="{{ $reg->id }}"
                                                        data-student-id="{{ $reg->student_id }}">
                                                </td>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <img src="/assets/student_photos/{{ $reg->student_id }}.jpg"
                                                        onerror="this.src='/assets/images/default-user.jpg';"
                                                        style="width:50px;height:65px;object-fit:cover;border-radius:6px;border:2px solid #e9ecef;">
                                                </td>
                                                <td><code>{{ $reg->student_id }}</code></td>
                                                <td>
                                                    {{ $reg->student_name }}
                                                    @if($reg->student_name_ar)
                                                        <br><small class="text-muted">{{ $reg->student_name_ar }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge badge-{{ $reg->category === 'ID' ? 'info' : 'secondary' }}">
                                                        {{ $reg->category === 'ID' ? 'Idaad' : 'Thanawi' }}
                                                    </span>
                                                </td>
                                                <td>{{ $reg->class ?? '-' }}</td>
                                                <td>{{ $reg->admission_year }}</td>
                                                <td>{{ $reg->student_sex }}</td>
                                                <td>{{ $reg->date_of_birth ? \Carbon\Carbon::parse($reg->date_of_birth)->format('d/m/Y') : '-' }}
                                                </td>
                                                <td><span class="badge-pending-approval">Pending Approval</span></td>
                                                <td>
                                                    <button class="btn btn-sm btn-info view-student" data-id="{{ $reg->id }}"
                                                        data-student='@json($reg)' title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
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

    <!-- View Student Modal -->
    <div class="modal fade" id="viewStudentModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #0d4b1f; color: white;">
                    <h5 class="modal-title"><i class="fas fa-user mr-2"></i> Student Details</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <img id="modal_photo" src="/assets/images/default-user.jpg"
                            style="width:100px;height:120px;object-fit:cover;border-radius:10px;border:3px solid #287C44;box-shadow:0 4px 12px rgba(0,0,0,0.15);">
                        <h5 class="mt-3 mb-0" id="modal_name"></h5>
                        <p class="text-muted mb-0" id="modal_name_ar"></p>
                        <code id="modal_student_id" class="d-block mt-1"></code>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="font-weight-bold text-muted" width="140">Category</td>
                                    <td id="modal_category"></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">Year</td>
                                    <td id="modal_year"></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">Gender</td>
                                    <td id="modal_sex"></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">Date of Birth</td>
                                    <td id="modal_dob"></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">Nationality</td>
                                    <td id="modal_nationality"></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="font-weight-bold text-muted" width="140">Class</td>
                                    <td id="modal_class"></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">Section</td>
                                    <td id="modal_section"></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">District</td>
                                    <td id="modal_district"></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">Birth Place</td>
                                    <td id="modal_birth_place"></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">School</td>
                                    <td id="modal_house"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

    @section('js')
        <script>
            $(document).ready(function () {

                // ── Check All ──────────────────────────────────────────────
                $('#checkAll').on('change', function () {
                    $('.approval-checkbox').prop('checked', $(this).is(':checked'));
                    updateSelectedCount();
                });

                $(document).on('change', '.approval-checkbox', function () {
                    updateSelectedCount();
                    const total = $('.approval-checkbox').length;
                    const checked = $('.approval-checkbox:checked').length;
                    $('#checkAll').prop('checked', total === checked);
                });

                function updateSelectedCount() {
                    const count = $('.approval-checkbox:checked').length;
                    $('#selectedCount').text(count + ' selected');
                }

                function getSelectedIds() {
                    const ids = [];
                    $('.approval-checkbox:checked').each(function () {
                        ids.push($(this).val());
                    });
                    return ids;
                }

                // ── View Student ───────────────────────────────────────────
                $(document).on('click', '.view-student', function () {
                    const s = $(this).data('student');
                    $('#modal_photo').attr('src', '/assets/student_photos/' + s.student_id + '.jpg');
                    $('#modal_photo').on('error', function () { $(this).attr('src', '/assets/images/default-user.jpg'); });
                    $('#modal_name').text(s.student_name);
                    $('#modal_name_ar').text(s.student_name_ar || '');
                    $('#modal_student_id').text(s.student_id);
                    $('#modal_category').text(s.category === 'ID' ? 'Idaad - ID' : 'Thanawi - TH');
                    $('#modal_year').text(s.admission_year);
                    $('#modal_sex').text(s.student_sex);
                    $('#modal_dob').text(s.date_of_birth ? s.date_of_birth.split('-').reverse().join('/') : '-');
                    $('#modal_nationality').text(s.student_nationality || '-');
                    $('#modal_class').text(s.class || '-');
                    $('#modal_section').text(s.section || '-');
                    $('#modal_district').text((s.district || '-') + (s.district_ar ? ' / ' + s.district_ar : ''));
                    $('#modal_birth_place').text((s.birth_place || '-') + (s.birth_place_ar ? ' / ' + s.birth_place_ar : ''));
                    $('#modal_house').text(s.house || '-');
                    $('#viewStudentModal').modal('show');
                });

                // ── Approve ────────────────────────────────────────────────
                $('#approveBtn').on('click', function () {
                    const ids = getSelectedIds();
                    if (ids.length === 0) {
                        Swal.fire({ icon: 'warning', title: 'None Selected', text: 'Please select at least one student.', confirmButtonColor: '#287C44' });
                        return;
                    }

                    Swal.fire({
                        title: 'Approve Students?',
                        html: `You are about to <b>approve</b> <b>${ids.length}</b> student(s).<br>
                                   They will be added to the main student database.`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#287C44',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, Approve!',
                        cancelButtonText: 'Cancel'
                    }).then(result => {
                        if (!result.isConfirmed) return;

                        Swal.fire({ title: 'Processing...', text: 'Please wait', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                        $.ajax({
                            url: '{{ route("admin.update.approval.status") }}',
                            method: 'POST',
                            data: {
                                ids: JSON.stringify(ids),
                                action: 'Approved',
                                _token: '{{ csrf_token() }}'
                            },
                            success: function (response) {
                                Swal.fire({ icon: 'success', title: 'Done!', text: response.message, confirmButtonColor: '#287C44' })
                                    .then(() => location.reload());
                            },
                            error: function (xhr) {
                                Swal.fire({ icon: 'error', title: 'Error', text: xhr.responseJSON?.message || 'Something went wrong.', confirmButtonColor: '#d33' });
                            }
                        });
                    });
                });

                // ── Send Back ──────────────────────────────────────────────
                $('#rejectBtn').on('click', function () {
                    const ids = getSelectedIds();
                    if (ids.length === 0) {
                        Swal.fire({ icon: 'warning', title: 'None Selected', text: 'Please select at least one student.', confirmButtonColor: '#287C44' });
                        return;
                    }

                    Swal.fire({
                        title: 'Send Back for Resubmission?',
                        html: `<b>${ids.length}</b> student(s) will be sent back to<br><b>"Attached Image, Pending Submission"</b> status.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e67e22',
                        cancelButtonColor: '#287C44',
                        confirmButtonText: 'Yes, Send Back!',
                        cancelButtonText: 'Cancel'
                    }).then(result => {
                        if (!result.isConfirmed) return;

                        Swal.fire({ title: 'Processing...', text: 'Please wait', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                        $.ajax({
                            url: '{{ route("admin.update.approval.status") }}',
                            method: 'POST',
                            data: {
                                ids: JSON.stringify(ids),
                                action: 'Rejected',
                                _token: '{{ csrf_token() }}'
                            },
                            success: function (response) {
                                Swal.fire({ icon: 'success', title: 'Done!', text: response.message, confirmButtonColor: '#287C44' })
                                    .then(() => location.reload());
                            },
                            error: function (xhr) {
                                Swal.fire({ icon: 'error', title: 'Error', text: xhr.responseJSON?.message || 'Something went wrong.', confirmButtonColor: '#d33' });
                            }
                        });
                    });
                });

            });
        </script>
    @endsection
@endsection