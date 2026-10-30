@php
    $c = $site['contact'];
    $social = array_filter($site['social'] ?? []);
    $win = $page['window'] ?? ['state' => 'none'];
    $stats = $page['stats'] ?? [];
    $papers = $page['papers'] ?? [];
    $portalOn = (bool) ($site['results_portal_enabled'] ?? false);
    $release = $site['results_release'] ?? [];

    // ---- Editable content --------------------------------------------------------------
    $capabilities = [
        ['lock', 'Secure data collection', 'Schools submit candidate details and photographs digitally, with validation that catches errors before they reach the board.'],
        ['bar-chart', 'Automated grading', 'Marks are processed against the board’s grading scales, removing manual calculation and the mistakes that come with it.'],
        ['shield-check', 'Multi-level quality control', 'Qualified examiners verify every result at several stages before it is approved and released.'],
        ['eye', 'Transparency', 'Clear processes between the board, schools and candidates, so everyone knows where a registration or result stands.'],
    ];

    $subjects = [
        ['Islamic Monotheism', 'id', 'The foundational belief in the absolute oneness of God (Tawhid): that Allah is unique, without partners or equals.', 'sparkle'],
        ['Sources of Exegesis', 'id', 'The tools used to interpret the Qur’an: the Qur’an itself, the Hadith, scholarly consensus and Arabic linguistics.', 'book-open'],
        ['Sources of Jurisprudence', 'id', 'The primary evidence for Islamic law (the Qur’an, Sunnah, consensus and analogy) and how rulings are derived from it.', 'scale'],
        ['Sources of Prophetic Traditions', 'id', 'The methodology for verifying Hadith: chains of narration, narrator reliability and text analysis.', 'scroll'],
        ['Qur’anic Orals', 'id', 'Correct pronunciation and melodious recitation of the Holy Qur’an, with articulation points and stopping rules (Tajweed).', 'mic'],
        ['Arabic Composition & Comprehension', 'both', 'Arabic writing and reading skills that let students construct texts and understand classical literature.', 'pen'],
        ['Islamic History', 'both', 'The Muslim world’s past, from the life of the Prophet to the spread of Islam in Africa.', 'landmark'],
        ['Qur’an Exegesis', 'th', 'Advanced study of the interpretation of the Qur’an, building on the foundations laid at Idaad.', 'book-open'],
        ['Jurisprudence of Rituals', 'th', 'The rulings that govern acts of worship, studied in depth with their evidences.', 'scale'],
        ['Religion & Sects', 'th', 'Islamic sects and other faith traditions, fostering understanding of doctrinal differences and religious diversity.', 'globe'],
        ['Rhetoric', 'th', 'The study of eloquent, effective communication in Arabic: figurative language, stylistic devices and composition.', 'quote'],
        ['Grammar & Morphology', 'th', 'The foundational sciences of Arabic: sentence structure and word patterns, for reading classical texts in their original form.', 'languages'],
    ];

    $steps = [
        ['school', 'Register your school', 'ITEB creates your school’s profile and portal access. Your school then appears on the board’s register.'],
        ['ticket', 'Receive registration slots', 'The board allocates registration slots for each admission year and opens the registration window.'],
        ['camera', 'Register students', 'During the window, add each candidate and upload a passport photograph, then submit the list to ITEB.'],
        ['list-checks', 'Board review', 'ITEB reviews every submission. Approved candidates are cleared; any that need fixing are returned to your school.'],
        ['id-card', 'Examinations', 'Approved candidates are issued passlips and sit the Idaad or Thanawi examinations according to the official timetable.'],
        ['award', 'Results & recognition', 'Results are processed and released, and recognised schools receive an ITEB recognition certificate.'],
    ];

    $portal = [
        ['ticket', 'Slots & registration', 'Register candidates for the open admission year, within the slots the board has allocated to your school.'],
        ['camera', 'Photos & student records', 'Upload a passport photograph for each candidate and keep every record in one place.'],
        ['check-circle', 'Live approval status', 'Track submitted candidates and see which are approved or have been returned for correction.'],
        ['file-text', 'Printable lists & album', 'Download submitted-student lists and individual student PDFs, and print your school album.'],
        ['trending-up', 'Grading summaries', 'Review grading summaries for your school once results have been processed.'],
        ['award', 'Recognition certificate', 'View your school’s ITEB recognition certificate.'],
    ];

    $faqs = [
        ['What is the Idaad and Thanawi system?', 'It is Uganda’s structured Islamic secondary education framework. Idaad (O-Level) covers the first four years and builds foundational knowledge. Thanawi (A-Level) is a two-year advanced programme for deeper specialisation in the Islamic sciences.'],
        ['How long does each level take?', 'Idaad spans four years and Thanawi two years. Students typically begin after completing primary Islamic education and progress through the curriculum systematically.'],
        ['What subjects are taught at Idaad level?', 'Idaad students study Islamic Monotheism, Sources of Exegesis, Sources of Jurisprudence, Sources of Prophetic Traditions, Qur’anic Orals, Arabic Composition and Comprehension and Islamic History, among others.'],
        ['What makes Thanawi different?', 'Thanawi offers advanced specialisation in subjects such as Qur’an Exegesis, Jurisprudence of Rituals, Religion and Sects, Rhetoric, and advanced Grammar and Morphology, preparing students for university-level Islamic scholarship.'],
        ['How does a school register with ITEB?', 'School access is created by the board. Contact ITEB to have your school registered; you will then receive login details for the school portal, where registration slots, candidate registration and results are managed.'],
        ['How do schools register candidates?', 'Through the school portal, while the registration window is open. Each candidate is added with a passport photograph and the list is submitted to ITEB, which approves it or returns it to the school for correction.'],
        ['When are results released?', 'Idaad results are released in ' . ($release['Idaad'] ?? 'March') . ' and Thanawi results in ' . ($release['Thanawi'] ?? 'May') . ' each year. Results are provisional until confirmed by the board.'],
        ['I have forgotten my registration number. What should I do?', 'Contact your school administration or the examination board and they will help you retrieve it.'],
        ['Are Thanawi graduates qualified for university admission?', 'Yes. Successful Thanawi graduates meet the admission requirements for Islamic universities, including the Islamic University in Uganda (IUIU), where they can pursue bachelor’s and postgraduate degrees in specialised Islamic disciplines.'],
    ];

    $initials = fn($name) => collect(preg_split('/\s+/', trim(preg_replace('/\b(Prof|Dr|Sheikh)\.?\s+/i', '', $name))))
        ->filter()->take(2)->map(fn($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('');

    $pageTitle = $site['short_name'] . ' – ' . $site['name'] . ' | Islamic secondary education examinations in Uganda';
    $pageDesc = 'The ' . $site['name'] . ' (' . $site['short_name'] . ') registers schools, administers the Idaad (O-Level) and Thanawi (A-Level) examinations and certifies Islamic secondary education in Uganda, under the ' . $site['parent'] . '.';

    $jsonLd = [
        '@context' => 'https://schema.org',
        '@type' => 'EducationalOrganization',
        'name' => $site['name'],
        'alternateName' => $site['short_name'],
        'url' => url('/'),
        'logo' => asset('assets/images/brand/logo.png'),
        'parentOrganization' => ['@type' => 'Organization', 'name' => $site['parent']],
        'address' => ['@type' => 'PostalAddress', 'streetAddress' => $c['address'], 'addressCountry' => 'UG'],
        'email' => $c['email'],
        'telephone' => $c['phone'],
    ];
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDesc }}">
    <meta name="theme-color" content="#0b3d1a">
    <link rel="canonical" href="{{ url('/') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/brand/logo.png') }}">

    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $site['short_name'] }} – {{ $site['name'] }}">
    <meta property="og:description" content="{{ $pageDesc }}">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:image" content="{{ asset('assets/images/brand/logo.png') }}">
    <meta name="twitter:card" content="summary">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/landing.css') }}?v={{ @filemtime(public_path('assets/css/landing.css')) }}">

    <script>document.documentElement.className += ' js';</script>
    <script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) !!}</script>
</head>

<body>
    {{-- Icon sprite --}}
    <svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
        <defs>
            <symbol id="i-arrow-right" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></symbol>
            <symbol id="i-arrow-up" viewBox="0 0 24 24"><path d="M12 19V5M5 12l7-7 7 7"/></symbol>
            <symbol id="i-chevron-down" viewBox="0 0 24 24"><path d="m6 9 6 6 6-6"/></symbol>
            <symbol id="i-menu" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></symbol>
            <symbol id="i-x" viewBox="0 0 24 24"><path d="M18 6 6 18M6 6l12 12"/></symbol>
            <symbol id="i-check" viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></symbol>
            <symbol id="i-check-circle" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="M22 4 12 14.01l-3-3"/></symbol>
            <symbol id="i-mail" viewBox="0 0 24 24"><path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"/><path d="m22 6-10 7L2 6"/></symbol>
            <symbol id="i-phone" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></symbol>
            <symbol id="i-map-pin" viewBox="0 0 24 24"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></symbol>
            <symbol id="i-calendar" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M8 2v4M16 2v4M3 10h18"/></symbol>
            <symbol id="i-clock" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></symbol>
            <symbol id="i-book-open" viewBox="0 0 24 24"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></symbol>
            <symbol id="i-users" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></symbol>
            <symbol id="i-award" viewBox="0 0 24 24"><circle cx="12" cy="8" r="7"/><path d="M8.21 13.89 7 23l5-3 5 3-1.21-9.12"/></symbol>
            <symbol id="i-shield-check" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></symbol>
            <symbol id="i-file-text" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></symbol>
            <symbol id="i-camera" viewBox="0 0 24 24"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></symbol>
            <symbol id="i-id-card" viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><circle cx="8" cy="11" r="2"/><path d="M5.5 16c.5-1.5 1.7-2 2.5-2s2 .5 2.5 2M14 10h5M14 14h3"/></symbol>
            <symbol id="i-graduation" viewBox="0 0 24 24"><path d="M22 10 12 5 2 10l10 5 10-5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></symbol>
            <symbol id="i-school" viewBox="0 0 24 24"><path d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6M9 10h.01M15 10h.01"/></symbol>
            <symbol id="i-bar-chart" viewBox="0 0 24 24"><path d="M12 20V10M18 20V4M6 20v-4"/></symbol>
            <symbol id="i-lock" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></symbol>
            <symbol id="i-list-checks" viewBox="0 0 24 24"><path d="m3 17 2 2 4-4M3 7l2 2 4-4M13 6h8M13 12h8M13 18h8"/></symbol>
            <symbol id="i-trending-up" viewBox="0 0 24 24"><path d="m22 7-8.5 8.5-5-5L2 17"/><path d="M16 7h6v6"/></symbol>
            <symbol id="i-ticket" viewBox="0 0 24 24"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"/><path d="M13 5v2M13 17v2M13 11v2"/></symbol>
            <symbol id="i-eye" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></symbol>
            <symbol id="i-sparkle" viewBox="0 0 24 24"><path d="M12 3l1.9 5.1L19 10l-5.1 1.9L12 17l-1.9-5.1L5 10l5.1-1.9z"/><path d="M19 15l.7 1.8L21.5 17.5l-1.8.7L19 20l-.7-1.8-1.8-.7 1.8-.7z"/></symbol>
            <symbol id="i-scale" viewBox="0 0 24 24"><path d="M12 3v18M5 21h14M4 8h16"/><path d="m4 8-2 7a3 3 0 0 0 4 0zM20 8l-2 7a3 3 0 0 0 4 0z"/></symbol>
            <symbol id="i-scroll" viewBox="0 0 24 24"><path d="M8 21h12a2 2 0 0 0 2-2v-2H10v2a2 2 0 1 1-4 0V5a2 2 0 1 0-4 0v3h4"/><path d="M19 17V5a2 2 0 0 0-2-2H4M15 8h-5M15 12h-5"/></symbol>
            <symbol id="i-mic" viewBox="0 0 24 24"><rect x="9" y="2" width="6" height="12" rx="3"/><path d="M19 10v1a7 7 0 0 1-14 0v-1M12 18v4M8 22h8"/></symbol>
            <symbol id="i-pen" viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/></symbol>
            <symbol id="i-landmark" viewBox="0 0 24 24"><path d="M3 22h18M6 18v-7M10 18v-7M14 18v-7M18 18v-7M12 2 3 7h18z"/></symbol>
            <symbol id="i-globe" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15 15 0 0 1 0 20 15 15 0 0 1 0-20z"/></symbol>
            <symbol id="i-quote" viewBox="0 0 24 24"><path d="M3 21c3 0 7-1 7-8V5a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h3M15 21c3 0 7-1 7-8V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h3"/></symbol>
            <symbol id="i-languages" viewBox="0 0 24 24"><path d="m5 8 6 6M4 14l6-6 2-3M2 5h12M7 2h1M22 22l-5-10-5 10M14 18h6"/></symbol>
            <symbol id="i-search" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></symbol>
        </defs>
    </svg>

    <a class="skip-link" href="#main">Skip to main content</a>

    {{-- ===== Announcement bar (only while a registration window is relevant) ===== --}}
    @if (in_array($win['state'], ['open', 'upcoming'], true))
        <div class="announce" role="status">
            <div class="container announce-inner">
                <span class="announce-dot"></span>
                @if ($win['state'] === 'open')
                    <span><strong>{{ $win['year'] }} registration is open</strong>
                        @if ($win['closes']) – closes {{ $win['closes'] }}@if (($win['days_left'] ?? null) !== null) ({{ $win['days_left'] }} {{ $win['days_left'] === 1 ? 'day' : 'days' }} left)@endif @endif
                    </span>
                @else
                    <span><strong>{{ $win['year'] }} registration opens {{ $win['opens'] }}</strong></span>
                @endif
                <a href="{{ route('users.login') }}" class="announce-link">School login <svg class="icon"><use href="#i-arrow-right"/></svg></a>
            </div>
        </div>
    @endif

    {{-- ===== Navigation ===== --}}
    <header class="nav" id="nav">
        <div class="container nav-inner">
            <a href="#top" class="brand" aria-label="{{ $site['short_name'] }} home">
                <span class="brand-logo"><img src="{{ asset('assets/images/brand/logo.png') }}" alt="" width="44" height="44"></span>
                <span class="brand-text"><strong>{{ $site['short_name'] }}</strong><small>Idaad &amp; Thanawi Examination Board</small></span>
            </a>

            <nav class="nav-links" id="nav-links" aria-label="Main">
                <a href="#about">About</a>
                <a href="#examinations">Examinations</a>
                <a href="#subjects">Subjects</a>
                <a href="#schools">For schools</a>
                <a href="#results">Results</a>
                <a href="#faq">FAQ</a>
                <a href="#contact">Contact</a>
                <a href="{{ route('users.login') }}" class="btn btn-gold nav-cta-mobile">Login</a>
            </nav>

            <div class="nav-actions">
                <a href="{{ route('users.login') }}" class="btn btn-gold btn-sm nav-login">
                    <svg class="icon"><use href="#i-lock"/></svg> Login
                </a>
                <button class="nav-toggle" id="nav-toggle" aria-label="Open menu" aria-expanded="false" aria-controls="nav-links">
                    <svg class="icon icon-open"><use href="#i-menu"/></svg>
                    <svg class="icon icon-close"><use href="#i-x"/></svg>
                </button>
            </div>
        </div>
    </header>

    <main id="main">
        {{-- ===== Hero ===== --}}
        <section class="hero" id="top">
            <div class="hero-bg" aria-hidden="true"></div>
            <div class="container hero-inner">
                <div class="hero-copy">
                    <img class="basmalah" src="{{ asset('assets/basmallah.png') }}" alt="Bismillah ir-Rahman ir-Rahim" width="214" height="45">
                    <p class="eyebrow eyebrow-light">{{ $site['parent'] }} · Examinations Board</p>
                    <h1>Setting the standard in <span class="accent">Islamic secondary education</span></h1>
                    <p class="lead lead-light">
                        {{ $site['short_name'] }} registers schools, administers the <strong>Idaad (O-Level)</strong> and
                        <strong>Thanawi (A-Level)</strong> examinations and certifies results you can trust, for schools across Uganda.
                    </p>
                    <div class="hero-cta">
                        <a href="{{ route('users.login') }}" class="btn btn-gold btn-lg">School login <svg class="icon"><use href="#i-arrow-right"/></svg></a>
                        <a href="#examinations" class="btn btn-ghost btn-lg">Explore the examinations</a>
                    </div>
                    <ul class="hero-points">
                        <li><svg class="icon"><use href="#i-check"/></svg> Secure digital registration</li>
                        <li><svg class="icon"><use href="#i-check"/></svg> Multi-level result verification</li>
                        <li><svg class="icon"><use href="#i-check"/></svg> Recognised school certificates</li>
                    </ul>
                </div>

                <aside class="hero-card-wrap" aria-label="Current status">
                    @if (in_array($win['state'], ['open', 'upcoming', 'ended'], true))
                        <div class="hero-card" data-reveal>
                            <div class="hc-head">
                                <span class="hc-label">Registration {{ $win['year'] }}</span>
                                <span class="hc-state hc-{{ $win['state'] }}">
                                    <i></i>{{ $win['state'] === 'open' ? 'Open' : ($win['state'] === 'upcoming' ? 'Opening soon' : 'Closed') }}
                                </span>
                            </div>
                            <div class="hc-ring" data-progress="{{ $win['progress'] ?? 0 }}">
                                <svg viewBox="0 0 120 120" aria-hidden="true">
                                    <circle class="ring-track" cx="60" cy="60" r="52"/>
                                    <circle class="ring-bar" cx="60" cy="60" r="52"/>
                                </svg>
                                <div class="ring-text">
                                    @if ($win['state'] === 'open' && ($win['days_left'] ?? null) !== null)
                                        <b>{{ $win['days_left'] }}</b><span>{{ $win['days_left'] === 1 ? 'day left' : 'days left' }}</span>
                                    @elseif ($win['state'] === 'upcoming')
                                        <b>Soon</b><span>opens {{ $win['opens'] }}</span>
                                    @else
                                        <b>Closed</b><span>{{ $win['closes'] }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="hc-dates">
                                <div><small>Opens</small><b>{{ $win['opens'] ?? '—' }}</b></div>
                                <div><small>Closes</small><b>{{ $win['closes'] ?? '—' }}</b></div>
                            </div>
                            <a href="{{ route('users.login') }}" class="btn btn-light btn-block">Register candidates <svg class="icon"><use href="#i-arrow-right"/></svg></a>
                        </div>
                    @else
                        <div class="hero-card" data-reveal>
                            <div class="hc-head"><span class="hc-label">Two levels, one standard</span></div>
                            <div class="hc-levels">
                                <div><b>Idaad</b><span>O-Level · 4 years</span></div>
                                <div><b>Thanawi</b><span>A-Level · 2 years</span></div>
                            </div>
                            <a href="#examinations" class="btn btn-light btn-block">Explore the examinations <svg class="icon"><use href="#i-arrow-right"/></svg></a>
                        </div>
                    @endif

                    @if (!empty($papers))
                        <div class="hero-mini" data-reveal>
                            <svg class="icon"><use href="#i-calendar"/></svg>
                            <div>
                                <small>Next paper</small>
                                <b dir="rtl" lang="ar">{{ $papers[0]['subject'] }}</b>
                                <span>{{ $papers[0]['level'] }} · {{ $papers[0]['weekday'] }} {{ $papers[0]['day'] }} {{ $papers[0]['month'] }}</span>
                            </div>
                        </div>
                    @endif
                </aside>
            </div>
            <svg class="hero-wave" viewBox="0 0 1440 80" preserveAspectRatio="none" aria-hidden="true"><path d="M0 80V40C240 0 480 0 720 24s480 40 720 0v56z"/></svg>
        </section>

        {{-- ===== Live stats ===== --}}
        @if (!empty($stats))
            <section class="stats" aria-label="ITEB at a glance">
                <div class="container">
                    <div class="stats-grid stats-{{ count($stats) }}">
                        @foreach ($stats as $s)
                            <div class="stat" data-reveal>
                                <span class="stat-icon"><svg class="icon"><use href="#i-{{ $s['icon'] }}"/></svg></span>
                                <div class="stat-num"><span class="count" data-to="{{ $s['value'] }}">{{ number_format($s['value']) }}</span></div>
                                <div class="stat-label">{{ $s['label'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        {{-- ===== About ===== --}}
        <section class="section" id="about">
            <div class="container split">
                <div class="split-copy" data-reveal>
                    <p class="eyebrow">About ITEB</p>
                    <h2>The official examination board for Islamic secondary education</h2>
                    <p class="lead">The {{ $site['name'] }} is based in Kawempe, Kampala, and conducts examinations under the Uganda Muslim Supreme Council (UMSC). It is responsible for standardising, administering and certifying Islamic secondary education at both the Idaad and Thanawi stages.</p>
                    <p>Our work is rooted in authentic sources and a commitment to transparency and quality: structured curricula covering Qur’anic recitation, Hadith verification and jurisprudence, delivered through processes schools and families can trust.</p>
                    <div class="callout">
                        <svg class="icon"><use href="#i-graduation"/></svg>
                        <p><strong>The duo curriculum.</strong> Many schools now combine standard secondary subjects with advanced Islamic theology, helping students compete in the international market while staying grounded in their faith.</p>
                    </div>
                </div>

                <div class="cap-grid">
                    @foreach ($capabilities as $i => [$icon, $title, $text])
                        <article class="cap" data-reveal style="--d:{{ $i * 70 }}ms">
                            <span class="cap-icon"><svg class="icon"><use href="#i-{{ $icon }}"/></svg></span>
                            <h3>{{ $title }}</h3>
                            <p>{{ $text }}</p>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ===== Examinations ===== --}}
        <section class="section section-cream pattern" id="examinations">
            <div class="container">
                <header class="section-head center" data-reveal>
                    <p class="eyebrow">The examinations</p>
                    <h2>Two levels, one clear pathway</h2>
                    <p class="lead">From foundational knowledge to advanced specialisation, Idaad and Thanawi guide students through Islamic secondary education and on to university.</p>
                </header>

                <div class="levels">
                    <article class="level level-idaad" data-reveal>
                        <div class="level-top">
                            <span class="level-tag">Ordinary Level</span>
                            <span class="level-ar" lang="ar" dir="rtl">الإعدادية</span>
                        </div>
                        <h3>Idaad</h3>
                        <p>The O-Level stage of secondary education, specialising in Islamic theology and building the foundations every later study depends on.</p>
                        <ul class="ticks">
                            <li><svg class="icon"><use href="#i-clock"/></svg> <span>Generally a <strong>3-year</strong> programme</span></li>
                            <li><svg class="icon"><use href="#i-book-open"/></svg> <span>Monotheism, exegesis, jurisprudence, Hadith, Qur’anic orals, Arabic and history</span></li>
                            <li><svg class="icon"><use href="#i-calendar"/></svg> <span>Results released in {{ $release['Idaad'] ?? 'March' }}</span></li>
                        </ul>
                    </article>

                    <div class="level-arrow" aria-hidden="true"><svg class="icon"><use href="#i-arrow-right"/></svg></div>

                    <article class="level level-thanawi" data-reveal style="--d:90ms">
                        <div class="level-top">
                            <span class="level-tag">Advanced Level</span>
                            <span class="level-ar" lang="ar" dir="rtl">الثانوية</span>
                        </div>
                        <h3>Thanawi</h3>
                        <p>The A-Level stage: deeper specialisation in the Islamic sciences, preparing students for university-level Islamic scholarship.</p>
                        <ul class="ticks">
                            <li><svg class="icon"><use href="#i-clock"/></svg> <span>Generally a <strong>3-year</strong> programme</span></li>
                            <li><svg class="icon"><use href="#i-book-open"/></svg> <span>Qur’an exegesis, rituals, religion &amp; sects, rhetoric, grammar and morphology</span></li>
                            <li><svg class="icon"><use href="#i-calendar"/></svg> <span>Results released in {{ $release['Thanawi'] ?? 'May' }}</span></li>
                        </ul>
                    </article>
                </div>

                <ol class="pathway" data-reveal aria-label="Education pathway">
                    <li><span><svg class="icon"><use href="#i-book-open"/></svg></span><b>Primary Islamic education</b><small>Where students begin</small></li>
                    <li><span><svg class="icon"><use href="#i-school"/></svg></span><b>Idaad</b><small>O-Level · 3 years</small></li>
                    <li><span><svg class="icon"><use href="#i-graduation"/></svg></span><b>Thanawi</b><small>A-Level · 3 years</small></li>
                    <li><span><svg class="icon"><use href="#i-landmark"/></svg></span><b>University</b><small>Certification for Studies Abroad.</small></li>
                </ol>
            </div>
        </section>

        {{-- ===== Subjects ===== --}}
        <section class="section" id="subjects">
            <div class="container">
                <header class="section-head center" data-reveal>
                    <p class="eyebrow">Subjects</p>
                    <h2>What students study</h2>
                    <p class="lead">A rigorous curriculum drawn from the Qur’an, Hadith, jurisprudence and the Arabic language. Filter by level to see what each stage covers.</p>
                </header>

                <div class="filters" role="group" aria-label="Filter subjects by level" data-reveal>
                    <button type="button" class="chip is-active" data-filter="all" aria-pressed="true">All subjects <span>{{ count($subjects) }}</span></button>
                    <button type="button" class="chip" data-filter="id" aria-pressed="false">Idaad <span>{{ collect($subjects)->whereIn(1, ['id', 'both'])->count() }}</span></button>
                    <button type="button" class="chip" data-filter="th" aria-pressed="false">Thanawi <span>{{ collect($subjects)->whereIn(1, ['th', 'both'])->count() }}</span></button>
                </div>

                <div class="subjects" id="subjects-grid">
                    @foreach ($subjects as [$name, $level, $desc, $icon])
                        <article class="subject" data-level="{{ $level }}">
                            <div class="subject-top">
                                <span class="subject-icon"><svg class="icon"><use href="#i-{{ $icon }}"/></svg></span>
                                <span class="badges">
                                    @if ($level === 'id' || $level === 'both')<em class="badge badge-id">Idaad</em>@endif
                                    @if ($level === 'th' || $level === 'both')<em class="badge badge-th">Thanawi</em>@endif
                                </span>
                            </div>
                            <h3>{{ $name }}</h3>
                            <p>{{ $desc }}</p>
                        </article>
                    @endforeach
                </div>
                <p class="note">Subjects shown are representative; the full list at each level includes other subjects as well.</p>
            </div>
        </section>

        {{-- ===== How it works ===== --}}
        <section class="section section-dark" id="how">
            <div class="container">
                <header class="section-head center" data-reveal>
                    <p class="eyebrow eyebrow-light">For schools</p>
                    <h2>From registration to results in six steps</h2>
                    <p class="lead lead-light">A clear, digital process that keeps schools and the board in step at every stage.</p>
                </header>

                <ol class="steps">
                    @foreach ($steps as $i => [$icon, $title, $text])
                        <li class="step" data-reveal style="--d:{{ $i * 60 }}ms">
                            <span class="step-num">{{ $i + 1 }}</span>
                            <span class="step-icon"><svg class="icon"><use href="#i-{{ $icon }}"/></svg></span>
                            <h3>{{ $title }}</h3>
                            <p>{{ $text }}</p>
                        </li>
                    @endforeach
                </ol>
            </div>
        </section>

        {{-- ===== School portal ===== --}}
        <section class="section section-cream" id="schools">
            <div class="container split split-reverse">
                <div class="portal-grid">
                    
                    @foreach ($portal as $i => [$icon, $title, $text])
                        <article class="feature" data-reveal style="--d:{{ $i * 60 }}ms">
                            <span class="feature-icon"><svg class="icon"><use href="#i-{{ $icon }}"/></svg></span>
                            <div><h3>{{ $title }}</h3><p>{{ $text }}</p></div>
                        </article>
                    @endforeach
                </div>

                <div class="split-copy" data-reveal>
                    <p class="eyebrow">The school portal</p>
                    <h2>Everything your school needs, in one place</h2>
                    <p class="lead">Registered schools manage their entire relationship with the board online: no queues, no paper forms, no guesswork about where things stand.</p>
                    <div class="cta-row">
                        <a href="{{ route('users.login') }}" class="btn btn-primary btn-lg">Log in to the portal <svg class="icon"><use href="#i-arrow-right"/></svg></a>
                        <a href="#contact" class="btn btn-outline btn-lg">Register your school</a>
                    </div>
                    <p class="fine">New to ITEB? Contact the board to have your school registered and receive your login details.</p>
                </div>
            </div>
        </section>

        {{-- ===== Results ===== --}}
        <section class="section" id="results">
            <div class="container split">
                <div class="split-copy" data-reveal>
                    <p class="eyebrow">Results &amp; calendar</p>
                    <h2>Reliable results, released on schedule</h2>
                    <p class="lead">Every result passes through multi-level verification before it is released, so schools and families can rely on it.</p>

                    <div class="release">
                        <div><small>Idaad results</small><b>{{ $release['Idaad'] ?? 'March' }}</b></div>
                        <div><small>Thanawi results</small><b>{{ $release['Thanawi'] ?? 'May' }}</b></div>
                    </div>

                    <ul class="ticks">
                        <li><svg class="icon"><use href="#i-shield-check"/></svg> <span>Results are <strong>provisional</strong> until confirmed by the board.</span></li>
                        <li><svg class="icon"><use href="#i-search"/></svg> <span>Forgotten your registration number? Ask your school administration or contact the board.</span></li>
                        <li><svg class="icon"><use href="#i-users"/></svg> <span>Spotted a discrepancy? Speak to your school so it can be raised with ITEB.</span></li>
                    </ul>

                    @if ($portalOn)
                        <a href="{{ route('public.portal') }}" class="btn btn-primary btn-lg">Check your results <svg class="icon"><use href="#i-arrow-right"/></svg></a>
                    @endif
                </div>

                <div class="timetable" data-reveal>
                    <div class="tt-head">
                        <h3>Upcoming papers</h3>
                        <span class="tt-sub">From the official timetable</span>
                    </div>
                    @if (!empty($papers))
                        <ul class="tt-list">
                            @foreach ($papers as $p)
                                <li>
                                    <div class="tt-date"><b>{{ $p['day'] }}</b><span>{{ $p['month'] }}</span></div>
                                    <div class="tt-info">
                                        <strong lang="ar" dir="rtl">{{ $p['subject'] }}</strong>
                                        @if ($p['paper'])<span class="tt-paper" lang="ar" dir="rtl">{{ $p['paper'] }}</span>@endif
                                        <small>{{ $p['level'] }} · {{ $p['weekday'] }} · {{ $p['time'] }}</small>
                                    </div>
                                    <span class="tt-tag {{ $p['days_until'] <= 7 ? 'soon' : '' }}">
                                        {{ $p['days_until'] === 0 ? 'Today' : ($p['days_until'] === 1 ? 'Tomorrow' : 'In ' . $p['days_until'] . ' days') }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="tt-empty">
                            <svg class="icon"><use href="#i-calendar"/></svg>
                            <p>No papers are scheduled right now. Check back soon, or contact the board for the latest timetable.</p>
                        </div>
                    @endif
                </div>
            </div>
        </section>

        {{-- ===== Leadership ===== --}}
        <section class="section section-cream" id="leadership">
            <div class="container">
                <header class="section-head center" data-reveal>
                    <p class="eyebrow">Leadership</p>
                    <h2>Guided by dedicated leaders</h2>
                    <p class="lead">The board’s work is overseen by respected scholars and administrators serving the Muslim community in Uganda.</p>
                </header>

                <div class="leaders">
                    @foreach ($site['leadership'] as $i => $l)
                        <article class="leader" data-reveal style="--d:{{ $i * 80 }}ms">
                            <span class="leader-avatar">{{ $initials($l['name']) }}</span>
                            <h3>{{ $l['name'] }}</h3>
                            <p>{{ $l['role'] }}</p>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ===== FAQ ===== --}}
        <section class="section" id="faq">
            <div class="container faq-wrap">
                <header class="section-head" data-reveal>
                    <p class="eyebrow">FAQ</p>
                    <h2>Frequently asked questions</h2>
                    <p class="lead">Quick answers about the Idaad and Thanawi system, school registration and results. Can’t find what you need? <a href="#contact">Get in touch</a>.</p>
                </header>

                <div class="faq-list" id="faq-list">
                    @foreach ($faqs as $i => [$q, $a])
                        <details class="faq" data-reveal @if ($i === 0) open @endif>
                            <summary>
                                <span>{{ $q }}</span>
                                <svg class="icon"><use href="#i-chevron-down"/></svg>
                            </summary>
                            <div class="faq-body"><p>{{ $a }}</p></div>
                        </details>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ===== Call to action ===== --}}
        <section class="cta-band">
            <div class="container cta-inner" data-reveal>
                <div>
                    <h2>Ready to bring your school onboard?</h2>
                    <p>Join the schools already registered with ITEB and give your students the recognition they deserve.</p>
                </div>
                <div class="cta-row">
                    <a href="{{ route('users.login') }}" class="btn btn-gold btn-lg">School login</a>
                    <a href="#contact" class="btn btn-ghost btn-lg">Contact the board</a>
                </div>
            </div>
        </section>

        {{-- ===== Contact ===== --}}
        <section class="section" id="contact">
            <div class="container">
                <header class="section-head center" data-reveal>
                    <p class="eyebrow">Contact</p>
                    <h2>We’re here to help</h2>
                    <p class="lead">Questions about registration, examinations or results? Reach the board directly.</p>
                </header>

                <div class="contact-grid">
                    <a class="contact-card" href="mailto:{{ $c['email'] }}" data-reveal>
                        <span class="cap-icon"><svg class="icon"><use href="#i-mail"/></svg></span>
                        <small>Email</small><b>{{ $c['email'] }}</b>
                    </a>
                    <a class="contact-card" href="tel:{{ preg_replace('/[^+\d]/', '', $c['phone']) }}" data-reveal style="--d:70ms">
                        <span class="cap-icon"><svg class="icon"><use href="#i-phone"/></svg></span>
                        <small>Phone</small><b>{{ $c['phone'] }}</b>
                    </a>
                    <div class="contact-card" data-reveal style="--d:140ms">
                        <span class="cap-icon"><svg class="icon"><use href="#i-map-pin"/></svg></span>
                        <small>Visit us</small><b>{{ $c['address'] }}</b>
                    </div>
                </div>

                <p class="center-text" data-reveal><a href="{{ route('contact.us') }}" class="btn btn-outline">Send us a message <svg class="icon"><use href="#i-arrow-right"/></svg></a></p>
            </div>
        </section>
    </main>

    {{-- ===== Footer ===== --}}
    <footer class="footer">
        <div class="container footer-grid">
            <div class="footer-brand">
                <a href="#top" class="brand brand-light">
                    <span class="brand-logo"><img src="{{ asset('assets/images/brand/logo.png') }}" alt="" width="44" height="44" loading="lazy"></span>
                    <span class="brand-text"><strong>{{ $site['short_name'] }}</strong><small>{{ $site['name'] }}</small></span>
                </a>
                <p>The official examination board responsible for standardising, administering and certifying Islamic secondary education in Uganda, under the {{ $site['parent'] }}.</p>
                @if ($social)
                    <ul class="social">
                        @foreach ($social as $name => $url)
                            <li><a href="{{ $url }}" target="_blank" rel="noopener noreferrer">{{ ['facebook' => 'Facebook', 'x' => 'X', 'youtube' => 'YouTube', 'whatsapp' => 'WhatsApp'][$name] ?? ucfirst($name) }}</a></li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <nav class="footer-col" aria-label="Explore">
                <h4>Explore</h4>
                <a href="#about">About ITEB</a>
                <a href="#examinations">Examinations</a>
                <a href="#subjects">Subjects</a>
                <a href="#results">Results &amp; calendar</a>
                <a href="#faq">FAQ</a>
            </nav>

            <nav class="footer-col" aria-label="Schools">
                <h4>Schools</h4>
                <a href="{{ route('users.login') }}">Portal login</a>
                <a href="#how">How registration works</a>
                <a href="#schools">Portal features</a>
                <a href="{{ route('about.us') }}">About us</a>
                <a href="{{ route('contact.us') }}">Contact us</a>
            </nav>

            <div class="footer-col">
                <h4>Get in touch</h4>
                <a href="mailto:{{ $c['email'] }}">{{ $c['email'] }}</a>
                <a href="tel:{{ preg_replace('/[^+\d]/', '', $c['phone']) }}">{{ $c['phone'] }}</a>
                <span>{{ $c['address'] }}</span>
            </div>
        </div>
        <div class="container footer-bottom">
            <span>© {{ date('Y') }} {{ $site['name'] }}. All rights reserved.</span>
            <span>Operating under the {{ $site['parent'] }}</span>
        </div>
    </footer>

    <button class="to-top" id="to-top" aria-label="Back to top"><svg class="icon"><use href="#i-arrow-up"/></svg></button>

    <script src="{{ asset('assets/js/landing.js') }}?v={{ @filemtime(public_path('assets/js/landing.js')) }}" defer></script>
</body>

</html>
