@extends('layouts-side-bar.master')

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

@section('content')

        <?php
            use App\Http\Controllers\Helper;
        ?>
        <style>
            /* Subject tables styling */
        .card-header {
            font-weight: 600;
            padding: 1rem 1.5rem;
        }

        .card-header.bg-success {
            background: linear-gradient(135deg, #28a745 0%, #218838 100%) !important;
        }

        .card-header.bg-danger {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%) !important;
        }

        .table td {
            vertical-align: middle;
            padding: 0.75rem;
        }

        .table tbody tr:hover {
            background-color: rgba(0,0,0,0.02);
        }

        /* Subject badges */
        .badge.bg-success {
            background: linear-gradient(135deg, #28a745 0%, #218838 100%) !important;
            padding: 0.4rem 0.6rem;
            font-size: 0.85rem;
        }

        .badge.bg-warning {
            color: #212529 !important;
            padding: 0.4rem 0.6rem;
            font-size: 0.85rem;
        }

        .badge.bg-info {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%) !important;
            padding: 0.4rem 0.6rem;
            font-size: 0.85rem;
        }

        .badge.bg-danger {
            padding: 0.4rem 0.6rem;
            font-size: 0.85rem;
        }

        /* Bronze badge for 3rd place */
        .badge.bg-bronze {
            background-color: #cd7f32 !important;
            color: white;
        }
        </style>

    <div class="side-app">
        <div class="container mt-4">
            <div class="card shadow-lg border-0 mb-4">
                <div class="card-header text-white d-flex justify-content-between align-items-center"
                    style="background-color: #17a2b8;">
                    <h4 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i> Exam Statistics : {{ $year }} - {{ $category }}
                    </h4>
                    <span class="badge badge-light text-dark">Level {{ $level }}</span>
                </div>

                <div class="card-body">
                    <!-- Filter Form -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <form action="{{ route('iteb.exam.statistics') }}" method="POST"
                                class="form-inline justify-content-center" id="examStatisticsForm">
                                @csrf
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-3">
                                        <label class="fw-bold">Year</label>
                                        <select name="year" class="form-control" required>
                                            <option value="">Select Year</option>
                                            @foreach ($years ?? [] as $y)
                                                <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}> {{ $y }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="fw-bold">Category</label>
                                        <select name="category" id="categorySelect" class="form-control" required>
                                            <option value="">Select Category</option>
                                            <option value="ID" {{ $category == 'ID' ? 'selected' : '' }}>Idaad (ID)
                                            </option>
                                            <option value="TH" {{ $category == 'TH' ? 'selected' : '' }}>Thanawi (TH)
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="fw-bold">Level</label>
                                        <select name="level" id="levelSelect" class="form-control">
                                            <option value="A" {{ $level == 'A' ? 'selected' : '' }}>Level A</option>
                                            <option value="O" {{ $level == 'O' ? 'selected' : '' }}>Level O</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn text-white px-4"
                                            style="background-color: #17a2b8;">
                                            <i class="fas fa-search me-2"></i> Generate Statistics
                                        </button>
                                    </div>
                                </div>
                            </form>

                                    <!-- Summary Cards -->
                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <h5>Registered Students</h5>
                                        <h3>{{ $registeredStudents }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <h5>Graded Students</h5>
                                        <h3>{{ $totalGraded }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-danger text-white">
                                    <div class="card-body">
                                        <h5>Failed Students</h5>
                                        <h3>{{ $failedBreakdown['total_failed'] }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                            @if(isset($schoolsTable))
                            <div class="d-flex justify-content-end mt-3">
                                <div class="btn-group shadow-sm" role="group" style="border-radius: 10px; overflow: hidden;">

                                    <button type="button" class="btn btn-danger" onclick="downloadPdf()"
                                            style="padding: 10px 25px; font-weight: 500;">
                                        <i class="fas fa-file-pdf me-2"></i>
                                        <span>General Report Export</span>
                                    </button>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <hr>

                    @if (isset($schoolsTable))
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h5 class="bg-light p-2 rounded d-flex justify-content-between align-items-center">
                                    <span>
                                        1- Number of schools registered for Exams {{ $year }}:
                                    </span>
                                </h5>

                                <table class="table table-bordered table-hover">
                                    <thead class="table-secondary">
                                        <tr>
                                            <th>S/N</th>
                                            <th>{{ $levelName }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($schoolsTable as $index => $school)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $school['count'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- 2- Number of students registered -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h5 class="bg-light p-2 rounded d-flex justify-content-between align-items-center">
    <span>
        2- Number of students registered:
    </span>

<div class="d-flex gap-2">

</div>

</h5>

                                <table class="table table-bordered table-hover">
                                    <thead class="table-secondary">
                                        <tr>
                                            <th>S/N</th>
                                            <th>{{ $levelName }}</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($studentsRegisteredTable as $index => $student)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $student['count'] }}</td>
                                                <td>{{ $student['total'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- 3- Grading Summary (IDAAD/THANAWI LEVEL) -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h5 class="bg-light p-2 rounded d-flex justify-content-between align-items-center">
    <span>
        3- Grading Summary - {{ $levelName }}:
    </span>

</h5>

                                <table class="table table-bordered table-hover">
                                    <thead class="table-secondary">
                                        <tr>
                                            <th>S/N</th>
                                            <th>{{ $levelName }}</th>
                                            <th>Male</th>
                                            <th>%</th>
                                            <th>Female</th>
                                            <th>%</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>a.</td>
                                            <td>Excellent D1</td>
                                            <td>{{ $gradingSummary['D1']['male_count'] }}</td>
                                            <td>{{ $gradingSummary['D1']['male_percent'] }}%</td>
                                            <td>{{ $gradingSummary['D1']['female_count'] }}</td>
                                            <td>{{ $gradingSummary['D1']['female_percent'] }}%</td>
                                            <td>{{ $gradingSummary['D1']['total'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>b.</td>
                                            <td>Very good D2</td>
                                            <td>{{ $gradingSummary['D2']['male_count'] }}</td>
                                            <td>{{ $gradingSummary['D2']['male_percent'] }}%</td>
                                            <td>{{ $gradingSummary['D2']['female_count'] }}</td>
                                            <td>{{ $gradingSummary['D2']['female_percent'] }}%</td>
                                            <td>{{ $gradingSummary['D2']['total'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>c.</td>
                                            <td>Good C3</td>
                                            <td>{{ $gradingSummary['C3']['male_count'] }}</td>
                                            <td>{{ $gradingSummary['C3']['male_percent'] }}%</td>
                                            <td>{{ $gradingSummary['C3']['female_count'] }}</td>
                                            <td>{{ $gradingSummary['C3']['female_percent'] }}%</td>
                                            <td>{{ $gradingSummary['C3']['total'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>d.</td>
                                            <td>Pass C4</td>
                                            <td>{{ $gradingSummary['C4']['male_count'] }}</td>
                                            <td>{{ $gradingSummary['C4']['male_percent'] }}%</td>
                                            <td>{{ $gradingSummary['C4']['female_count'] }}</td>
                                            <td>{{ $gradingSummary['C4']['female_percent'] }}%</td>
                                            <td>{{ $gradingSummary['C4']['total'] }}</td>
                                        </tr>
                                        <tr class="table-warning fw-bold">
                                            <td colspan="2">Total</td>
                                            <td>{{ $totals['male_total'] }}</td>
                                            <td>{{ $totals['male_total'] > 0 ? round(($totals['male_total'] / $totals['overall_total']) * 100, 2) : 0 }}%
                                            </td>
                                            <td>{{ $totals['female_total'] }}</td>
                                            <td>{{ $totals['female_total'] > 0 ? round(($totals['female_total'] / $totals['overall_total']) * 100, 2) : 0 }}%
                                            </td>
                                            <td>{{ $totals['overall_total'] }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- 4- Students Failed -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h5 class="bg-light p-2 rounded d-flex justify-content-between align-items-center">
    <span>
        4- Students failed:
    </span>

</h5>

                                <table class="table table-bordered table-hover">
                                    <thead class="table-danger">
                                        <tr>
                                            <th>S/N</th>
                                            <th>{{ $levelName }}</th>
                                            <th>Male</th>
                                            <th>Female</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td>{{ $failedBreakdown['male_failed'] }}</td>
                                            <td>{{ $failedBreakdown['female_failed'] }}</td>
                                            <td>{{ $failedBreakdown['total_failed'] }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                <!-- Top 10 Students Table (For both O and A Levels) -->
                @if(isset($level) && in_array($level, ['O', 'A']))
                <div class="mt-5">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 style="color: #287c44;">
                                <i class="fas fa-star me-2"></i>
                                Top 10 Performing Students - {{ $levelName }}
                            </h5>
                            <div>
                            </div>
                        </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-dark" style="background-color: #287c44; color:#FFF;">
                                <tr>
                                    <th class="text-center" width="5%">Rank</th>
                                    <th class="text-center" width="10%">Student ID</th>
                                    <th class="text-center" width="25%">Student Name</th>
                                    <th class="text-center" width="25%">School</th>
                                    <th class="text-center" width="10%">Gender</th>
                                    <th class="text-center" width="15%">Total Marks</th>
                                    <th class="text-center" width="10%">Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($topStudents ?? [] as $index => $student)

                            <tr>
                                <td class="text-center fw-bold">
                                    @if($index == 0)
                                        <span class="badge bg-warning text-dark">🥇 1st</span>
                                    @elseif($index == 1)
                                        <span class="badge bg-secondary">🥈 2nd</span>
                                    @elseif($index == 2)
                                        <span class="badge bg-bronze" style="background-color: #cd7f32;">🥉 3rd</span>
                                    @else
                                        {{ $index + 1 }}th
                                    @endif
                                </td>
                                
                                <td class="text-center">{{ $student['student_id'] }}</td>
                                <td>{{ Helper::getStudentName($student['student_id'])  }}</td>
                                <td>{{ $student['school_name'] }}</td>
                                <td class="text-center">
                                    @if(strtolower($student['gender']) == 'male')
                                        <span class="badge bg-info">♂ Male</span>
                                    @else
                                        <span class="badge bg-danger">♀ Female</span>
                                    @endif
                                </td>
                                <td class="text-center fw-bold">{{ number_format($student['total_marks'], 2) }}</td>
                                <td class="text-center">
                                    <span class="badge" style="background-color: #287c44; color:#FFF;">
                                        {{ number_format($student['percentage'], 2) }}%
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="fas fa-info-circle me-2"></i>
                                    No student data available for the selected criteria.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Optional: Summary Statistics for Top 10 -->
                @if(!empty($topStudents))
                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="bg-light p-3 rounded">
                            <small class="text-muted">Average Percentage (Top 10)</small>
                            <h5 class="mb-0">
                                {{ number_format(collect($topStudents)->avg('percentage'), 2) }}%
                            </h5>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bg-light p-3 rounded">
                            <small class="text-muted">Gender Distribution</small>
                            <h5 class="mb-0">
                                {{ collect($topStudents)->where('gender', 'male')->count() }} Male / 
                                {{ collect($topStudents)->where('gender', 'female')->count() }} Female
                            </h5>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bg-light p-3 rounded">
                            <small class="text-muted">Top Score</small>
                            <h5 class="mb-0">{{ number_format($topStudents[0]['percentage'] ?? 0, 2) }}%</h5>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            @endif

            <!-- Top 10 Students Table (For both O and A Levels) -->
            @if(isset($level) && in_array($level, ['O', 'A']))
            <div class="mt-5">
                <h5 class="mb-3" style="color: #287c44;">
                    @if($level == 'O')
                        <i class="fas fa-graduation-cap me-2"></i>
                    @else
                        <i class="fas fa-university me-2"></i>
                    @endif
                    Top 10 Performing Students - {{ $levelName }}
                    @if($level == 'O')
                        <span class="badge bg-info ms-2">IDAAD (O Level)</span>
                    @else
                        <span class="badge bg-primary text-white ms-2">THANAWI (A Level)</span>
                    @endif
                </h5>
                
                <!-- ... rest of the table code ... -->
            </div>
            @endif

            <!-- Optional: Summary Statistics for Top 10 with Level Context -->
            @if(!empty($topStudents))
            <div class="row mt-3">
                <div class="col-md-3">
                    <div class="bg-light p-3 rounded">
                        <small class="text-muted">Level</small>
                        <h5 class="mb-0">
                            @if($level == 'O')
                                IDAAD (O Level)
                            @else
                                THANAWI (A Level)
                            @endif
                        </h5>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="bg-light p-3 rounded">
                        <small class="text-muted">Average (Top 10)</small>
                        <h5 class="mb-0">
                            {{ number_format(collect($topStudents)->avg('percentage'), 2) }}%
                        </h5>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="bg-light p-3 rounded">
                        <small class="text-muted">Gender Split</small>
                        <h5 class="mb-0">
                            {{ collect($topStudents)->where('gender', 'male')->count() }} M / 
                            {{ collect($topStudents)->where('gender', 'female')->count() }} F
                        </h5>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="bg-light p-3 rounded">
                        <small class="text-muted">Top Score</small>
                        <h5 class="mb-0">{{ number_format($topStudents[0]['percentage'] ?? 0, 2) }}%</h5>
                    </div>
                </div>
            </div>
            @endif

            <!-- Subject Performance Analysis - Best and Worst Subjects -->
                            @if(isset($level) && in_array($level, ['O', 'A']) && isset($bestSubjects) && isset($worstSubjects))
                            <div class="mt-5">
                            <h5 class="mb-3 d-flex justify-content-between align-items-center" style="color: #287c44;">
                                <span>
                                    <i class="fas fa-book-open me-2"></i>
                                    Subject Performance Analysis - {{ $levelName }}
                                </span>
                            </h5>

                                <div class="row">
                                    <!-- Best Performing Subjects -->
                                    <div class="col-md-6">
                                        <div class="card shadow-sm border-0 mb-4">
                                            <div class="card-header bg-success text-white">
                                                <h6 class="mb-0">
                                                    <i class="fas fa-trophy me-2"></i>
                                                    Top 10 Best Performing Subjects
                                                </h6>
                                            </div>
                                            <div class="card-body p-0">
                                                <div class="table-responsive">
                                                    <table class="table table-hover mb-0">
                                                        <thead class="bg-light">
                                                            <tr>
                                                                <th class="text-center" width="5%">Rank</th>
                                                                <th width="40%">Subject Name</th>
                                                                <th class="text-center" width="15%">Average</th>
                                                                <th class="text-center" width="15%">Highest</th>
                                                                <th class="text-center" width="15%">Pass Rate</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($bestSubjects as $index => $subject)
                                                            <tr>
                                                                <td class="text-center fw-bold">
                                                                    @if($index == 0)
                                                                        <span class="badge bg-warning text-dark">🥇 1st</span>
                                                                    @elseif($index == 1)
                                                                        <span class="badge bg-secondary">🥈 2nd</span>
                                                                    @elseif($index == 2)
                                                                        <span class="badge bg-bronze">🥉 3rd</span>
                                                                    @else
                                                                        {{ $index + 1 }}
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                
                                                                    <strong>{{Helper::item_md_name($subject['subject_name']) }} </strong>
                                                                    <br>
                                                                    <small class="text-muted">{{ $subject['student_count'] }} students</small>
                                                                </td>
                                                                <td class="text-center">
                                                                    <span class="badge bg-success">
                                                                        {{ number_format($subject['average'], 2) }}%
                                                                    </span>
                                                                </td>
                                                                <td class="text-center">
                                                                    <span class="badge bg-info">
                                                                        {{ $subject['highest'] }}%
                                                                    </span>
                                                                </td>
                                                                <td class="text-center">
                                                                    <span class="badge" style="background-color: #287c44; color:#FFF;">
                                                                        {{ $subject['pass_percentage'] }}%
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Worst Performing Subjects -->
                                    <div class="col-md-6">
                                        <div class="card shadow-sm border-0 mb-4">
                                            <div class="card-header bg-danger text-white">
                                                <h6 class="mb-0">
                                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                                    Top 10 Worst Performing Subjects
                                                </h6>
                                            </div>
                                            <div class="card-body p-0">
                                                <div class="table-responsive">
                                                    <table class="table table-hover mb-0">
                                                        <thead class="bg-light">
                                                            <tr>
                                                                <th class="text-center" width="5%">Rank</th>
                                                                <th width="40%">Subject Name</th>
                                                                <th class="text-center" width="15%">Average</th>
                                                                <th class="text-center" width="15%">Lowest</th>
                                                                <th class="text-center" width="15%">Pass Rate</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($worstSubjects as $index => $subject)
                                                            <tr>
                                                                <td class="text-center fw-bold">
                                                                    @if($index == 0)
                                                                        <span class="badge bg-danger">⚠️ 1st</span>
                                                                    @elseif($index == 1)
                                                                        <span class="badge bg-warning text-dark">⚠️ 2nd</span>
                                                                    @elseif($index == 2)
                                                                        <span class="badge bg-secondary">⚠️ 3rd</span>
                                                                    @else
                                                                        {{ $index + 1 }}
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <strong>{{ $subject['subject_name'] }}</strong>
                                                                    <br>
                                                                    <small class="text-muted">{{ $subject['student_count'] }} students</small>
                                                                </td>
                                                                <td class="text-center">
                                                                    <span class="badge bg-warning text-dark">
                                                                        {{ number_format($subject['average'], 2) }}%
                                                                    </span>
                                                                </td>
                                                                <td class="text-center">
                                                                    <span class="badge bg-danger">
                                                                        {{ $subject['lowest'] }}%
                                                                    </span>
                                                                </td>
                                                                <td class="text-center">
                                                                    <span class="badge" style="background-color: #dc3545;">
                                                                        {{ $subject['pass_percentage'] }}%
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Subject Performance Summary Stats -->
                                <div class="row mt-2">
                                                            <div class="col-md-3">
                                                                <div class="bg-light p-3 rounded">
                                                                    <small class="text-muted">Overall Subject Average</small>
                                                                    <h5 class="mb-0">
                                                                        {{ number_format(collect($bestSubjects)->merge($worstSubjects)->avg('average'), 2) }}%
                                                                    </h5>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="bg-light p-3 rounded">
                                                                    <small class="text-muted">Best Subject</small>
                                                                    <h5 class="mb-0">{{ $bestSubjects[0]['subject_name'] ?? 'N/A' }}</h5>
                                                                    <small>{{ $bestSubjects[0]['average'] ?? 0 }}% avg</small>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="bg-light p-3 rounded">
                                                                    <small class="text-muted">Worst Subject</small>
                                                                    <h5 class="mb-0">{{ $worstSubjects[0]['subject_name'] ?? 'N/A' }}</h5>
                                                                    <small>{{ $worstSubjects[0]['average'] ?? 0 }}% avg</small>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="bg-light p-3 rounded">
                                                                    <small class="text-muted">Total Subjects</small>
                                                                    <h5 class="mb-0">{{ count($bestSubjects) + count($worstSubjects) }}</h5>
                                                                </div>
                                                            </div>
                                </div>
                              </div>
                            @endif

                    @endif
                </div>
            </div>
        </div>
    </div>
                </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
function showMissingResourcesAlert() {
    Swal.fire({
        icon: 'error',
        title: 'Missing Required Resources',
        text: 'Some required resources are missing. Please update Server',
        confirmButtonColor: '#d33',
        confirmButtonText: 'OK'
    });
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const category = document.getElementById('categorySelect');
    const level = document.getElementById('levelSelect');

    function setLevelBasedOnCategory() {
        if (category.value === 'ID') {
            level.value = 'O';
        } else if (category.value === 'TH') {
            level.value = 'A';
        }

        level.disabled = true; // 🔥 disable after setting value
    }

    // When category changes
    category.addEventListener('change', setLevelBasedOnCategory);

    // Run once on page load
    setLevelBasedOnCategory();
});

function setLevelBasedOnCategory() {
    if (category.value === 'ID') {
        level.value = 'O';
    } else if (category.value === 'TH') {
        level.value = 'A';
    }

    level.disabled = true; // prevents manual change
}
</script>

<script>
$(document).ready(function() {
    // Show appropriate icon/message based on level selection
    $('select[name="level"]').on('change', function() {
        const selectedLevel = $(this).val();
        const levelText = selectedLevel === 'O' ? 'IDAAD (O Level)' : 'THANAWI (A Level)';
        
        // Optional: Update some preview text
        console.log('Selected level:', levelText);
    });
});

// Update the form submission handler
$('#examStatisticsForm').on('submit', function(e) {
    const level = $('select[name="level"]').val();
    const category = $('select[name="category"]').val();
    
    if (level && category) {
        const levelName = level === 'O' ? 'IDAAD (O Level)' : 'THANAWI (A Level)';
        
        Swal.fire({
            title: 'Loading Top Students...',
            html: `Fetching top 10 performers for <strong>${levelName}</strong>`,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }
});
</script>

<!-- Add this JavaScript function at the bottom of your blade file -->
<script>
function downloadStatistics() {

    const year = $('select[name="year"]').val();
    const category = $('select[name="category"]').val();
    const level = $('select[name="level"]').val();

    if (!year || !category) {
        Swal.fire({
            icon: 'warning',
            title: 'Missing Information',
            text: 'Please select Year and Category before downloading.',
            confirmButtonColor: '#17a2b8'
        });
        return;
    }

    // Show loading
    Swal.fire({
        title: 'Generating Download...',
        text: 'Please wait while we prepare your file.',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Create hidden iframe
    let iframe = document.createElement('iframe');
    iframe.style.display = 'none';
    document.body.appendChild(iframe);

    iframe.onload = function () {
        Swal.close(); // Close ONLY when server responds
        document.body.removeChild(iframe);
    };

    // Create form
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("iteb.exam.statistics.download") }}';
    form.target = iframe.name = "downloadFrame";

    // CSRF
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = '{{ csrf_token() }}';
    form.appendChild(csrfInput);

    // Year
    const yearInput = document.createElement('input');
    yearInput.type = 'hidden';
    yearInput.name = 'year';
    yearInput.value = year;
    form.appendChild(yearInput);

    // Category
    const categoryInput = document.createElement('input');
    categoryInput.type = 'hidden';
    categoryInput.name = 'category';
    categoryInput.value = category;
    form.appendChild(categoryInput);

    // Level
    const levelInput = document.createElement('input');
    levelInput.type = 'hidden';
    levelInput.name = 'level';
    levelInput.value = level;
    form.appendChild(levelInput);

    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}
</script>

<script>
function downloadExcel() {
    downloadFile('excel');
}

function downloadPdf() {
    downloadFile('pdf');
}

function downloadFile(type) {
    // Get the current form values
    const year = $('select[name="year"]').val();
    const category = $('select[name="category"]').val();
    const level = $('select[name="level"]').val();
    
    if (!year || !category) {
        Swal.fire({
            icon: 'warning',
            title: 'Missing Information',
            text: 'Please select Year and Category before downloading.',
            confirmButtonColor: '#17a2b8'
        });
        return;
    }
    
    // Determine the route based on type
    const route = type === 'excel' 
        ? '{{ route("iteb.exam.statistics.download.excel") }}'
        : '{{ route("iteb.exam.statistics.download.pdf") }}';
    
    // Create a form and submit it to download
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = route;
    
    // Add CSRF token
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = '{{ csrf_token() }}';
    form.appendChild(csrfInput);
    
    // Add year
    const yearInput = document.createElement('input');
    yearInput.type = 'hidden';
    yearInput.name = 'year';
    yearInput.value = year;
    form.appendChild(yearInput);
    
    // Add category
    const categoryInput = document.createElement('input');
    categoryInput.type = 'hidden';
    categoryInput.name = 'category';
    categoryInput.value = category;
    form.appendChild(categoryInput);
    
    // Add level
    const levelInput = document.createElement('input');
    levelInput.type = 'hidden';
    levelInput.name = 'level';
    levelInput.value = level;
    form.appendChild(levelInput);
    
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
    
    // Show loading message
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
    
    // Close the loading message after a delay
    setTimeout(() => {
        Swal.close();
    }, 3000);
}
</script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
