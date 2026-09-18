@extends('layouts-side-bar.master')
@section('content')
    <style>
        :root {
            --forest: #0d4b1f;
            --green: #287C44;
            --mint: #e8f5e9;
            --slate: #64748b;
        }

        .subs-hero {
            background: linear-gradient(135deg, #0d4b1f 0%, #287C44 100%);
            color: #fff;
            border-radius: 16px;
            padding: 24px 28px;
            margin-bottom: 22px;
        }

        .subs-hero h4 {
            margin: 0;
            font-weight: 700;
        }

        .subs-hero p {
            margin: 4px 0 0;
            opacity: .9;
            font-size: 13px;
        }

        .subs-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, .08);
        }

        .subs-filter-bar {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 14px 18px;
            margin-bottom: 18px;
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
            align-items: flex-end;
        }

        .subs-filter-bar .form-group {
            margin-bottom: 0;
        }

        .subs-filter-bar label {
            font-size: 12px;
            color: var(--slate);
            margin-bottom: 4px;
            display: block;
        }

        .subs-action-bar {
            background: #f8f9fa;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 14px 18px;
            margin-bottom: 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        table.subs-table thead th {
            background: var(--forest);
            color: #fff;
            padding: 12px 14px;
            font-size: 13px;
            white-space: nowrap;
        }

        table.subs-table tbody td {
            padding: 10px 14px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            font-size: 13px;
        }

        .badge-pending {
            background: #ffc107;
            color: #000;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-approved {
            background: #22c55e;
            color: #fff;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-returned {
            background: #ef4444;
            color: #fff;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }
    </style>

    <div class="side-app">
        <div class="subs-hero">
            <h4><i class="fas fa-file-alt mr-2"></i>Submitted Students</h4>
            <p>Students already sent to the ITEB admin for approval — view their information or download it as a PDF.</p>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card subs-card">
                    <div class="card-body">

                        <div class="subs-filter-bar">
                            <div class="form-group">
                                <label>Admission Year</label>
                                <select id="subs_year" class="form-control">
                                    <option value="">All Years</option>
                                    @foreach($years as $y)
                                        <option value="{{ $y->year_en }}">{{ $y->year_en }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Category</label>
                                <select id="subs_category" class="form-control">
                                    <option value="">All Categories</option>
                                    <option value="ID">Idaad</option>
                                    <option value="TH">Thanawi</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                <select id="subs_status" class="form-control">
                                    <option value="">All Statuses</option>
                                    <option value="Pending Admin Approval">Pending Approval</option>
                                    <option value="Approved">Approved</option>
                                    <option value="Returned">Returned</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <button id="subs_filter_btn" class="btn btn-sm"
                                    style="background:var(--forest); color:#fff;">
                                    <i class="fas fa-filter mr-1"></i>Apply Filters
                                </button>
                            </div>
                        </div>

                        <div class="subs-action-bar">
                            <div class="d-flex align-items-center" style="gap:12px;">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="subs_check_all">
                                    <label class="custom-control-label font-weight-600" for="subs_check_all">Select
                                        All</label>
                                </div>
                                <span id="subs_selected_count" class="text-muted" style="font-size:13px;">0 selected</span>
                            </div>
                            <button id="subs_bulk_pdf_btn" class="btn btn-sm text-white"
                                style="background:var(--green); display:none;">
                                <i class="fas fa-file-pdf mr-1"></i>Download Selected as PDF
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover subs-table" id="subsTable">
                                <thead>
                                    <tr>
                                        <th style="width:36px;"></th>
                                        <th>#</th>
                                        <th>Photo</th>
                                        <th>Student ID</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Class</th>
                                        <th>Year</th>
                                        <th>Status</th>
                                        <th>Submitted On</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="subsTableBody">
                                    <tr>
                                        <td colspan="11" class="text-center text-muted py-4"><i
                                                class="fas fa-spinner fa-spin mr-2"></i>Loading…</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div id="subs_empty" class="text-center py-5 text-muted" style="display:none;">
                            <i class="fas fa-inbox fa-3x mb-3 d-block"></i>No submitted students found.
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- View Student Modal --}}
    <div class="modal fade" id="subsViewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background:var(--forest); color:#fff;">
                    <h5 class="modal-title"><i class="fas fa-user mr-2"></i>Student Details</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <img id="subs_modal_photo" src="/assets/images/default-user.jpg"
                            style="width:100px;height:120px;object-fit:cover;border-radius:10px;border:3px solid var(--green);">
                        <h5 class="mt-2 mb-0" id="subs_modal_name"></h5>
                        <p class="text-muted" id="subs_modal_name_ar"></p>
                        <code id="subs_modal_student_id"></code>
                    </div>
                    <div class="row" id="subs_modal_details"></div>
                </div>
                <div class="modal-footer">
                    <a href="#" id="subs_modal_pdf_link" class="btn text-white" style="background:var(--green);">
                        <i class="fas fa-file-pdf mr-1"></i>Download PDF
                    </a>
                    <button class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
     </div>
        </div>
    </div>

    <script>
        $(function () {
            let subsRegistrations = [];

            function statusBadge(status) {
                if (status === 'Approved') return '<span class="badge-approved">Approved</span>';
                if (status === 'Returned') return '<span class="badge-returned">Returned</span>';
                return '<span class="badge-pending">Pending Approval</span>';
            }

            function loadSubmittedStudents() {
                $('#subsTableBody').html('<tr><td colspan="11" class="text-center text-muted py-4"><i class="fas fa-spinner fa-spin mr-2"></i>Loading…</td></tr>');
                $('#subs_empty').hide();

                $.get('{{ route("school.submitted.students.data") }}', {
                    year: $('#subs_year').val(),
                    category: $('#subs_category').val(),
                    status: $('#subs_status').val(),
                }, function (res) {
                    subsRegistrations = res.registrations || [];

                    if (!subsRegistrations.length) {
                        $('#subsTableBody').empty();
                        $('#subs_empty').show();
                        updateSubsSelection();
                        return;
                    }

                    let html = '';
                    subsRegistrations.forEach(function (r, i) {
                        html += `<tr>
                        <td><input type="checkbox" class="subs-checkbox" value="${r.id}"></td>
                        <td>${i + 1}</td>
                        <td><img src="/assets/student_photos/${r.student_id}.jpg" onerror="this.src='/assets/images/default-user.jpg';" style="width:38px;height:48px;object-fit:cover;border-radius:6px;border:2px solid #e9ecef;"></td>
                        <td><code>${r.student_id}</code></td>
                        <td>${r.student_name}</td>
                        <td>${r.category === 'ID' ? 'Idaad' : 'Thanawi'}</td>
                        <td>${r.class || '—'}</td>
                        <td>${r.admission_year}</td>
                        <td>${statusBadge(r.status)}</td>
                        <td>${r.submitted_at ? r.submitted_at.replace('T', ' ').substring(0, 16) : '—'}</td>
                        <td style="white-space:nowrap;">
                            <button class="btn btn-sm btn-info subs-view-btn" data-index="${i}" title="View Details"><i class="fas fa-eye"></i></button>
                            <a class="btn btn-sm text-white subs-pdf-btn" style="background:var(--green);" href="/school/submitted-students/${r.id}/pdf" title="Download PDF"><i class="fas fa-file-pdf"></i></a>
                        </td>
                    </tr>`;
                    });
                    $('#subsTableBody').html(html);
                    updateSubsSelection();
                }).fail(function () {
                    $('#subsTableBody').html('<tr><td colspan="11" class="text-center text-danger py-4"><i class="fas fa-exclamation-circle mr-1"></i>Error loading submitted students.</td></tr>');
                });
            }

            function updateSubsSelection() {
                const n = $('.subs-checkbox:checked').length;
                $('#subs_selected_count').text(n + ' selected');
                $('#subs_bulk_pdf_btn').toggle(n > 0);
            }

            $('#subs_filter_btn').on('click', loadSubmittedStudents);

            $('#subs_check_all').on('change', function () {
                $('.subs-checkbox').prop('checked', $(this).is(':checked'));
                updateSubsSelection();
            });

            $(document).on('change', '.subs-checkbox', function () {
                const total = $('.subs-checkbox').length;
                const checked = $('.subs-checkbox:checked').length;
                $('#subs_check_all').prop('checked', total === checked && total > 0);
                updateSubsSelection();
            });

            $(document).on('click', '.subs-view-btn', function () {
                const r = subsRegistrations[$(this).data('index')];
                if (!r) return;

                $('#subs_modal_name').text(r.student_name);
                $('#subs_modal_name_ar').text(r.student_name_ar || '');
                $('#subs_modal_student_id').text(r.student_id);
                $('#subs_modal_photo').attr('src', '/assets/student_photos/' + r.student_id + '.jpg')
                    .off('error').on('error', function () { this.src = '/assets/images/default-user.jpg'; });
                $('#subs_modal_pdf_link').attr('href', '/school/submitted-students/' + r.id + '/pdf');

                const fields = [
                    ['Category', r.category === 'ID' ? 'Idaad' : 'Thanawi'],
                    ['Gender', r.student_sex], ['Admission Year', r.admission_year],
                    ['Date of Birth', r.date_of_birth], ['Nationality', r.student_nationality || '—'],
                    ['Birth Place', r.birth_place || '—'], ['Class', r.class || '—'],
                    ['Section', r.section || '—'], ['District', r.district || '—'],
                    ['Status', r.status],
                    ['Submitted On', r.submitted_at ? r.submitted_at.replace('T', ' ').substring(0, 16) : '—'],
                ];

                let html = '';
                fields.forEach(([lbl, val]) => {
                    html += `<div class="col-md-4 mb-2"><small class="text-muted">${lbl}</small><div><strong>${val || '—'}</strong></div></div>`;
                });
                $('#subs_modal_details').html(html);
                $('#subsViewModal').modal('show');
            });

            $('#subs_bulk_pdf_btn').on('click', function () {
                const ids = $('.subs-checkbox:checked').map(function () { return $(this).val(); }).get();
                if (!ids.length) return;

                const $btn = $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Preparing…');

                fetch('{{ route("school.submitted.students.pdf") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({ ids: JSON.stringify(ids) }),
                })
                    .then(function (res) {
                        if (!res.ok) throw new Error('Failed to generate PDF');
                        return res.blob();
                    })
                    .then(function (blob) {
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = 'submitted_students.pdf';
                        document.body.appendChild(a);
                        a.click();
                        a.remove();
                        window.URL.revokeObjectURL(url);
                    })
                    .catch(function () {
                        Swal.fire('Error', 'Could not generate the PDF. Please try again.', 'error');
                    })
                    .finally(function () {
                        $btn.prop('disabled', false).html('<i class="fas fa-file-pdf mr-1"></i>Download Selected as PDF');
                    });
            });

            loadSubmittedStudents();
        });
    </script>
@endsection