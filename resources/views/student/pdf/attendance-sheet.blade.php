<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="utf-8">
    <title>كشف الحضور</title>
    <style>
        @page { margin: 30px 35px; }

        * { box-sizing: border-box; }

        body {
            font-family: 'Dejavu Sans', 'Segoe UI', Tahoma, Arial, sans-serif;
            direction: rtl;
            color: #000;
            font-size: 12px;
        }

        .bismillah {
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            margin: 0 0 8px 0;
        }

        table.masthead {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }

        table.masthead td {
            vertical-align: middle;
            width: 33.33%;
        }

        table.masthead .en-title {
            direction: ltr;
            text-align: left;
            font-weight: bold;
            font-size: 12px;
            line-height: 1.4;
        }

        table.masthead .ar-title {
            text-align: right;
            font-weight: bold;
            font-size: 14px;
            line-height: 1.6;
        }

        table.masthead .logo-cell {
            text-align: center;
        }

        table.masthead .logo-cell img {
            width: 70px;
            height: 70px;
        }

        .sheet-title {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            margin: 6px 0 12px 0;
        }

        table.info-box {
            width: 100%;
            border: 1px solid #000;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        table.info-box td {
            border: 1px solid #000;
            padding: 6px 10px;
            font-size: 12px;
            vertical-align: top;
        }

        table.info-box .label {
            font-weight: bold;
        }

        table.roster {
            width: 100%;
            border-collapse: collapse;
        }

        table.roster th,
        table.roster td {
            border: 1px solid #000;
            padding: 6px 6px;
            font-size: 11px;
            text-align: center;
        }

        table.roster thead th {
            background: #f0f0f0;
            font-weight: bold;
        }

        table.roster td.name-cell {
            text-align: right;
            padding-right: 10px;
        }

        table.roster td.reg-cell {
            direction: ltr;
        }

        .col-no { width: 6%; }
        .col-name { width: 34%; }
        .col-reg { width: 22%; }
        .col-sign { width: 19%; }
        .col-booklet { width: 19%; }

        table.footer-block {
            width: 100%;
            margin-top: 26px;
            border-collapse: collapse;
        }

        table.footer-block td {
            width: 50%;
            padding: 4px 0;
            font-size: 12px;
            vertical-align: top;
        }

        .sig-line {
            display: inline-block;
            border-bottom: 1px solid #000;
            min-width: 160px;
            margin-right: 4px;
        }

        table.footer-block .row {
            display: block;
            margin-bottom: 18px;
        }
    </style>
</head>
<body>

    <p class="bismillah">بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ</p>

    <table class="masthead">
        <tr>
            <td class="en-title">
                IDAAD AND THANAWI<br>
                EXAMINATIONS BOARD (U)
            </td>
            <td class="logo-cell">
                @if($logoData)
                    <img src="{{ $logoData }}" alt="ITEBU">
                @endif
            </td>
            <td class="ar-title">
                هيئة الامتحانات الإعدادية والثانوية<br>
                ( أوغندا )
            </td>
        </tr>
    </table>

    <p class="sheet-title">
        كشف أسماء الطلبة/الطالبات في الامتحان النهائي لعام: {{ $year }}
    </p>

    <table class="info-box">
        <tr>
            <td style="width:50%;">
                <span class="label">المادة:</span> {{ $subject }}<br>
                <span class="label">الورقة:</span> {{ $paper }}<br>
                <span class="label">الزمن:</span>
                @if($startTime || $endTime)
                    {{ $startTime }} - {{ $endTime }}
                @endif
            </td>
            <td style="width:50%;">
                <span class="label">اسم المعهد:</span> {{ $house->House_AR ?? $house->House }}<br>
                <span class="label">رقم المعهد:</span> {{ $house->Number }}<br>
                <span class="label">المرحلة:</span> {{ $stage }}
                &nbsp;&nbsp;&nbsp;
                <span class="label">التاريخ:</span> {{ $examDate }}
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
            @forelse($students as $i => $student)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td class="name-cell">{{ $student->Student_Name_AR ?: $student->Student_Name }}</td>
                    <td class="reg-cell">{{ $student->Student_ID }}</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="padding: 20px;">لا يوجد طلبة مسجلون</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="footer-block">
        <tr>
            <td>
                <span class="row">اسم المراقب: <span class="sig-line">&nbsp;</span></span>
                <span class="row">مكان عمله: <span class="sig-line">&nbsp;</span></span>
            </td>
            <td>
                <span class="row">التاريخ: <span class="sig-line">&nbsp;</span></span>
                <span class="row">التوقيع: <span class="sig-line">&nbsp;</span></span>
            </td>
        </tr>
    </table>

</body>
</html>