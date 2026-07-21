<?php
use App\Http\Controllers\Helper;
?>
@extends('layouts-side-bar.master')

@section('css')
    <link href="{{ URL::asset('assets/plugins/jvectormap/jqvmap.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet" />
@endsection

@section('content')

    <div class="side-app">

        <div class="row">
            <div class="col-lg-12 col-xl-12 col-md-10 col-sm-12 mx-auto">
                <div class="card shadow-sm" style="border-top: 4px solid #0d4b1f; border-radius: 10px;">

                    {{-- Card Header --}}
                    <div class="card-header d-flex justify-content-between align-items-center"
                        style="background-color: #0d4b1f; border-radius: 6px 6px 0 0;">
                        <div class="d-flex align-items-center gap-2">
                            <div style="background: rgba(255,255,255,0.15); border-radius: 8px; width:38px; height:38px;
                                        display:flex; align-items:center; justify-content:center;">
                                <i class="fas fa-school text-white" style="font-size:16px;"></i>
                            </div>
                            <div class="ms-2">
                                <h5 class="mb-0 text-white fw-semibold">&nbsp; Edit School Information</h5>
                                <small class="text-white-50" style="font-size:11px;">&nbsp; &nbsp;Update the details
                                    below</small>
                            </div>
                        </div>
                        <a href="{{ route('school.allSchools') }}" class="btn btn-sm text-white"
                            style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); border-radius:6px;">
                            <i class="fas fa-list me-1"></i> All Schools
                        </a>
                    </div>

                    {{-- Card Body --}}
                    <div class="card-body" style="background:#f8faf9; border-radius: 0 0 10px 10px; padding: 28px 32px;">

                        <form id="updateSchoolForm" method="POST" action="{{ route('update.school') }}">
                            @csrf
                            <input type="hidden" name="school_id" value="{{ $school_id }}">

                            {{-- School Name --}}
                            <div class="form-group mb-3">
                                <label class="form-label fw-semibold" style="color:#2d3748; font-size:13px;">
                                    School Name <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text" style="background:#0d4b1f; border-color:#0d4b1f;">
                                        <i class="fas fa-school text-white" style="font-size:13px;"></i>
                                    </span>
                                    <input type="text" name="House" id="House" class="form-control"
                                        value="{{ old('House', $school->House) }}" required
                                        style="border-left: none; font-size:14px;">
                                </div>
                            </div>

                            {{-- School Name Arabic --}}
                            <div class="form-group mb-3">
                                <label class="form-label fw-semibold" style="color:#2d3748; font-size:13px;">
                                    School Name Arabic <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text" style="background:#0d4b1f; border-color:#0d4b1f;">
                                        <i class="fas fa-school text-white" style="font-size:13px;"></i>
                                    </span>
                                    <input type="text" name="House_AR" id="House_AR" class="form-control"
                                        value="{{ old('House_AR', $school->House_AR) }}" required
                                        style="border-left: none; font-size:14px;">
                                </div>
                            </div>

                            {{-- Location --}}
                            <div class="form-group mb-3">
                                <label class="form-label fw-semibold" style="color:#2d3748; font-size:13px;">
                                    Location <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text" style="background:#0d4b1f; border-color:#0d4b1f;">
                                        <i class="fas fa-map-marker-alt text-white" style="font-size:13px;"></i>
                                    </span>
                                    <input type="text" name="Location" id="Location" class="form-control"
                                        value="{{ old('Location', $school->Location) }}" required
                                        style="border-left: none; font-size:14px;">
                                </div>
                            </div>

                            <hr style="border-color:#d4eadb; margin: 20px 0;">

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('school.allSchools') }}" class="btn btn-outline-secondary"
                                    style="font-size:13px; border-radius:6px; padding: 8px 20px;">
                                    <i class="fas fa-times me-1"></i> Cancel
                                </a> &nbsp; &nbsp;
                                <button type="submit" id="submitBtn" class="btn text-white" style="background:#0d4b1f; font-size:13px; border-radius:6px;
                                           padding: 8px 24px; min-width:120px;">
                                    <i class="fas fa-paper-plane me-2"></i> Update School
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#updateSchoolForm').on('submit', function (e) {
                e.preventDefault();

                let $form = $(this);
                let $btn = $('#submitBtn');
                let isValid = true;

                $form.find('.form-control').removeClass('is-invalid');
                $form.find('.invalid-feedback').remove();

                ['House', 'House_AR', 'Location'].forEach(function (field) {
                    let $input = $form.find('[name="' + field + '"]');
                    if (!$input.val() || $input.val().trim() === '') {
                        $input.addClass('is-invalid');
                        $input.after('<div class="invalid-feedback">This field is required.</div>');
                        isValid = false;
                    }
                });

                if (!isValid) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Incomplete Form',
                        text: 'Please fill in all required fields.',
                        confirmButtonColor: '#0d4b1f'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You are about to update this school's information.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#0d4b1f',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, update it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let originalHtml = $btn.html();
                        $btn.prop('disabled', true).html('Updating… <i class="fas fa-spinner fa-spin ms-1"></i>');

                        $.ajax({
                            url: $form.attr('action'),
                            method: 'POST',
                            data: $form.serialize(),
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            success: function (response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Updated!',
                                    text: response.message || 'School has been updated successfully.',
                                    confirmButtonColor: '#0d4b1f'
                                }).then(function () {
                                    window.location.href = '{{ route("school.allSchools") }}';
                                });
                            },
                            error: function (xhr) {
                                if (xhr.status === 422) {
                                    let errors = xhr.responseJSON.errors;
                                    $.each(errors, function (field, messages) {
                                        let $field = $form.find('[name="' + field + '"]');
                                        $field.addClass('is-invalid');
                                        $field.after('<div class="invalid-feedback">' + messages[0] + '</div>');
                                    });
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Validation Error',
                                        text: 'Please correct the highlighted fields.',
                                        confirmButtonColor: '#0d4b1f'
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Something went wrong. Please try again.',
                                        confirmButtonColor: '#0d4b1f'
                                    });
                                }
                            },
                            complete: function () {
                                $btn.prop('disabled', false).html(originalHtml);
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection