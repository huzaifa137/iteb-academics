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
        @endif
    </div>
    </div>
    </div>
    </div>
@endsection