{{--
    One examination card (matches the approved sample).
    Expects: $student, $photo, $cardNo, $house, $year, $stage, $stageEn, $stampData
--}}
<div class="exam-card">
    {{-- Card number cell --}}
    <div class="c-no">
        <div class="c-no-ar">رقم البطاقة</div>
        <div class="c-no-en">Card no.</div>
        <div class="c-no-num">{{ number_format($cardNo) }}</div>
    </div>

    {{-- Board name --}}
    <div class="c-head">
        <div class="c-head-ar">هيئة الامتحانات الإعدادية والثانوية &ndash; أوغندا</div>
        <div class="c-head-en">IDAAD AND THANAWI EXAMINATIONS BOARD - (U)</div>
    </div>

    {{-- Student photo --}}
    <div class="c-photo"><img src="{{ $photo }}" alt=""></div>

    {{-- Body rows --}}
    <div class="c-row r1">
        <span class="ar"><span class="lbl">اسم المعهد :</span> <span class="val">{{ $house->House_AR ?: $house->House }}</span></span>
    </div>

    <div class="c-row r2">
        <span class="en"><span class="lbl">School:</span> <span class="val">{{ $house->House }}</span></span>
        <span class="ar"><span class="lbl">العام الدراسي :</span> <span class="val">{{ \App\Http\Controllers\Helper::toArabicNumberDate($year) }}</span></span>
    </div>

    <div class="c-row r3">
        <span class="en"><span class="lbl">Student's Name:</span> <span class="val">{{ $student->Student_Name }}</span></span>
        <span class="ar"><span class="lbl">المرحلة :</span><span class="val">{{ $stage }}</span></span>
    </div>

    <div class="c-row r4">
        <span class="en"><span class="val">{{ $student->Student_ID }}</span></span>
        <span class="ar-small lbl">رقم الطالب:</span>
    </div>

    {{-- Footer --}}
    <div class="c-foot">يجب إظهار هذه البطاقة للمراقب أثناء جريان الامتحانات.</div>
    <div class="c-foot-box"></div>

    @if($stampData)
        <img class="c-stamp" src="{{ $stampData }}" alt="">
    @endif
</div>
