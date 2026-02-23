<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Item Academics</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="{{ asset('asset/img/favicon.ico') }}" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@600&family=Lobster+Two:wght@700&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('asset/lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('asset/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('asset/css/style.css') }}" rel="stylesheet">
</head>

<body>
    <div class="container-xxl bg-white p-0">
        <!-- Spinner Start -->
        <div id="spinner"
            class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->


        <!-- Navbar Start -->
        <nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top px-4 px-lg-5 py-lg-0">
            <a href="{{ route('home.page') }}" class="navbar-brand d-flex align-items-center">
                <img src="{{ asset('assets/images/brand/logo.png') }}" alt="ITEB Logo" class="navbar-logo me-3">
                <h1 class="m-0 text-primary">ITEB</h1>
            </a>
            <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav mx-auto">
                    <a href="{{ route('home.page') }}" class="nav-item nav-link active">Home</a>
                    <a href="{{ route('about.us') }}" class="nav-item nav-link">About Us</a>
                    <a href="{{ route('contact.us') }}" class="nav-item nav-link">Contact Us</a>
                    <a href="{{ route('users.login') }}" class="nav-item nav-link">Login</a>
                </div>
                <a href="{{ route('public.portal') }}"
                    class="btn btn-primary rounded-pill px-3 d-none d-lg-block">Portal<i
                        class="fa fa-arrow-right ms-3"></i></a>
            </div>
        </nav>
        <!-- Navbar End -->


        <!-- Carousel Start -->
        <div class="container-fluid p-0 mb-5">
            <div class="owl-carousel header-carousel position-relative">
                <div class="owl-carousel-item position-relative">
                    <img class="img-fluid" src="{{ asset('asset/img/carousel-1.jpg') }}" alt="">
                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center"
                        style="background: rgba(0, 0, 0, .2);">
                        <div class="container">
                            <div class="row justify-content-start">
                                <div class="col-10 col-lg-8">
                                    <h1 class="display-2 text-white animated slideInDown mb-4">Idaad & Thanawi
                                        Examination Board</h1>
                                    <p class="fs-5 fw-medium text-white mb-4 pb-2">A modern, transparent, and efficient
                                        system for grading Idaad and Thanawi examination results across Uganda.</p>
                                    <a href=""
                                        class="btn btn-primary rounded-pill py-sm-3 px-sm-5 me-3 animated slideInLeft">Learn
                                        More</a>
                                    <a href=""
                                        class="btn btn-dark rounded-pill py-sm-3 px-sm-5 animated slideInRight">Our
                                        Classes</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="owl-carousel-item position-relative">
                    <img class="img-fluid" src="{{ asset('asset/img/carousel-2.jpg') }}" alt="">
                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center"
                        style="background: rgba(0, 0, 0, .2);">
                        <div class="container">
                            <div class="row justify-content-start">
                                <div class="col-10 col-lg-8">
                                    <h1 class="display-2 text-white animated slideInDown mb-4">Make A Brighter Future
                                        For Your Child</h1>
                                    <p class="fs-5 fw-medium text-white mb-4 pb-2">Ensuring accuracy and fairness for
                                        every student.</p>
                                    <a href=""
                                        class="btn btn-primary rounded-pill py-sm-3 px-sm-5 me-3 animated slideInLeft">Learn
                                        More</a>
                                    <a href=""
                                        class="btn btn-dark rounded-pill py-sm-3 px-sm-5 animated slideInRight">Our
                                        Classes</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Carousel End -->


        <!-- Facilities Start -->
        <div class="container-xxl py-5">
            <div class="container">
                <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                    <h1 class="mb-3">Examination Structures</h1>
                    <p>Idaad (O-Level): The Ordinary Level secondary education stage specializing in Islamic Theology.
                        Generally a 4-year program.</p>
                    <p>Thanawi (A-Level): The Advanced Level secondary education stage. Generally a 2-year program.</p>
                </div>
                <div class="row g-4">
                    <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="facility-item">
                            <div class="facility-icon bg-primary">
                                <span class="bg-primary"></span>
                                <i class="fa fa-bus-alt fa-3x text-primary"></i>
                                <span class="bg-primary"></span>
                            </div>
                            <div class="facility-text bg-primary">
                                <h3 class="text-primary mb-3">Data Collection</h3>
                                <p class="mb-0">Secure digital submission of examination papers with automated
                                    validation and error detection.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.3s">
                        <div class="facility-item">
                            <div class="facility-icon bg-success">
                                <span class="bg-success"></span>
                                <i class="fa fa-futbol fa-3x text-success"></i>
                                <span class="bg-success"></span>
                            </div>
                            <div class="facility-text bg-success">
                                <h3 class="text-success mb-3">Automated Grading</h3>
                                <p class="mb-0">System processes results using AI-powered algorithms aligned with
                                    national grading standards.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.5s">
                        <div class="facility-item">
                            <div class="facility-icon bg-warning">
                                <span class="bg-warning"></span>
                                <i class="fa fa-home fa-3x text-warning"></i>
                                <span class="bg-warning"></span>
                            </div>
                            <div class="facility-text bg-warning">
                                <h3 class="text-warning mb-3">Quality Control</h3>
                                <p class="mb-0">Multi-level verification by qualified examiners ensures accuracy
                                    before final approval.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.7s">
                        <div class="facility-item">
                            <div class="facility-icon bg-info">
                                <span class="bg-info"></span>
                                <i class="fa fa-chalkboard-teacher fa-3x text-info"></i>
                                <span class="bg-info"></span>
                            </div>
                            <div class="facility-text bg-info">
                                <h3 class="text-info mb-3">Result Distribution</h3>
                                <p class="mb-0">Secure access to results via web portal, mobile app, SMS, and
                                    official school notifications.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Facilities End -->


        <!-- About Start -->
        <div class="container-xxl py-5">
            <div class="container">
                <div class="row g-5 align-items-center">
                    <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                        <h1 class="mb-4">About ITEB</h1>
                        <p>UMSC Examination Board: Based in Kawempe. Conducts examinations under the Uganda Muslim
                            Supreme Council. Dr. Sheikh Ziyad Swaleh Lubanga is the Executive Secretary</p>
                        <p class="mb-4">IMPORTANT: many schools are adopting a "duo curriculum" (Combining standard
                            secondary subjects with advanced Islamic Theology) to help students compete on the
                            international market</p>
                        <div class="row g-4 align-items-center">
                            <div class="col-sm-6">
                                <a class="btn btn-primary rounded-pill py-3 px-5" href="">Read More</a>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center">
                                    <img class="rounded-circle flex-shrink-0" src="{{ asset('asset/img/user.jpg') }}"
                                        alt="" style="width: 45px; height: 45px;">
                                    <div class="ms-3">
                                        <h6 class="text-primary mb-1">Prof. Dr Ziyad Swalleh Lubanga</h6>
                                        <small>Executive Secretary</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 about-img wow fadeInUp" data-wow-delay="0.5s">
                        <div class="row">
                            <div class="col-12 text-center">
                                <img class="img-fluid w-75 rounded-circle bg-light p-3"
                                    src="{{ asset('asset/img/about-1.jpg') }}" alt="">
                            </div>
                            <div class="col-6 text-start" style="margin-top: -150px;">
                                <img class="img-fluid w-100 rounded-circle bg-light p-3"
                                    src="{{ asset('asset/img/about-2.jpg') }}" alt="">
                            </div>
                            <div class="col-6 text-end" style="margin-top: -150px;">
                                <img class="img-fluid w-100 rounded-circle bg-light p-3"
                                    src="{{ asset('asset/img/about-3.jpg') }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- About End -->


        <!-- Call To Action Start -->
        <div class="container-xxl py-5">
            <div class="container">
                <div class="bg-light rounded">
                    <div class="row g-0">
                        <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s" style="min-height: 400px;">
                            <div class="position-relative h-100">
                                <img class="position-absolute w-100 h-100 rounded"
                                    src="{{ asset('asset/img/call-to-action.jpg') }}" style="object-fit: cover;">
                            </div>
                        </div>
                        <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
                            <div class="h-100 d-flex flex-column justify-content-center p-5">
                                <h1 class="mb-4">Register Your School now</h1>
                                <p class="mb-4">Join the ITEB family and ensure your students receive accurate,
                                    timely, and secure examination results. Our platform offers a seamless experience
                                    for schools to submit results, track progress, and access comprehensive reports.
                                    Register now to empower your students with the recognition they deserve and
                                    contribute to a brighter future for education in Uganda.
                                </p>
                                <a class="btn btn-primary py-3 px-5" href="">Get Started Now<i
                                        class="fa fa-arrow-right ms-2"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Call To Action End -->


        <!-- Classes Start-Idaad -->
        <div class="container-xxl py-5">
            <div class="container">
                <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                    <h1 class="mb-3">Idaad Examinations set</h1>
                    <p>Register your school for the coming Idaad Examinations on Our Portal now to avoid late
                        registration costs</p>
                </div>
                <div class="row g-4">
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="classes-item">
                            <div class="bg-light rounded-circle w-75 mx-auto p-3">
                                <img class="img-fluid rounded-circle" src="{{ asset('asset/img/classes-1.jpg') }}"
                                    alt="">
                            </div>
                            <div class="bg-light rounded p-4 pt-5 mt-n5">
                                <a class="d-block text-center h3 mt-3 mb-4" href="">Dictation and
                                    Calligraphy</a>
                                <div class="d-flex align-items-center justify-content-between mb-4">
                                    <div class="d-flex align-items-center">
                                        <img class="rounded-circle flex-shrink-0"
                                            src="{{ asset('asset/img/user.jpg') }}" alt=""
                                            style="width: 45px; height: 45px;">
                                        <div class="ms-3">
                                            <h6 class="text-primary mb-1">Jhon Doe</h6>
                                            <small>H.O.D</small>
                                        </div>
                                    </div>
                                    <span class="bg-primary text-white rounded-pill py-2 px-3" href=""></span>
                                </div>
                                <div class="row g-1">
                                    <div class="col-4">
                                        <div class="border-top border-3 border-primary pt-2">
                                            <h6 class="text-primary mb-1">papers:</h6>
                                            <small>10 papers</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="border-top border-3 border-success pt-2">
                                            <h6 class="text-success mb-1">Duration:</h6>
                                            <small>6 days</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="border-top border-3 border-warning pt-2">
                                            <h6 class="text-warning mb-1">Capacity:</h6>
                                            <small>S4 Equivalent</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                        <div class="classes-item">
                            <div class="bg-light rounded-circle w-75 mx-auto p-3">
                                <img class="img-fluid rounded-circle" src="{{ asset('asset/img/classes-2.jpg') }}"
                                    alt="">
                            </div>
                            <div class="bg-light rounded p-4 pt-5 mt-n5">
                                <a class="d-block text-center h3 mt-3 mb-4" href="">Sources of Exegesis</a>
                                <div class="d-flex align-items-center justify-content-between mb-4">
                                    <div class="d-flex align-items-center">
                                        <img class="rounded-circle flex-shrink-0"
                                            src="{{ asset('asset/img/user.jpg') }}" alt=""
                                            style="width: 45px; height: 45px;">
                                        <div class="ms-3">
                                            <h6 class="text-primary mb-1">Jhon Doe</h6>
                                            <small>H.O.D</small>
                                        </div>
                                    </div>
                                    <span class="bg-primary text-white rounded-pill py-2 px-3" href=""></span>
                                </div>
                                <div class="row g-1">
                                    <div class="col-4">
                                        <div class="border-top border-3 border-primary pt-2">
                                            <h6 class="text-primary mb-1">papers:</h6>
                                            <small>10 papers</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="border-top border-3 border-success pt-2">
                                            <h6 class="text-success mb-1">Duration:</h6>
                                            <small>6 days</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="border-top border-3 border-warning pt-2">
                                            <h6 class="text-warning mb-1">Capacity:</h6>
                                            <small>S4 Equivalent</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                        <div class="classes-item">
                            <div class="bg-light rounded-circle w-75 mx-auto p-3">
                                <img class="img-fluid rounded-circle" src="{{ asset('asset/img/classes-3.jpg') }}"
                                    alt="">
                            </div>
                            <div class="bg-light rounded p-4 pt-5 mt-n5">
                                <a class="d-block text-center h3 mt-3 mb-4" href="">Islamic Monotheism
                                    (Tawheed)</a>
                                <div class="d-flex align-items-center justify-content-between mb-4">
                                    <div class="d-flex align-items-center">
                                        <img class="rounded-circle flex-shrink-0"
                                            src="{{ asset('asset/img/user.jpg') }}" alt=""
                                            style="width: 45px; height: 45px;">
                                        <div class="ms-3">
                                            <h6 class="text-primary mb-1">Jhon Doe</h6>
                                            <small>H.O.D</small>
                                        </div>
                                    </div>
                                    <span class="bg-primary text-white rounded-pill py-2 px-3" href=""></span>
                                </div>
                                <div class="row g-1">
                                    <div class="col-4">
                                        <div class="border-top border-3 border-primary pt-2">
                                            <h6 class="text-primary mb-1">papers:</h6>
                                            <small>10 papers</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="border-top border-3 border-success pt-2">
                                            <h6 class="text-success mb-1">Duration:</h6>
                                            <small>6 days</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="border-top border-3 border-warning pt-2">
                                            <h6 class="text-warning mb-1">Capacity:</h6>
                                            <small>S4 Equivalent</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="classes-item">
                            <div class="bg-light rounded-circle w-75 mx-auto p-3">
                                <img class="img-fluid rounded-circle" src="{{ asset('asset/img/classes-4.jpg') }}"
                                    alt="">
                            </div>
                            <div class="bg-light rounded p-4 pt-5 mt-n5">
                                <a class="d-block text-center h3 mt-3 mb-4" href="">Holy Qur’an Recitation and
                                    Its Rules (Tilawah/Tajweed)</a>
                                <div class="d-flex align-items-center justify-content-between mb-4">
                                    <div class="d-flex align-items-center">
                                        <img class="rounded-circle flex-shrink-0"
                                            src="{{ asset('asset/img/user.jpg') }}" alt=""
                                            style="width: 45px; height: 45px;">
                                        <div class="ms-3">
                                            <h6 class="text-primary mb-1">Jhon Doe</h6>
                                            <small>H.O.D</small>
                                        </div>
                                    </div>
                                    <span class="bg-primary text-white rounded-pill py-2 px-3" href=""></span>
                                </div>
                                <div class="row g-1">
                                    <div class="col-4">
                                        <div class="border-top border-3 border-primary pt-2">
                                            <h6 class="text-primary mb-1">papers:</h6>
                                            <small>10 papers</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="border-top border-3 border-success pt-2">
                                            <h6 class="text-success mb-1">Duration:</h6>
                                            <small>6 days</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="border-top border-3 border-warning pt-2">
                                            <h6 class="text-warning mb-1">Capacity:</h6>
                                            <small>S4 Equivalent</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                        <div class="classes-item">
                            <div class="bg-light rounded-circle w-75 mx-auto p-3">
                                <img class="img-fluid rounded-circle" src="{{ asset('asset/img/classes-5.jpg') }}"
                                    alt="">
                            </div>
                            <div class="bg-light rounded p-4 pt-5 mt-n5">
                                <a class="d-block text-center h3 mt-3 mb-4" href="">Arabic Literature and
                                    Texts</a>
                                <div class="d-flex align-items-center justify-content-between mb-4">
                                    <div class="d-flex align-items-center">
                                        <img class="rounded-circle flex-shrink-0"
                                            src="{{ asset('asset/img/user.jpg') }}" alt=""
                                            style="width: 45px; height: 45px;">
                                        <div class="ms-3">
                                            <h6 class="text-primary mb-1">Jhon Doe</h6>
                                            <small>H.O.D</small>
                                        </div>
                                    </div>
                                    <span class="bg-primary text-white rounded-pill py-2 px-3" href=""></span>
                                </div>
                                <div class="row g-1">
                                    <div class="col-4">
                                        <div class="border-top border-3 border-primary pt-2">
                                            <h6 class="text-primary mb-1">papers:</h6>
                                            <small>10 papers</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="border-top border-3 border-success pt-2">
                                            <h6 class="text-success mb-1">Duration:</h6>
                                            <small>6 days</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="border-top border-3 border-warning pt-2">
                                            <h6 class="text-warning mb-1">Capacity:</h6>
                                            <small>S4 Equivalent</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                        <div class="classes-item">
                            <div class="bg-light rounded-circle w-75 mx-auto p-3">
                                <img class="img-fluid rounded-circle" src="{{ asset('asset/img/classes-6.jpg') }}"
                                    alt="">
                            </div>
                            <div class="bg-light rounded p-4 pt-5 mt-n5">
                                <a class="d-block text-center h3 mt-3 mb-4" href="">Composition and
                                    Comprehension (Insha’)</a>
                                <div class="d-flex align-items-center justify-content-between mb-4">
                                    <div class="d-flex align-items-center">
                                        <img class="rounded-circle flex-shrink-0"
                                            src="{{ asset('asset/img/user.jpg') }}" alt=""
                                            style="width: 45px; height: 45px;">
                                        <div class="ms-3">
                                            <h6 class="text-primary mb-1">Jhon Doe</h6>
                                            <small>H.O.D</small>
                                        </div>
                                    </div>
                                    <span class="bg-primary text-white rounded-pill py-2 px-3" href=""></span>
                                </div>
                                <div class="row g-1">
                                    <div class="col-4">
                                        <div class="border-top border-3 border-primary pt-2">
                                            <h6 class="text-primary mb-1">papers:</h6>
                                            <small>10 papers</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="border-top border-3 border-success pt-2">
                                            <h6 class="text-success mb-1">Duration:</h6>
                                            <small>6 days</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="border-top border-3 border-warning pt-2">
                                            <h6 class="text-warning mb-1">Capacity:</h6>
                                            <small>S4 Equivalent</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Classes End -->




        <!-- Classes Start -->

        <div class="row g-4">
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                <div class="classes-item">
                    <div class="bg-light rounded-circle w-75 mx-auto p-3">
                        <img class="img-fluid rounded-circle" src="{{ asset('asset/img/classes-7.jpg') }}"
                            alt="">
                    </div>
                    <div class="bg-light rounded p-4 pt-5 mt-n5">
                        <a class="d-block text-center h3 mt-3 mb-4" href="">Sources of Islamic Law (Usool
                            al-Fiqh)</a>
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div class="d-flex align-items-center">
                                <img class="rounded-circle flex-shrink-0" src="{{ asset('asset/img/user.jpg') }}"
                                    alt="" style="width: 45px; height: 45px;">
                                <div class="ms-3">
                                    <h6 class="text-primary mb-1">Jhon Doe</h6>
                                    <small>H.O.D</small>
                                </div>
                            </div>
                            <span class="bg-primary text-white rounded-pill py-2 px-3" href=""></span>
                        </div>
                        <div class="row g-1">
                            <div class="col-4">
                                <div class="border-top border-3 border-primary pt-2">
                                    <h6 class="text-primary mb-1">papers:</h6>
                                    <small>10 papers</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border-top border-3 border-success pt-2">
                                    <h6 class="text-success mb-1">Duration:</h6>
                                    <small>6 days</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border-top border-3 border-warning pt-2">
                                    <h6 class="text-warning mb-1">Capacity:</h6>
                                    <small>S4 Equivalent</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                <div class="classes-item">
                    <div class="bg-light rounded-circle w-75 mx-auto p-3">
                        <img class="img-fluid rounded-circle" src="{{ asset('asset/img/classes-8.jpg') }}"
                            alt="">
                    </div>
                    <div class="bg-light rounded p-4 pt-5 mt-n5">
                        <a class="d-block text-center h3 mt-3 mb-4" href="">Sources of Prophetic Traditions
                            (Usool al-Hadith)</a>
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div class="d-flex align-items-center">
                                <img class="rounded-circle flex-shrink-0" src="{{ asset('asset/img/user.jpg') }}"
                                    alt="" style="width: 45px; height: 45px;">
                                <div class="ms-3">
                                    <h6 class="text-primary mb-1">Jhon Doe</h6>
                                    <small>H.O.D</small>
                                </div>
                            </div>
                            <span class="bg-primary text-white rounded-pill py-2 px-3" href=""></span>
                        </div>
                        <div class="row g-1">
                            <div class="col-4">
                                <div class="border-top border-3 border-primary pt-2">
                                    <h6 class="text-primary mb-1">papers:</h6>
                                    <small>10 papers</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border-top border-3 border-success pt-2">
                                    <h6 class="text-success mb-1">Duration:</h6>
                                    <small>6 days</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border-top border-3 border-warning pt-2">
                                    <h6 class="text-warning mb-1">Capacity:</h6>
                                    <small>S4 Equivalent</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                <div class="classes-item">
                    <div class="bg-light rounded-circle w-75 mx-auto p-3">
                        <img class="img-fluid rounded-circle" src="{{ asset('asset/img/classes-9.jpg') }}"
                            alt="">
                    </div>
                    <div class="bg-light rounded p-4 pt-5 mt-n5">
                        <a class="d-block text-center h3 mt-3 mb-4" href="">Qur’anic Orals and Rhetoric</a>
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div class="d-flex align-items-center">
                                <img class="rounded-circle flex-shrink-0" src="{{ asset('asset/img/user.jpg') }}"
                                    alt="" style="width: 45px; height: 45px;">
                                <div class="ms-3">
                                    <h6 class="text-primary mb-1">Jhon Doe</h6>
                                    <small>H.O.D</small>
                                </div>
                            </div>
                            <span class="bg-primary text-white rounded-pill py-2 px-3" href=""></span>
                        </div>
                        <div class="row g-1">
                            <div class="col-4">
                                <div class="border-top border-3 border-primary pt-2">
                                    <h6 class="text-primary mb-1">papers:</h6>
                                    <small>10 papers</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border-top border-3 border-success pt-2">
                                    <h6 class="text-success mb-1">Duration:</h6>
                                    <small>6 days</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border-top border-3 border-warning pt-2">
                                    <h6 class="text-warning mb-1">Capacity:</h6>
                                    <small>S4 Equivalent</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                <div class="classes-item">
                    <div class="bg-light rounded-circle w-75 mx-auto p-3">
                        <img class="img-fluid rounded-circle" src="{{ asset('asset/img/classes-10.jpg') }}"
                            alt="">
                    </div>
                    <div class="bg-light rounded p-4 pt-5 mt-n5">
                        <a class="d-block text-center h3 mt-3 mb-4" href="">(Implied general papers) </a>
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div class="d-flex align-items-center">
                                <img class="rounded-circle flex-shrink-0" src="{{ asset('asset/img/user.jpg') }}"
                                    alt="" style="width: 45px; height: 45px;">
                                <div class="ms-3">
                                    <h6 class="text-primary mb-1">Jhon Doe</h6>
                                    <small>H.O.D</small>
                                </div>
                            </div>
                            <span class="bg-primary text-white rounded-pill py-2 px-3" href=""></span>
                        </div>
                        <div class="row g-1">
                            <div class="col-4">
                                <div class="border-top border-3 border-primary pt-2">
                                    <h6 class="text-primary mb-1">papers:</h6>
                                    <small>10 papers</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border-top border-3 border-success pt-2">
                                    <h6 class="text-success mb-1">Duration:</h6>
                                    <small>6 days</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border-top border-3 border-warning pt-2">
                                    <h6 class="text-warning mb-1">Capacity:</h6>
                                    <small>S4 Equivalent</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    </div>
    </div>
    </div>
    <!-- Classes End -->















    <!-- Classes Start-Thanawi -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                <h1 class="mb-3">Thanawi Examinations set</h1>
                <p>Performance Insight: Hadith and Arabic Grammar are historically cited as the "worst done" subjects,
                    presenting a major area for academic focus</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="classes-item">
                        <div class="bg-light rounded-circle w-75 mx-auto p-3">
                            <img class="img-fluid rounded-circle" src="{{ asset('asset/img/classes-8.jpg') }}"
                                alt="">
                        </div>
                        <div class="bg-light rounded p-4 pt-5 mt-n5">
                            <a class="d-block text-center h3 mt-3 mb-4" href="">Holy Qur’an Exegesis
                                (Tafsir)</a>
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <div class="d-flex align-items-center">
                                    <img class="rounded-circle flex-shrink-0" src="{{ asset('asset/img/user.jpg') }}"
                                        alt="" style="width: 45px; height: 45px;">
                                    <div class="ms-3">
                                        <h6 class="text-primary mb-1">Jhon Doe</h6>
                                        <small>H.O.D</small>
                                    </div>
                                </div>
                                <span class="bg-primary text-white rounded-pill py-2 px-3" href=""></span>
                            </div>
                            <div class="row g-1">
                                <div class="col-4">
                                    <div class="border-top border-3 border-primary pt-2">
                                        <h6 class="text-primary mb-1">papers:</h6>
                                        <small>15 papers</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="border-top border-3 border-success pt-2">
                                        <h6 class="text-success mb-1">Duration:</h6>
                                        <small>8 days</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="border-top border-3 border-warning pt-2">
                                        <h6 class="text-warning mb-1">Capacity:</h6>
                                        <small>S6 Equivalent</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="classes-item">
                        <div class="bg-light rounded-circle w-75 mx-auto p-3">
                            <img class="img-fluid rounded-circle" src="{{ asset('asset/img/classes-16.jpg') }}"
                                alt="">
                        </div>
                        <div class="bg-light rounded p-4 pt-5 mt-n5">
                            <a class="d-block text-center h3 mt-3 mb-4" href="">Grammar and Morphology
                                (Sarf/Nahv)</a>
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <div class="d-flex align-items-center">
                                    <img class="rounded-circle flex-shrink-0" src="{{ asset('asset/img/user.jpg') }}"
                                        alt="" style="width: 45px; height: 45px;">
                                    <div class="ms-3">
                                        <h6 class="text-primary mb-1">Jhon Doe</h6>
                                        <small>H.O.D</small>
                                    </div>
                                </div>
                                <span class="bg-primary text-white rounded-pill py-2 px-3" href=""></span>
                            </div>
                            <div class="row g-1">
                                <div class="col-4">
                                    <div class="border-top border-3 border-primary pt-2">
                                        <h6 class="text-primary mb-1">papers:</h6>
                                        <small>15 papers</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="border-top border-3 border-success pt-2">
                                        <h6 class="text-success mb-1">Duration:</h6>
                                        <small>8 days</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="border-top border-3 border-warning pt-2">
                                        <h6 class="text-warning mb-1">Capacity:</h6>
                                        <small>S6 Equivalent</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="classes-item">
                        <div class="bg-light rounded-circle w-75 mx-auto p-3">
                            <img class="img-fluid rounded-circle" src="{{ asset('asset/img/classes-14.jpg') }}"
                                alt="">
                        </div>
                        <div class="bg-light rounded p-4 pt-5 mt-n5">
                            <a class="d-block text-center h3 mt-3 mb-4" href="">Islamic Family Law</a>
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <div class="d-flex align-items-center">
                                    <img class="rounded-circle flex-shrink-0" src="{{ asset('asset/img/user.jpg') }}"
                                        alt="" style="width: 45px; height: 45px;">
                                    <div class="ms-3">
                                        <h6 class="text-primary mb-1">Jhon Doe</h6>
                                        <small>H.O.D</small>
                                    </div>
                                </div>
                                <span class="bg-primary text-white rounded-pill py-2 px-3" href=""></span>
                            </div>
                            <div class="row g-1">
                                <div class="col-4">
                                    <div class="border-top border-3 border-primary pt-2">
                                        <h6 class="text-primary mb-1">papers:</h6>
                                        <small>15 papers</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="border-top border-3 border-success pt-2">
                                        <h6 class="text-success mb-1">Duration:</h6>
                                        <small>8 days</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="border-top border-3 border-warning pt-2">
                                        <h6 class="text-warning mb-1">Capacity:</h6>
                                        <small>S6 Equivalent</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="classes-item">
                        <div class="bg-light rounded-circle w-75 mx-auto p-3">
                            <img class="img-fluid rounded-circle" src="{{ asset('asset/img/classes-11.jpg') }}"
                                alt="">
                        </div>
                        <div class="bg-light rounded p-4 pt-5 mt-n5">
                            <a class="d-block text-center h3 mt-3 mb-4" href="">Arabic Literature</a>
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <div class="d-flex align-items-center">
                                    <img class="rounded-circle flex-shrink-0" src="{{ asset('asset/img/user.jpg') }}"
                                        alt="" style="width: 45px; height: 45px;">
                                    <div class="ms-3">
                                        <h6 class="text-primary mb-1">Jhon Doe</h6>
                                        <small>H.O.D</small>
                                    </div>
                                </div>
                                <span class="bg-primary text-white rounded-pill py-2 px-3" href=""></span>
                            </div>
                            <div class="row g-1">
                                <div class="col-4">
                                    <div class="border-top border-3 border-primary pt-2">
                                        <h6 class="text-primary mb-1">papers:</h6>
                                        <small>15 papers</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="border-top border-3 border-success pt-2">
                                        <h6 class="text-success mb-1">Duration:</h6>
                                        <small>8 days</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="border-top border-3 border-warning pt-2">
                                        <h6 class="text-warning mb-1">Capacity:</h6>
                                        <small>S6 Equivalent</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="classes-item">
                        <div class="bg-light rounded-circle w-75 mx-auto p-3">
                            <img class="img-fluid rounded-circle" src="{{ asset('asset/img/classes-5.jpg') }}"
                                alt="">
                        </div>
                        <div class="bg-light rounded p-4 pt-5 mt-n5">
                            <a class="d-block text-center h3 mt-3 mb-4" href="">Jurisprudence of Rituals (Fiqh
                                al-Ibadat)</a>
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <div class="d-flex align-items-center">
                                    <img class="rounded-circle flex-shrink-0" src="{{ asset('asset/img/user.jpg') }}"
                                        alt="" style="width: 45px; height: 45px;">
                                    <div class="ms-3">
                                        <h6 class="text-primary mb-1">Jhon Doe</h6>
                                        <small>H.O.D</small>
                                    </div>
                                </div>
                                <span class="bg-primary text-white rounded-pill py-2 px-3" href=""></span>
                            </div>
                            <div class="row g-1">
                                <div class="col-4">
                                    <div class="border-top border-3 border-primary pt-2">
                                        <h6 class="text-primary mb-1">papers:</h6>
                                        <small>15 papers</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="border-top border-3 border-success pt-2">
                                        <h6 class="text-success mb-1">Duration:</h6>
                                        <small>8 days</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="border-top border-3 border-warning pt-2">
                                        <h6 class="text-warning mb-1">Capacity:</h6>
                                        <small>S6 Equivalent</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="classes-item">
                        <div class="bg-light rounded-circle w-75 mx-auto p-3">
                            <img class="img-fluid rounded-circle" src="{{ asset('asset/img/classes-18.jpg') }}"
                                alt="">
                        </div>
                        <div class="bg-light rounded p-4 pt-5 mt-n5">
                            <a class="d-block text-center h3 mt-3 mb-4" href="">Islamic History</a>
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <div class="d-flex align-items-center">
                                    <img class="rounded-circle flex-shrink-0" src="{{ asset('asset/img/user.jpg') }}"
                                        alt="" style="width: 45px; height: 45px;">
                                    <div class="ms-3">
                                        <h6 class="text-primary mb-1">Jhon Doe</h6>
                                        <small>H.O.D</small>
                                    </div>
                                </div>
                                <span class="bg-primary text-white rounded-pill py-2 px-3" href=""></span>
                            </div>
                            <div class="row g-1">
                                <div class="col-4">
                                    <div class="border-top border-3 border-primary pt-2">
                                        <h6 class="text-primary mb-1">papers:</h6>
                                        <small>15 papers</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="border-top border-3 border-success pt-2">
                                        <h6 class="text-success mb-1">Duration:</h6>
                                        <small>8 days</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="border-top border-3 border-warning pt-2">
                                        <h6 class="text-warning mb-1">Capacity:</h6>
                                        <small>S6 Equivalent</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Classes End -->




    <!-- Classes Start -->

    <div class="row g-4">
        <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
            <div class="classes-item">
                <div class="bg-light rounded-circle w-75 mx-auto p-3">
                    <img class="img-fluid rounded-circle" src="{{ asset('asset/img/classes-15.jpg') }}"
                        alt="">
                </div>
                <div class="bg-light rounded p-4 pt-5 mt-n5">
                    <a class="d-block text-center h3 mt-3 mb-4" href="">Religion and Sects</a>
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div class="d-flex align-items-center">
                            <img class="rounded-circle flex-shrink-0" src="{{ asset('asset/img/user.jpg') }}"
                                alt="" style="width: 45px; height: 45px;">
                            <div class="ms-3">
                                <h6 class="text-primary mb-1">Jhon Doe</h6>
                                <small>H.O.D</small>
                            </div>
                        </div>
                        <span class="bg-primary text-white rounded-pill py-2 px-3" href=""></span>
                    </div>
                    <div class="row g-1">
                        <div class="col-4">
                            <div class="border-top border-3 border-primary pt-2">
                                <h6 class="text-primary mb-1">papers:</h6>
                                <small>15 papers</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border-top border-3 border-success pt-2">
                                <h6 class="text-success mb-1">Duration:</h6>
                                <small>8 days</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border-top border-3 border-warning pt-2">
                                <h6 class="text-warning mb-1">Capacity:</h6>
                                <small>S6 Equivalent</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
            <div class="classes-item">
                <div class="bg-light rounded-circle w-75 mx-auto p-3">
                    <img class="img-fluid rounded-circle" src="{{ asset('asset/img/classes-9.jpg') }}"
                        alt="">
                </div>
                <div class="bg-light rounded p-4 pt-5 mt-n5">
                    <a class="d-block text-center h3 mt-3 mb-4" href="">Sources of Islamic Law (Usool
                        al-Fiqh)</a>
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div class="d-flex align-items-center">
                            <img class="rounded-circle flex-shrink-0" src="{{ asset('asset/img/user.jpg') }}"
                                alt="" style="width: 45px; height: 45px;">
                            <div class="ms-3">
                                <h6 class="text-primary mb-1">Jhon Doe</h6>
                                <small>H.O.D</small>
                            </div>
                        </div>
                        <span class="bg-primary text-white rounded-pill py-2 px-3" href=""></span>
                    </div>
                    <div class="row g-1">
                        <div class="col-4">
                            <div class="border-top border-3 border-primary pt-2">
                                <h6 class="text-primary mb-1">papers:</h6>
                                <small>15 papers</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border-top border-3 border-success pt-2">
                                <h6 class="text-success mb-1">Duration:</h6>
                                <small>8 days</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border-top border-3 border-warning pt-2">
                                <h6 class="text-warning mb-1">Capacity:</h6>
                                <small>S6 Equivalent</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
            <div class="classes-item">
                <div class="bg-light rounded-circle w-75 mx-auto p-3">
                    <img class="img-fluid rounded-circle" src="{{ asset('asset/img/classes-4.jpg') }}"
                        alt="">
                </div>
                <div class="bg-light rounded p-4 pt-5 mt-n5">
                    <a class="d-block text-center h3 mt-3 mb-4" href="">Prophetic Traditions (Hadith)</a>
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div class="d-flex align-items-center">
                            <img class="rounded-circle flex-shrink-0" src="{{ asset('asset/img/user.jpg') }}"
                                alt="" style="width: 45px; height: 45px;">
                            <div class="ms-3">
                                <h6 class="text-primary mb-1">Jhon Doe</h6>
                                <small>H.O.D</small>
                            </div>
                        </div>
                        <span class="bg-primary text-white rounded-pill py-2 px-3" href=""></span>
                    </div>
                    <div class="row g-1">
                        <div class="col-4">
                            <div class="border-top border-3 border-primary pt-2">
                                <h6 class="text-primary mb-1">papers:</h6>
                                <small>15 papers</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border-top border-3 border-success pt-2">
                                <h6 class="text-success mb-1">Duration:</h6>
                                <small>8 days</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border-top border-3 border-warning pt-2">
                                <h6 class="text-warning mb-1">Capacity:</h6>
                                <small>S6 Equivalent</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
            <div class="classes-item">
                <div class="bg-light rounded-circle w-75 mx-auto p-3">
                    <img class="img-fluid rounded-circle" src="{{ asset('asset/img/classes-17.jpg') }}"
                        alt="">
                </div>
                <div class="bg-light rounded p-4 pt-5 mt-n5">
                    <a class="d-block text-center h3 mt-3 mb-4" href="">Composition and Comprehension </a>
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div class="d-flex align-items-center">
                            <img class="rounded-circle flex-shrink-0" src="{{ asset('asset/img/user.jpg') }}"
                                alt="" style="width: 45px; height: 45px;">
                            <div class="ms-3">
                                <h6 class="text-primary mb-1">Jhon Doe</h6>
                                <small>H.O.D</small>
                            </div>
                        </div>
                        <span class="bg-primary text-white rounded-pill py-2 px-3" href=""></span>
                    </div>
                    <div class="row g-1">
                        <div class="col-4">
                            <div class="border-top border-3 border-primary pt-2">
                                <h6 class="text-primary mb-1">papers:</h6>
                                <small>15 papers</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border-top border-3 border-success pt-2">
                                <h6 class="text-success mb-1">Duration:</h6>
                                <small>8 days</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border-top border-3 border-warning pt-2">
                                <h6 class="text-warning mb-1">Capacity:</h6>
                                <small>S6 Equivalent</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
            <div class="classes-item">
                <div class="bg-light rounded-circle w-75 mx-auto p-3">
                    <img class="img-fluid rounded-circle" src="{{ asset('asset/img/classes-14.jpg') }}"
                        alt="">
                </div>
                <div class="bg-light rounded p-4 pt-5 mt-n5">
                    <a class="d-block text-center h3 mt-3 mb-4" href="">Orals</a>
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div class="d-flex align-items-center">
                            <img class="rounded-circle flex-shrink-0" src="{{ asset('asset/img/user.jpg') }}"
                                alt="" style="width: 45px; height: 45px;">
                            <div class="ms-3">
                                <h6 class="text-primary mb-1">Jhon Doe</h6>
                                <small>H.O.D</small>
                            </div>
                        </div>
                        <span class="bg-primary text-white rounded-pill py-2 px-3" href=""></span>
                    </div>
                    <div class="row g-1">
                        <div class="col-4">
                            <div class="border-top border-3 border-primary pt-2">
                                <h6 class="text-primary mb-1">papers:</h6>
                                <small>15 papers</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border-top border-3 border-success pt-2">
                                <h6 class="text-success mb-1">Duration:</h6>
                                <small>8 days</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border-top border-3 border-warning pt-2">
                                <h6 class="text-warning mb-1">Capacity:</h6>
                                <small>S6 Equivalent</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    </div>
    </div>
    </div>
    </div>
    <!-- Classes End -->



















    <!-- Appointment Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="bg-light rounded">
                <div class="row g-0">
                    <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                        <div class="h-100 d-flex flex-column justify-content-center p-5">
                            <h1 class="mb-4">Make Appointment</h1>
                            <form>
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control border-0" id="gname"
                                                placeholder="Gurdian Name">
                                            <label for="gname">School Name</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-floating">
                                            <input type="email" class="form-control border-0" id="gmail"
                                                placeholder="Gurdian Email">
                                            <label for="gmail">School Email</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control border-0" id="cname"
                                                placeholder="Child Name">
                                            <label for="cname">Head Teacher's Name</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control border-0" id="cage"
                                                placeholder="Child Age">
                                            <label for="cage">Location</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <textarea class="form-control border-0" placeholder="Leave a message here" id="message" style="height: 100px"></textarea>
                                            <label for="message">Message</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button class="btn btn-primary w-100 py-3" type="submit">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s" style="min-height: 400px;">
                        <div class="position-relative h-100">
                            <img class="position-absolute w-100 h-100 rounded"
                                src="{{ asset('asset/img/appointment.jpg') }}" style="object-fit: cover;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Appointment End -->


    <!-- Team Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                <h1 class="mb-3">Board of Directors</h1>
                <p>Meet our dedicated board members. </p>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="team-item position-relative">
                        <img class="img-fluid rounded-circle w-75" src="{{ asset('asset/img/team-1.jpg') }}"
                            alt="">
                        <div class="team-text">
                            <h3>Prof Dr Ziyad Swaleh Lubanga</h3>
                            <p>Executive Secretary</p>
                            <div class="d-flex align-items-center">
                                <a class="btn btn-square btn-primary mx-1" href=""><i
                                        class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-square btn-primary  mx-1" href=""><i
                                        class="fab fa-twitter"></i></a>
                                <a class="btn btn-square btn-primary  mx-1" href=""><i
                                        class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="team-item position-relative">
                        <img class="img-fluid rounded-circle w-75" src="{{ asset('asset/img/team-2.jpg') }}"
                            alt="">
                        <div class="team-text">
                            <h3>Sheikh Hatimu Wamala</h3>
                            <p>Board Chairperson</p>
                            <div class="d-flex align-items-center">
                                <a class="btn btn-square btn-primary mx-1" href=""><i
                                        class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-square btn-primary  mx-1" href=""><i
                                        class="fab fa-twitter"></i></a>
                                <a class="btn btn-square btn-primary  mx-1" href=""><i
                                        class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="team-item position-relative">
                        <img class="img-fluid rounded-circle w-75" src="{{ asset('asset/img/team-3.jpg') }}"
                            alt="">
                        <div class="team-text">
                            <h3>Dr Shaban Mubaje</h3>
                            <p>Mufti</p>
                            <div class="d-flex align-items-center">
                                <a class="btn btn-square btn-primary mx-1" href=""><i
                                        class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-square btn-primary  mx-1" href=""><i
                                        class="fab fa-twitter"></i></a>
                                <a class="btn btn-square btn-primary  mx-1" href=""><i
                                        class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Team End -->


    <!-- Testimonial Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                <h1 class="mb-3">Our Clients Say!</h1>
                <p>At the Uganda Muslim Supreme Council (UMSC) Examination Board, we are committed to upholding the
                    highest standards in Islamic secondary education. Here’s what some of the leading Idaad and Thanawi
                    institutions have to say about our examination processes, curriculum support, and professional
                    conduct.</p>
            </div>
            <div class="owl-carousel testimonial-carousel wow fadeInUp" data-wow-delay="0.1s">
                <div class="testimonial-item bg-light rounded p-5">
                    <p class="fs-5">“For years, we have trusted the UMSC Examination Board to assess our Thanawi
                        candidates. Their structured timetable, clear subject guidelines, and timely release of results
                        give our students a competitive edge. ”</p>
                    <div class="d-flex align-items-center bg-white me-n5" style="border-radius: 50px 0 0 50px;">
                        <img class="img-fluid flex-shrink-0 rounded-circle"
                            src="{{ asset('asset/img/testimonial-1.jpg') }}" style="width: 90px; height: 90px;">
                        <div class="ps-3">
                            <h3 class="mb-1">Sheikh Abdulrahman Kiggundu</h3>
                            <span>Head of Academics</span>
                        </div>
                        <i class="fa fa-quote-right fa-3x text-primary ms-auto d-none d-sm-flex"></i>
                    </div>
                </div>
                <div class="testimonial-item bg-light rounded p-5">
                    <p class="fs-5">“The UMSC Examination Board has consistently demonstrated excellence in managing
                        our examinations. Their attention to detail and commitment to fairness ensures that our students
                        are assessed accurately and professionally.”</p>
                    <div class="d-flex align-items-center bg-white me-n5" style="border-radius: 50px 0 0 50px;">
                        <img class="img-fluid flex-shrink-0 rounded-circle"
                            src="{{ asset('asset/img/testimonial-2.jpg') }}" style="width: 90px; height: 90px;">
                        <div class="ps-3">
                            <h3 class="mb-1">Sheikh Mustafa Kigunda</h3>
                            <span>Head of Studies</span>
                        </div>
                        <i class="fa fa-quote-right fa-3x text-primary ms-auto d-none d-sm-flex"></i>
                    </div>
                </div>
                <div class="testimonial-item bg-light rounded p-5">
                    <p class="fs-5">“The UMSC team is always accessible. From the dispatch of examination packages to
                        the eight gazetted centres, everything runs smoothly. Their anti‑malpractice measures are strict
                        yet fair – this preserves the integrity of our Idaad graduates.</p>
                    <div class="d-flex align-items-center bg-white me-n5" style="border-radius: 50px 0 0 50px;">
                        <img class="img-fluid flex-shrink-0 rounded-circle"
                            src="{{ asset('asset/img/testimonial-3.jpg') }}" style="width: 90px; height: 90px;">
                        <div class="ps-3">
                            <h3 class="mb-1">Sheikh Yusuf Kiganda</h3>
                            <span>Head of Idaad Studies</span>
                        </div>
                        <i class="fa fa-quote-right fa-3x text-primary ms-auto d-none d-sm-flex"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Testimonial End -->


    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-3 col-md-6">
                    <h3 class="text-white mb-4">Get In Touch</h3>
                    <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>kampala-kawempe division</p>
                    <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>+256 700 123456</p>
                    <p class="mb-2"><i class="fa fa-envelope me-3"></i>info@iteb.org.ug</p>
                    <div class="d-flex pt-2">
                        <a class="btn btn-outline-light btn-social" href=""><i
                                class="fab fa-twitter"></i></a>
                        <a class="btn btn-outline-light btn-social" href=""><i
                                class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-outline-light btn-social" href=""><i
                                class="fab fa-youtube"></i></a>
                        <a class="btn btn-outline-light btn-social" href=""><i
                                class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h3 class="text-white mb-4">Quick Links</h3>
                    <a class="btn btn-link text-white-50" href="">About Us</a>
                    <a class="btn btn-link text-white-50" href="">Contact Us</a>
                    <a class="btn btn-link text-white-50" href="">Our Services</a>
                    <a class="btn btn-link text-white-50" href="">Privacy Policy</a>
                    <a class="btn btn-link text-white-50" href="">Terms & Condition</a>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h3 class="text-white mb-4">Photo Gallery</h3>
                    <div class="row g-2 pt-2">
                        <div class="col-4">
                            <img class="img-fluid rounded bg-light p-1"
                                src="{{ asset('asset/img/classes-1.jpg') }}" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid rounded bg-light p-1"
                                src="{{ asset('asset/img/classes-2.jpg') }}" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid rounded bg-light p-1"
                                src="{{ asset('asset/img/classes-3.jpg') }}" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid rounded bg-light p-1"
                                src="{{ asset('asset/img/classes-4.jpg') }}" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid rounded bg-light p-1"
                                src="{{ asset('asset/img/classes-5.jpg') }}" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid rounded bg-light p-1"
                                src="{{ asset('asset/img/classes-6.jpg') }}" alt="">
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h3 class="text-white mb-4">Newsletter</h3>
                    <p>subscribe to our newsletter for updates</p>
                    <div class="position-relative mx-auto" style="max-width: 400px;">
                        <input class="form-control bg-transparent w-100 py-3 ps-4 pe-5" type="text"
                            placeholder="Your email">
                        <button type="button"
                            class="btn btn-primary py-2 position-absolute top-0 end-0 mt-2 me-2">SignUp</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="copyright">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        &copy; <a class="border-bottom" href="#">iteb</a>, All Right Reserved.

                        <!--/*** This template is free as long as you keep the footer author’s credit link/attribution link/backlink. If you'd like to use the template without the footer author’s credit link/attribution link/backlink, you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". Thank you for your support. ***/-->

                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <div class="footer-menu">
                            <a href="">Home</a>
                            <a href="">Cookies</a>
                            <a href="">Help</a>
                            <a href="">FQAs</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('asset/lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('asset/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('asset/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('asset/lib/owlcarousel/owl.carousel.min.js') }}"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('asset/js/main.js') }}"></script>
</body>

</html>
