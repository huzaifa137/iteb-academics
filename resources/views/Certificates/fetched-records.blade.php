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
                                            <!-- Update the form in the loop -->
                                            {{-- <form action="{{ route('download.individual.passlip') }}" method="POST"
                                                class="downloadPasslipForm" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="student_id" value="{{ $studentId }}">
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-file-pdf"></i> Download Passlip
                                                </button>
                                            </form> --}}

                                            {{-- <form action="{{ route('download.individual.certificate') }}" method="POST"
                                                class="downloadCertificateForm" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="student_id" value="{{ $studentId }}">
                                                <button class="btn btn-sm btn-success">
                                                    <i class="fas fa-file-pdf"></i> Download Certificate
                                                </button>
                                            </form> --}}

                                            <a href="{{ route('passlip.view', ['student_id' => $studentId]) }}"
                                                class="btn btn-sm btn-primary" target="_blank">
                                                <i class="fas fa-file-pdf"></i> Download Passlip
                                            </a>

                                            <a href="{{ route('certificate.view', ['student_id' => $studentId]) }}"
                                                class="btn btn-sm btn-success " target="_blank">
                                                <i class="fas fa-file-pdf"></i> Download Certificate
                                            </a>
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
        // Use class selector instead of ID selector
        document.querySelectorAll('.downloadPasslipForm').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Downloading...',
                    text: 'Please wait while your passlip is being prepared.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });

                let formData = new FormData(this);
                let studentId = this.querySelector('input[name="student_id"]').value;

                fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.blob();
                    })
                    .then(blob => {
                        // Create temporary download link
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = "passlip_" + studentId + ".pdf";
                        document.body.appendChild(a);
                        a.click();

                        // Clean up
                        setTimeout(() => {
                            document.body.removeChild(a);
                            window.URL.revokeObjectURL(url);

                            Swal.fire({
                                icon: 'success',
                                title: 'Downloaded!',
                                text: 'Passlip has been downloaded successfully.',
                                confirmButtonText: 'OK'
                            });
                        }, 100);
                    })
                    .catch(err => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to download passlip. Please try again.'
                        });
                        console.error('Download error:', err);
                    });
            });
        });
    </script>

    <script>
        // Use class selector instead of ID selector
        document.querySelectorAll('.downloadCertificateForm').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Downloading...',
                    text: 'Please wait while your certificate is being prepared.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });

                let formData = new FormData(this);
                let studentId = this.querySelector('input[name="student_id"]').value;

                fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(async response => {
                        if (!response.ok) {
                            // Try to get the error response as text
                            const errorText = await response.text();
                            throw new Error(errorText || `HTTP error! status: ${response.status}`);
                        }
                        return response.blob();
                    })
                    .then(blob => {
                        // Create temporary download link
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = "certificate_" + studentId + ".pdf";
                        document.body.appendChild(a);
                        a.click();

                        // Clean up
                        setTimeout(() => {
                            document.body.removeChild(a);
                            window.URL.revokeObjectURL(url);

                            Swal.fire({
                                icon: 'success',
                                title: 'Downloaded!',
                                text: 'Certificate has been downloaded successfully.',
                                confirmButtonText: 'OK'
                            });
                        }, 100);
                    })
                    .catch(err => {
                        // Close the loading Swal first
                        Swal.close();

                        // Display the error response in the body
                        error({
                            responseText: err.message
                        });

                        console.error('Download error:', err);
                    });
            });
        });

        function error(data) {
            $('body').html(data.responseText);
        }
    </script>
@endsection
