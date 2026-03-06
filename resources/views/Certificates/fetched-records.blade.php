@extends('layouts-side-bar.master')

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
    rel="stylesheet">

@section('content')
    <?php use App\Http\Controllers\Helper; ?>

    <div class="side-app">
        <div class="stats-container">

            @if (isset($groupedByStudent))
                <div class="row">
                    <div class="col-md-12 text-center">
                        <a href="{{ route('generate.certifications') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-file-pdf"></i> Generate Certificate
                        </a>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header text-white d-flex align-items-center" style="background-color: #263f2e;">
                        <div class="w-33 text-start">
                            <h5 class="mb-0">
                                {{ Helper::schoolName($filters['school_number']) }}
                            </h5>
                        </div>

                        <div class="w-33 text-center">
                            <strong>
                                Category: {{ $filters['category'] }} |
                                Year: {{ $filters['year'] }}
                            </strong>
                        </div>

                        <div class="w-33 text-end">
                            <strong>Total Students:</strong> {{ $totalStudents }}
                        </div>

                    </div>
                    <style>
                        .w-33 {
                            width: 33.33%;
                        }
                    </style>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width:1px;">No.</th>
                                    <th style="text-align: center">Student Information</th>
                                    <th style="text-align: center;">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($groupedByStudent as $studentId => $allocations)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>

                                        <td>{{ $studentId }} - {{ Helper::getStudentName($studentId) }}</td>

                                        <td>
                                            <button class="btn btn-sm btn-primary">
                                                <i class="fas fa-file-pdf"></i> Download Passlip
                                            </button>

                                            <button class="btn btn-sm btn-success">
                                                <i class="fas fa-file-pdf"></i> Download Certificate
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
    </div>
    </div>


    <!-- Keep existing scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.getElementById('examStatisticsForm').addEventListener('submit', function() {

            const button = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnLoader = document.getElementById('btnLoader');

            // Disable button
            button.disabled = true;
            button.style.opacity = "0.7";
            button.style.cursor = "not-allowed";

            // Swap text with loader
            btnText.style.display = "none";
            btnLoader.style.display = "inline-block";
        });
    </script>

    <script>
        // Your existing JavaScript functions here
        function showMissingResourcesAlert() {
            Swal.fire({
                icon: 'error',
                title: 'Missing Required Resources',
                text: 'Some required resources are missing. Please update Server',
                confirmButtonColor: '#287c44',
                confirmButtonText: 'OK'
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const category = document.getElementById('categorySelect');
            const level = document.getElementById('levelSelect');

            function setLevelBasedOnCategory() {
                if (category.value === 'ID') {
                    level.value = 'O';
                } else if (category.value === 'TH') {
                    level.value = 'A';
                }
                level.disabled = true;
            }

            category.addEventListener('change', setLevelBasedOnCategory);
            setLevelBasedOnCategory();
        });

        function downloadPdf() {
            downloadFile('pdf');
        }

        function downloadFile(type) {
            const year = $('select[name="year"]').val();
            const category = $('select[name="category"]').val();
            const level = $('select[name="level"]').val();

            if (!year || !category) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Information',
                    text: 'Please select Year and Category before downloading.',
                    confirmButtonColor: '#287c44'
                });
                return;
            }

            const route = type === 'excel' ?
                '{{ route('iteb.exam.statistics.download.excel') }}' :
                '{{ route('iteb.exam.statistics.download.pdf') }}';

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = route;

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);

            const yearInput = document.createElement('input');
            yearInput.type = 'hidden';
            yearInput.name = 'year';
            yearInput.value = year;
            form.appendChild(yearInput);

            const categoryInput = document.createElement('input');
            categoryInput.type = 'hidden';
            categoryInput.name = 'category';
            categoryInput.value = category;
            form.appendChild(categoryInput);

            const levelInput = document.createElement('input');
            levelInput.type = 'hidden';
            levelInput.name = 'level';
            levelInput.value = level;
            form.appendChild(levelInput);

            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);

            Swal.fire({
                title: `Generating ${type.toUpperCase()}...`,
                text: 'Your file will be downloaded shortly.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            setTimeout(() => {
                Swal.close();
            }, 3000);
        }
    </script>

    <script>
        function downloadStudentsFullReport() {
            downloadReport('students', 'full');
        }

        function downloadSchoolsFullReport() {
            downloadReport('schools', 'full');
        }

        function downloadReport(reportType, reportScope) {
            const year = $('select[name="year"]').val();
            const category = $('select[name="category"]').val();
            const level = $('select[name="level"]').val();

            if (!year || !category) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Information',
                    text: 'Please select Year and Category before downloading.',
                    confirmButtonColor: '#287c44'
                });
                return;
            }

            Swal.fire({
                title: `Generating ${reportType} report...`,
                text: 'This may take a moment for large datasets.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const route = reportType === 'students' ?
                '{{ route('iteb.exam.statistics.download.students') }}' :
                '{{ route('iteb.exam.statistics.download.schools') }}';

            // Create a hidden form and submit
            const form = $('<form>', {
                'method': 'POST',
                'action': route,
                'target': '_blank'
            });

            form.append($('<input>', {
                'name': '_token',
                'value': '{{ csrf_token() }}',
                'type': 'hidden'
            }));

            form.append($('<input>', {
                'name': 'year',
                'value': year,
                'type': 'hidden'
            }));

            form.append($('<input>', {
                'name': 'category',
                'value': category,
                'type': 'hidden'
            }));

            form.append($('<input>', {
                'name': 'level',
                'value': level,
                'type': 'hidden'
            }));

            $('body').append(form);
            form.submit();
            form.remove();

            // Close the loading message after a delay
            setTimeout(() => {
                Swal.close();
            }, 4000);
        }
    </script>
@endsection
