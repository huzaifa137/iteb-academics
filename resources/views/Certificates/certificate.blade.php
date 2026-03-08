<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>O LEVEL Certificate</title>
    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            background: white;
        }

        body {
            background: #e9ecef;
        }

        /* CERTIFICATE CONTAINER */
        .certificate {
            width: 297mm;
            height: 210mm;
            margin: 0 auto;
            background: #fff;
            padding: 25mm;
            box-sizing: border-box;
            position: relative;
        }

        /* OUTER ORNAMENT FRAME */
        .certificate::before {
            content: "";
            position: absolute;
            top: 8mm;
            left: 8mm;
            right: 8mm;
            bottom: 8mm;
            border: 4px double #2f7a59;
        }

        /* INNER GOLD FRAME */
        .certificate::after {
            content: "";
            position: absolute;
            top: 14mm;
            left: 14mm;
            right: 14mm;
            bottom: 14mm;
            border: 2px solid #c9a646;
        }

        /* CORNER ORNAMENTS */
        .corner {
            position: absolute;
            width: 60px;
            height: 60px;
            border: 4px solid #c9a646;
        }

        .corner.tl {
            top: 10px;
            left: 10px;
            border-right: none;
            border-bottom: none;
        }

        .corner.tr {
            top: 10px;
            right: 10px;
            border-left: none;
            border-bottom: none;
        }

        .corner.bl {
            bottom: 10px;
            left: 10px;
            border-right: none;
            border-top: none;
        }

        .corner.br {
            bottom: 10px;
            right: 10px;
            border-left: none;
            border-top: none;
        }

        /* BISMILLAH */
        .bismillah {
            text-align: center;
            color: #1e5cc4;
            font-size: 24px;
            font-weight: bold;
        }

        .bismillah-translation {
            text-align: center;
            font-style: italic;
            font-size: 13px;
            margin-bottom: 15px;
        }

        /* HEADER */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .left {
            width: 40%;
        }

        .left h2 {
            margin: 0;
            font-size: 22px;
        }

        .red {
            color: #b11226;
            font-weight: bold;
        }

        .left h3 {
            margin: 5px 0;
        }

        .left h4 {
            margin-top: 8px;
        }

        .center-logo {
            width: 110px;
            height: 110px;
            /* border: 2px solid #777; */
            /* transform: rotate(45deg); */
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            text-align: center;
        }

        .right {
            width: 40%;
            text-align: right;
            direction: rtl;
        }

        .right h3 {
            margin: 5px 0;
        }

        /* TITLE (optional, not present in HTML) but keep style */
        .title-ar {
            text-align: center;
            font-size: 22px;
            margin-top: 10px;
        }

        /* ARABIC PARAGRAPH */
        .arabic {
            direction: rtl;
            text-align: right;
            font-size: 18px;
            line-height: 2;
            margin-top: 20px;
        }

        /* ENGLISH PARAGRAPH */
        .english {
            margin-top: 20px;
            font-size: 18px;
            line-height: 1.7;
        }

        /* FOOTER AREA */
        .footer {
            margin-top: 25px;
            display: flex;
            justify-content: space-between;
        }

        .qr {
            width: 90px;
            height: 90px;
            background: repeating-linear-gradient(45deg,
                    black,
                    black 5px,
                    white 5px,
                    white 10px);
        }

        /* SIGNATURES */
        .signatures {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }

        .sign {
            text-align: center;
        }

        .date-ar {
            direction: rtl;
        }

        .footer {
            margin-top: 25px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .footer-col {
            text-align: center;
        }

        .sno-section {
            text-align: center;
        }

        .sign {
            margin-top: 15px;
        }

        .qr {
            width: 90px;
            height: 90px;
            margin-top: 8px;
            background: repeating-linear-gradient(45deg,
                    black,
                    black 5px,
                    white 5px,
                    white 10px);
        }

        * {
            box-sizing: border-box;
        }
    </style>
</head>

<body>

    <div class="certificate">

        @php
            use App\Http\Controllers\Helper;

            $currentDate = date('d/m/Y');
        @endphp
        <!-- top-ornament DIV removed (the ball border) -->
        <div class="certificate-content">
            <div class="bismillah">
                بِسْمِ اللّٰهِ الرَّحْمٰنِ الرَّحِيْم
            </div>

            <div class="bismillah-translation">
                In the name of Allah the most Gracious the most Merciful
            </div>


            <div class="header">

                <div class="left">
                    <h2>Uganda Muslim Supreme Council</h2>
                    <h3 class="red">Idaad and Thanawi Examinations Board (U)</h3>
                    <h4>{{ $level }} Certificate</h4>
                </div>


                <div class="center-logo">
                    <img src="{{ asset('assets/images/brand/uplogolight.png') }}" alt="Covido logo"
                        style="max-width: 150%; max-height: 150%;">
                </div>


                <div class="right">
                    <h2>المجلس الأعلى الإسلامي الأوغندي</h2>
                    <h3 class="red">هيئة الامتحانات الإعدادية والثانوية (أوغندا)</h3>
                    <h4>الشهادة {{ $ArLevel }}</h4>
                </div>
            </div>

            @php
                $allSubjectCodes = DB::table('master_datas')
                    ->where('md_master_code_id', config('constants.options.ThanawiPapers'))
                    ->pluck('md_code');
                $stats = Helper::calculatePasslipStats(
                    $studentId,
                    $allSubjectCodes,
                    $studentCategory,
                    $year,
                    $schoolId,
                );
            @endphp

            <div class="arabic">
                الحمد لله رب العالمين والصلاة والسلام على خاتم الأنبياء والمرسلين نبينا محمد وعلى آله وصحبه ومن تبعهم
                بإحسان
                إلى يوم الدين أما بعد:

                تشهد الهيئة بأن الطالب <b>{{ Helper::getStudentName($studentId) }}</b> المولود سنة
                <b>{{ Helper::getStudentYearofBirth($studentId) }}</b> وجنسيته
                <b>{{ Helper::getStudentARNationality($studentId) }}</b>
                قد جلس في الامتحان النهائي للشهادة الإعدادية سنة
                <b>{{ Helper::getStudentAdmissionYear($studentId) }}</b>
                بمدرسة <b>{{ Helper::getStudentSchool($studentId) }}</b>
                برقم تسجيل <b>{{ $studentId }}</b>
                ونجح بنسبة <b>{{ $stats['average'] }}%</b> بتقدير
                <b>{{ Helper::getArabicGradeComment($stats['grade']) }}</b>.
            </div>


            <div class="english">
                The Board hereby certifies that <b>{{ Helper::getStudentName($studentId) }}</b> Born in
                <b>{{ Helper::getStudentYearofBirth($studentId) }}</b> of
                <b>{{ Helper::getStudentNationality($studentId) }}</b> Nationality, sat for the final examinations in
                <b>{{ Helper::getStudentAdmissionYear($studentId) }}</b>,
                at <b>{{ Helper::getStudentSchool($studentId) }}</b> under registration Number
                <b>{{ $studentId }}</b>, after successful completion of
                <b>{{ $level == "O'LEVEL" ? 'Idaad' : 'Thanawi' }} {{ $level }}</b> and passed with
                <b>{{ $stats['average'] }}%</b>.
                Grade: <b>{{ $stats['grade'] }}</b>.
            </div>

            <div class="footer">

                <div class="footer-col">
                    <div>Date of Issue {{ $currentDate }}</div>

                    <div class="sign">
                        <b> سكرتير التعليم بالمجلس<br><br></b>
                        Secretary for Education (UMSC)
                    </div>
                </div>


                <div class="sno-section">
                    <div>SNO: {{ random_int(1000000, 9999999) }}</div>
                    <div class="qr"></div>
                </div>


                <div class="footer-col date-ar">
                    <div>التاريخ {{ Helper::toArabicNumberDate($currentDate) }}</div>

                    <div class="sign">
                        <b> السكرتير التنفيذي للهيئة<br><br></b>
                        Executive Secretary (ITEBU)
                    </div>
                </div>

            </div>
            <!-- bottom-ornament DIV removed (the ball border) -->
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <script>
        window.onload = function() {

            const element = document.querySelector('.certificate');

            const opt = {
                margin: 0,
                filename: 'certificate_{{ $studentId }}.pdf',
                image: {
                    type: 'jpeg',
                    quality: 1
                },
                html2canvas: {
                    scale: 4,
                    useCORS: true
                },
                jsPDF: {
                    unit: 'mm',
                    format: 'a4',
                    orientation: 'landscape'
                }
            };

            html2pdf().set(opt).from(element).save();
        };
    </script>
</body>

</html>
