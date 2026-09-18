<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>All Students</title>
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
    </style>
</head>
<body>
    <header>
        <p class="title">ITEB ACADEMICS — All Students</p>
        <p class="subtitle">
            @if($houseName) House: {{ $houseName }} &middot; @endif
            @if($year) Year: {{ $year }} &middot; @endif
            @if($type) Category: {{ ucfirst($type) }} &middot; @endif
            {{ $students->count() }} student(s)
        </p>
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
                <th>Gender</th>
                <th>House</th>
                <th>Class</th>
                <th>Section</th>
                <th>Admission Year</th>
                <th>District</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $i => $student)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $student->Student_ID }}</td>
                    <td>{{ $student->Student_Name }}</td>
                    <td>{{ $student->StudentSex }}</td>
                    <td>{{ $student->House }}</td>
                    <td>{{ $student->Class }}</td>
                    <td>{{ $student->Section }}</td>
                    <td>{{ $student->admnyr }}</td>
                    <td>{{ $student->District }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align:center; padding: 20px;">No students found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
