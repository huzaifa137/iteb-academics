<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="utf-8">
    <title>كشف الحضور - جميع المعاهد</title>
    {{--
        Bulk attendance sheets (all schools, one or all subjects). Too many pages for the in-browser
        html2pdf renderer, so this opens the browser print dialog: choose "Save as PDF".
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

        #sheets { width: 210mm; background: #fff; }

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
            padding: 2mm 0;
            margin: 0;
        }

        table.info { width: 100%; border-collapse: collapse; margin-bottom: 4mm; border: 1px solid #000; border-top: 0; }
        table.info td { width: 50%; vertical-align: top; padding: 2mm 3mm; font-size: 16px; line-height: 1.55; }
        table.info b { font-weight: bold; }

        table.roster { width: 100%; border-collapse: collapse; }
        table.roster th, table.roster td {
            border: 1px solid #000;
            text-align: center;
            font-size: 15px;
            height: 6.5mm;
            padding: 0 1.5mm;
        }
        table.roster thead th { font-weight: bold; background: #fff; height: 8mm; font-size: 14px; }
        table.roster { table-layout: fixed; }
        table.roster td.name-cell { text-align: right; padding-right: 3mm; white-space: nowrap; overflow: hidden; }
        /* Long names shrink a little so the whole name stays visible on one line */
        table.roster td.name-sm  { font-size: 13px; }
        table.roster td.name-xs  { font-size: 11.5px; }
        table.roster td.name-xxs { font-size: 10px; }
        table.roster td.reg-cell { direction: ltr; font-family: Arial, sans-serif; font-size: 13px; }

        .col-no { width: 5%; }
        .col-name { width: 47%; }
        .col-reg { width: 18%; }
        .col-sign { width: 14%; }
        .col-booklet { width: 16%; }

        table.footer { width: 100%; border-collapse: collapse; margin-top: 4mm; }
        table.footer td { width: 50%; padding: 1.5mm 0; font-size: 15px; font-weight: bold; vertical-align: bottom; }
        .line { display: inline-block; border-bottom: 1px solid #000; width: 46mm; height: 4mm; }
    
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

            /* Fit the printable width instead of a hard 210mm: if the print dialog adds even a few mm
               of margin, a fixed 210mm sheet overflows and loses the closing (left) border in RTL. */
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

            table.masthead, table.info, table.roster, table.footer, .sheet-title { width: 100%; }
        }
    </style>
</head>
<body>

@php
    $perPage = 25;
    $arYear = \App\Http\Controllers\Helper::toArabicNumberDate($year);
    $fmtTime = function ($t) {
        $t = trim((string) $t);
        $t = substr($t, 0, 5);
        return preg_match('/^\d{1,2}:\d{2}$/', $t) ? $t . ':00' : $t;
    };
    $totalPages = 0;
    foreach ($sheets as $sheet) {
        $totalPages += (int) ceil($sheet['students']->count() / $perPage);
    }
@endphp

<div id="toolbar">
    <span>
        {{ $schoolCount }} school(s) &middot; {{ $subjectCount }} subject(s) &middot; {{ $totalPages }} page(s).
        In the print window choose <b>Save as PDF</b>, margins <b>None</b>.
        @if (!empty($skippedHouses ?? []))
            <br><span style="color:#ffd27f;">Skipped (no students for this year/category): {{ implode(' | ', $skippedHouses) }}</span>
        @endif
    </span>
    <button type="button" id="printBtn">Print / Save as PDF</button>
</div>

<div id="sheets">
    @foreach ($sheets as $sheet)
        @php
            $house = $sheet['house'];
            $entry = $sheet['entry'];
            $timeText = $fmtTime($entry->start_time) . ' - ' . $fmtTime($entry->end_time);
        @endphp

        @foreach ($sheet['students']->chunk($perPage) as $pageIndex => $chunk)
            <div class="sheet-page">
                <p class="bismillah">بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ</p>

                <table class="masthead">
                    <tr>
                        <td class="en-title">IDAAD AND THANAWI<br>EXAMINATIONS BOARD (U)</td>
                        <td class="logo-cell">@if ($logoData)<img src="{{ $logoData }}" alt="ITEBU">@endif</td>
                        <td class="ar-title">هيئة الامتحانات الإعدادية والثانوية<br>( أوغندا )</td>
                    </tr>
                </table>

                <p class="sheet-title">كشف أسماء الطلبة/الطالبات في الامتحان النهائي لعام: {{ $arYear }}</p>

                <table class="info">
                    <tr>
                        <td>
                            <b>اسم المعهد:</b> {{ $house->House_AR ?: $house->House }}<br>
                            <b>رقم المعهد:</b> <span dir="ltr">{{ $house->Number }}</span><br>
                            <b>المرحلة:</b> {{ $stage }}<br>
                            <b>التاريخ:</b> {{ $entry->exam_date->format('d/m/Y') }}
                        </td>
                        <td>
                            <b>المادة:</b> {{ $entry->subject_ar }}<br>
                            @if ($entry->paper_ar)
                                <b>الورقة:</b> {{ $entry->paper_ar }}<br>
                            @endif
                            <b>الزمن:</b> <span dir="ltr">{{ $timeText }}</span>
                        </td>
                    </tr>
                </table>

                <table class="roster">
                    <thead>
                        <tr>
                            <th class="col-no">الرقم</th>
                            <th class="col-name">الاسم</th>
                            <th class="col-reg">رقم التسجيل</th>
                            <th class="col-sign">التوقيع</th>
                            <th class="col-booklet">رقم دفتر الإجابة</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- ->values() re-indexes the chunk from 0: chunk() keeps the original keys, which made page 2 start at 51 --}}
                        @foreach ($chunk->values() as $i => $student)
                            @php
                                $studentName = $student->Student_Name_AR ?: $student->Student_Name;
                                $nameLen = mb_strlen((string) $studentName);
                                $nameClass = $nameLen > 64 ? 'name-xxs' : ($nameLen > 54 ? 'name-xs' : ($nameLen > 44 ? 'name-sm' : ''));
                            @endphp
                            <tr>
                                <td>{{ $pageIndex * $perPage + $i + 1 }}</td>
                                <td class="name-cell {{ $nameClass }}">{{ $studentName }}</td>
                                <td class="reg-cell">{{ $student->Student_ID }}</td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <table class="footer">
                    <tr>
                        <td>اسم المراقب: <span class="line"></span></td>
                        <td>التاريخ: <span class="line"></span></td>
                    </tr>
                    <tr>
                        <td>مكان عمله: <span class="line"></span></td>
                        <td>التوقيع: <span class="line"></span></td>
                    </tr>
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
