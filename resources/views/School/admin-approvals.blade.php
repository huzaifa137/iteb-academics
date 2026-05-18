<?php use App\Http\Controllers\Helper; ?>
@extends('layouts-side-bar.master')
@section('content')

    <style>
        .approval-card {
            border: none;
            border-radius: 14px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            overflow: hidden;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .approval-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(13, 75, 31, 0.15);
            text-decoration: none;
            color: inherit;
        }

        .approval-card .card-header-custom {
            background: linear-gradient(135deg, #0d4b1f 0%, #287C44 100%);
            color: white;
            padding: 18px 20px 14px;
        }

        .approval-card .school-prefix {
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .approval-card .school-name {
            font-size: 13px;
            opacity: 0.85;
            margin-top: 2px;
        }

        .stat-box {
            text-align: center;
            padding: 16px 10px;
        }

        .stat-box .stat-number {
            font-size: 32px;
            font-weight: 700;
            line-height: 1;
        }

        .stat-box .stat-label {
            font-size: 12px;
            color: #6c757d;
            margin-top: 4px;
            font-weight: 500;
        }

        .stat-pending {
            color: #e67e22;
        }

        .stat-approved {
            color: #287C44;
        }

        .page-header-custom {
            background: linear-gradient(135deg, #0d4b1f, #287C44);
            color: white;
            border-radius: 12px;
            padding: 20px 25px;
            margin-bottom: 25px;
        }

        .badge-pending-approval {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffc107;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .document-badge {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 8px;
    padding: 6px 10px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 11px;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 8px;
}

.document-badge:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-1px);
}

.document-badge i {
    font-size: 14px;
}

.document-tooltip {
    position: relative;
    display: inline-block;
}

.document-list {
    max-height: 300px;
    overflow-y: auto;
}

.document-item {
    padding: 12px;
    border-bottom: 1px solid #e0e0e0;
    cursor: pointer;
    transition: all 0.2s ease;
}

.document-item:hover {
    background: #f5f5f5;
    transform: translateX(5px);
}

.document-item:last-child {
    border-bottom: none;
}

.document-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: #f0f0f0;
}

.document-info {
    flex: 1;
}

.document-name {
    font-size: 13px;
    font-weight: 500;
    margin-bottom: 4px;
}

.document-meta {
    font-size: 11px;
    color: #6c757d;
}

.preview-modal .modal-dialog {
    max-width: 800px;
}

.preview-container {
    text-align: center;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
}

.preview-container iframe,
.preview-container img {
    max-width: 100%;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
    </style>

    <div class="side-app">
        <div class="row">
            <div class="col-12">
                <div class="page-header-custom d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1"><i class="fas fa-user-check mr-2"></i> Student Approvals</h4>
                        <small style="opacity:0.85;">Review and approve student registrations submitted by schools</small>
                    </div>
                    <div class="text-right">
                        <span
                            style="font-size:28px; font-weight:700;">{{ collect($schools)->sum('pending_count') }}</span><br>
                        <small style="opacity:0.85;">Total Pending</small>
                    </div>
                </div>
            </div>
        </div>

        @if(empty($schools))
            <div class="text-center py-5">
                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                <h5 class="text-muted">No pending student approvals at this time.</h5>
            </div>
        @else
            <div class="row">
                @foreach($schools as $school)
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                        <a href="{{ route('admin.student.approvals.detail', $school['prefix']) }}" class="approval-card card">
                            <div class="card-header-custom">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="school-prefix">{{ $school['prefix'] }}</div>
                                        <div class="school-name">{{ $school['school_name'] }}</div>
                                    </div>
                                    <span class="badge-pending-approval">
                                        <i class="fas fa-clock mr-1"></i> Pending
                                    </span>
                                </div>
                            </div>
<div class="card-body p-0">
    <div class="row no-gutters">
        <div class="col-6 stat-box border-right">
            <div class="stat-number stat-pending">{{ $school['pending_count'] }}</div>
            <div class="stat-label">Awaiting Approval</div>
        </div>
        <div class="col-6 stat-box">
            <div class="stat-number stat-approved">{{ $school['approved_count'] }}</div>
            <div class="stat-label">Already Approved</div>
        </div>
    </div>
    
    @if($school['has_documents'])
        <div class="px-3 py-2 border-top" style="background:#f8f9fa;">
            <div class="d-flex align-items-center justify-content-between">
                <small class="text-muted">
                    <i class="fas fa-paperclip mr-1"></i> 
                    Attached Documents:
                </small>
                <button type="button" class="btn btn-link btn-sm p-0 view-school-docs" 
                        data-school-prefix="{{ $school['prefix'] }}"
                        data-documents='@json($school['documents'])'
                        style="font-size: 12px;">
                    View All ({{ count($school['documents']) }})
                </button>
            </div>
            <div class="mt-2">
                @foreach(array_slice($school['documents'], 0, 2) as $doc)
                    <div class="document-badge view-document" data-doc='@json($doc)'>
                        <i class="fas {{ $doc->file_type == 'application/pdf' ? 'fa-file-pdf' : 'fa-file-image' }}"></i>
                        <span>{{ Str::limit($doc->file_name, 30) }}</span>
                    </div>
                @endforeach
                @if(count($school['documents']) > 2)
                    <span class="document-badge" style="background: rgba(0,0,0,0.1);">
                        +{{ count($school['documents']) - 2 }} more
                    </span>
                @endif
            </div>
        </div>
    @else
        <div class="px-3 py-2 border-top" style="background:#f8f9fa;">
            <small class="text-muted">
                <i class="fas fa-info-circle mr-1"></i> 
                No supporting documents attached
            </small>
        </div>
    @endif
    
    <div class="px-3 py-2 border-top" style="background:#f8f9fa;">
        <small class="text-muted">
            <i class="fas fa-calendar-alt mr-1"></i>
            Last submission:
            {{ $school['latest_submission'] ? \Carbon\Carbon::parse($school['latest_submission'])->diffForHumans() : 'N/A' }}
        </small>
    </div>
</div>
                        </a>
                    </div>
                @endforeach
            </div>

            <!-- Document Preview Modal -->
<div class="modal fade" id="documentPreviewModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #0d4b1f, #287C44); color: white;">
                <h5 class="modal-title">
                    <i class="fas fa-file-alt mr-2"></i> 
                    Supporting Document
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="preview-container" id="documentPreviewContent">
                    <!-- Dynamic content will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" id="downloadDocumentBtn" class="btn btn-success" download>
                    <i class="fas fa-download mr-1"></i> Download
                </a>
                <button type="button" class="btn btn-danger" data-dismiss="modal">
    <i class="fas fa-times mr-1"></i> Close
</button>
            </div>
        </div>
    </div>
</div>

<!-- School Documents Modal -->
<div class="modal fade" id="schoolDocumentsModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #0d4b1f, #287C44); color: white;">
                <h5 class="modal-title">
                    <i class="fas fa-paperclip mr-2"></i> 
                    All Attached Documents
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="document-list" id="schoolDocumentsList">
                    <!-- Documents will be listed here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary-reg" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script>
    // View individual document
$(document).on('click', '.view-document', function(e) {
    e.preventDefault();
    e.stopPropagation();
    
    const doc = $(this).data('doc');
    const filePath = '/' + doc.file_path;
    const fileExtension = doc.file_name.split('.').pop().toLowerCase();
    
    // Update download link
    $('#downloadDocumentBtn').attr('href', filePath);
    
    // Preview based on file type
    const previewContent = $('#documentPreviewContent');
    
    if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileExtension)) {
        previewContent.html(`
            <img src="${filePath}" alt="${doc.file_name}" 
                 style="max-width:100%; max-height:70vh; border-radius:8px;">
        `);
    } else if (fileExtension === 'pdf') {
        previewContent.html(`
            <iframe src="${filePath}" style="width:100%; height:70vh; border:none;"></iframe>
        `);
    } else {
        // For other file types, show info and download option
        const fileSize = (doc.file_size / 1024).toFixed(2);
        const sizeText = fileSize > 1024 ? (fileSize / 1024).toFixed(2) + ' MB' : fileSize + ' KB';
        
        previewContent.html(`
            <div style="text-align:center; padding:40px;">
                <i class="fas fa-file-alt" style="font-size: 64px; color: #287C44; margin-bottom: 20px;"></i>
                <h5>${doc.file_name}</h5>
                <p class="text-muted">File Type: ${doc.file_type}<br>Size: ${sizeText}</p>
                <p>This file type cannot be previewed directly.</p>
                <a href="${filePath}" class="btn btn-primary-reg" download>
                    <i class="fas fa-download mr-1"></i> Download File
                </a>
            </div>
        `);
    }
    
    $('#documentPreviewModal').modal('show');
});

// View all documents for a school
$(document).on('click', '.view-school-docs', function(e) {
    e.preventDefault();
    e.stopPropagation();
    
    const documents = $(this).data('documents');
    const schoolPrefix = $(this).data('school-prefix');
    
    if (!documents || documents.length === 0) {
        Swal.fire({
            icon: 'info',
            title: 'No Documents',
            text: 'No supporting documents attached for this school.',
            confirmButtonColor: '#287C44'
        });
        return;
    }
    
    let html = '';
    documents.forEach((doc, index) => {
        const fileExtension = doc.file_name.split('.').pop().toLowerCase();
        let fileIcon = 'fa-file-alt';
        
        if (fileExtension === 'pdf') fileIcon = 'fa-file-pdf';
        else if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) fileIcon = 'fa-file-image';
        else if (['doc', 'docx'].includes(fileExtension)) fileIcon = 'fa-file-word';
        else if (['xls', 'xlsx'].includes(fileExtension)) fileIcon = 'fa-file-excel';
        
        const fileSize = (doc.file_size / 1024).toFixed(2);
        const sizeText = fileSize > 1024 ? (fileSize / 1024).toFixed(2) + ' MB' : fileSize + ' KB';
        
        html += `
            <div class="document-item d-flex align-items-center" data-doc='${JSON.stringify(doc)}'>
                <div class="document-icon mr-3">
                    <i class="fas ${fileIcon}" style="font-size: 24px;"></i>
                </div>
                <div class="document-info">
                    <div class="document-name">${doc.file_name}</div>
                    <div class="document-meta">
                        <i class="fas fa-database mr-1"></i>${sizeText}
                        <span class="mx-2">•</span>
                        <i class="fas fa-users mr-1"></i>${JSON.parse(doc.student_ids).length} students
                    </div>
                </div>
                <i class="fas fa-chevron-right text-muted"></i>
            </div>
        `;
    });
    
    $('#schoolDocumentsList').html(html);
    $('#schoolDocumentsModal').modal('show');
});

// Handle document item click in school documents modal
$(document).on('click', '.document-item', function() {
    const doc = $(this).data('doc');
    const filePath = '/' + doc.file_path;
    
    $('#downloadDocumentBtn').attr('href', filePath);
    
    const previewContent = $('#documentPreviewContent');
    const fileExtension = doc.file_name.split('.').pop().toLowerCase();
    
    if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileExtension)) {
        previewContent.html(`
            <img src="${filePath}" alt="${doc.file_name}" 
                 style="max-width:100%; max-height:70vh; border-radius:8px;">
        `);
    } else if (fileExtension === 'pdf') {
        previewContent.html(`
            <iframe src="${filePath}" style="width:100%; height:70vh; border:none;"></iframe>
        `);
    } else {
        const fileSize = (doc.file_size / 1024).toFixed(2);
        const sizeText = fileSize > 1024 ? (fileSize / 1024).toFixed(2) + ' MB' : fileSize + ' KB';
        
        previewContent.html(`
            <div style="text-align:center; padding:40px;">
                <i class="fas fa-file-alt" style="font-size: 64px; color: #287C44; margin-bottom: 20px;"></i>
                <h5>${doc.file_name}</h5>
                <p class="text-muted">File Type: ${doc.file_type}<br>Size: ${sizeText}</p>
                <p>This file type cannot be previewed directly.</p>
                <a href="${filePath}" class="btn btn-primary-reg" download>
                    <i class="fas fa-download mr-1"></i> Download File
                </a>
            </div>
        `);
    }
    
    $('#schoolDocumentsModal').modal('hide');
    setTimeout(() => {
        $('#documentPreviewModal').modal('show');
    }, 300);
});

// Prevent card click when clicking on document badges
$(document).on('click', '.document-badge, .view-school-docs', function(e) {
    e.stopPropagation();
});
</script>
        @endif
    </div>
    </div>
    </div>
    </div>
@endsection