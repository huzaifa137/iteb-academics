<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="utf-8">
    <title>بطاقات الامتحان</title>
    {{--
        Same approach as the pass slip / attendance sheet: plain HTML so the browser shapes
        the Arabic correctly, then html2pdf produces the PDF. 6 cards per A4 landscape page (2 x 3).
        The "Print" button uses the browser print dialog with the same layout.
    --}}
    <style>
        @page { size: A4 landscape; margin: 0; }
        * { box-sizing: border-box; }

        html, body {
            margin: 0; background: #fff; color: #000;
            font-family: 'Traditional Arabic', 'Times New Roman', Arial, Tahoma, sans-serif;
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

        /* A4 landscape, 2 columns x 3 rows = 6 cards per page, first card top-left */
        .cards-page {
            width: 297mm; height: 209mm; padding: 7mm 7.3mm;
            display: grid; grid-template-columns: 138mm 138mm; grid-template-rows: repeat(3, 61.5mm);
            column-gap: 6.4mm; row-gap: 5.7mm; align-content: start;
            page-break-after: always; overflow: hidden;
        }
        .cards-page:last-child { page-break-after: auto; }

        /* ---------- card (138mm x 61.5mm) ---------- */
        .exam-card {
            position: relative; width: 138mm; height: 61.5mm;
            border: 0.35mm solid #000; background: #fff; direction: ltr;
            font-family: Calibri, Carlito, Arial, sans-serif;
        }
        .exam-card .lbl { color: #1f6fb5; }
        .exam-card .val { color: #e60012; font-weight: bold; }
        .exam-card .ar { direction: rtl; font-family: 'Traditional Arabic', 'Times New Roman', serif; }

        /* header strip: card no | board name */
        .c-no {
            position: absolute; left: 0; top: 0; width: 21.2mm; height: 16mm;
            border-right: 0.35mm solid #000; border-bottom: 0.35mm solid #000;
            text-align: center; display: flex; flex-direction: column; justify-content: center;
        }
        .c-no-ar { direction: rtl; color: #1f6fb5; font-size: 9px; font-weight: bold; line-height: 1.1; font-family: 'Times New Roman', serif; }
        .c-no-en { color: #1f6fb5; font-size: 14px; font-weight: bold; line-height: 1.15; }
        .c-no-num { color: #000; font-size: 16px; font-weight: bold; line-height: 1.2; }

        .c-head {
            position: absolute; left: 21.2mm; top: 0; width: 88mm; height: 16mm;
            border-bottom: 0.35mm solid #000; text-align: center;
            display: flex; flex-direction: column; justify-content: center;
        }
        .c-head-ar { direction: rtl; font-family: 'Traditional Arabic', 'Times New Roman', serif; font-weight: bold; font-size: 14px; line-height: 1.3; }
        .c-head-en { font-family: 'Times New Roman', serif; font-weight: bold; font-size: 12px; line-height: 1.3; }

        .c-photo { position: absolute; left: 110.4mm; top: 1.7mm; width: 26mm; height: 23mm; }
        .c-photo img { width: 100%; height: 100%; object-fit: cover; display: block; }

        /* body rows */
        .c-row { position: absolute; left: 0.8mm; width: 108mm; display: flex; justify-content: space-between; align-items: baseline; white-space: nowrap; }
        .c-row .en { font-size: 14px; font-weight: bold; }
        .c-row .en .val { font-size: 14px; }
        .c-row .ar { font-size: 10.5px; font-weight: bold; }
        .c-row .ar .val { font-size: 10.5px; }
        .r1 { top: 17.3mm; justify-content: flex-end; padding-right: 13.5mm; }
        .r2 { top: 22.7mm; padding-right: 8.1mm; }
        .r3 { top: 28.9mm; padding-right: 1.2mm; }
        .r4 { top: 35.1mm; justify-content: flex-start; gap: 1.5mm; }
        .r4 .en .val { font-size: 13px; }
        .r4 .ar-small { direction: rtl; font-size: 9.5px; font-weight: bold; font-family: 'Times New Roman', serif; }

        /* footer */
        .c-foot {
            position: absolute; left: 0; bottom: 0; width: 108.7mm; height: 6mm;
            border-top: 0.35mm solid #000; border-right: 0.35mm solid #000;
            direction: rtl; text-align: center; font-weight: bold; font-size: 13px; line-height: 5.6mm;
            font-family: 'Traditional Arabic', 'Times New Roman', serif;
        }
        .c-foot-box { position: absolute; left: 108.7mm; bottom: 0; right: 0; height: 6mm; border-top: 0.35mm solid #000; }

        /* round-diamond stamp over the lower right of the card */
        .c-stamp { position: absolute; left: 99mm; top: 22mm; width: 42mm; height: 42mm; opacity: 0.95; pointer-events: none; }

        @media print {
            #toolbar { display: none; }
        }
    </style>
</head>
<body>

<div id="toolbar">
    <span id="status">Preparing PDF… it will download automatically.</span>
    <span>
        <button type="button" id="printBtn">Print</button>
        <button type="button" id="redownload">Download again</button>
    </span>
</div>

<div id="sheets">
    @foreach($cards->chunk($perPage) as $chunk)
        <div class="cards-page">
            @foreach($chunk as $card)
                @include('student.partials.examination-card', [
                    'student'   => $card['student'],
                    'photo'     => $card['photo'],
                    'cardNo'    => $card['cardNo'],
                    'house'     => $house,
                    'year'      => $year,
                    'stage'     => $stage,
                    'stageEn'   => $stageEn,
                    'stampData' => $stampData,
                ])
            @endforeach
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
    document.getElementById('printBtn').addEventListener('click', function () { window.print(); });
</script>
</body>
</html>
