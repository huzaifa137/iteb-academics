<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="utf-8">
    <title>جدول الامتحانات</title>
    {{-- Rendered as HTML and turned into a landscape PDF in the browser (html2pdf), like the attendance sheet. --}}
    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: #fff;
            color: #000;
            direction: rtl;
            font-family: 'Traditional Arabic', 'Times New Roman', Arial, Tahoma, sans-serif;
            font-size: 14px;
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

        #sheets { width: 297mm; background: #fff; }

        .sheet-page {
            width: 297mm;
            height: 209mm;
            padding: 8mm 12mm 6mm 12mm;
            overflow: hidden;
            page-break-after: always;
            position: relative;
        }

        .sheet-page:last-child { page-break-after: auto; }

        .bismillah { text-align: center; font-size: 18px; font-weight: bold; margin: 0 0 2mm 0; }

        table.masthead { width: 100%; border-collapse: collapse; margin-bottom: 2mm; }
        table.masthead td { width: 33.33%; vertical-align: middle; }
        table.masthead .en-title {
            direction: ltr; text-align: left; font-family: 'Times New Roman', serif;
            font-weight: bold; font-size: 15px; line-height: 1.35;
        }
        table.masthead .ar-title { text-align: right; font-weight: bold; font-size: 17px; line-height: 1.5; }
        table.masthead .logo-cell { text-align: center; }
        table.masthead .logo-cell img { width: 22mm; height: 22mm; object-fit: contain; }

        .sheet-title {
            border: 1px solid #000;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            padding: 1.5mm 0;
            margin: 0 0 3mm 0;
        }

        table.tt { width: 100%; border-collapse: collapse; table-layout: fixed; }
        table.tt th, table.tt td {
            border: 1px solid #555;
            text-align: center;
            vertical-align: middle;
            padding: 1mm 2mm;
        }

        table.tt thead th {
            background: #b8bec6;
            font-family: Arial, sans-serif;
            font-size: 13px;
            font-weight: bold;
            height: 15mm;
            line-height: 1.6;
        }

        table.tt thead th.day-head { font-family: 'Traditional Arabic', 'Times New Roman', Arial, sans-serif; font-size: 16px; }

        table.tt td.day-cell { font-weight: bold; font-size: 15px; line-height: 1.8; }
        table.tt td.prayer { font-size: 15px; color: #444; }

        table.tt td.slot { height: 27mm; font-size: 15px; font-weight: bold; line-height: 1.9; padding: 0; vertical-align: top; }
        table.tt td.slot .group { padding: 1mm 2mm; }
        table.tt td.slot .time {
            background: #b8bec6;
            font-family: Arial, sans-serif;
            font-size: 13px;
            line-height: 1.6;
            padding: 1mm 0;
            font-weight: bold;
        }
        table.tt td.slot .empty { display: block; border-bottom: 1px solid #000; width: 70%; margin: 12mm auto 0 auto; }

        .col-day { width: 14%; }
        .col-slot { width: 21%; }
        .col-prayer { width: 11%; }
    </style>
</head>
<body>

@php
    $perPage = 6;
    $arYear = \App\Http\Controllers\Helper::toArabicNumberDate($year);
    $pages = collect($dayRows)->chunk($perPage);
    $slotKeys = ['A', 'B', 'C'];
@endphp

<div id="toolbar">
    <span id="status">Preparing PDF… it will download automatically.</span>
    <button type="button" id="redownload">Download again</button>
</div>

<div id="sheets">
    @foreach ($pages as $page)
        <div class="sheet-page">
            <p class="bismillah">بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ</p>

            <table class="masthead">
                <tr>
                    <td class="en-title">IDAAD AND THANAWI<br>EXAMINATIONS BOARD (U)</td>
                    <td class="logo-cell">@if ($logoData)<img src="{{ $logoData }}" alt="ITEBU">@endif</td>
                    <td class="ar-title">هيئة الامتحانات الإعدادية والثانوية<br>( أوغندا )</td>
                </tr>
            </table>

            <p class="sheet-title">جدول الامتحانات النهائية للمرحلة {{ $stage }} لعام: {{ $arYear }}</p>

            <table class="tt">
                <thead>
                    <tr>
                        <th class="col-day day-head">اليوم والتاريخ</th>
                        <th class="col-slot">09:00 am<br>11:00 am</th>
                        <th class="col-slot">11:00 am<br>01:00 pm</th>
                        <th class="col-prayer">01:00 pm<br>02:00 pm</th>
                        <th class="col-slot">02:00 pm<br>04:00 pm</th>
                        <th class="col-prayer">04:00 pm<br>05:00 pm</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($page as $i => $row)
                        <tr>
                            <td class="day-cell">{{ $row['dayAr'] }}<br>{{ $row['date'] }}</td>

                            @foreach (['A', 'B'] as $key)
                                <td class="slot">
                                    @include('student.partials.timetable-slot', ['groups' => $row['slots'][$key]])
                                </td>
                            @endforeach

                            @if ($i === 0)
                                <td class="prayer" rowspan="{{ $page->count() }}">صلاة الظهر</td>
                            @endif

                            <td class="slot">
                                @include('student.partials.timetable-slot', ['groups' => $row['slots']['C']])
                            </td>

                            @if ($i === 0)
                                <td class="prayer" rowspan="{{ $page->count() }}">صلاة العصر</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
    function buildPdf() {
        const status = document.getElementById('status');
        status.textContent = 'Preparing PDF… it will download automatically.';

        const opt = {
            margin: 0,
            filename: @json($fileName),
            image: { type: 'jpeg', quality: 1 },
            html2canvas: { scale: 3, useCORS: true },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' },
            pagebreak: { mode: ['css', 'legacy'] }
        };

        return html2pdf().set(opt).from(document.getElementById('sheets')).save().then(function () {
            status.textContent = 'PDF downloaded. You can close this tab.';
        });
    }

    window.onload = function () {
        (document.fonts && document.fonts.ready ? document.fonts.ready : Promise.resolve()).then(buildPdf);
    };

    document.getElementById('redownload').addEventListener('click', buildPdf);
</script>
</body>
</html>
