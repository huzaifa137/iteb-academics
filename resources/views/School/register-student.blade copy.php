<?php
use App\Http\Controllers\Helper;
?>
@extends('layouts-side-bar.master')
@section('content')

    <style>
        /* DataTable Beautiful Styling */
        .dataTables_wrapper {}

        /* Length menu (Show entries) styling */
        .dataTables_length {
            padding: 10px 0;
            display: inline-block;
        }

        .dataTables_length label {
            font-weight: 500;
            color: #0d4b1f;
            margin-bottom: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .dataTables_length select {
            width: auto;
            display: inline-block;
            padding: 6px 12px;
            font-size: 14px;
            font-weight: 500;
            color: #0d4b1f;
            background-color: #fff;
            border: 1px solid #287C44;
            border-radius: 6px;
            cursor: pointer;
            margin: 0 4px;
            transition: all 0.3s ease;
        }

        .dataTables_length select:hover {
            border-color: #1e5a33;
            background-color: #f8fff9;
        }

        .dataTables_length select:focus {
            outline: none;
            border-color: #1e5a33;
            box-shadow: 0 0 0 3px rgba(40, 124, 68, 0.1);
        }

        /* Search box styling */
        .dataTables_filter {
            padding: 10px 0;
        }

        .dataTables_filter label {
            font-weight: 500;
            color: #0d4b1f;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
            margin-bottom: 0;
        }

        .dataTables_filter input {
            border: 1px solid #287C44;
            border-radius: 6px;
            padding: 6px 12px;
            width: 250px;
            transition: all 0.3s ease;
        }

        .dataTables_filter input:focus {
            outline: none;
            border-color: #1e5a33;
            box-shadow: 0 0 0 3px rgba(40, 124, 68, 0.1);
        }

        /* Table styling */
        .dataTables_wrapper table.dataTable {
            border-collapse: separate;
            border-spacing: 0;
            width: 100% !important;
            margin-top: 10px !important;
            margin-bottom: 10px !important;
        }

        .dataTables_wrapper table.dataTable thead th {
            background-color: #0d4b1f;
            color: white;
            padding: 12px 15px;
            font-weight: 600;
            font-size: 14px;
            border-bottom: none;
        }

        .dataTables_wrapper table.dataTable tbody td {
            padding: 10px 15px;
            vertical-align: middle;
            border-bottom: 1px solid #e0e0e0;
        }

        .dataTables_wrapper table.dataTable tbody tr:hover {
            background-color: #f5f9f5 !important;
        }

        /* Info text styling */
        .dataTables_info {
            padding: 12px 0;
            font-size: 13px;
            color: #6c757d;
            font-weight: 500;
        }

        /* Pagination styling */
        .dataTables_paginate {
            padding: 10px 0;
        }

        .dataTables_paginate .paginate_button {
            padding: 6px 12px;
            margin: 0 3px;
            border-radius: 6px;
            border: 1px solid #287C44;
            background-color: white;
            color: #287C44 !important;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .dataTables_paginate .paginate_button:hover {
            background-color: #287C44;
            color: white !important;
            border-color: #287C44;
        }

        .dataTables_paginate .paginate_button.current {
            background-color: #287C44;
            color: white !important;
            border-color: #287C44;
        }

        .dataTables_paginate .paginate_button.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .dataTables_paginate .paginate_button.disabled:hover {
            background-color: white;
            color: #287C44 !important;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {

            .dataTables_length,
            .dataTables_filter {
                text-align: center;
                width: 100%;
            }

            .dataTables_filter label {
                justify-content: center;
            }

            .dataTables_info,
            .dataTables_paginate {
                text-align: center;
                width: 100%;
            }
        }

        /* Badge styling (keep your existing) */
        .badge-pending {
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
            font-weight: 500;
        }

        .badge-rejected {
            background-color: #35c6dc;
            color: #fff;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        /* Wrapper spacing */
        .dataTables_wrapper .row {
            margin: 0;
        }

        .dataTables_wrapper .col-sm-12 {
            padding: 0;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }

        .form-control:focus,
        .select2-container--default .select2-selection--single:focus {
            border-color: #287C44;
            box-shadow: 0 0 0 0.2rem rgba(40, 124, 68, 0.25);
        }

        .badge-pending {
            background-color: #ffc107;
            color: #000;
            padding: 5px 10px;
            border-radius: 20px;
        }

        .badge-approved {
            background-color: #28a745;
            color: #fff;
            padding: 5px 10px;
            border-radius: 20px;
        }

        .badge-blue {
            background-color: #0022ff;
            color: #fff;
            padding: 5px 10px;
            border-radius: 20px;
        }

                .badge-green {
            background-color: #28a745;
            color: #fff;
            padding: 5px 10px;
            border-radius: 20px;
        }

        .badge-rejected {
            background-color: #35c6dc;
            color: #fff;
            padding: 5px 10px;
            border-radius: 20px;
        }

        /* Fix SweetAlert appearing behind Bootstrap modal */
        .swal2-container {
            z-index: 9999 !important;
        }

        .modal {
            z-index: 1050;
        }

        .modal-backdrop {
            z-index: 1040;
        }

        /* Ensure SweetAlert is always on top */
        .swal2-popup {
            z-index: 10000 !important;
        }

        /* Fix for when modal is open and SweetAlert appears */
        body.swal2-shown:not(.swal2-no-backdrop):not(.swal2-toast-shown) {
            overflow-y: hidden !important;
        }

        /* Make sure modal doesn't block SweetAlert */
        .modal-open .swal2-container {
            z-index: 10000 !important;
        }

        /* Fix for SweetAlert loading state */
        .swal2-loading {
            z-index: 10001 !important;
        }

        /* Photo Upload Section Styling */
        .photo-upload-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            border: 1px solid #e0e0e0;
            transition: all 0.3s ease;
        }

        .photo-upload-section:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border-color: #287C44;
        }

        .photo-upload-section label {
            font-weight: 600;
            color: #0d4b1f;
            font-size: 16px;
            margin-bottom: 15px;
            display: block;
            border-left: 4px solid #287C44;
            padding-left: 12px;
        }

        .photo-preview-container {
            position: relative;
            display: inline-block;
        }

        .photo-preview {
            width: 120px;
            height: 140px;
            object-fit: cover;
            border-radius: 12px;
            border: 3px solid white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            background-color: #f8f9fa;
        }

        .photo-preview:hover {
            transform: scale(1.02);
            box-shadow: 0 6px 16px rgba(40, 124, 68, 0.2);
            border-color: #287C44;
        }

        .photo-actions {
            margin-left: 20px;
            flex: 1;
        }

        .photo-upload-input {
            position: relative;
            margin-bottom: 12px;
        }

        .photo-upload-input input[type="file"] {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
            z-index: 2;
        }

        .custom-file-upload {
            display: inline-block;
            padding: 10px 20px;
            background: linear-gradient(135deg, #287C44 0%, #1e5a33 100%);
            color: white;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            text-align: center;
            width: 100%;
        }

        .custom-file-upload:hover {
            background: linear-gradient(135deg, #1e5a33 0%, #154a28 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(40, 124, 68, 0.3);
        }

        .custom-file-upload i {
            margin-right: 8px;
        }

        .file-info {
            font-size: 12px;
            color: #6c757d;
            margin-top: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .file-info i {
            font-size: 11px;
        }

        .btn-remove-photo {
            background: white;
            border: 1px solid #dc3545;
            color: #dc3545;
            padding: 6px 16px;
            font-size: 13px;
            border-radius: 6px;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-remove-photo:hover {
            background: #dc3545;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(220, 53, 69, 0.3);
        }

        .btn-remove-photo i {
            margin-right: 6px;
        }

        .photo-actions .row {
            margin: 0 -5px;
        }

        .photo-actions .col {
            padding: 0 5px;
        }

        /* Upload status indicators */
        .upload-status {
            margin-top: 10px;
            font-size: 12px;
            display: none;
        }

        .upload-status.success {
            color: #28a745;
            display: block;
        }

        .upload-status.error {
            color: #dc3545;
            display: block;
        }

        .upload-status.loading {
            color: #ffc107;
            display: block;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .d-flex.align-items-center.gap-3 {
                flex-direction: column;
                text-align: center;
            }

            .photo-actions {
                margin-left: 0;
                margin-top: 15px;
                width: 100%;
            }

            .photo-preview {
                width: 100px;
                height: 120px;
            }
        }

        /* Animation for photo change */
        @keyframes photoPulse {
            0% {
                transform: scale(1);
                opacity: 0.7;
            }

            50% {
                transform: scale(1.05);
                opacity: 1;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .photo-preview.updated {
            animation: photoPulse 0.5s ease;
        }

        /* Optional: Add a subtle overlay on hover for photo */
        .photo-preview-container::after {
            content: '\f030';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0, 0, 0, 0.6);
            color: white;
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 12px;
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }

        .photo-preview-container:hover::after {
            opacity: 1;
        }
    </style>

    <div class="side-app">
        <div class="row">
            <div class="col-lg-12 col-xl-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-header text-white d-flex justify-content-between align-items-center"
                        style="background-color: #0d4b1f;">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-user-plus mr-2"></i> Student Registration
                        </h4>
                        <span class="badge badge-light text-dark">
                            <i class="fas fa-school mr-1"></i> {{ $school->House }}
                        </span>
                    </div>
                    <div class="card-body bg-light">

                        <!-- Info Alert -->
                        <div class="alert border-0 d-flex justify-content-between align-items-center"
                            style="background-color: #e8f5e9; color: #0d4b1f;">

                            <div>
                                <strong>Step 1 :</strong>
                            </div>

                            <div class="text-right">
                                <i class="fas fa-info-circle mr-2"></i>
                                Fill in the student details below. The registration will be submitted for admin approval.
                            </div>

                        </div>

                        <form id="schoolRegistrationForm">
                            @csrf

                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label>Category <span class="text-danger">*</span></label>

                                    <select name="category" id="category" class="form-control select2" required>
                                        <option value="">-- Select Category --</option>
                                        <option value="ID">Idaad - ID</option>
                                        <option value="TH">Thanawi - TH</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-3">
                                    <label>Admission Year <span class="text-danger">*</span></label>
                                    <select name="admission_year" id="admission_year" class="form-control select2" required>
                                        <option value="">-- Select Year --</option>
                                        @foreach ($years as $year)
                                            <option value="{{ $year->year_en }}" {{ $year->year_en == $currentYear ? 'selected' : '' }}>
                                                {{ $year->year_en }} - {{ $year->year_ar }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Student ID <span class="text-danger">*</span> (Auto-generated)</label>
                                    <input type="text" name="student_id" id="student_id" class="form-control" readonly
                                        required>
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle"></i> Student ID will be generated automatically based
                                        on selections
                                    </small>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Student Name <span class="text-danger">*</span></label>
                                    <input type="text" name="student_name" class="form-control"
                                        placeholder="Enter full student name" required>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Student Name (AR)</label>
                                    <input type="text" name="student_name_ar" class="form-control"
                                        placeholder="أدخل اسم الطالب بالعربية">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>Gender <span class="text-danger">*</span></label>
                                    <select name="student_sex" class="form-control select2" required>
                                        <option value="">-- Select Gender --</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label>Date of Birth</label>
                                    <input type="date" name="date_of_birth" class="form-control">
                                </div>

                                <div class="form-group col-md-4">
                                    <label>Nationality</label>
                                    <input type="text" name="student_nationality" class="form-control"
                                        placeholder="e.g., UGANDAN">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Birth Place</label>
                                    <input type="text" name="birth_place" class="form-control"
                                        placeholder="Enter birth place">
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Birth Place (AR)</label>
                                    <input type="text" name="birth_place_ar" class="form-control"
                                        placeholder="أدخل مكان الميلاد بالعربية">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>Class</label>

                                    <input type="text" name="class" id="student_class" class="form-control" readonly>
                                </div>

                                <div class="form-group col-md-4">
                                    <label>Section</label>

                                    <select name="section" class="form-control select2">
                                        <option value="">-- Select Section --</option>
                                        <option value="Day">Day</option>
                                        <option value="Boarding">Boarding</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label>District</label>
                                    <input type="text" name="district" class="form-control" placeholder="Enter district">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>District (AR)</label>
                                    <input type="text" name="district_ar" class="form-control"
                                        placeholder="أدخل المنطقة بالعربية">
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-secondary" onclick="window.history.back()">
                                    <i class="fas fa-arrow-left mr-1"></i> Back
                                </button>
                                <button type="submit" class="btn text-white" style="background-color: #287C44;">
                                    <i class="fas fa-paper-plane mr-1"></i> Submit Registration
                                </button>
                            </div>
                        </form>

                        <!-- Recent Registrations Table -->
                        <div class="mt-5">
                            <div class="alert border-0 d-flex justify-content-between align-items-center"
                                style="background-color: #e8f5e9; color: #0d4b1f;">

                                <div>
                                    <strong>Step 2 :</strong>
                                </div>

                                <div>
                                    <i class="fas fa-clock mr-2"></i> Most Recent Registrations
                                </div>

                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="recentRegistrations">
                                    <thead class="text-white" style="background-color: #0d4b1f;">
                                        <tr>
                                            <th>No</th>
                                            <th>Photo</th>
                                            <th>Student ID</th>
                                            <th>Name</th>
                                            <th>Class</th>
                                            <th>Year</th>
                                            <th>Status</th>
                                            <th>Date of Birth</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody id="registrationTableBody">
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">
                                                <i class="fas fa-spinner fa-spin mr-2"></i> Loading...
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Step 3: Submit Students with Attached Images -->
                        <div class="mt-5">
                            <div class="alert border-0 d-flex justify-content-between align-items-center"
                                style="background-color: #e8f5e9; color: #0d4b1f;">
                                <div>
                                    <strong>Step 3 :</strong>
                                </div>
                                <div>
                                    <i class="fas fa-paper-plane mr-2"></i> Submit Students with Attached Images for Admin
                                    Approval
                                </div>
                            </div>

                            <!-- Filters -->
                            <div class="form-row mb-3">
                                <div class="form-group col-md-3">
                                    <label>Admission Year <span class="text-danger">*</span></label>
                                    <select id="step3_year" class="form-control">
                                        <option value="">-- Select Year --</option>
                                        @foreach ($years as $year)
                                            <option value="{{ $year->year_en }}" {{ $year->year_en == $currentYear ? 'selected' : '' }}>
                                                {{ $year->year_en }} - {{ $year->year_ar }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Category <span class="text-danger">*</span></label>
                                    <select id="step3_category" class="form-control">
                                        <option value="">-- Select Category --</option>
                                        <option value="ID">Idaad - ID</option>
                                        <option value="TH">Thanawi - TH</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3 d-flex align-items-end">
                                    <button type="button" id="step3_filter_btn" class="btn text-white w-100"
                                        style="background-color: #287C44;">
                                        <i class="fas fa-search mr-1"></i> Filter Students
                                    </button>
                                </div>
                            </div>

                            <!-- Students Table -->
                            <div id="step3_table_wrapper" style="display:none;">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="step3Table">
                                        <thead class="text-white" style="background-color: #0d4b1f;">
                                            <tr>
                                                <th>
                                                    <input type="checkbox" id="step3_check_all" title="Check All">
                                                </th>
                                                <th>No</th>
                                                <th>Photo</th>
                                                <th>Student ID</th>
                                                <th>Name</th>
                                                <th>Class</th>
                                                <th>Section</th>
                                                <th>Date of Birth</th>
                                            </tr>
                                        </thead>
                                        <tbody id="step3TableBody">
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Supporting Document Upload -->
                                <div class="photo-upload-section mt-4">
                                    <label>
                                        <i class="fas fa-file-image mr-2"></i> Attach Supporting Document / Cover Image
                                    </label>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="photo-preview-container">
                                            <img id="step3_doc_preview" src="/assets/images/default-user.jpg"
                                                class="photo-preview" onerror="this.src='/assets/images/default-user.jpg';">
                                        </div>
                                        <div class="photo-actions">
                                            <div class="row">
                                                <div class="col">
                                                    <div class="photo-upload-input">
                                                        <input type="file" id="step3_doc_input"
                                                            accept="image/jpeg,image/jpg,image/png">
                                                        <div class="custom-file-upload">
                                                            <i class="fas fa-upload"></i> Choose Image
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <button type="button" id="step3_remove_doc_btn"
                                                        class="btn-remove-photo">
                                                        <i class="fas fa-trash-alt"></i> Remove
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="file-info">
                                                <i class="fas fa-info-circle"></i>
                                                <span>Supported formats: JPG, PNG (Max 2MB)</span>
                                            </div>
                                            <div id="step3_upload_status" class="upload-status"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="d-flex justify-content-end mt-3">
                                    <button type="button" id="step3_submit_btn" class="btn text-white px-5"
                                        style="background-color: #287C44;">
                                        <i class="fas fa-paper-plane mr-1"></i> Submit Selected Students for Approval
                                    </button>
                                </div>
                            </div>

                            <!-- Empty state -->
                            <div id="step3_empty" class="text-center text-muted py-4" style="display:none;">
                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                No students with "Attached Image, Pending Submission" found for the selected filters.
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Student Modal -->
    <div class="modal fade" id="editStudentModal" tabindex="-1" role="dialog" aria-labelledby="editStudentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #0d4b1f; color: white;">
                    <h5 class="modal-title" id="editStudentModalLabel">
                        <i class="fas fa-edit mr-2"></i> Edit Student Registration
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editStudentForm">

                    <!-- Photo Upload Section -->
                    <!-- Photo Upload Section -->
                    <div class="photo-upload-section">
                        <label>
                            <i class="fas fa-camera mr-2"></i> Student Photo
                        </label>
                        <div class="d-flex align-items-center gap-3">
                            <div class="photo-preview-container">
                                <img id="edit_photo_preview" src="" class="photo-preview"
                                    onerror="this.src='/assets/images/default-user.jpg';">
                            </div>
                            <div class="photo-actions">
                                <div class="row">
                                    <div class="col">
                                        <div class="photo-upload-input">
                                            <input type="file" id="edit_photo_input"
                                                accept="image/jpeg,image/jpg,image/png">
                                            <div class="custom-file-upload">
                                                <i class="fas fa-upload"></i> Choose Photo
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <button type="button" id="remove_photo_btn" class="btn-remove-photo">
                                            <i class="fas fa-trash-alt"></i> Remove
                                        </button>
                                    </div>
                                </div>
                                <div class="file-info">
                                    <i class="fas fa-info-circle"></i>
                                    <span>Supported formats: JPG, PNG (Max 2MB)</span>
                                </div>
                                <div id="upload_status" class="upload-status"></div>
                            </div>
                        </div>
                    </div>


                    <div class="modal-body">
                        <input type="hidden" name="edit_id" id="edit_id">
                        <input type="hidden" name="edit_student_id" id="edit_student_id">

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Category <span class="text-danger">*</span></label>
                                <select name="edit_category" id="edit_category" class="form-control" required disabled>
                                    <option value="">-- Select Category --</option>
                                    <option value="ID">Idaad - ID</option>
                                    <option value="TH">Thanawi - TH</option>
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label>Admission Year <span class="text-danger">*</span></label>
                                <select name="edit_admission_year" id="edit_admission_year" class="form-control" required
                                    disabled>
                                    <option value="">-- Select Year --</option>
                                    @foreach ($years as $year)
                                        <option value="{{ $year->year_en }}">{{ $year->year_en }} - {{ $year->year_ar }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Student Name <span class="text-danger">*</span></label>
                                <input type="text" name="edit_student_name" id="edit_student_name" class="form-control"
                                    required>
                            </div>

                            <div class="form-group col-md-6">
                                <label>Student Name (AR)</label>
                                <input type="text" name="edit_student_name_ar" id="edit_student_name_ar"
                                    class="form-control">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>Gender <span class="text-danger">*</span></label>
                                <select name="edit_student_sex" id="edit_student_sex" class="form-control" required>
                                    <option value="">-- Select Gender --</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Date of Birth</label>
                                <input type="date" name="edit_date_of_birth" id="edit_date_of_birth" class="form-control">
                            </div>

                            <div class="form-group col-md-4">
                                <label>Nationality</label>
                                <input type="text" name="edit_student_nationality" id="edit_student_nationality"
                                    class="form-control">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Birth Place</label>
                                <input type="text" name="edit_birth_place" id="edit_birth_place" class="form-control">
                            </div>

                            <div class="form-group col-md-6">
                                <label>Birth Place (AR)</label>
                                <input type="text" name="edit_birth_place_ar" id="edit_birth_place_ar" class="form-control">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>Class</label>
                                <input type="text" name="edit_class" id="edit_class" class="form-control" readonly>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Section</label>
                                <select name="edit_section" id="edit_section" class="form-control">
                                    <option value="">-- Select Section --</option>
                                    <option value="Day">Day</option>
                                    <option value="Boarding">Boarding</option>
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label>District</label>
                                <input type="text" name="edit_district" id="edit_district" class="form-control">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>District (AR)</label>
                                <input type="text" name="edit_district_ar" id="edit_district_ar" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i> Cancel
                        </button>
                        <button type="submit" class="btn text-white" style="background-color: #287C44;">
                            <i class="fas fa-save mr-1"></i> Update Registration
                        </button>
                    </div>
                </form>
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
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {
            // Initialize Select2 for all select elements
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%'
            });

            // Initialize Select2 for Step 3 selects (separate class, same init)
            // $('#step3_year, #step3_category').select2({
            //     theme: 'bootstrap4',
            //     width: '100%'
            // });

            let dataTable;
            let currentPhotoFile = null;

            // Function to generate student ID
            function updateStudentID() {
                let category = $('select[name="category"]').val();
                let year = $('select[name="admission_year"]').val();

                if (category && year) {
                    $.ajax({
                        url: '{{ route('school.generate.student.id') }}',
                        method: 'GET',
                        data: { category: category, year: year },
                        success: function (response) {
                            $('#student_id').val(response.student_id);
                        },
                        error: function () {
                            $('#student_id').val('');
                        }
                    });
                } else {
                    $('#student_id').val('');
                }
            }

            // Auto assign class based on category
            $('#category').on('change', function () {
                let category = $(this).val();
                if (category === 'ID') {
                    $('#student_class').val('Senior Four / ضصثقف');
                } else if (category === 'TH') {
                    $('#student_class').val('Senior Six / الثانوية');
                } else {
                    $('#student_class').val('');
                }
            });

            // Trigger ID generation when category or year changes
            $('select[name="category"], select[name="admission_year"]').on('change', updateStudentID);

            // Load recent registrations and initialize DataTable
            function loadRecentRegistrations() {
                $.ajax({
                    url: '{{ route('school.recent.registrations') }}',
                    method: 'GET',
                    success: function (response) {
                        let html = '';

                        if (response.registrations && response.registrations.length > 0) {
                            response.registrations.forEach(function (reg, index) {
                                let statusBadge = '';
                                if (reg.status === 'Pending Photo Submission') {
                                    statusBadge = '<span class="badge-pending">Pending Photo Submission</span>';
                                } else if (reg.status === 'Attached Image, Pending Submission') {
                                    statusBadge = '<span class="badge-rejected">Attached Image, Pending Submission</span>';
                                } else if (reg.status === 'Pending Admin Approval') {
                                    statusBadge = '<span class="badge-blue">Pending Admin Approval</span>';
                                }else if (reg.status === 'Approved') {
                                    statusBadge = '<span class="badge-green">Approved Student Registration</span>';
                                }

                                html += `
                                    <tr>
                                        <td>${index + 1}</td>
                                        <td>
                                            <img src="/assets/student_photos/${reg.student_id}.jpg"
                                                 onerror="this.src='/assets/images/default-user.jpg'; this.dataset.missing='1';"
                                                 style="width:50px;height:65px;object-fit:cover;border-radius:6px;border:2px solid #e9ecef;">
                                        </td>
                                        <td>${reg.student_id}</td>
                                        <td>
                                            ${reg.student_name}
                                            ${reg.student_name_ar ? `<br><small class="text-muted">${reg.student_name_ar}</small>` : ''}
                                        </td>
                                        <td>${reg.class ?? '-'}</td>
                                        <td>${reg.admission_year}</td>
                                        <td>${statusBadge}</td>
                                        <td>${reg.date_of_birth ? reg.date_of_birth.split('-').reverse().join('/') : '-'}</td>
                                        <td>
                                            <button class="btn btn-sm btn-primary edit-student mr-1"
                                                    data-id="${reg.id}"
                                                    data-student-id="${reg.student_id}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger delete-student"
                                                    data-id="${reg.student_id}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                `;
                            });
                        } else {
                            html = `
                                <tr>
                                    <td colspan="9" class="text-center text-muted">
                                        No registrations yet
                                    </td>
                                </tr>
                            `;
                        }

                        $('#registrationTableBody').html(html);

                        if ($.fn.DataTable.isDataTable('#recentRegistrations')) {
                            $('#recentRegistrations').DataTable().clear().destroy();
                        }

                        dataTable = $('#recentRegistrations').DataTable({
                            pageLength: 10,
                            lengthChange: true,
                            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                            searching: true,
                            ordering: true,
                            order: [[0, 'asc']],
                            language: {
                                search: "Search Students:",
                                searchPlaceholder: "Type to search...",
                                emptyTable: "No registrations found",
                                info: "Showing _START_ to _END_ of _TOTAL_ registrations",
                                infoEmpty: "Showing 0 to 0 of 0 registrations",
                                infoFiltered: "(filtered from _MAX_ total registrations)",
                                lengthMenu: "Show _MENU_ entries",
                                paginate: {
                                    first: '<i class="fas fa-angle-double-left"></i>',
                                    last: '<i class="fas fa-angle-double-right"></i>',
                                    previous: '<i class="fas fa-angle-left"></i>',
                                    next: '<i class="fas fa-angle-right"></i>'
                                }
                            },
                            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                                '<"row"<"col-sm-12"tr>>' +
                                '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
                        });
                    },
                    error: function () {
                        $('#registrationTableBody').html(`
                            <tr>
                                <td colspan="9" class="text-center text-danger">
                                    Error loading registrations
                                </td>
                            </tr>
                        `);
                    }
                });
            }

            // Delete student functionality
            $(document).on('click', '.delete-student', function () {
                let studentId = $(this).data('id');

                Swal.fire({
                    title: 'Delete Student?',
                    html: `Are you sure you want to delete Student ID:<br><b>${studentId}</b>?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#287C44',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (!result.isConfirmed) return;

                    Swal.fire({
                        title: 'Deleting...',
                        html: 'Please wait',
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading(); }
                    });

                    $.ajax({
                        url: '{{ route("school.delete.registration") }}',
                        method: 'POST',
                        data: {
                            student_id: studentId,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                html: `Student <b>${studentId}</b> has been deleted.`,
                                confirmButtonColor: '#287C44'
                            }).then(() => { location.reload(); });
                        },
                        error: function (xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: xhr.responseJSON?.message || 'Failed to delete student',
                                confirmButtonColor: '#d33'
                            });
                        }
                    });
                });
            });

            // Load registrations on page load
            loadRecentRegistrations();

            // Handle form submission
            $('#schoolRegistrationForm').on('submit', function (e) {
                e.preventDefault();

                let $form = $(this);
                let requiredFields = ['category', 'admission_year', 'student_id', 'student_name', 'student_sex'];
                let isValid = true;

                requiredFields.forEach(function (field) {
                    let input = $form.find(`[name="${field}"]`);
                    if (!input.val()) {
                        input.addClass('is-invalid');
                        isValid = false;
                    } else {
                        input.removeClass('is-invalid');
                    }
                });

                if (!isValid) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Missing Fields',
                        text: 'Please fill in all required fields',
                        confirmButtonColor: '#287C44'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Submit Registration?',
                    text: "Are you sure you want to submit this student registration?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#287C44',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, submit it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Submitting...',
                            text: 'Please wait',
                            allowOutsideClick: false,
                            didOpen: () => { Swal.showLoading(); }
                        });

                        let formData = {
                            category: $form.find('[name="category"]').val(),
                            admission_year: $form.find('[name="admission_year"]').val(),
                            student_id: $form.find('[name="student_id"]').val(),
                            student_name: $form.find('[name="student_name"]').val(),
                            student_name_ar: $form.find('[name="student_name_ar"]').val(),
                            date_of_birth: $form.find('[name="date_of_birth"]').val(),
                            student_sex: $form.find('[name="student_sex"]').val(),
                            student_nationality: $form.find('[name="student_nationality"]').val(),
                            birth_place: $form.find('[name="birth_place"]').val(),
                            birth_place_ar: $form.find('[name="birth_place_ar"]').val(),
                            class: $form.find('[name="class"]').val(),
                            section: $form.find('[name="section"]').val(),
                            district: $form.find('[name="district"]').val(),
                            district_ar: $form.find('[name="district_ar"]').val(),
                        };

                        $.ajax({
                            url: '{{ route('school.store.registration') }}',
                            method: 'POST',
                            data: formData,
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            success: function (response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: response.message,
                                    confirmButtonColor: '#287C44'
                                }).then((result) => {
                                    if (result.isConfirmed) { location.reload(); }
                                });
                            },
                            error: function (xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: xhr.responseJSON?.message || 'Something went wrong',
                                    confirmButtonColor: '#287C44'
                                });
                            }
                        });
                    }
                });
            });

            // Edit student functionality - open modal with data
            $(document).on('click', '.edit-student', function () {
                let registrationId = $(this).data('id');
                let studentId = $(this).data('student-id');

                $.ajax({
                    url: '{{ route("school.get.registration") }}',
                    method: 'GET',
                    data: { id: registrationId, student_id: studentId },
                    success: function (response) {
                        let confirmedPhotoUrl = '/assets/student_photos/' + response.registration.student_id + '.jpg?v=' + Date.now();
                        $('#edit_photo_preview').attr('src', confirmedPhotoUrl);
                        $('#edit_photo_input').val('');
                        currentPhotoFile = null;

                        $('#edit_id').val(response.registration.id);
                        $('#edit_student_id').val(response.registration.student_id);
                        $('#edit_category').val(response.registration.category);
                        $('#edit_admission_year').val(response.registration.admission_year);
                        $('#edit_student_name').val(response.registration.student_name);
                        $('#edit_student_name_ar').val(response.registration.student_name_ar || '');
                        $('#edit_student_sex').val(response.registration.student_sex);
                        $('#edit_date_of_birth').val(response.registration.date_of_birth || '');
                        $('#edit_student_nationality').val(response.registration.student_nationality || '');
                        $('#edit_birth_place').val(response.registration.birth_place || '');
                        $('#edit_birth_place_ar').val(response.registration.birth_place_ar || '');
                        $('#edit_class').val(response.registration.class || '');
                        $('#edit_section').val(response.registration.section || '');
                        $('#edit_district').val(response.registration.district || '');
                        $('#edit_district_ar').val(response.registration.district_ar || '');

                        if (response.registration.category === 'ID') {
                            $('#edit_class').val('Senior Four / ضصثقف');
                        } else if (response.registration.category === 'TH') {
                            $('#edit_class').val('Senior Six / الثانوية');
                        }

                        $('#editStudentModal').modal('show');
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to load student data',
                            confirmButtonColor: '#d33'
                        });
                    }
                });
            });

            // Handle edit category change to auto-update class in modal
            $(document).on('change', '#edit_category', function () {
                let category = $(this).val();
                if (category === 'ID') {
                    $('#edit_class').val('Senior Four / ضصثقف');
                } else if (category === 'TH') {
                    $('#edit_class').val('Senior Six / الثانوية');
                } else {
                    $('#edit_class').val('');
                }
            });

            // ========== EDIT MODAL IMAGE PREVIEW FUNCTIONALITY ==========

            $('#edit_photo_input').on('change', function (e) {
                const file = e.target.files[0];
                if (!file) return;

                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!allowedTypes.includes(file.type)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid File Type',
                        text: 'Please select a JPG, JPEG, or PNG image file.',
                        confirmButtonColor: '#287C44'
                    });
                    $(this).val('');
                    return;
                }

                const maxSize = 2 * 1024 * 1024;
                if (file.size > maxSize) {
                    Swal.fire({
                        icon: 'error',
                        title: 'File Too Large',
                        text: 'Please select an image less than 2MB.',
                        confirmButtonColor: '#287C44'
                    });
                    $(this).val('');
                    return;
                }

                $('#upload_status').removeClass('success error').addClass('loading')
                    .html('<i class="fas fa-spinner fa-spin"></i> Loading preview...').show();

                const reader = new FileReader();
                reader.onload = function (e) {
                    $('#edit_photo_preview').attr('src', e.target.result).addClass('updated');
                    setTimeout(() => $('#edit_photo_preview').removeClass('updated'), 500);
                    $('#upload_status').removeClass('loading error').addClass('success')
                        .html('<i class="fas fa-check-circle"></i> Photo loaded successfully!').show();
                    setTimeout(() => $('#upload_status').fadeOut('slow'), 3000);
                    currentPhotoFile = file;
                };
                reader.onerror = function () {
                    $('#upload_status').removeClass('loading success').addClass('error')
                        .html('<i class="fas fa-exclamation-circle"></i> Error loading image. Please try again.').show();
                    setTimeout(() => $('#upload_status').fadeOut('slow'), 3000);
                };
                reader.readAsDataURL(file);
            });

            $('#remove_photo_btn').on('click', function () {
                const studentId = $('#edit_student_id').val();

                if (!studentId) {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Student ID not found', confirmButtonColor: '#d33' });
                    return;
                }

                Swal.fire({
                    title: 'Remove Photo?',
                    text: 'Are you sure you want to remove this student\'s photo?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#287C44',
                    confirmButtonText: 'Yes, remove it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Removing Photo...',
                            text: 'Please wait',
                            allowOutsideClick: false,
                            didOpen: () => { Swal.showLoading(); }
                        });

                        $.ajax({
                            url: '{{ route("school.remove.registration.photo") }}',
                            method: 'POST',
                            data: {
                                student_id: studentId,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function (response) {
                                $('#edit_photo_input').val('');
                                $('#edit_photo_preview').attr('src', '/assets/images/default-user.jpg').addClass('updated');
                                setTimeout(() => $('#edit_photo_preview').removeClass('updated'), 500);
                                $('#upload_status').removeClass('loading success error').addClass('success')
                                    .html('<i class="fas fa-check-circle"></i> ' + response.message).show();
                                setTimeout(() => $('#upload_status').fadeOut('slow'), 3000);
                                currentPhotoFile = null;
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Removed!',
                                    text: response.message,
                                    confirmButtonColor: '#287C44'
                                });
                            },
                            error: function (xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: xhr.responseJSON?.message || 'Failed to remove photo',
                                    confirmButtonColor: '#d33'
                                });
                            }
                        });
                    }
                });
            });

            // Drag and drop — scoped only to the edit modal upload section
            const editPhotoUploadSection = document.querySelector('#editStudentModal .photo-upload-section');

            if (editPhotoUploadSection) {
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    editPhotoUploadSection.addEventListener(eventName, function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                    }, false);
                });

                ['dragenter', 'dragover'].forEach(eventName => {
                    editPhotoUploadSection.addEventListener(eventName, function () {
                        editPhotoUploadSection.style.borderColor = '#287C44';
                        editPhotoUploadSection.style.backgroundColor = '#f0f9f0';
                        editPhotoUploadSection.style.transform = 'scale(1.01)';
                    }, false);
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    editPhotoUploadSection.addEventListener(eventName, function () {
                        editPhotoUploadSection.style.borderColor = '#e0e0e0';
                        editPhotoUploadSection.style.backgroundColor = '';
                        editPhotoUploadSection.style.transform = 'scale(1)';
                    }, false);
                });

                editPhotoUploadSection.addEventListener('drop', function (e) {
                    const files = e.dataTransfer.files;
                    if (files.length > 0) {
                        $('#edit_photo_input')[0].files = files;
                        $('#edit_photo_input').trigger('change');
                    }
                }, false);
            }

            // Function to upload photo
            function uploadStudentPhoto(studentId, file) {
                return new Promise((resolve, reject) => {
                    const formData = new FormData();
                    formData.append('student_id', studentId);
                    formData.append('photo', file);
                    formData.append('_token', '{{ csrf_token() }}');

                    $.ajax({
                        url: '{{ route("school.upload.registration.photo") }}',
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) { resolve(response); },
                        error: function (xhr) { reject(xhr); }
                    });
                });
            }

            // Handle edit form submission with photo upload
            $('#editStudentForm').on('submit', async function (e) {
                e.preventDefault();

                let formData = {
                    id: $('#edit_id').val(),
                    student_id: $('#edit_student_id').val(),
                    category: $('#edit_category').val(),
                    admission_year: $('#edit_admission_year').val(),
                    student_name: $('#edit_student_name').val(),
                    student_name_ar: $('#edit_student_name_ar').val(),
                    date_of_birth: $('#edit_date_of_birth').val(),
                    student_sex: $('#edit_student_sex').val(),
                    student_nationality: $('#edit_student_nationality').val(),
                    birth_place: $('#edit_birth_place').val(),
                    birth_place_ar: $('#edit_birth_place_ar').val(),
                    class: $('#edit_class').val(),
                    section: $('#edit_section').val(),
                    district: $('#edit_district').val(),
                    district_ar: $('#edit_district_ar').val(),
                };

                if (!formData.category || !formData.admission_year || !formData.student_name || !formData.student_sex) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Missing Fields',
                        text: 'Please fill in all required fields',
                        confirmButtonColor: '#287C44'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Update Student?',
                    text: "Are you sure you want to update this student registration?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#287C44',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, update it!',
                    cancelButtonText: 'Cancel'
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Updating...',
                            text: 'Please wait',
                            allowOutsideClick: false,
                            didOpen: () => { Swal.showLoading(); }
                        });

                        try {
                            const updateResponse = await $.ajax({
                                url: '{{ route("school.update.registration") }}',
                                method: 'POST',
                                data: formData,
                                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                            });

                            if (currentPhotoFile) {
                                try {
                                    await uploadStudentPhoto(formData.student_id, currentPhotoFile);
                                } catch (photoError) {
                                    console.warn('Photo upload failed:', photoError);
                                }
                            }

                            Swal.fire({
                                icon: 'success',
                                title: 'Updated!',
                                text: updateResponse.message,
                                confirmButtonColor: '#287C44'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $('#editStudentModal').modal('hide');
                                    location.reload();
                                }
                            });
                        } catch (error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: error.responseJSON?.message || 'Failed to update student',
                                confirmButtonColor: '#d33'
                            });
                        }
                    }
                });
            });

            // Reset photo preview when modal is closed
            $('#editStudentModal').on('hidden.bs.modal', function () {
                $('#edit_photo_input').val('');
                $('#upload_status').hide().removeClass('success error loading').empty();
                currentPhotoFile = null;
            });

            // ========== STEP 3 FUNCTIONALITY ==========

            let step3DocFile = null;

            // Filter button click
            $('#step3_filter_btn').on('click', function () {
                const year = $('#step3_year').val();
                const category = $('#step3_category').val();

                if (!year || !category) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Required',
                        text: 'Please select both Admission Year and Category to filter.',
                        confirmButtonColor: '#287C44'
                    });
                    return;
                }

                loadStep3Students(year, category);
            });

            function loadStep3Students(year, category) {
                $('#step3_table_wrapper').hide();
                $('#step3_empty').hide();
                $('#step3TableBody').html(`
                    <tr>
                        <td colspan="8" class="text-center text-muted">
                            <i class="fas fa-spinner fa-spin mr-2"></i> Loading...
                        </td>
                    </tr>
                `);

                $.ajax({
                    url: '{{ route("school.step3.students") }}',
                    method: 'GET',
                    data: { year: year, category: category },
                    success: function (response) {
                        const students = response.students;

                        if (!students || students.length === 0) {
                            $('#step3_empty').show();
                            $('#step3_table_wrapper').hide();
                            return;
                        }

                        let html = '';
                        students.forEach(function (s, index) {
                            html += `
                                <tr>
                                    <td>
                                        <input type="checkbox" class="step3-checkbox" value="${s.id}" data-student-id="${s.student_id}">
                                    </td>
                                    <td>${index + 1}</td>
                                    <td>
                                        <img src="/assets/student_photos/${s.student_id}.jpg"
                                             onerror="this.src='/assets/images/default-user.jpg';"
                                             style="width:50px;height:65px;object-fit:cover;border-radius:6px;border:2px solid #e9ecef;">
                                    </td>
                                    <td>${s.student_id}</td>
                                    <td>
                                        ${s.student_name}
                                        ${s.student_name_ar ? `<br><small class="text-muted">${s.student_name_ar}</small>` : ''}
                                    </td>
                                    <td>${s.class ?? '-'}</td>
                                    <td>${s.section ?? '-'}</td>
                                    <td>${s.date_of_birth ? s.date_of_birth.split('-').reverse().join('/') : '-'}</td>
                                </tr>
                            `;
                        });

                        $('#step3TableBody').html(html);
                        $('#step3_check_all').prop('checked', false);
                        $('#step3_table_wrapper').show();
                        $('#step3_empty').hide();
                    },
                    error: function () {
                        $('#step3TableBody').html(`
                            <tr>
                                <td colspan="8" class="text-center text-danger">
                                    <i class="fas fa-exclamation-circle mr-1"></i> Error loading students.
                                </td>
                            </tr>
                        `);
                        $('#step3_table_wrapper').show();
                    }
                });
            }

            // Check All
            $('#step3_check_all').on('change', function () {
                $('.step3-checkbox').prop('checked', $(this).is(':checked'));
            });

            // Sync check-all state when individual checkboxes change
            $(document).on('change', '.step3-checkbox', function () {
                if (!$(this).is(':checked')) {
                    $('#step3_check_all').prop('checked', false);
                } else {
                    if ($('.step3-checkbox:not(:checked)').length === 0) {
                        $('#step3_check_all').prop('checked', true);
                    }
                }
            });

            // Step 3 document file preview
            $('#step3_doc_input').on('change', function (e) {
                const file = e.target.files[0];
                if (!file) return;

                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!allowedTypes.includes(file.type)) {
                    Swal.fire({ icon: 'error', title: 'Invalid File Type', text: 'Please select JPG or PNG.', confirmButtonColor: '#287C44' });
                    $(this).val('');
                    return;
                }

                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire({ icon: 'error', title: 'File Too Large', text: 'Max file size is 2MB.', confirmButtonColor: '#287C44' });
                    $(this).val('');
                    return;
                }

                $('#step3_upload_status').removeClass('success error').addClass('loading')
                    .html('<i class="fas fa-spinner fa-spin"></i> Loading preview...').show();

                const reader = new FileReader();
                reader.onload = function (e) {
                    $('#step3_doc_preview').attr('src', e.target.result).addClass('updated');
                    setTimeout(() => $('#step3_doc_preview').removeClass('updated'), 500);
                    $('#step3_upload_status').removeClass('loading error').addClass('success')
                        .html('<i class="fas fa-check-circle"></i> Image loaded successfully!').show();
                    setTimeout(() => $('#step3_upload_status').fadeOut('slow'), 3000);
                    step3DocFile = file;
                };
                reader.onerror = function () {
                    $('#step3_upload_status').removeClass('loading success').addClass('error')
                        .html('<i class="fas fa-exclamation-circle"></i> Error loading image.').show();
                    setTimeout(() => $('#step3_upload_status').fadeOut('slow'), 3000);
                };
                reader.readAsDataURL(file);
            });

            // Remove step 3 document
            $('#step3_remove_doc_btn').on('click', function () {
                $('#step3_doc_input').val('');
                $('#step3_doc_preview').attr('src', '/assets/images/default-user.jpg');
                step3DocFile = null;
                $('#step3_upload_status').hide().removeClass('success error loading').empty();
            });

            // Step 3 Submit
            $('#step3_submit_btn').on('click', function () {
                const checkedBoxes = $('.step3-checkbox:checked');

                if (checkedBoxes.length === 0) {
                    Swal.fire({ icon: 'warning', title: 'No Students Selected', text: 'Please select at least one student to submit.', confirmButtonColor: '#287C44' });
                    return;
                }

                if (!step3DocFile) {
                    Swal.fire({ icon: 'warning', title: 'No Document Attached', text: 'Please attach a supporting document/image before submitting.', confirmButtonColor: '#287C44' });
                    return;
                }

                const selectedIds = [];
                checkedBoxes.each(function () {
                    selectedIds.push($(this).val());
                });

                Swal.fire({
                    title: 'Submit for Approval?',
                    html: `You are about to submit <b>${selectedIds.length}</b> student(s) for admin approval.<br>Their status will be updated to <b>Pending Admin Approval</b>.`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#287C44',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Submit!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (!result.isConfirmed) return;

                    Swal.fire({
                        title: 'Submitting...',
                        text: 'Please wait',
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading(); }
                    });

                    const formData = new FormData();
                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('ids', JSON.stringify(selectedIds));
                    formData.append('document', step3DocFile);

                    $.ajax({
                        url: '{{ route("school.step3.submit") }}',
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Submitted!',
                                text: response.message,
                                confirmButtonColor: '#287C44'
                            }).then(() => {
                                $('#step3_doc_input').val('');
                                $('#step3_doc_preview').attr('src', '/assets/images/default-user.jpg');
                                step3DocFile = null;
                                $('#step3_upload_status').hide().removeClass('success error loading').empty();
                                const year = $('#step3_year').val();
                                const category = $('#step3_category').val();
                                loadStep3Students(year, category);
                                loadRecentRegistrations();
                            });
                        },
                        error: function (xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: xhr.responseJSON?.message || 'Failed to submit students.',
                                confirmButtonColor: '#d33'
                            });
                        }
                    });
                });
            });

        });
    </script>
@endsection
@endsection