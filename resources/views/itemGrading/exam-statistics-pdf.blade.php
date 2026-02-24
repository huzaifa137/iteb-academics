<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Statistics Report - {{ $year }} - {{ $category }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #17a2b8;
            color: white;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
        }

        .header h3 {
            margin: 5px 0 0;
            font-size: 14px;
            font-weight: normal;
        }

        .section {
            margin-bottom: 20px;
        }

        .section-title {
            background-color: #f0f0f0;
            padding: 8px;
            margin: 15px 0 10px;
            font-weight: bold;
            font-size: 13px;
            border-left: 4px solid #17a2b8;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th {
            background-color: #17a2b8;
            color: white;
            padding: 8px;
            text-align: center;
            font-weight: bold;
            font-size: 11px;
        }

        td {
            padding: 6px 8px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .badge {
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }

        .bg-success {
            background-color: #28a745;
            color: white;
        }

        .bg-warning {
            background-color: #ffc107;
            color: #333;
        }

        .bg-danger {
            background-color: #dc3545;
            color: white;
        }

        .bg-info {
            background-color: #17a2b8;
            color: white;
        }

        .summary-card {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 10px;
            margin-top: 10px;
        }

        .summary-item {
            display: inline-block;
            margin-right: 20px;
        }

        .summary-label {
            font-size: 10px;
            color: #6c757d;
        }

        .summary-value {
            font-size: 14px;
            font-weight: bold;
            color: #17a2b8;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 9px;
            color: #6c757d;
        }

        .rank-1 {
            background-color: #ffd700;
            color: #000;
        }

        .rank-2 {
            background-color: #c0c0c0;
            color: #000;
        }

        .rank-3 {
            background-color: #cd7f32;
            color: #000;
        }

        .page-break {
            page-break-after: always;
        }

        .watermark {
            position: fixed;
            bottom: 10px;
            right: 10px;
            opacity: 0.1;
            font-size: 50px;
            transform: rotate(-45deg);
            z-index: -1;
        }
    </style>
</head>

<body>
    <!-- <div class="watermark">OFFICIAL REPORT</div> -->
    <?php
use App\Http\Controllers\Helper;
?>
    <!-- Header -->
    <div class="header">
        <h1>EXAM STATISTICS REPORT</h1>
        <h3>{{ $year }} - {{ $category }} - {{ $levelName }}</h3>
    </div>

    <!-- 1. Schools Registered -->
    <div class="section">
        <div class="section-title">1. NUMBER OF SCHOOLS REGISTERED</div>
        <table>
            <thead>
                <tr>
                    <th width="10%">S/N</th>
                    <th width="90%">{{ $levelName }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">1</td>
                    <td class="text-center">{{ $schoolsCount }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- 2. Students Registered -->
    <div class="section">
        <div class="section-title">2. NUMBER OF STUDENTS REGISTERED</div>
        <table>
            <thead>
                <tr>
                    <th width="10%">S/N</th>
                    <th width="45%">{{ $levelName }}</th>
                    <th width="45%">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">1</td>
                    <td class="text-center">{{ $registeredStudents }}</td>
                    <td class="text-center">{{ $registeredStudents }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- 3. Grading Summary -->
    <div class="section">
        <div class="section-title">3. GRADING SUMMARY - {{ $levelName }}</div>
        <table>
            <thead>
                <tr>
                    <th width="8%">S/N</th>
                    <th width="27%">Grade</th>
                    <th width="13%">Male</th>
                    <th width="13%">%</th>
                    <th width="13%">Female</th>
                    <th width="13%">%</th>
                    <th width="13%">Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $grades = ['D1', 'D2', 'C3', 'C4'];
                    $labels = ['Excellent D1', 'Very good D2', 'Good C3', 'Pass C4'];
                    $serial = ['a', 'b', 'c', 'd'];
                @endphp

                @foreach($grades as $index => $grade)
                    @if(isset($gradingSummary[$grade]))
                        <tr>
                            <td class="text-center">{{ $serial[$index] }}.</td>
                            <td>{{ $labels[$index] }}</td>
                            <td class="text-center">{{ $gradingSummary[$grade]['male_count'] }}</td>
                            <td class="text-center">{{ $gradingSummary[$grade]['male_percent'] }}%</td>
                            <td class="text-center">{{ $gradingSummary[$grade]['female_count'] }}</td>
                            <td class="text-center">{{ $gradingSummary[$grade]['female_percent'] }}%</td>
                            <td class="text-center">{{ $gradingSummary[$grade]['total'] }}</td>
                        </tr>
                    @endif
                @endforeach

                <tr style="background-color: #f0f0f0; font-weight: bold;">
                    <td colspan="2" class="text-right">Total</td>
                    <td class="text-center">{{ $totals['male_total'] }}</td>
                    <td class="text-center">
                        {{ $totals['male_total'] > 0 ? round(($totals['male_total'] / $totals['overall_total']) * 100, 2) : 0 }}%
                    </td>
                    <td class="text-center">{{ $totals['female_total'] }}</td>
                    <td class="text-center">
                        {{ $totals['female_total'] > 0 ? round(($totals['female_total'] / $totals['overall_total']) * 100, 2) : 0 }}%
                    </td>
                    <td class="text-center">{{ $totals['overall_total'] }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- 4. Failed Students -->
    <div class="section">
        <div class="section-title">4. STUDENTS FAILED</div>
        <table>
            <thead>
                <tr>
                    <th width="10%">S/N</th>
                    <th width="40%">{{ $levelName }}</th>
                    <th width="15%">Male</th>
                    <th width="15%">Female</th>
                    <th width="20%">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">1</td>
                    <td></td>
                    <td class="text-center">{{ $failedBreakdown['male_failed'] }}</td>
                    <td class="text-center">{{ $failedBreakdown['female_failed'] }}</td>
                    <td class="text-center">{{ $failedBreakdown['total_failed'] }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Summary Cards -->
    <div style="margin: 20px 0;">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="border: none; width: 33%;">
                    <div
                        style="background-color: #17a2b8; color: white; padding: 10px; border-radius: 4px; text-align: center;">
                        <div style="font-size: 11px;">Registered Students</div>
                        <div style="font-size: 18px; font-weight: bold;">{{ $registeredStudents }}</div>
                    </div>
                </td>
                <td style="border: none; width: 33%;">
                    <div
                        style="background-color: #28a745; color: white; padding: 10px; border-radius: 4px; text-align: center;">
                        <div style="font-size: 11px;">Graded Students</div>
                        <div style="font-size: 18px; font-weight: bold;">{{ $totals['overall_total'] }}</div>
                    </div>
                </td>
                <td style="border: none; width: 33%;">
                    <div
                        style="background-color: #dc3545; color: white; padding: 10px; border-radius: 4px; text-align: center;">
                        <div style="font-size: 11px;">Failed Students</div>
                        <div style="font-size: 18px; font-weight: bold;">{{ $failedBreakdown['total_failed'] }}</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="page-break"></div>

    <!-- Top 10 Students -->
    <div class="section">
        <div class="section-title">5. TOP 10 PERFORMING STUDENTS - {{ $levelName }}</div>
        <table>
            <thead>
                <tr>
                    <th width="8%">Rank</th>
                    <th width="12%">Student ID</th>
                    <th width="20%">Name</th>
                    <th width="20%">School</th>
                    <th width="8%">Gender</th>
                    <th width="12%">Total Marks</th>
                    <th width="10%">Percentage</th>
                    <th width="10%">Grade</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topStudents as $index => $student)
                    <tr>
                        <td class="text-center">
                            @if($index == 0)
                                <span class="badge" style="background-color: #ffd700;">1st</span>
                            @elseif($index == 1)
                                <span class="badge" style="background-color: #c0c0c0;">2nd</span>
                            @elseif($index == 2)
                                <span class="badge" style="background-color: #cd7f32;">3rd</span>
                            @else
                                {{ $index + 1 }}th
                            @endif
                        </td>
                        <td>{{ $student['student_id'] }}</td>
                        <td>{{ Helper::getStudentName($student['student_id'])  }}</td>
                        <td>{{ $student['school_name'] }}</td>
                        <td class="text-center text-white">
                            @if(strtolower($student['gender']) == 'male')
                                <span class="badge bg-info text-white">♂ Male</span>
                            @else
                                <span class="badge bg-danger text-white">♀ Female</span>
                            @endif
                        </td>
                        <td class="text-center">{{ number_format($student['total_marks'], 2) }}</td>
                        <td class="text-center">
                            <span class="badge bg-success">{{ number_format($student['percentage'], 2) }}%</span>
                        </td>
                        <td class="text-center">{{ $student['grade'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Top 10 Summary -->
        <div class="summary-card">
            <div style="display: flex; justify-content: space-between;">
                <div class="summary-item">
                    <div class="summary-label">Average Percentage</div>
                    <div class="summary-value">{{ number_format(collect($topStudents)->avg('percentage'), 2) }}%</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Gender Distribution</div>
                    <div class="summary-value">
                        {{ collect($topStudents)->where('gender', 'male')->count() }} Male /
                        {{ collect($topStudents)->where('gender', 'female')->count() }} Female
                    </div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Top Score</div>
                    <div class="summary-value">
                        {{ isset($topStudents[0]) ? number_format($topStudents[0]['percentage'], 2) . '%' : '0%' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-break"></div>

    <!-- Subject Performance -->
    <div class="section">
        <div class="section-title">6. TOP 10 BEST PERFORMING SUBJECTS</div>
        <table>
            <thead>
                <tr>
                    <th width="8%">Rank</th>
                    <th width="37%">Subject Name</th>
                    <th width="15%">Students</th>
                    <th width="15%">Average %</th>
                    <th width="15%">Highest %</th>
                    <th width="10%">Pass Rate %</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bestSubjects as $index => $subject)
                    <tr>
                        <td class="text-center">
                            @if($index == 0)<span class="badge bg-warning">1st</span>
                            @elseif($index == 1)<span class="badge bg-secondary">2nd</span>
                            @elseif($index == 2)<span class="badge" style="background-color: #cd7f32;">3rd</span>
                            @else{{ $index + 1 }}@endif
                        </td>
                        <strong>{{Helper::item_md_name($subject['subject_id']) }} </strong>
                        <td class="text-center">{{ $subject['student_count'] }}</td>
                        <td class="text-center"><span
                                class="badge bg-success">{{ number_format($subject['average'], 2) }}%</span></td>
                        <td class="text-center"><span class="badge bg-info">{{ $subject['highest'] }}%</span></td>
                        <td class="text-center"><span class="badge bg-success">{{ $subject['pass_percentage'] }}%</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">7. TOP 10 WORST PERFORMING SUBJECTS</div>
        <table>
            <thead>
                <tr>
                    <th width="8%">Rank</th>
                    <th width="37%">Subject Name</th>
                    <th width="15%">Students</th>
                    <th width="15%">Average %</th>
                    <th width="15%">Lowest %</th>
                    <th width="10%">Pass Rate %</th>
                </tr>
            </thead>
            <tbody>
                @foreach($worstSubjects as $index => $subject)
                    <tr>
                        <td class="text-center">
                            @if($index == 0)<span class="badge bg-danger">1st</span>
                            @elseif($index == 1)<span class="badge bg-warning">2nd</span>
                            @elseif($index == 2)<span class="badge bg-secondary">3rd</span>
                            @else{{ $index + 1 }}@endif
                        </td>
                        <td><strong>{{ Helper::item_md_name($subject['subject_id']) }}</strong></td>
                        <td class="text-center">{{ $subject['student_count'] }}</td>
                        <td class="text-center"><span
                                class="badge bg-warning">{{ number_format($subject['average'], 2) }}%</span></td>
                        <td class="text-center"><span class="badge bg-danger">{{ $subject['lowest'] }}%</span></td>
                        <td class="text-center"><span class="badge bg-danger">{{ $subject['pass_percentage'] }}%</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Subject Performance Summary -->
    <div class="summary-card" style="margin-top: 20px;">
        <div style="display: flex; justify-content: space-between;">
            <div class="summary-item">
                <div class="summary-label">Overall Subject Average</div>
                <div class="summary-value">
                    {{ number_format(collect($bestSubjects)->merge($worstSubjects)->avg('average'), 2) }}%
                </div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Best Subject</div>
                <div class="summary-value">
                    {{ Helper::item_md_name($bestSubjects[0]['subject_id'])  ?? 'N/A' }} ({{ $bestSubjects[0]['average'] ?? 0 }}%)
                </div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Worst Subject</div>
                <div class="summary-value">
                    {{  Helper::item_md_name($worstSubjects[0]['subject_id']) ?? 'N/A' }} ({{ $worstSubjects[0]['average'] ?? 0 }}%)
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        Generated on: {{ date('F j, Y, g:i a') }} | Report ID: {{ uniqid() }}
    </div>
</body>

</html>