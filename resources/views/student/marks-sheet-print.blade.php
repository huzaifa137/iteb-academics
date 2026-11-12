<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="utf-8">
    <title>كشف أسماء الطلبة - الامتحان النهائي</title>
    {{--
        Marks Sheet. Plain HTML printed by the browser (Save as PDF), the same approach as the
        bulk attendance sheet, so the browser shapes and joins the Arabic letters correctly.
    --}}
    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: #fff;
            color: #000;
            direction: rtl;
            font-family: 'Traditional Arabic', 'Times New Roman', Arial, Tahoma, sans-serif;
            font-size: 15px;
        }

        #toolbar {
            direction: ltr;
            font-family: Arial, sans-serif;
            padding: 8px 12px;
            background: #0d4b1f;
            color: #fff;
            font-size: 13px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        #toolbar button {
            background: #fff;
            color: #0d4b1f;
            border: 0;
            border-radius: 4px;
            padding: 5px 12px;
            font-weight: bold;
            cursor: pointer;
        }

        #sheets { width: 210mm; background: #fff; margin: 0 auto; }

        .sheet-page {
            width: 210mm;
            height: 296mm;
            padding: 9mm 11mm 8mm 11mm;
            overflow: hidden;
            page-break-after: always;
            position: relative;
        }

        .sheet-page:last-child { page-break-after: auto; }

        .bismillah {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            margin: 0 0 3mm 0;
        }

        table.masthead { width: 100%; border-collapse: collapse; margin-bottom: 3mm; }
        table.masthead td { width: 33.33%; vertical-align: middle; }
        table.masthead .en-title {
            direction: ltr; text-align: left; font-family: 'Times New Roman', serif;
            font-weight: bold; font-size: 16px; line-height: 1.35;
        }
        table.masthead .ar-title { text-align: right; font-weight: bold; font-size: 20px; line-height: 1.5; }
        table.masthead .logo-cell { text-align: center; }
        table.masthead .logo-cell img { width: 22mm; height: 22mm; object-fit: contain; }

        .sheet-title {
            border: 1px solid #000;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            padding: 1.5mm 0;
            margin: 0;
        }

        table.info { width: 100%; border-collapse: collapse; margin-bottom: 4mm; border: 1px solid #000; border-top: 0; }
        table.info td { width: 50%; vertical-align: top; padding: 1.5mm 3mm; font-size: 16px; line-height: 1.55; }
        table.info b { font-weight: bold; }

        /* ---- marks table: [row no.] [student no.] [1 .. N] [total] ---- */
        table.marks { width: 100%; border-collapse: collapse; table-layout: fixed; }
        table.marks th, table.marks td {
            border: 1px solid #000;
            text-align: center;
            font-size: 14px;
            height: 6.3mm;
            padding: 0;
            overflow: hidden;
        }
        table.marks thead th { font-weight: bold; height: 7.5mm; font-size: 13px; white-space: nowrap; }
        table.marks .rowno { font-family: Arial, sans-serif; font-size: 13px; }
        table.marks td.sid { direction: ltr; font-family: Arial, sans-serif; font-size: 12.5px; white-space: nowrap; }
        table.marks th.qh { font-size: 15px; }

        /* ---- markers / checkers block ---- */
        table.sign { width: 100%; border-collapse: collapse; table-layout: fixed; margin-top: 4mm; }
        table.sign th, table.sign td {
            border: 1px solid #000;
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            height: 8mm;
            padding: 0 1mm;
        }
        table.sign td.blank { font-weight: normal; }

        @page { size: A4 portrait; margin: 0; }

        @media print {
            #toolbar { display: none !important; }

            html, body {
                width: 100%;
                margin: 0 !important;
                padding: 0 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            #sheets { width: 100%; margin: 0; }

            .sheet-page {
                width: 100%;
                height: auto;
                min-height: 0;
                overflow: visible;
                break-after: page;
                page-break-after: always;
                break-inside: avoid;
                page-break-inside: avoid;
            }
            .sheet-page:last-child { break-after: auto; page-break-after: auto; }
        }
    </style>
</head>
<body>
@php
    $perPage = 25;
    $questions = max(1, min(20, (int) ($questions ?? 15)));
    $arYear = \App\Http\Controllers\Helper::toArabicNumberDate($year);
    $arDate = \App\Http\Controllers\Helper::toArabicNumberDate($examDate);
    $fmtTime = function ($t) {
        $t = trim((string) $t);
        $t = substr($t, 0, 5);
        return preg_match('/^\d{1,2}:\d{2}$/', $t) ? $t . ':00' : $t;
    };
    $timeText = ($startTime || $endTime) ? trim($fmtTime($startTime) . ' - ' . $fmtTime($endTime), ' -') : '';

    // column widths (%): row no. 4, student no. 24, total 9, the rest shared by the question columns
    $qWidth = round(63 / $questions, 3);

    $totalPages = 0;
    foreach ($sheets as $sheet) {
        $totalPages += (int) ceil($sheet['students']->count() / $perPage);
    }
@endphp

<div id="toolbar">
    <span>
        {{ $schoolCount }} school(s) &middot; {{ $totalPages }} page(s).
        In the print window choose <b>Save as PDF</b>, margins <b>None</b>.
    </span>
    <button type="button" id="printBtn">Print / Save as PDF</button>
</div>

<div id="sheets">
    @foreach ($sheets as $sheet)
        @php $house = $sheet['house']; @endphp
        @foreach ($sheet['students']->chunk($perPage) as $pageIndex => $chunk)
            <div class="sheet-page">
                <p class="bismillah">بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ</p>

                <table class="masthead">
                    <tr>
                        <td class="ar-title">هيئة الامتحانات الإعدادية والثانوية<br>( أوغندا )</td>
                        <td class="logo-cell">@if ($logoData)<img src="{{ $logoData }}" alt="ITEBU">@endif</td>
                        <td class="en-title">IDAAD AND THANAWI<br>EXAMINATIONS BOARD (U)</td>
                    </tr>
                </table>

                <p class="sheet-title">كشف أسماء الطلبة/الطالبات في الامتحان النهائي لعام: {{ $arYear }}</p>

                <table class="info">
                    <tr>
                        <td>
                            <b>اسم المعهد:</b> {{ $house->House_AR ?: $house->House }}<br>
                            <b>رقم المعهد:</b> <span dir="ltr">{{ $house->Number }}</span><br>
                            <b>المرحلة:</b> {{ $stage }}<br>
                            <b>التاريخ:</b> <span dir="ltr">{{ $arDate }}</span>
                        </td>
                        <td>
                            <b>المادة:</b> {{ $subject }}<br>
                            @if ($paper)
                                <b>الورقة:</b> {{ $paper }}<br>
                            @endif
                            <b>الزمن:</b> <span dir="ltr">{{ $timeText }}</span>
                        </td>
                    </tr>
                </table>

                <table class="marks">
                    <colgroup>
                        <col style="width: 4%;">
                        <col style="width: 24%;">
                        @for ($q = 1; $q <= $questions; $q++)
                            <col style="width: {{ $qWidth }}%;">
                        @endfor
                        <col style="width: 9%;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th class="rowno"></th>
                            <th>رقم الطالب/الطالبة</th>
                            @for ($q = 1; $q <= $questions; $q++)
                                <th class="qh">{{ \App\Http\Controllers\Helper::toArabicNumberDate((string) $q) }}</th>
                            @endfor
                            <th>المجموع</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- ->values() re-indexes the chunk from 0: chunk() keeps the original keys --}}
                        @foreach ($chunk->values() as $i => $student)
                            <tr>
                                <td class="rowno">{{ $pageIndex * $perPage + $i + 1 }}</td>
                                <td class="sid">{{ $student->Student_ID }}</td>
                                @for ($q = 1; $q <= $questions; $q++)
                                    <td></td>
                                @endfor
                                <td></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <table class="sign">
                    <colgroup>
                        <col style="width: 16%;">
                        <col style="width: 38%;">
                        <col style="width: 26%;">
                        <col style="width: 20%;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th></th>
                            <th>الاسم</th>
                            <th>التوقيع</th>
                            <th>الملاحظات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (['المصحح الأول', 'المصحح الثاني', 'المدقق الأول', 'المدقق الثاني'] as $role)
                            <tr>
                                <th>{{ $role }}</th>
                                <td class="blank"></td>
                                <td class="blank"></td>
                                <td class="blank"></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    @endforeach
</div>

<script>
    document.getElementById('printBtn').addEventListener('click', function () { window.print(); });
    window.onload = function () {
        // Wait for fonts so the Arabic is shaped with the final font, then open the print dialog.
        (document.fonts && document.fonts.ready ? document.fonts.ready : Promise.resolve()).then(function () {
            setTimeout(function () { window.print(); }, 300);
        });
    };
</script>
</body>
</html>
