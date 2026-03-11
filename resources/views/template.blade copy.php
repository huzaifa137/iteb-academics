<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Idaad and Thanawi Pass Slip</title>
    <style>
        :root {
            --border-green: #2e5a31;
            --light-green: #e9f5ea;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            padding: 20px;
            margin: 0;
        }

        .document-container {
            width: 800px;
            background-color: #fff;
            border: 15px solid var(--border-green);
            padding: 20px;
            position: relative;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        /* Watermark */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-15deg);
            font-size: 60px;
            color: rgba(46, 90, 49, 0.05);
            pointer-events: none;
            z-index: 0;
            text-align: center;
            width: 100%;
        }

        header {
            text-align: center;
            border-bottom: 3px solid var(--border-green);
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .header-arabic { font-size: 24px; font-weight: bold; margin: 5px 0; }
        .header-english { font-size: 20px; font-weight: bold; margin: 5px 0; }

        .logo-section {
            display: flex;
            justify-content: center;
            margin: 10px 0;
        }

        .logo-placeholder {
            width: 100px;
            height: 100px;
            border: 2px solid gold;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
        }

        .pass-slip-banner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: white;
            padding: 5px 0;
        }

        .pass-slip-banner h1 { margin: 0; font-size: 28px; }

        .student-info {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            line-height: 1.6;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }

        .info-col { width: 48%; }
        .info-row { display: flex; margin-bottom: 2px; }
        .label { font-weight: bold; min-width: 80px; }
        .arabic-label { text-align: right; flex-grow: 1; direction: rtl; }

        .photo-box {
            width: 100px;
            height: 120px;
            border: 1px solid #000;
            background-color: #ddd;
            text-align: center;
            line-height: 120px;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            position: relative;
            z-index: 1;
        }

        th {
            background-color: white;
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }

        td {
            border: 1px solid #000;
            padding: 4px;
        }

        .category-row {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .score-col { text-align: center; width: 60px; font-weight: bold; }
        .code-col { text-align: center; width: 60px; }

        .footer-stats {
            margin-top: 10px;
            border-top: 2px solid #000;
            padding-top: 5px;
        }

        .stat-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            font-weight: bold;
        }

        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            text-align: center;
        }

        .sig-box { width: 200px; border-top: 1px solid #000; padding-top: 5px; }

        .grading-scale {
            margin-top: 20px;
            font-size: 10px;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 10px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
    </style>
</head>
<body>

<div class="document-container">
    <div class="watermark">
        IDAAD AND THANAWI<br>EXAMINATIONS BOARD
    </div>

    <header>
        <div class="header-arabic">بسم الله الرحمن الرحيم</div>
        <div class="header-arabic">هيئة الامتحانات الإعدادية والثانوية (أوغندا)</div>
        <div class="header-english">IDAAD AND THANAWI EXAMINATIONS BOARD (U)</div>
        
        <div class="logo-section">
            <div class="logo-placeholder">LOGO HERE</div>
        </div>
    </header>

    <div class="pass-slip-banner">
        <h1>PASS SLIP</h1>
        <h1 style="direction: rtl;">كشف الدرجات</h1>
    </div>

    <div class="student-info">
        <div class="info-col">
            <div class="info-row"><span class="label">NAME:</span> SENYONJO ISMAEL ABDUL-SSWAMM</div>
            <div class="info-row"><span class="label">INDEX NO:</span> IT-046-TH-039-2024</div>
            <div class="info-row"><span class="label">GENDER:</span> Male</div>
            <div class="info-row"><span class="label">SCH NAME:</span> MADRASAT DIINIYYAH BUGEMBE ISLAMIC INSTITUTE</div>
        </div>
        <div class="info-col" style="text-align: right;">
            <div class="photo-box" style="float: right; margin-left: 10px;">PHOTO</div>
            <div>اسم الطالب: سينيونجو إسماعيل عبد الصمد</div>
            <div>المرحلة: الثانوية</div>
            <div>العام: ٢٠٢٤</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="code-col">PAPER CODE</th>
                <th>PAPER</th>
                <th class="score-col">SCORE</th>
                <th class="score-col">الدرجات</th>
                <th>اسم الورقة</th>
                <th class="code-col">رمز الورقة</th>
            </tr>
        </thead>
        <tbody>
            <tr class="category-row">
                <td colspan="3">ARABIC LANGUAGE</td>
                <td colspan="3" style="text-align: right;">اللغة العربية</td>
            </tr>
            <tr>
                <td class="code-col">AR-002</td><td>ARABIC LITERATURE</td><td class="score-col">81</td><td class="score-col">٨١</td><td style="text-align: right;">الأدب العربي</td><td class="code-col">AR-002</td>
            </tr>
             <tr>
                <td class="code-col">AR-001</td><td>RHETORIC</td><td class="score-col">81</td><td class="score-col">٨١</td><td style="text-align: right;">الأدب العربي</td><td class="code-col">AR-001</td>
            </tr>
             <tr>
                <td class="code-col">AR-004</td><td>GRAMMAR & MORPHOLOGY</td><td class="score-col">81</td><td class="score-col">٨١</td><td style="text-align: right;">الأدب العربي</td><td class="code-col">AR-004</td>
            </tr>
            <tr>
                <td class="code-col">AR-003</td><td>COMPOSITION & COMPREHENSION</td><td class="score-col">83</td><td class="score-col">٨٣</td><td style="text-align: right;">البلاغة</td><td class="code-col">AR-003</td>
            </tr>
            
            <tr class="category-row">
                <td colspan="3">FAITH & CIVILIZATION</td>
                <td colspan="3" style="text-align: right;">العقيدة والحضارة</td>
            </tr>
            <tr>
                <td class="code-col">FC-005</td><td>ISLAMIC MONOTHEISM</td><td class="score-col">92</td><td class="score-col">٩٢</td><td style="text-align: right;">التوحيد</td><td class="code-col">FC-005</td>
            </tr>
            <tr>
                <td class="code-col">FC-007</td><td>ISLAMIC HISTORY</td><td class="score-col">92</td><td class="score-col">٩٢</td><td style="text-align: right;">التوحيد</td><td class="code-col">FC-007</td>
            </tr>
            <tr>
                <td class="code-col">FC-006</td><td>RELIGIONS & SECTS</td><td class="score-col">100</td><td class="score-col">١٠٠</td><td style="text-align: right;">الأديان والفرق</td><td class="code-col">FC-006</td>
            </tr>

            <tr class="category-row">
                <td colspan="3">JURISPRUDENCE & ITS SOURCES</td>
                <td colspan="3" style="text-align: right;">الفقه وأصوله</td>
            </tr>
            <tr>
                <td class="code-col">JS-009</td><td>SOURCES OF JURISPRUDENCE</td><td class="score-col">96</td><td class="score-col">٩٦</td><td style="text-align: right;">فقه الأحوال الشخصية</td><td class="code-col">JS-009</td>
            </tr>
            <tr>
                <td class="code-col">JS-010</td><td>ISLAMIC FAMILY LAW</td><td class="score-col">96</td><td class="score-col">٩٦</td><td style="text-align: right;">فقه الأحوال الشخصية</td><td class="code-col">JS-010</td>
            </tr>
            <tr>
                <td class="code-col">JS-008</td><td>INHERITANCE</td><td class="score-col">97</td><td class="score-col">٩٧</td><td style="text-align: right;">الفرائض</td><td class="code-col">JS-008</td>
            </tr>

            <tr class="category-row">
                <td colspan="3">PROPHETIC TRADITIONS</td>
                <td colspan="3" style="text-align: right;">الفقه وأصوله</td>
            </tr>
            <tr>
                <td class="code-col">PT-012</td><td>SOURCES OF PROPHETIC TRADITIONS</td><td class="score-col">96</td><td class="score-col">٩٦</td><td style="text-align: right;">فقه الأحوال الشخصية</td><td class="code-col">PT-012</td>
            </tr>
            <tr>
                <td class="code-col">PT-013</td><td>TRADITIONS OF THE PROPHET</td><td class="score-col">96</td><td class="score-col">٩٦</td><td style="text-align: right;">فقه الأحوال الشخصية</td><td class="code-col">PT-013</td>
            </tr>

            <tr class="category-row">
                <td colspan="3">QURAN & ITS SCIENCES</td>
                <td colspan="3" style="text-align: right;">الفقه وأصوله</td>
            </tr>
            <tr>
                <td class="code-col">QS-014</td><td>SOURCES OF EXEGESIS</td><td class="score-col">96</td><td class="score-col">٩٦</td><td style="text-align: right;">فقه الأحوال الشخصية</td><td class="code-col">QS-014</td>
            </tr>
            <tr>
                <td class="code-col">QS-015</td><td>QURAN RECITATION AND IT'S RULES</td><td class="score-col">96</td><td class="score-col">٩٦</td><td style="text-align: right;">فقه الأحوال الشخصية</td><td class="code-col">QS-015</td>
            </tr>
            <tr>
                <td class="code-col">QS-016</td><td>QURAN MEMORIZATION AND EXEGESIS</td><td class="score-col">96</td><td class="score-col">٩٦</td><td style="text-align: right;">فقه الأحوال الشخصية</td><td class="code-col">QS-016</td>
            </tr>
        </tbody>
    </table>

    <div class="footer-stats">
        <div class="stat-row">
            <span>TOTAL MARK: 1286</span>
            <span style="direction: rtl;">المجموع: ١٢٨٦</span>
        </div>
        <div class="stat-row">
            <span>AVERAGE SCORE: 85.73</span>
            <span style="direction: rtl;">النسبة المئوية: ٨٥,٧٣</span>
        </div>
        <div class="stat-row">
            <span>GRADE: FIRST CLASS</span>
            <span style="direction: rtl;">التقدير: ممتاز</span>
        </div>
    </div>

    <div class="signatures">
        <div class="sig-box">
            CHAIRMAN<br><strong>مدير هيئة الامتحانات</strong>
        </div>
        <div class="sig-box">
            EXECUTIVE SECRETARY<br><strong>السكرتير التنفيذي</strong>
        </div>
    </div>

    <div class="grading-scale">
        <div>80-100 - EXCELLENT (FIRST CLASS)</div>
        <div>70-79.9 - VERY GOOD (SECOND CLASS UPPER)</div>
        <div>60-69.9 - GOOD (SECOND CLASS LOWER)</div>
    </div>
</div>

</body>
</html>