<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>School Registration Certificate – {{ $cert->certificate_number }}</title>
    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            background: white;
        }

        body {
            width: 297mm;
            height: 210mm;
            margin: auto;
            background: #FFF;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* ── CERTIFICATE CONTAINER ── */
        .certificate {
            width: 287mm;
            height: 198mm;
            position: relative;
            margin-left: auto;
            margin-right: auto;
            margin-top: 6mm;
            margin-bottom: 6mm;
            display: block;
        }

        /* ── BORDER IMAGE ── */
        .certificate-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .certificate-bg img {
            width: 100%;
            height: 100%;
            object-fit: fill;
            display: block;
        }

        /* ── WATERMARK ── */
        .watermark {
            position: absolute;
            top: 55%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(0deg);
            z-index: 0;
            opacity: 0.12;
            width: 400px;
            height: auto;
            pointer-events: none;
        }

        .watermark img {
            width: 100%;
            height: auto;
            object-fit: contain;
            display: block;
        }

        /* ── CONTENT WRAPPER ── */
        .certificate-content {
            position: absolute;
            left: 20mm;
            right: 20mm;
            top: 8mm;
            bottom: 10mm;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            /* ← KEY: spread sections evenly */
        }

        .certificate-content>* {
            position: relative;
            z-index: 1;
        }

        * {
            box-sizing: border-box;
        }

        /* ══ BISMILLAH ROW ══ */
        .bismillah-row {
            text-align: center;
            margin-bottom: 0;
        }

        .bismillah {
            font-size: 22px;
            font-weight: bold;
            color: #1e5cc4;
            line-height: 1.3;
        }

        .bismillah-translation {
            font-style: italic;
            font-size: 11.5px;
            color: #1e5cc4;
            margin-top: 1px;
        }

        /* ══ HEADER: 3 COLUMNS ══ */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-left {
            flex: 1;
            text-align: left;
        }

        .header-left h2 {
            margin: 0 0 2px;
            font-size: 18px;
            color: #0d4b1e;
            font-weight: 900;
            line-height: 1.3;
        }

        .header-left h3 {
            margin: 0 0 2px;
            font-size: 15px;
            color: #b11226;
            font-weight: 700;
            line-height: 1.3;
        }

        .header-left h4 {
            margin: 0;
            font-size: 13px;
            color: #555;
            font-weight: 600;
        }

        .header-center {
            width: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .header-center img {
            max-width: 65px;
            max-height: 65px;
        }

        .header-right {
            flex: 1;
            text-align: right;
            direction: rtl;
        }

        .header-right h2 {
            margin: 0 0 2px;
            font-size: 18px;
            color: #0d4b1e;
            font-weight: 900;
            line-height: 1.3;
        }

        .header-right h3 {
            margin: 0 0 2px;
            font-size: 15px;
            color: #b11226;
            font-weight: 700;
            line-height: 1.3;
        }

        .header-right h4 {
            margin: 0;
            font-size: 13px;
            color: #555;
        }

        /* ══ TITLE BAND ══ */
        .title-band {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1px;
        }

        .title-arabic {
            font-size: 17px;
            font-weight: 900;
            color: #0d4b1e;
            direction: rtl;
            unicode-bidi: embed;
            word-spacing: 4px;
            line-height: 1.3;
        }

        .title-english {
            font-size: 16px;
            font-weight: 900;
            color: #0d4b1e;
            letter-spacing: 2px;
            text-transform: uppercase;
            line-height: 1.3;
        }

        /* ── HORIZONTAL DIVIDER ── */
        .divider {
            border: none;
            border-top: 1.5px solid #4e9624;
            margin: 0;
        }

        /* ══ BODY: TWO COLUMNS ══ */
        .body-columns {
            display: flex;
            gap: 0;
            flex: 1;
            /* ← KEY: body columns grow to fill available space */
            min-height: 0;
            align-items: stretch;
        }

        .body-col-en {
            flex: 1;
            font-size: 15px;
            line-height: 2.0;
            font-family: Tahoma, Arial, sans-serif;
            text-align: justify;
            color: #111;
            padding-right: 14px;
            padding-top: 8px;
            padding-bottom: 8px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            /* ← vertically center the text within the column */
        }

        /* Vertical rule between columns */
        .body-divider-v {
            width: 1.5px;
            background: #4e9624;
            flex-shrink: 0;
            align-self: stretch;
        }

        .body-col-ar {
            flex: 1;
            direction: rtl;
            unicode-bidi: embed;
            font-size: 16px;
            padding-left: 14px;
            line-height: 2.0;
            text-align: justify;
            color: #111;
            padding-top: 8px;
            padding-bottom: 8px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            /* ← vertically center the text */
        }

        .reg-number-box {
            display: inline-block;
            border: 1.5px solid #0d4b1e;
            border-radius: 4px;
            padding: 0px 6px;
            font-weight: bold;
            font-size: 12px;
            color: #0d4b1e;
            letter-spacing: 1px;
        }

        .ar-inline {
            direction: rtl;
            unicode-bidi: embed;
            display: inline;
        }

        /* ══ DATE OF ISSUE ══ */
        .date-of-issue {
            font-size: 14px;
            font-weight: 600;
            color: #111;
            padding: 4px 0;
            font-family: Tahoma, Arial, sans-serif;
        }

        /* ══ FOOTER: 3-COLUMN LAYOUT ══ */
        .footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 8px;
        }

        .footer-left {
            flex: 1;
            text-align: left;
            padding-top: 10px;
        }

        .footer-center {
            flex: 0 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2px;
            padding-top: 10px;
        }

        .footer-right {
            flex: 1;
            text-align: right;
            direction: rtl;
            padding-top: 10px;
        }

        .footer-date {
            font-size: 15px;
            color: #333;
            margin-bottom: 4px;
            font-weight: 600;
        }

        .footer-title-ar {
            font-size: 16px;
            font-weight: bold;
            color: #0d4b1e;
            direction: rtl;
        }

        .footer-title-en {
            font-size: 14.5px;
            font-weight: bold;
            color: #0d4b1e;
        }

        .signature-space {
            height: 45px;
            /* ← slightly taller to use footer space */
            border-bottom: 1px solid #555;
            margin: 6px 0;
            width: 100%;
        }

        .serial-label {
            font-size: 13px;
            font-weight: bold;
            color: #333;
            margin-bottom: 2px;
        }

        #qr {
            display: flex;
            justify-content: center;
        }

        .nowrap {
            white-space: nowrap;
        }
    </style>
</head>

<body>

    @php
        use App\Http\Controllers\Helper;
        $currentDate = date('d/m/Y');
        $issuedDateFmt = \Carbon\Carbon::parse($cert->issued_date)->format('d/m/Y');
        $issuedDateLong = \Carbon\Carbon::parse($cert->issued_date)->format('d F Y');
    @endphp

    <div class="certificate">

        {{-- ── BORDER ── --}}
        <div class="certificate-bg">
            <img src="{{ asset('assets/certificates/border.jpg') }}" alt="border">
        </div>

        {{-- ── WATERMARK ── --}}
        <div class="watermark">
            <img src="{{ asset('assets/images/brand/uplogolight.png') }}" alt="Watermark">
        </div>

        <div class="certificate-content">

            {{-- ══ BISMILLAH ══ --}}
            <div class="bismillah-row">
                <div class="bismillah">
                    @if($bismillahBase64)
                        <img src="{{ $bismillahBase64 }}" alt="Bismillah" style="height: 36px; width: auto;">
                    @else
                        بِسْمِ اللهِ الرَّحْمٰنِ الرَّحِيْم
                    @endif
                </div>
                <div class="bismillah-translation">In the name of Allah the most Gracious the most Merciful</div>
            </div>

            {{-- ══ HEADER ══ --}}
            <div class="header">
                <div class="header-left">
                    <h2>Uganda Muslim Supreme Council</h2>
                    <h3>Idaad and Thanawi Examinations Board (U)</h3>
                    <h4>ITEBU &nbsp;·&nbsp; Kampala, Uganda</h4>
                </div>

                <div class="header-center">
                    <img src="{{ asset('assets/images/brand/uplogolight.png') }}" alt="UMSC logo">
                </div>

                <div class="header-right">
                    <h2>
                        <span
                            class="ar-inline">{!! Helper::arabicWordSpacing('المجلس الأعلى الإسلامي الأوغندي') !!}</span>
                    </h2>
                    <h3>
                        <span
                            class="ar-inline">{!! Helper::arabicWordSpacing('هيئة الامتحانات الإعدادية والثانوية') !!}</span>
                        <span style="direction:rtl;unicode-bidi:embed;">(أوغندا)</span>
                    </h3>
                    <h4>
                        <span class="ar-inline">كمبالا، أوغندا</span>
                    </h4>
                </div>
            </div>

            {{-- ══ TITLE BAND ══ --}}
            <div class="title-band">
                <span class="title-arabic">شهادة التسجيل</span>
                <span class="title-english">Certificate of Registration</span>
            </div>

            <hr class="divider">

            {{-- ══ BODY: TWO COLUMNS (grows to fill space) ══ --}}
            <div class="body-columns">

                <div class="body-col-en">
                    <div>
                        The Board hereby certifies that
                        <b>{{ $schoolNameEn }}</b>,
                        located in <b>{{ $location }}</b>,
                        has been registered under the Board
                        Registration Number&nbsp;<span class="reg-number-box">{{ $cert->certificate_number }}</span>.
                        <br><br>
                        This school is duly recognised and authorised to participate
                        in the examinations conducted by the
                        <b>Idaad and Thanawi Examinations Board (Uganda)</b>.
                    </div>
                </div>

                <div class="body-divider-v"></div>

                <div class="body-col-ar">
                    <div>
                        الحمد لله رب العالمين والصلاة والسلام على خاتم الأنبياء والمرسلين، أما بعد:
                        <br>
                        تشهد الهيئة بأن مدرسة
                        <b><span class="ar-inline">{!! Helper::arabicWordSpacing($schoolNameAr) !!}</span></b>
                        الواقعة في
                        <b><span class="ar-inline">{{ $location }}</span></b>
                        قد تم تسجيلها لدى الهيئة برقم
                        <b><span class="ar-inline">{{ $schoolNumber }}</span></b>،
                        وهي مدرسة معتمدة ومأذون لها بالمشاركة في الامتحانات التي تجريها الهيئة.
                        <br><br>
                        تاريخ الإصدار:
                        <b><span class="ar-inline">{{ Helper::toArabicNumberDateReversed($issuedDateFmt) }}</span></b>
                        هـ / <span class="ar-inline">{{ $issuedDateFmt }}</span> م
                    </div>
                </div>

            </div>{{-- /body-columns --}}

            <hr class="divider">

            {{-- ══ DATE OF ISSUE ══ --}}
            <div class="date-of-issue">
                Date of Issue: <b>{{ $issuedDateLong }}</b>
            </div>

            <hr class="divider">

            {{-- ══ FOOTER ══ --}}
            <div class="footer">

                <div class="footer-left">
                    <div class="footer-date">Date: {{ $issuedDateFmt }}</div>
                    <div class="footer-title-ar">
                        <span class="ar-inline">{!! Helper::arabicWordSpacing('السكرتير التنفيذي للهيئة') !!}</span>
                    </div>
                    <div class="signature-space"></div>
                    <div class="footer-title-en nowrap">Executive Secretary (ITEBU)</div>
                </div>

                <div class="footer-center">
                    <div class="serial-label">Ref: {{ $cert->certificate_number }}</div>
                    <div id="qr"></div>
                    <div style="font-size:10px; color:#555; margin-top:2px;">Serial Number</div>
                </div>

                <div class="footer-right">
                    <div class="footer-date" style="direction:ltr; text-align:right;">
                        <span class="ar-inline">التاريخ {{ Helper::toArabicNumberDateReversed($issuedDateFmt) }}</span>
                    </div>
                    <div class="footer-title-ar">
                        <span class="ar-inline">{!! Helper::arabicWordSpacing('سكرتير التعليم للمجلس') !!}</span>
                    </div>
                    <div class="signature-space"></div>
                    <div class="footer-title-en nowrap" style="direction:ltr; text-align:right;">
                        Secretary for Education (UMSC)
                    </div>
                </div>

            </div>{{-- /footer --}}

        </div>{{-- /certificate-content --}}
    </div>{{-- /certificate --}}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <script>
        window.onload = function () {
            const qrData = "Certificate No: {{ $cert->certificate_number }}\nSchool: {{ $schoolNameEn }}\nSchool Code: {{ $schoolNumber }}\nIssued: {{ \Carbon\Carbon::parse($cert->issued_date)->format('d M Y') }}";

            new QRCode(document.getElementById("qr"), {
                text: qrData,
                width: 58,
                height: 58,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });

            if (window.self !== window.top) {
                return;
            }

            const element = document.querySelector('.certificate');
            const opt = {
                margin: 0,
                filename: 'registration_certificate_{{ $schoolNumber }}.pdf',
                image: { type: 'jpeg', quality: 1 },
                html2canvas: { scale: 4, useCORS: true, scrollY: 0 },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' }
            };

            setTimeout(function () {
                html2pdf().set(opt).from(element).save();
            }, 600);
        };
    </script>

</body>

</html>