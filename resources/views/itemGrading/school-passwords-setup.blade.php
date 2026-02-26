@extends('layouts-side-bar.master')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
    integrity="sha512-p1B9XJvxXlJ0sFh1ExAmH4y3L1kGk+x+r6Gx7q6v5+PgfKhnYzOZ3xGlKEX2eVZCMu1k7r1R7pLLj5p2lP2vXw=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

@section('content')
    <div class="side-app">

        <div class="row">
            <div class="col-lg-12 col-xl-12 col-md-12 col-sm-12">
                <div class="card bg-primary">
                    <div class="card-header">
                        @include('layouts.iteb-grading-buttons')
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow-lg border-0">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0">School Password Management</h4>
                        </div>

                        <div class="card-body bg-light">
                            <!-- School Selection Form -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <label><strong>Select School</strong></label>
                                    <div class="input-group">
                                        <select id="schoolSelect" class="form-control select2" required>
                                            <option value="">-- Select School --</option>
                                            @foreach ($houses as $house)
                                                <option value="{{ $house->ID }}">{{ $house->House }} - {{ $house->Number }}</option>
                                            @endforeach
                                        </select>
                                        <button type="button" id="fetchPasswordBtn" class="btn btn-primary mt-3">
                                            <i class="fa fa-search me-2"></i> Fetch Password
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Password Display Section (Initially Hidden) -->
                            <div id="passwordSection" style="display: none;" class="mt-4">
                                <div class="card border-info">
                                    <div class="card-header text-white" style="background-color: #287c44;">
                                        <h5 class="mb-0">School Password Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <!-- School Info -->
                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <h5 id="schoolNameDisplay" class="text-primary"></h5>
                                            </div>
                                        </div>

                                        <!-- Password Display -->
                                        <div class="row mb-3">
                                            <div class="col-md-8">
                                                <label><strong>Current Password:</strong></label>
                                                <style>
                                                    .copy-btn {
                                                        margin-left: 8px;
                                                    }
                                                </style>

                                                <div class="input-group">
                                                    <input type="text" id="passwordDisplay" class="form-control" readonly>

                                                    <button type="button" class="btn btn-outline-secondary copy-btn"
                                                        onclick="copyPassword()">
                                                        <i class="fa fa-copy"></i> Copy
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label><strong>Status:</strong></label>
                                                <div>
                                                    <span id="passwordStatus" class="badge bg-success">Active</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <button type="button" id="regenerateBtn" class="btn btn-warning">
                                                    <i class="fa fa-refresh me-2"></i> <span id="actionBtnText">Regenerate
                                                        New
                                                        Password</span>
                                                </button>
                                                <button type="button" id="savePasswordBtn" class="btn btn-success" disabled>
                                                    <i class="fa fa-save me-2"></i> Save Password
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Generated Password Preview (Shows when regenerating) -->
                                        <div id="generatedPasswordPreview" class="mt-3 p-3 bg-light border rounded"
                                            style="display: none;">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <label><strong>New Generated Password:</strong></label>
                                                    <input type="text" id="newPasswordDisplay"
                                                        class="form-control border-success" readonly>
                                                </div>
                                                <div class="col-md-4 d-flex align-items-end">
                                                    <button type="button" class="btn btn-outline-success"
                                                        onclick="copyNewPassword()">
                                                        <i class="fa fa-copy"></i> Copy New
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>

@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function () {
        let currentSchoolId = null;
        let generatedPassword = null;
        let hasExistingPassword = false;

        // Initialize Select2
        $('.select2').select2({
            width: '100%'
        });

        // Fetch Password Button Click
        $('#fetchPasswordBtn').click(function () {
            const schoolId = $('#schoolSelect').val();

            if (!schoolId) {
                Swal.fire('Error', 'Please select a school', 'error');
                return;
            }

            Swal.fire({
                title: 'Fetching Password...',
                text: 'Please wait while we retrieve the password information.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: '{{ route("school.passwords.fetch") }}',
                type: 'POST',
                data: {
                    school_id: schoolId,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    Swal.close();

                    currentSchoolId = response.school_id;
                    $('#schoolNameDisplay').text(response.school_name);

                    if (response.has_password) {
                        // Existing password found
                        $('#passwordDisplay').val(response.password_plain);
                        $('#actionBtnText').text('Regenerate New Password');
                        hasExistingPassword = true;
                        $('#savePasswordBtn').prop('disabled', true);
                        $('#generatedPasswordPreview').hide();
                    } else {
                        // No password found
                        $('#passwordDisplay').val('No password set');
                        $('#actionBtnText').text('Create New Password');
                        hasExistingPassword = false;
                        $('#savePasswordBtn').prop('disabled', true);
                        $('#generatedPasswordPreview').hide();
                    }

                    $('#passwordSection').show();
                },
                // error: function (xhr) {
                //     Swal.fire('Error', 'Failed to fetch password information', 'error');
                // }
                error: function (data) {
                    $('body').html(data.responseText);
                }
            });
        });

        // Regenerate/Create Password Button Click
        $('#regenerateBtn').click(function () {
            if (!currentSchoolId) {
                Swal.fire('Error', 'Please select a school first', 'error');
                return;
            }

            Swal.fire({
                title: 'Generating Password...',
                text: 'Please wait while we generate a secure password.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: '{{ route("school.passwords.generate") }}',
                type: 'POST',
                data: {
                    school_id: currentSchoolId,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    Swal.close();

                    generatedPassword = response.generated_password;
                    $('#newPasswordDisplay').val(generatedPassword);
                    $('#generatedPasswordPreview').show();
                    $('#savePasswordBtn').prop('disabled', false);
                },
                error: function (xhr) {
                    Swal.fire('Error', 'Failed to generate password', 'error');
                }
                // error: function (data) {
                //     $('body').html(data.responseText);
                // }
            });
        });

        // Save Password Button Click
        $('#savePasswordBtn').click(function () {
            if (!currentSchoolId || !generatedPassword) {
                Swal.fire('Error', 'No password generated to save', 'error');
                return;
            }

            // Show confirmation first
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to save this password?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, save it!',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {

                    // Show loading while saving
                    Swal.fire({
                        title: 'Saving Password...',
                        text: 'Please wait while we save the password.',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: '{{ route("school.passwords.save") }}',
                        type: 'POST',
                        data: {
                            school_id: currentSchoolId,
                            password: generatedPassword,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Password saved successfully',
                                timer: 2000,
                                showConfirmButton: false
                            });

                            // Update the display
                            $('#passwordDisplay').val(generatedPassword);
                            $('#actionBtnText').text('Regenerate New Password');
                            $('#generatedPasswordPreview').hide();
                            $('#savePasswordBtn').prop('disabled', true);
                            hasExistingPassword = true;
                        },
                        // error: function (xhr) {
                        //     Swal.fire('Error', 'Failed to save password', 'error');
                        // }
                        error: function (data) {
                            $('body').html(data.responseText);
                        }
                    });
                }
            });
        });

        // School selection change
        $('#schoolSelect').change(function () {
            // Hide password section when school changes
            $('#passwordSection').hide();
            currentSchoolId = null;
            generatedPassword = null;
        });
    });

    // Copy password functions
    function copyPassword() {
        const passwordField = document.getElementById('passwordDisplay');
        passwordField.select();
        document.execCommand('copy');

        Swal.fire({
            icon: 'success',
            title: 'Copied!',
            text: 'Password copied to clipboard',
            timer: 1500,
            showConfirmButton: false
        });
    }

    function copyNewPassword() {
        const passwordField = document.getElementById('newPasswordDisplay');
        passwordField.select();
        document.execCommand('copy');

        Swal.fire({
            icon: 'success',
            title: 'Copied!',
            text: 'New password copied to clipboard',
            timer: 1500,
            showConfirmButton: false
        });
    }
</script>