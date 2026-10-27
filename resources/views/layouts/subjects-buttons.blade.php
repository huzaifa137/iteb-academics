{{-- <div class="row w-100 g-2">
    <div class="col-12 col-sm-4 mb-2">
        <a  class="btn btn-white text-dark w-100">
            <i class="fas fa-search me-2"></i> Search Student
        </a>
    </div>

    <div class="col-12 col-sm-4 mb-2">
        <a href="{{ route('all.students.dashboard') }}" class="btn btn-white text-dark w-100">
            <i class="fas fa-user-plus me-2"></i> Add Students
        </a>
    </div>

    <div class="col-12 col-sm-4 mb-2">
        <a href="{{ route('students.all.students') }}" class="btn btn-white text-dark w-100">
            <i class="fas fa-users me-2"></i> All Students
        </a>
    </div>
</div> --}}


{{-- Quick-action buttons: no hover / focus / active effects (no radius change, border, shadow, fade) --}}
<style>
    .sb-nav .btn.btn-white,
    .sb-nav .btn.btn-white:hover,
    .sb-nav .btn.btn-white:focus,
    .sb-nav .btn.btn-white:focus-visible,
    .sb-nav .btn.btn-white:active,
    .sb-nav .btn.btn-white.focus,
    .sb-nav .btn.btn-white:not(:disabled):not(.disabled):active,
    .sb-nav .btn.btn-white:not(:disabled):not(.disabled):active:focus {
        background: #fff !important;
        background-image: none !important;
        color: #424e79 !important;
        border: 1px solid transparent !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        outline: 0 !important;
        opacity: 1 !important;
        filter: none !important;
        transform: none !important;
        transition: none !important;
        animation: none !important;
        -webkit-tap-highlight-color: transparent;
    }

    .sb-nav .btn.btn-white::before,
    .sb-nav .btn.btn-white::after {
        content: none !important;
        display: none !important;
    }
</style>

<div class="row w-100 g-2 sb-nav">
    <div class="col-12 col-sm-3 mb-2">
        <a href="{{ route('students.individual.search') }}" class="btn btn-white text-dark w-100">
            <i class="fas fa-search me-2"></i> Search Student
        </a>
    </div>

    <div class="col-12 col-sm-3 mb-2">
        <a href="{{ route('students.add.new.student') }}" class="btn btn-white text-dark w-100">
            <i class="fas fa-user-plus me-2"></i> Add Students
        </a>
    </div>

    <div class="col-12 col-sm-3 mb-2">
        <a href="{{route('students.all.students')}}" class="btn btn-white text-dark w-100">
            <i class="fas fa-users me-2"></i> All Students
        </a>
    </div>

    <div class="col-12 col-sm-3 mb-2">
        <a href="{{ route('students.attendance-sheet') }}" class="btn btn-white text-dark w-100">
            <i class="fas fa-clipboard-list me-2"></i> Attendance Sheet
        </a>
    </div>

    <div class="col-12 col-sm-3 mb-2">
        <a href="{{ route('students.timetable') }}" class="btn btn-white text-dark w-100">
            <i class="fas fa-calendar-alt me-2"></i> Exam Timetable
        </a>
    </div>

    <div class="col-12 col-sm-3 mb-2">
        <a href="{{ route('students.examination-cards') }}" class="btn btn-white text-dark w-100">
            <i class="fas fa-id-card me-2"></i> Examination Cards
        </a>
    </div>

    <div class="col-12 col-sm-3 mb-2">
        <a href="{{ route('students.school-album') }}" class="btn btn-white text-dark w-100">
            <i class="fas fa-images me-2"></i> School Album
        </a>
    </div>
</div>
