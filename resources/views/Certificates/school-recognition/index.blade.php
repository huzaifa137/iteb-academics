@extends('layouts-side-bar.master')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
    integrity="sha512-p1B9XJvxXlJ0sFh1ExAmH4y3L1kGk+x+r6Gx7q6v5+PgfKhnYzOZ3xGlKEX2eVZCMu1k7r1R7pLLj5p2lP2vXw=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

@section('content')
    <div class="side-app">

        <div class="page-header">
            <h4 class="page-title">School Recognition Certificates</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/student/dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Recognition Certificates</li>
            </ol>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0" style="border-radius: 16px; overflow: hidden;">
                    <div class="card-header d-flex justify-content-between align-items-center"
                        style="background: linear-gradient(135deg, #0d4b1e 0%, #287c44 100%); padding: 1.2rem 1.6rem;">
                        <h5 class="mb-0 text-white font-weight-bold">
                            <i class="fas fa-certificate mr-2"></i> Issued Recognition Certificates
                        </h5>
                        <a href="{{ route('school.recognition.create') }}" class="btn btn-sm btn-warning font-weight-bold"
                            style="border-radius: 8px;">
                            <i class="fas fa-plus mr-1"></i> Issue New Certificate
                        </a>
                    </div>
                    <div class="card-body p-0">

                        @if(session('success'))
                            <div class="alert alert-success mx-3 mt-3 mb-0" style="border-radius: 10px;">
                                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                            </div>
                        @endif

                        @if($certificates->isEmpty())
                            <div class="text-center py-5">
                                <i class="fas fa-file-certificate fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No recognition certificates issued yet.</p>
                                <a href="{{ route('school.recognition.create') }}" class="btn btn-primary">
                                    Issue First Certificate
                                </a>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" style="font-size: 0.92rem;">
                                    <thead style="background: #f5f7fa;">
                                        <tr>
                                            <th class="pl-4">#</th>
                                            <th>Certificate No.</th>
                                            <th>School (House)</th>
                                            <th>School Code</th>
                                            <th>Location</th>
                                            <th>Issued Date</th>
                                            <th>Status</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($certificates as $i => $cert)
                                            <tr>
                                                <td class="pl-4 align-middle text-muted">{{ $i + 1 }}</td>
                                                <td class="align-middle font-weight-bold text-dark">
                                                    {{ $cert->certificate_number }}
                                                </td>
                                                <td class="align-middle">
                                                    <div class="font-weight-semibold">{{ $cert->house->House ?? '—' }}</div>
                                                    <small class="text-muted" dir="rtl">{{ $cert->house->House_AR ?? '' }}</small>
                                                </td>
                                                <td class="align-middle">
                                                    <span class="badge badge-secondary"
                                                        style="font-size: 0.85em; border-radius: 6px;">
                                                        {{ $cert->house_number }}
                                                    </span>
                                                </td>
                                                <td class="align-middle text-muted">{{ $cert->house->Location ?? '—' }}</td>
                                                <td class="align-middle">
                                                    {{ \Carbon\Carbon::parse($cert->issued_date)->format('d M Y') }}
                                                </td>
                                                <td class="align-middle">
                                                    @if($cert->status === 'active')
                                                        <span class="badge badge-success"
                                                            style="border-radius: 8px; padding: 5px 10px;">
                                                            <i class="fas fa-check-circle mr-1"></i>Active
                                                        </span>
                                                    @else
                                                        <span class="badge badge-danger" style="border-radius: 8px; padding: 5px 10px;">
                                                            <i class="fas fa-ban mr-1"></i>Revoked
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="align-middle text-center">
                                                    <a href="{{ route('school.recognition.show', $cert->id) }}"
                                                        class="btn btn-sm btn-outline-primary mr-1" title="View Certificate"
                                                        style="border-radius: 6px;">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

                                                    @if($cert->status === 'active')
                                                        <button
                                                            onclick="confirmRevoke({{ $cert->id }}, '{{ $cert->certificate_number }}')"
                                                            class="btn btn-sm btn-outline-warning mr-1" title="Revoke"
                                                            style="border-radius: 6px;">
                                                            <i class="fas fa-ban"></i>
                                                        </button>
                                                    @endif

                                                    <button
                                                        onclick="confirmDelete({{ $cert->id }}, '{{ $cert->certificate_number }}')"
                                                        class="btn btn-sm btn-outline-danger" title="Delete Record"
                                                        style="border-radius: 6px;">
                                                        <i class="fas fa-trash"></i>
                                                    </button>

                                                    {{-- Hidden forms --}}
                                                    <form id="revoke-form-{{ $cert->id }}"
                                                        action="{{ route('school.recognition.revoke', $cert->id) }}" method="POST"
                                                        class="d-none">
                                                        @csrf
                                                    </form>
                                                    <form id="delete-form-{{ $cert->id }}"
                                                        action="{{ route('school.recognition.destroy', $cert->id) }}" method="POST"
                                                        class="d-none">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
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
    </div>
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmRevoke(id, certNo) {
            Swal.fire({
                title: 'Revoke Certificate?',
                html: `Are you sure you want to revoke certificate <b>${certNo}</b>?<br>The school will lose access to download it.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e6a817',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Revoke',
            }).then(result => {
                if (result.isConfirmed) {
                    document.getElementById('revoke-form-' + id).submit();
                }
            });
        }

        function confirmDelete(id, certNo) {
            Swal.fire({
                title: 'Delete Record?',
                html: `This will permanently delete certificate <b>${certNo}</b> and cannot be undone.`,
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Delete',
            }).then(result => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
@endsection