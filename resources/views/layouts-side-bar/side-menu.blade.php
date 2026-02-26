<div class="app-sidebar app-sidebar2">
    {{-- <div class="app-sidebar__logo" style="height: 155px; padding: 50px 10px;"> --}}
        <div class="app-sidebar__logo">
            <a class="header-brand" href="{{ url('/student/dashboard') }}">
                <img src="{{ URL::asset('assets/images/brand/uplogolight.png') }}" alt="Covido logo"
                    style="width: 100%; height: auto; max-width: 170px;">
            </a>
        </div>
    </div>

    <?php
use App\Helpers\PermissionHelper;
    ?>

    <aside class="app-sidebar app-sidebar3">
        <ul class="side-menu" style="margin-top:100px !important;">

            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

            @if (!Session('LoggedSchool'))

                <li class="slide">
                    <a class="side-menu__item" href="{{ url('/student/dashboard') }}">
                        <i class="fa fa-home fa-2x mr-3"></i>
                        Dashboard
                    </a>
                </li>

                <li class="slide">
                    <a class="side-menu__item" href="{{ route('school.allSchools') }}">
                        <i class="fa fa-school fa-2x mr-3"></i>
                        Schools
                    </a>
                </li>

                <li class="slide">
                    <a class="side-menu__item" href="{{ route('students.individual.search') }}">
                        <i class="fa fa-user-graduate fa-2x mr-3"></i>
                        Students
                    </a>
                </li>

                <li class="slide">
                    <a class="side-menu__item" href="{{ url('/enter-marks') }}">
                        <i class="fas fa-balance-scale-right fa-2x mr-3"></i>
                        Grading & Marks
                    </a>
                </li>

                <li class="slide">
                    <a class="side-menu__item" href="{{ route('school.passwords.setup') }}">
                        <i class="fas fa-key fa-2x mr-3"></i>
                        Schools & Passwords
                    </a>
                </li>
            @endif

            @if (!Session('LoggedStudent'))
                <li class="slide">
                    <a class="side-menu__item" href="{{ url('/school/dashboard') }}">
                        <i class="fa fa-home fa-2x mr-3"></i>
                        Dashboard
                    </a>
                </li>
            @endif

            <li class="slide">
                <a class="side-menu__item" href="#" id="logoutMenu">
                    <i class="fa fa-sign-out fa-2x mr-3"></i>
                    Logout
                </a>
            </li>

        </ul>
    </aside>

    <style>
        .sub-menu {
            display: none;
            padding-left: 40px;
        }

        .slide.active>.sub-menu {
            display: block;
        }

        .has-sub>a {
            cursor: pointer;
        }
    </style>

    {{-- Scripts (common) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#helpSupportToggle').on('click', function (e) {
                e.preventDefault();
                $(this).parent('.slide').toggleClass('active');
            });
        });

        document.getElementById('logoutMenu').addEventListener('click', function (event) {
            event.preventDefault();

            Swal.fire({
                title: "Are you sure?",
                text: "Do you really want to Logout ?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, Logout",
                cancelButtonText: "Cancel",
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route('student-logout') }}';
                }
            });
        });
    </script>

    <!--aside closed-->