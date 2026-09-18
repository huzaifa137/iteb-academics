<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Submitted Students</title>
    <style>
        @page { margin: 90px 30px 60px 30px; }

        body {
            font-family: sans-serif;
            font-size: 12px;
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
            bottom: -40px;
            left: 0;
            right: 0;
            height: 30px;
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 6px;
        }

        .student-card {
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 14px;
            margin-bottom: 16px;
            page-break-inside: avoid;
        }

        .student-card table.layout {
            width: 100%;
            border-collapse: collapse;
        }

        .photo-cell {
            width: 90px;
            vertical-align: top;
        }

        .photo-cell img {
            width: 80px;
            height: 100px;
            object-fit: cover;
            border: 2px solid #287C44;
            border-radius: 4px;
        }

        .info-cell {
            vertical-align: top;
            padding-left: 14px;
        }

        .student-name {
            font-size: 15px;
            font-weight: bold;
            color: #0d4b1f;
            margin: 0 0 1px 0;
        }

        .student-name-ar {
            font-size: 13px;
            color: #475569;
            margin: 0 0 6px 0;
        }

        .student-id {
            display: inline-block;
            background: #e8f5e9;
            color: #0d4b1f;
            font-weight: bold;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 11px;
            margin-bottom: 8px;
        }

        table.details {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }

        table.details td {
            padding: 3px 6px 3px 0;
            font-size: 11px;
            vertical-align: top;
        }

        table.details td.label {
            color: #64748b;
            width: 100px;
        }

        table.details td.value {
            font-weight: bold;
            color: #111827;
        }

        .status-badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 12px;
            font-size: 10px;
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
        <p class="subtitle">{{ $school->House ?? 'School' }} @if(!empty($school->Number)) ({{ $school->Number }}) @endif</p>
    </header>

    <footer>
        Generated on {{ $generated_at }} &middot; {{ $students->count() }} student(s) &middot; ITEB ACADEMICS
    </footer>

    @foreach($students as $reg)
        @php
            $photoPath = public_path('assets/student_photos/' . $reg->student_id . '.jpg');
            $photoSrc = file_exists($photoPath) ? $photoPath : public_path('assets/images/default-user.jpg');

            $statusClass = 'status-pending';
            if ($reg->status === 'Approved') $statusClass = 'status-approved';
            elseif ($reg->status === 'Returned') $statusClass = 'status-returned';
        @endphp

        <div class="student-card">
            <table class="layout">
                <tr>
                    <!-- <td class="photo-cell">
                        <img src="{{ $photoSrc }}">
                    </td> -->
                    <td class="info-cell">
                        <p class="student-name">{{ $reg->student_name }}</p>
                        @if($reg->student_name_ar)
                            <p class="student-name-ar">{{ $reg->student_name_ar }}</p>
                        @endif
                        <span class="student-id">{{ $reg->student_id }}</span>
                        <span class="status-badge {{ $statusClass }}">{{ $reg->status }}</span>

                        <table class="details">
                            <tr>
                                <td class="label">Category</td>
                                <td class="value">{{ $reg->category === 'ID' ? 'Idaad' : 'Thanawi' }}</td>
                                <td class="label">Admission Year</td>
                                <td class="value">{{ $reg->admission_year }}</td>
                            </tr>
                            <tr>
                                <td class="label">Gender</td>
                                <td class="value">{{ $reg->student_sex }}</td>
                                <td class="label">Date of Birth</td>
                                <td class="value">{{ $reg->date_of_birth ? \Carbon\Carbon::parse($reg->date_of_birth)->format('d/m/Y') : '—' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Nationality</td>
                                <td class="value">{{ $reg->student_nationality ?? '—' }}</td>
                                <td class="label">Birth Place</td>
                                <td class="value">{{ $reg->birth_place ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Class</td>
                                <td class="value">{{ $reg->class ?? '—' }}</td>
                                <td class="label">Section</td>
                                <td class="value">{{ $reg->section ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td class="label">District</td>
                                <td class="value">{{ $reg->district ?? '—' }}</td>
                                <td class="label">Submitted On</td>
                                <td class="value">{{ $reg->submitted_at ? $reg->submitted_at->format('d/m/Y H:i') : '—' }}</td>
                            </tr>
                            @if($reg->admin_remarks)
                                <tr>
                                    <td class="label">Admin Remarks</td>
                                    <td class="value" colspan="3">{{ $reg->admin_remarks }}</td>
                                </tr>
                            @endif
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    @endforeach
</body>
</html>
