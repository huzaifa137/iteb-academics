<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Submitted Students</title>
    <style>
        @page { margin: 90px 25px 50px 25px; }

        body {
            font-family: sans-serif;
            font-size: 11px;
            color: #1f2937;
        }

        header {
            position: fixed;
            top: -70px;
            left: 0;
            right: 0;
            height: 70px;
            border-bottom: 2px solid #0d4b1f;
            padding-bottom: 8px;
        }

        header .title {
            color: #0d4b1f;
            font-size: 18px;
            font-weight: bold;
            margin: 0;
        }

        header .subtitle {
            color: #64748b;
            font-size: 11px;
            margin: 2px 0 0 0;
        }

        footer {
            position: fixed;
            bottom: -35px;
            left: 0;
            right: 0;
            height: 25px;
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 6px;
        }

        table.students {
            width: 100%;
            border-collapse: collapse;
        }

        table.students thead th {
            background: #0d4b1f;
            color: #fff;
            padding: 6px 8px;
            font-size: 10px;
            text-align: left;
        }

        table.students tbody td {
            padding: 5px 8px;
            font-size: 10px;
            border-bottom: 1px solid #e5e7eb;
        }

        table.students tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: bold;
        }

        .status-pending { background: #ffc107; color: #000; }
        .status-approved { background: #22c55e; color: #fff; }
        .status-returned { background: #ef4444; color: #fff; }
    </style>
</head>
<body>
    <header>
        <p class="title">ITEB ACADEMICS — Submitted Student Information</p>
        <p class="subtitle">{{ $school->House ?? 'School' }} @if(!empty($school->Number)) ({{ $school->Number }}) @endif &middot; {{ $students->count() }} student(s)</p>
    </header>

    <footer>
        Generated on {{ $generated_at }} &middot; ITEB ACADEMICS
    </footer>

    <table class="students">
        <thead>
            <tr>
                <th>#</th>
                <th>Student ID</th>
                <th>Name</th>
                <th>Category</th>
                <th>Class</th>
                <th>Section</th>
                <th>Admission Year</th>
                <th>District</th>
                <th>Status</th>
                <th>Submitted On</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $i => $reg)
                @php
                    $statusClass = 'status-pending';
                    if ($reg->status === 'Approved') $statusClass = 'status-approved';
                    elseif ($reg->status === 'Returned') $statusClass = 'status-returned';

                    // submitted_at may be null on registrations submitted before that
                    // column existed — fall back to created_at so the date still shows.
                    $submittedOn = $reg->submitted_at ?? $reg->created_at;
                @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $reg->student_id }}</td>
                    <td>{{ $reg->student_name }}</td>
                    <td>{{ $reg->category === 'ID' ? 'Idaad' : 'Thanawi' }}</td>
                    <td>{{ $reg->class ?? '—' }}</td>
                    <td>{{ $reg->section ?? '—' }}</td>
                    <td>{{ $reg->admission_year }}</td>
                    <td>{{ $reg->district ?? '—' }}</td>
                    <td><span class="status-badge {{ $statusClass }}">{{ $reg->status }}</span></td>
                    <td>{{ $submittedOn ? $submittedOn->format('d/m/Y H:i') : '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align:center; padding: 20px;">No submitted students found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
