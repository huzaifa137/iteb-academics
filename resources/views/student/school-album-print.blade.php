<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="utf-8">
    <title>School Album</title>
    {{--
        Same approach as the pass slip / attendance sheet / examination cards: plain HTML so the
        browser joins the Arabic letters correctly, then html2pdf produces the PDF.
        15 students per A4 landscape page (3 columns x 5 rows); the header repeats on every page.
    --}}
    <style>
        @page { size: A4 landscape; margin: 0; }
        * { box-sizing: border-box; }

        html, body {
            margin: 0; background: #fff; color: #000;
            font-family: 'Times New Roman', Times, serif;
            -webkit-print-color-adjust: exact; print-color-adjust: exact;
        }

        #toolbar {
            direction: ltr; font-family: Arial, sans-serif; padding: 8px 12px;
            background: #0d4b1f; color: #fff; font-size: 13px;
            display: flex; justify-content: space-between; align-items: center;
        }
        #toolbar button {
            background: #fff; color: #0d4b1f; border: 0; border-radius: 4px;
            padding: 5px 12px; font-weight: bold; cursor: pointer; margin-left: 6px;
        }

        #sheets { width: 297mm; background: #fff; direction: ltr; }

        .album-page {
            width: 297mm; height: 209mm; padding: 5mm 11mm 0 11mm;
            position: relative; overflow: hidden; page-break-after: always;
        }
        .album-page:last-child { page-break-after: auto; }

        /* ---- header ---- */
        .a-head { position: relative; height: 24mm; text-align: center; border-bottom: 0.3mm solid #000; }
        .a-head .logo { position: absolute; left: 1mm; top: 0; width: 22mm; height: 22mm; object-fit: contain; opacity: 0.85; }
        .a-head .t-ar { direction: rtl; font-family: 'Traditional Arabic', 'Times New Roman', serif; font-weight: bold; font-size: 23px; line-height: 1.25; }
        .a-head .t-en { font-weight: bold; font-size: 19px; line-height: 1.2; }
        .a-head .t-album { font-weight: bold; font-size: 22px; line-height: 1.2; color: #e60012; }

        /* ---- school / stage / year strip ---- */
        .a-info { position: relative; height: 12mm; margin-bottom: 1.5mm; }
        .a-info .school {
            position: absolute; left: 1mm; top: 3.2mm; font-family: Arial, Helvetica, sans-serif;
            font-weight: bold; font-size: 12.5px;
        }
        .a-info .ar-block { position: absolute; right: 1mm; top: 2.4mm; direction: rtl; text-align: right; font-family: 'Traditional Arabic', 'Times New Roman', serif; font-weight: bold; font-size: 12px; line-height: 1.55; }
        .a-info .ar-block .line2 { display: flex; justify-content: flex-end; gap: 7mm; }

        .lbl { color: #1f6fb5; }
        .val { color: #e60012; }

        /* ---- grid ---- */
        .a-grid { display: grid; grid-template-columns: repeat(3, 1fr); grid-template-rows: repeat(5, 30.6mm); }
        .s-cell { position: relative; border: 0.3mm solid #000; margin: 0 0 -0.3mm -0.3mm; padding: 0.6mm 1mm 0 24mm; overflow: hidden; }
        .s-cell .photo { position: absolute; left: 0; bottom: 0; width: 22.6mm; height: 26.8mm; }
        .s-cell .photo img { display: block; width: 100%; height: 100%; object-fit: cover; }
        .s-cell .ln { font-weight: bold; font-size: 12px; line-height: 1.2; }
        .s-cell .nm { min-height: 2.4em; }
        .s-cell .gap { height: 0; }

        @media print { #toolbar { display: none; } }

        .a-foot { position: absolute; left: 11mm; right: 11mm; bottom: 3mm; border-top: 0.3mm solid #000; }
    </style>
</head>
<body>

@php
    $arYear = \App\Http\Controllers\Helper::toArabicNumberDate($year);
@endphp

<div id="toolbar">
    <span id="status">Preparing PDF… it will download automatically.</span>
    <span>
        <button type="button" id="printBtn">Print</button>
        <button type="button" id="redownload">Download again</button>
    </span>
</div>

<div id="sheets">
    @foreach($records->chunk($perPage) as $chunk)
        <div class="album-page">
            <div class="a-head">
                @if($logoData)<img class="logo" src="{{ $logoData }}" alt="">@endif
                <div class="t-ar">هيئة الامتحانات الإعدادية والثانوية - أوغندا</div>
                <div class="t-en">IDAAD AND THANAWI EXAMINATION BOARD - (U)</div>
                <div class="t-album">School Album</div>
            </div>

            <div class="a-info">
                <div class="school"><span class="lbl">School:</span> <span class="val">{{ strtoupper($house->House) }}</span></div>
                <div class="ar-block">
                    <div><span class="lbl">اسم المعهد :</span> <span class="val">{{ $house->House_AR ?: $house->House }}</span></div>
                    <div class="line2">
                        <span><span class="lbl">المرحلة :</span><span class="val">{{ $stage }}</span></span>
                        <span><span class="lbl">العام الدراسي :</span> <span class="val">{{ $arYear }}</span></span>
                    </div>
                </div>
            </div>

            <div class="a-grid">
                @foreach($chunk as $r)
                    <div class="s-cell">
                        <div class="photo"><img src="{{ $r['photo'] }}" alt=""></div>
                        <div class="ln"><span class="lbl">Student ID:</span> <span class="val">{{ $r['id'] }}</span></div>
                        <div class="ln nm"><span class="lbl">Student's Name:</span> <span class="val">{{ $r['name'] }}</span></div>
                        <div class="ln"><span class="lbl">Date of Birth:</span> <span class="val">{{ $r['dob'] }}</span></div>
                        <div class="ln"><span class="lbl">Place of Birth:</span> <span class="val">{{ $r['pob'] }}</span></div>
                        <div class="ln"><span class="lbl">Nationality:</span> <span class="val">{{ $r['nationality'] }}</span></div>
                        <div class="ln"><span class="lbl">District:</span> <span class="val">{{ $r['district'] }}</span></div>
                    </div>
                @endforeach
            </div>

            <div class="a-foot"></div>
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
            image: { type: 'jpeg', quality: 0.95 },
            html2canvas: { scale: 2.5, useCORS: true },
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
    document.getElementById('printBtn').addEventListener('click', function () { window.print(); });
</script>
</body>
</html>
