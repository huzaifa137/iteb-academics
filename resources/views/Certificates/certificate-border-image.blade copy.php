<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>O LEVEL Certificate</title>
    <style>
        body {
            background: #e9ecef;
            font-family: "Times New Roman", serif;
            margin: 0;
            padding: 20px;
        }

        /* CERTIFICATE CONTAINER */
        .certificate {
            width: 1100px;
            margin: 40px auto;
            position: relative;
            background: white;
        }

        /* Border container using multiple divs for each side */
        .certificate-border {
            position: relative;
            padding: 46px;
            /* Height of your border image */
            background: white;
        }

        /* Border sides */
        .border-top {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 46px;
            background-image: url('{{ asset('assets/images/certificate-border.png') }}');
            background-repeat: repeat-x;
            background-size: auto 46px;
            transform: rotate(180deg);
            /* Flip if needed */
        }

        .border-bottom {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 46px;
            background-image: url('{{ asset('assets/images/certificate-border.png') }}');
            background-repeat: repeat-x;
            background-size: auto 46px;
        }

        .border-left {
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            width: 90px;
            /* Width of your border image */
            background-image: url('{{ asset('assets/images/certificate-border.png') }}');
            background-repeat: repeat-y;
            background-size: 90px auto;
        }

        .border-right {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            width: 90px;
            background-image: url('{{ asset('assets/images/certificate-border.png') }}');
            background-repeat: repeat-y;
            background-size: 90px auto;
        }

        /* Corners - using the same image but positioned */
        .corner-tl {
            position: absolute;
            top: 0;
            left: 0;
            width: 90px;
            height: 46px;
            background-image: url('{{ asset('assets/images/certificate-border.png') }}');
            background-size: cover;
            z-index: 3;
        }

        .corner-tr {
            position: absolute;
            top: 0;
            right: 0;
            width: 90px;
            height: 46px;
            background-image: url('{{ asset('assets/images/certificate-border.png') }}');
            background-size: cover;
            transform: scaleX(-1);
            /* Flip horizontally */
            z-index: 3;
        }

        .corner-bl {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 90px;
            height: 46px;
            background-image: url('{{ asset('assets/images/certificate-border.png') }}');
            background-size: cover;
            transform: scaleY(-1);
            /* Flip vertically */
            z-index: 3;
        }

        .corner-br {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 90px;
            height: 46px;
            background-image: url('{{ asset('assets/images/certificate-border.png') }}');
            background-size: cover;
            transform: scale(-1, -1);
            /* Flip both */
            z-index: 3;
        }

        /* Certificate content */
        .certificate-content {
            position: relative;
            z-index: 2;
            background: white;
            padding: 40px 60px;
            border: 1px solid #ddd;
            /* Optional inner border */
        }

        /* Alternative approach using border-image (simpler) */
        .certificate-border-image {
            border: 46px solid transparent;
            border-image: url('{{ asset('assets/images/certificate-border.png') }}') 46 round;
            background: white;
            padding: 40px 60px;
        }

        /* Your existing styles */
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
            border: 2px solid #777;
            transform: rotate(45deg);
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

        .arabic {
            direction: rtl;
            text-align: right;
            font-size: 18px;
            line-height: 2;
            margin-top: 20px;
        }

        .english {
            margin-top: 20px;
            font-size: 18px;
            line-height: 1.7;
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

        .date-ar {
            direction: rtl;
        }
    </style>
</head>

<body>


    <!-- OPTION 2: Using multiple divs for more control (if border-image doesn't work well) -->
    <div class="certificate">
        <div class="certificate-border">
            <!-- Border elements -->
            <div class="border-top"></div>
            <div class="border-bottom"></div>
            <div class="border-left"></div>
            <div class="border-right"></div>

            <!-- Corners -->
            <div class="corner-tl"></div>
            <div class="corner-tr"></div>
            <div class="corner-bl"></div>
            <div class="corner-br"></div>

            <!-- Content -->
            <div class="certificate-content">
                <!-- Same content as above -->
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
                        <h4>'O' LEVEL Certificate</h4>
                    </div>

                    <div class="center-logo">
                        ITEBU
                    </div>

                    <div class="right">
                        <h2>المجلس الأعلى الإسلامي الأوغندي</h2>
                        <h3 class="red">هيئة الامتحانات الإعدادية والثانوية (أوغندا)</h3>
                        <h4>الشهادة الإعدادية</h4>
                    </div>
                </div>

                <div class="arabic">
                    الحمد لله رب العالمين والصلاة والسلام على خاتم الأنبياء والمرسلين نبينا محمد وعلى آله وصحبه ومن
                    تبعهم
                    بإحسان إلى يوم الدين أما بعد:
                    تشهد الهيئة بأن الطالب <b>SSERUBIRI SUHAIL</b> المولود سنة <b>2008</b> وجنسيته <b>أوغندي</b>
                    قد جلس في الامتحان النهائي للشهادة الإعدادية سنة <b>2024</b>
                    بمدرسة <b>EMIRATES HIGH SCHOOL</b>
                    برقم تسجيل <b>IT-126-ID-001-2024</b>
                    ونجح بنسبة <b>79.30%</b> بتقدير <b>جيد جداً مرتفع</b>.
                </div>

                <div class="english">
                    The Board hereby certifies that <b>SSERUBIRI SUHAIL</b> Born in <b>2008</b> of
                    <b>UGANDAN</b> Nationality, sat for the final examinations in <b>2024</b>,
                    at <b>EMIRATES HIGH SCHOOL</b> under registration Number
                    <b>IT-126-ID-001-2024</b>, after successful completion of
                    <b>Idaad ('O' LEVEL)</b> and passed with <b>79.30%</b>.
                    Grade: <b>SECOND CLASS UPPER</b>.
                </div>

                <div class="footer">
                    <div class="footer-col">
                        <div>Date of Issue 15/02/2025</div>
                        <div class="sign">
                            سكرتير التعليم بالمجلس<br><br>
                            Secretary for Education (UMSC)
                        </div>
                    </div>

                    <div class="sno-section">
                        <div>SNO: 1242126</div>
                        <div class="qr"></div>
                    </div>

                    <div class="footer-col date-ar">
                        <div>التاريخ ١٥ / ٠٢ / ٢٠٢٥</div>
                        <div class="sign">
                            السكرتير التنفيذي للهيئة<br><br>
                            Executive Secretary (ITEBU)
                        </div>
                    </div>
                </div>
                <!-- ... rest of your content ... -->
            </div>
        </div>
    </div>

</body>

</html>
