<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="utf-8">
    <title>كشف الحضور</title>
    {{--
        Rendered as normal HTML and turned into a PDF in the browser (html2pdf), exactly like the
        pass slip (resources/views/template.blade.php). The browser shapes/joins the Arabic letters
        correctly, which the server-side dompdf renderer could not do.
    --}}
    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: #fff;
            color: #000;
            direction: rtl;
            font-family: 'Traditional Arabic', 'Times New Roman', Arial, Tahoma, sans-serif;
            font-size: 13px;
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
            font-size: 18px;
            font-weight: bold;
            margin: 0 0 4mm 0;
        }

        table.masthead { width: 100%; border-collapse: collapse; margin-bottom: 3mm; }
        table.masthead td { width: 33.33%; vertical-align: middle; }
        table.masthead .en-title {
            direction: ltr; text-align: left; font-family: 'Times New Roman', serif;
            font-weight: bold; font-size: 15px; line-height: 1.35;
        }
        table.masthead .ar-title { text-align: right; font-weight: bold; font-size: 17px; line-height: 1.5; }
        table.masthead .logo-cell { text-align: center; }
        table.masthead .logo-cell img { width: 24mm; height: 24mm; object-fit: contain; }

        .sheet-title {
            border: 1px solid #000;
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            padding: 2mm 0;
            margin: 0;
        }

        table.info { width: 100%; border-collapse: collapse; margin-bottom: 4mm; border: 1px solid #000; border-top: 0; }
        table.info td { width: 50%; vertical-align: top; padding: 2mm 3mm; font-size: 13px; line-height: 1.7; }
        table.info b { font-weight: bold; }

        table.roster { width: 100%; border-collapse: collapse; }
        table.roster th, table.roster td {
            border: 1px solid #000;
            text-align: center;
            font-size: 12px;
            height: 6mm;
            padding: 0 2mm;
        }
        table.roster thead th { font-weight: bold; background: #fff; height: 7mm; }
        table.roster td.name-cell { text-align: right; padding-right: 3mm; }
        table.roster td.reg-cell { direction: ltr; font-family: Arial, sans-serif; font-size: 11.5px; }

        .col-no { width: 6%; }
        .col-name { width: 34%; }
        .col-reg { width: 22%; }
        .col-sign { width: 19%; }
        .col-booklet { width: 19%; }

        table.footer { width: 100%; border-collapse: collapse; margin-top: 4mm; }
        table.footer td { width: 50%; padding: 1.5mm 0; font-size: 13px; font-weight: bold; vertical-align: bottom; }
        .line { display: inline-block; border-bottom: 1px solid #000; width: 46mm; height: 4mm; }
    </style>
</head>
<body>

@php
    $perPage = 25;
    $arYear = \App\Http\Controllers\Helper::toArabicNumberDate($year);
    $fmtTime = function ($t) {
        $t = trim((string) $t);
        return preg_match('/^\d{1,2}:\d{2}$/', $t) ? $t . ':00' : $t;
    };
    $timeText = ($startTime || $endTime) ? trim($fmtTime($startTime) . ' - ' . $fmtTime($endTime), ' -') : '';
    $pages = $students->values()->chunk($perPage);
@endphp

<div id="toolbar">
    <span id="status">Preparing PDF… it will download automatically.</span>
    <button type="button" id="redownload">Download again</button>
</div>

<div id="sheets">
    @foreach($pages as $pageIndex => $chunk)
        <div class="sheet-page">
            <p class="bismillah">بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ</p>

            <table class="masthead">
                <tr>
                    <td class="en-title">IDAAD AND THANAWI<br>EXAMINATIONS BOARD (U)</td>
                    <td class="logo-cell">@if($logoData)<img src="{{ $logoData }}" alt="ITEBU">@endif</td>
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
                        <b>التاريخ:</b> {{ $examDate }}
                    </td>
                    <td>
                        <b>المادة:</b> {{ $subject }}<br>
                        <b>الورقة:</b> {{ $paper }}<br>
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
                    @foreach($chunk as $i => $student)
                        <tr>
                            <td>{{ $pageIndex * $perPage + $i + 1 }}</td>
                            <td class="name-cell">{{ $student->Student_Name_AR ?: $student->Student_Name }}</td>
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
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' },
            pagebreak: { mode: ['css', 'legacy'] }
        };

        return html2pdf().set(opt).from(document.getElementById('sheets')).save().then(function () {
            status.textContent = 'PDF downloaded. You can close this tab.';
        });
    }

    window.onload = function () {
        // Wait for web fonts so the Arabic is measured with the final font before capture.
        (document.fonts && document.fonts.ready ? document.fonts.ready : Promise.resolve()).then(buildPdf);
    };

    document.getElementById('redownload').addEventListener('click', buildPdf);
</script>
</body>
</html>
