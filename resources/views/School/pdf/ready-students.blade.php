<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ready Students</title>
    <style>
        @page { margin: 95px 28px 50px 28px; }

        body {
            font-family: sans-serif;
            font-size: 10px;
            color: #1f2937;
        }

        header {
            position: fixed;
            top: -75px;
            left: 0;
            right: 0;
            height: 70px;
            border-bottom: 2px solid #0d4b1f;
            padding-bottom: 6px;
        }

        header .title { color: #0d4b1f; font-size: 18px; font-weight: bold; margin: 0; }
        header .subtitle { color: #64748b; font-size: 10px; margin: 3px 0 0 0; }

        footer {
            position: fixed;
            bottom: -38px;
            left: 0;
            right: 0;
            height: 25px;
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 6px;
        }

        .page-number:before { content: "Page " counter(page); }

        h2.section {
            color: #0d4b1f;
            font-size: 13px;
            margin: 16px 0 6px 0;
            padding-bottom: 3px;
            border-bottom: 1px solid #cbd5e1;
        }

        /* Summary cards (table based - dompdf has no flex/grid) */
        table.cards { width: 100%; border-collapse: separate; border-spacing: 8px 0; margin: 0 -8px 4px -8px; }
        table.cards td {
            width: 25%;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 8px 10px;
            text-align: center;
            background: #f8fafc;
        }
        .card-n { font-size: 20px; font-weight: bold; }
        .card-l { font-size: 9px; color: #64748b; margin-top: 2px; }
        .c-green { color: #15803d; }
        .c-amber { color: #b45309; }
        .c-red { color: #b91c1c; }
        .c-blue { color: #1d4ed8; }

        table.grid { width: 100%; border-collapse: collapse; }
        table.grid thead th {
            background: #0d4b1f;
            color: #fff;
            padding: 6px 6px;
            font-size: 9px;
            text-align: left;
        }
        table.grid tbody td {
            padding: 4px 6px;
            font-size: 9px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
        }
        table.grid tbody tr:nth-child(even) { background: #f8fafc; }
        table.grid tr { page-break-inside: avoid; }
        td.num, th.num { text-align: center; }
        tr.zero td { background: #fdecea !important; color: #b71c1c; font-weight: bold; }

        .school-bar {
            background: #e8f5e9;
            border-left: 4px solid #287C44;
            padding: 6px 10px;
            margin: 14px 0 5px 0;
            font-size: 11px;
            font-weight: bold;
            color: #0d4b1f;
            page-break-after: avoid;
        }
        .school-bar span { font-weight: normal; color: #475569; font-size: 9px; }

        .reason {
            display: inline-block;
            background: #fee2e2;
            color: #991b1b;
            padding: 1px 6px;
            border-radius: 8px;
            font-size: 8.5px;
            font-weight: bold;
        }

        .note { font-size: 9px; color: #64748b; margin: 4px 0 0 0; }
        .empty { text-align: center; padding: 18px; color: #64748b; }
    </style>
</head>
<body>
    <header>
        <p class="title">ITEB ACADEMICS — Schools with Incomplete Details / No Photo</p>
        <p class="subtitle">
            Generated {{ $generated_at }}
            &middot; Slot year {{ $slotYear }}
            @foreach($filters as $label => $value)
                &middot; {{ $label }}: {{ $value }}
            @endforeach
            &middot; {{ $strict ? 'Complete details required' : 'Optional details not required' }}
        </p>
    </header>

    <footer>
        ITEB ACADEMICS &middot; Incomplete / no-photo report &middot; <span class="page-number"></span>
    </footer>

    {{-- ═════════ SUMMARY ═════════ --}}
    <table class="cards">
        <tr>
            <td><div class="card-n c-green">{{ number_format($totals['ready']) }}</div><div class="card-l">Ready (all schools)</div></td>
            <td><div class="card-n c-amber">{{ number_format($totals['incomplete']) }}</div><div class="card-l">Incomplete details</div></td>
            <td><div class="card-n c-red">{{ number_format($totals['no_photo']) }}</div><div class="card-l">No photo</div></td>
            <td><div class="card-n c-blue">{{ number_format(count($schools)) }}</div><div class="card-l">Schools listed</div></td>
        </tr>
    </table>

    <h2 class="section">Schools with incomplete details / no photo</h2>
    <table class="grid">
        <thead>
            <tr>
                <th style="width:4%;" class="num">#</th>
                <th>School</th>
                <th class="num" style="width:16%;">Students / (Slots)</th>
                <th class="num" style="width:10%;">Ready</th>
                <th class="num" style="width:10%;">Incomplete</th>
                <th class="num" style="width:10%;">No photo</th>
            </tr>
        </thead>
        <tbody>
            @forelse($schools as $i => $s)
                <tr class="{{ !empty($s['no_students']) ? 'zero' : '' }}">
                    <td class="num">{{ $i + 1 }}</td>
                    <td>{{ $s['school_name'] }}</td>
                    <td class="num">{{ $s['students'] }} / ({{ $s['slots'] ?? '–' }})</td>
                    <td class="num">{{ $s['ready'] }}</td>
                    <td class="num">{{ $s['incomplete'] }}</td>
                    <td class="num">{{ $s['no_photo'] }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="empty">No school has incomplete details or missing photos.</td></tr>
            @endforelse
        </tbody>
    </table>
    <p class="note">
        Students = already sent to admin + ready now, for {{ $slotYear }}; Slots = allocated slots.
    </p>

    {{-- ═════════ NOT READY ═════════ --}}
    @if(count($notReady))
        <h2 class="section" style="page-break-before: always;">Students needing attention ({{ number_format(count($notReady)) }})</h2>
        <table class="grid">
            <thead>
                <tr>
                    <th class="num" style="width:3%;">#</th>
                    <th style="width:22%;">School</th>
                    <th style="width:12%;">Student ID</th>
                    <th style="width:20%;">Name</th>
                    <th class="num" style="width:5%;">Sex</th>
                    <th style="width:7%;">Category</th>
                    <th>Reason</th>
                </tr>
            </thead>
            <tbody>
                @foreach($notReady as $i => $st)
                    <tr>
                        <td class="num">{{ $i + 1 }}</td>
                        <td>{{ $st['school'] }}</td>
                        <td>{{ $st['student_id'] }}</td>
                        <td>{{ $st['name'] ?: '—' }}</td>
                        <td class="num">{{ $st['sex'] ?: '—' }}</td>
                        <td>{{ $st['category'] }}</td>
                        <td><span class="reason">{{ $st['reason'] }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
