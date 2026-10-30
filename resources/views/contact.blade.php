@php
    $c = $site['contact'];
    $social = array_filter($site['social'] ?? []);
    $topics = $site['contact_topics'] ?? [];
    $sent = session('contact_sent');
    $home = route('home.page');
    $phoneHref = 'tel:' . preg_replace('/[^+\d]/', '', $c['phone']);
    $mapQuery = urlencode($c['address']);

    $pageTitle = 'Contact ' . $site['short_name'] . ' | ' . $site['name'];
    $pageDesc = 'Get in touch with the ' . $site['name'] . ' (' . $site['short_name'] . ') about school registration, examinations, results and certificates. Send a message, call or visit us in ' . $c['address'] . '.';

    $jsonLd = [
        '@context' => 'https://schema.org',
        '@type' => 'ContactPage',
        'name' => $pageTitle,
        'url' => route('contact.us'),
        'mainEntity' => [
            '@type' => 'EducationalOrganization',
            'name' => $site['name'],
            'alternateName' => $site['short_name'],
            'url' => $home,
            'logo' => asset('assets/images/brand/logo.png'),
            'email' => $c['email'],
            'telephone' => $c['phone'],
            'address' => ['@type' => 'PostalAddress', 'streetAddress' => $c['address'], 'addressCountry' => 'UG'],
        ],
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
    <link rel="canonical" href="{{ route('contact.us') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/brand/logo.png') }}">

    <meta property="og:type" content="website">
    <meta property="og:title" content="Contact {{ $site['short_name'] }}">
    <meta property="og:description" content="{{ $pageDesc }}">
    <meta property="og:url" content="{{ route('contact.us') }}">
    <meta property="og:image" content="{{ asset('assets/images/brand/logo.png') }}">
    <meta name="twitter:card" content="summary">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/landing.css') }}?v={{ @filemtime(public_path('assets/css/landing.css')) }}">
    <link rel="stylesheet" href="{{ asset('assets/css/contact.css') }}?v={{ @filemtime(public_path('assets/css/contact.css')) }}">

    <script>document.documentElement.className += ' js';</script>
    <script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) !!}</script>
</head>

<body>
    {{-- Icon sprite --}}
    <svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
        <defs>
            <symbol id="i-arrow-right" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></symbol>
            <symbol id="i-arrow-up" viewBox="0 0 24 24"><path d="M12 19V5M5 12l7-7 7 7"/></symbol>
            <symbol id="i-menu" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></symbol>
            <symbol id="i-x" viewBox="0 0 24 24"><path d="M18 6 6 18M6 6l12 12"/></symbol>
            <symbol id="i-check" viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></symbol>
            <symbol id="i-check-circle" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="M22 4 12 14.01l-3-3"/></symbol>
            <symbol id="i-mail" viewBox="0 0 24 24"><path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"/><path d="m22 6-10 7L2 6"/></symbol>
            <symbol id="i-phone" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></symbol>
            <symbol id="i-map-pin" viewBox="0 0 24 24"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></symbol>
            <symbol id="i-lock" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></symbol>
            <symbol id="i-send" viewBox="0 0 24 24"><path d="m22 2-7 20-4-9-9-4z"/><path d="M22 2 11 13"/></symbol>
            <symbol id="i-alert" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></symbol>
            <symbol id="i-help" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3M12 17h.01"/></symbol>
            <symbol id="i-users" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></symbol>
            <symbol id="i-external" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6M15 3h6v6M10 14 21 3"/></symbol>
            <symbol id="i-shield-check" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></symbol>
        </defs>
    </svg>

    <a class="skip-link" href="#main">Skip to main content</a>

    {{-- ===== Navigation ===== --}}
    <header class="nav" id="nav">
        <div class="container nav-inner">
            <a href="{{ $home }}" class="brand" aria-label="{{ $site['short_name'] }} home">
                <span class="brand-logo"><img src="{{ asset('assets/images/brand/logo.png') }}" alt="" width="44" height="44"></span>
                <span class="brand-text"><strong>{{ $site['short_name'] }}</strong><small>Idaad &amp; Thanawi Examination Board</small></span>
            </a>

            <nav class="nav-links" id="nav-links" aria-label="Main">
                <a href="{{ $home }}">Home</a>
                <a href="{{ $home }}#about">About</a>
                <a href="{{ $home }}#examinations">Examinations</a>
                <a href="{{ $home }}#subjects">Subjects</a>
                <a href="{{ $home }}#schools">For schools</a>
                <a href="{{ $home }}#faq">FAQ</a>
                <a href="{{ route('contact.us') }}" aria-current="page">Contact</a>
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
        {{-- ===== Page header ===== --}}
        <section class="page-hero">
            <div class="hero-bg" aria-hidden="true"></div>
            <div class="container page-hero-inner">
                <nav class="crumbs" aria-label="Breadcrumb">
                    <a href="{{ $home }}">Home</a>
                    <span aria-hidden="true">/</span>
                    <span aria-current="page">Contact</span>
                </nav>
                <p class="eyebrow eyebrow-light">Contact the board</p>
                <h1>We’d love to <span class="accent">hear from you</span></h1>
                <p class="lead lead-light">
                    Questions about registration, examinations, results or school recognition? Send us a message
                    and the right person at {{ $site['short_name'] }} will get back to you.
                </p>
            </div>
            <svg class="hero-wave" viewBox="0 0 1440 80" preserveAspectRatio="none" aria-hidden="true"><path d="M0 80V40C240 0 480 0 720 24s480 40 720 0v56z"/></svg>
        </section>

        {{-- ===== Quick contact cards ===== --}}
        <section class="quick" aria-label="Contact details">
            <div class="container">
                <div class="contact-grid">
                    <a class="contact-card" href="mailto:{{ $c['email'] }}" data-reveal>
                        <span class="cap-icon"><svg class="icon"><use href="#i-mail"/></svg></span>
                        <small>Email</small><b>{{ $c['email'] }}</b>
                    </a>
                    <a class="contact-card" href="{{ $phoneHref }}" data-reveal style="--d:70ms">
                        <span class="cap-icon"><svg class="icon"><use href="#i-phone"/></svg></span>
                        <small>Phone</small><b>{{ $c['phone'] }}</b>
                    </a>
                    <a class="contact-card" href="https://www.google.com/maps/search/?api=1&amp;query={{ $mapQuery }}" target="_blank" rel="noopener noreferrer" data-reveal style="--d:140ms">
                        <span class="cap-icon"><svg class="icon"><use href="#i-map-pin"/></svg></span>
                        <small>Visit us</small><b>{{ $c['address'] }}</b>
                    </a>
                </div>
            </div>
        </section>

        {{-- ===== Form + side column ===== --}}
        <section class="section contact-section">
            <div class="container contact-main">

                <div class="form-card" id="send-message" data-reveal>
                    @if ($sent)
                        <div class="sent" role="status">
                            <span class="sent-icon"><svg class="icon"><use href="#i-check-circle"/></svg></span>
                            <h2>Message sent</h2>
                            <p>Thank you for contacting {{ $site['short_name'] }}. We have received your message and will reply by email as soon as we can.</p>
                            @if (!empty($sent['ref']))
                                <span class="ref">Reference: {{ $sent['ref'] }}</span>
                            @endif
                            <div class="sent-actions">
                                <a href="{{ route('contact.us') }}" class="btn btn-outline">Send another message</a>
                                <a href="{{ $home }}" class="btn btn-primary">Back to home <svg class="icon"><use href="#i-arrow-right"/></svg></a>
                            </div>
                        </div>
                    @else
                        <h2>Send us a message</h2>
                        <p class="form-sub">Fields marked <span class="req">*</span> are required.</p>

                        @if ($errors->has('form'))
                            <div class="alert alert-error" role="alert">
                                <svg class="icon"><use href="#i-alert"/></svg>
                                <span>{{ $errors->first('form') }}</span>
                            </div>
                        @elseif ($errors->any())
                            <div class="alert alert-error" role="alert">
                                <svg class="icon"><use href="#i-alert"/></svg>
                                <span>Please check the highlighted fields and try again.</span>
                            </div>
                        @endif

                        <form id="contact-form" method="POST" action="{{ route('contact.submit') }}">
                            @csrf

                            {{-- Honeypot: hidden from people, irresistible to bots. --}}
                            <div class="hp" aria-hidden="true">
                                <label>Leave this field empty <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                            </div>

                            <div class="form-grid">
                                <div class="field {{ $errors->has('name') ? 'has-error' : '' }}">
                                    <label for="f-name">Full name <span class="req">*</span></label>
                                    <input class="input" type="text" id="f-name" name="name" value="{{ old('name') }}" maxlength="120" required autocomplete="name" placeholder="Your full name" @error('name') aria-invalid="true" aria-describedby="e-name" @enderror>
                                    @error('name')<p class="field-error" id="e-name">{{ $message }}</p>@enderror
                                </div>

                                <div class="field {{ $errors->has('email') ? 'has-error' : '' }}">
                                    <label for="f-email">Email address <span class="req">*</span></label>
                                    <input class="input" type="email" id="f-email" name="email" value="{{ old('email') }}" maxlength="190" required autocomplete="email" placeholder="you@example.com" @error('email') aria-invalid="true" aria-describedby="e-email" @enderror>
                                    @error('email')<p class="field-error" id="e-email">{{ $message }}</p>@enderror
                                </div>

                                <div class="field {{ $errors->has('phone') ? 'has-error' : '' }}">
                                    <label for="f-phone">Phone <span class="opt">(optional)</span></label>
                                    <input class="input" type="tel" id="f-phone" name="phone" value="{{ old('phone') }}" maxlength="40" autocomplete="tel" placeholder="+256 …" @error('phone') aria-invalid="true" aria-describedby="e-phone" @enderror>
                                    @error('phone')<p class="field-error" id="e-phone">{{ $message }}</p>@enderror
                                </div>

                                <div class="field {{ $errors->has('school') ? 'has-error' : '' }}">
                                    <label for="f-school">School <span class="opt">(optional)</span></label>
                                    <input class="input" type="text" id="f-school" name="school" value="{{ old('school') }}" maxlength="190" autocomplete="organization" placeholder="Your school or institution" @error('school') aria-invalid="true" aria-describedby="e-school" @enderror>
                                    @error('school')<p class="field-error" id="e-school">{{ $message }}</p>@enderror
                                </div>

                                <div class="field full {{ $errors->has('topic') ? 'has-error' : '' }}">
                                    <label for="f-topic">What is your message about? <span class="req">*</span></label>
                                    <select class="input select" id="f-topic" name="topic" required @error('topic') aria-invalid="true" aria-describedby="e-topic" @enderror>
                                        <option value="" disabled {{ old('topic') ? '' : 'selected' }}>Choose a topic</option>
                                        @foreach ($topics as $key => $label)
                                            <option value="{{ $key }}" {{ old('topic') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('topic')<p class="field-error" id="e-topic">{{ $message }}</p>@enderror
                                </div>

                                <div class="field full {{ $errors->has('message') ? 'has-error' : '' }}">
                                    <label for="f-message">Your message <span class="req">*</span></label>
                                    <textarea class="input textarea" id="f-message" name="message" rows="6" minlength="10" maxlength="3000" required placeholder="How can we help? Please include any registration numbers or school details that will help us answer faster." @error('message') aria-invalid="true" aria-describedby="e-message" @enderror>{{ old('message') }}</textarea>
                                    <div class="hint">
                                        <span>@error('message')<span class="field-error" id="e-message">{{ $message }}</span>@enderror</span>
                                        <span><span id="msg-count">0</span> / 3000</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-foot">
                                <p class="privacy"><svg class="icon"><use href="#i-shield-check"/></svg> We only use your details to reply to your message.</p>
                                <button type="submit" class="btn btn-primary btn-lg" id="send-btn">
                                    <span class="btn-label">Send message</span>
                                    <svg class="icon"><use href="#i-send"/></svg>
                                </button>
                            </div>
                        </form>
                    @endif
                </div>

                <aside class="side-stack" aria-label="More ways to reach us">
                    <div class="map-card" data-reveal style="--d:80ms">
                        <iframe title="Map showing {{ $c['address'] }}" src="https://www.google.com/maps?q={{ $mapQuery }}&amp;output=embed" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
                        <div class="map-cap">
                            <span class="cap-icon"><svg class="icon"><use href="#i-map-pin"/></svg></span>
                            <div>
                                <b>{{ $site['short_name'] }} office</b>
                                <span>{{ $c['address'] }}</span>
                            </div>
                            <a href="https://www.google.com/maps/search/?api=1&amp;query={{ $mapQuery }}" target="_blank" rel="noopener noreferrer" class="map-open" aria-label="Open in Google Maps"><svg class="icon"><use href="#i-external"/></svg></a>
                        </div>
                    </div>

                    <div class="help-card" data-reveal style="--d:140ms">
                        <h3>Looking for something else?</h3>
                        <p>These may get you an answer even faster.</p>
                        <a class="help-link" href="{{ $home }}#faq"><span><svg class="icon"><use href="#i-help"/></svg> Read the FAQ</span><svg class="icon"><use href="#i-arrow-right"/></svg></a>
                        <a class="help-link" href="{{ route('users.login') }}"><span><svg class="icon"><use href="#i-lock"/></svg> School portal login</span><svg class="icon"><use href="#i-arrow-right"/></svg></a>
                        <a class="help-link" href="{{ $home }}#about"><span><svg class="icon"><use href="#i-users"/></svg> About {{ $site['short_name'] }}</span><svg class="icon"><use href="#i-arrow-right"/></svg></a>
                        @if (!empty($social['whatsapp']))
                            <a class="btn btn-gold btn-block help-wa" href="{{ $social['whatsapp'] }}" target="_blank" rel="noopener noreferrer">Chat on WhatsApp</a>
                        @endif
                    </div>
                </aside>
            </div>
        </section>
    </main>

    {{-- ===== Footer ===== --}}
    <footer class="footer">
        <div class="container footer-grid">
            <div class="footer-brand">
                <a href="{{ $home }}" class="brand brand-light">
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
                <a href="{{ $home }}#about">About ITEB</a>
                <a href="{{ $home }}#examinations">Examinations</a>
                <a href="{{ $home }}#subjects">Subjects</a>
                <a href="{{ $home }}#results">Results &amp; calendar</a>
                <a href="{{ $home }}#faq">FAQ</a>
            </nav>

            <nav class="footer-col" aria-label="Schools">
                <h4>Schools</h4>
                <a href="{{ route('users.login') }}">Portal login</a>
                <a href="{{ $home }}#how">How registration works</a>
                <a href="{{ $home }}#schools">Portal features</a>
                <a href="{{ route('contact.us') }}">Contact us</a>
            </nav>

            <div class="footer-col">
                <h4>Get in touch</h4>
                <a href="mailto:{{ $c['email'] }}">{{ $c['email'] }}</a>
                <a href="{{ $phoneHref }}">{{ $c['phone'] }}</a>
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
    <script src="{{ asset('assets/js/contact-page.js') }}?v={{ @filemtime(public_path('assets/js/contact-page.js')) }}" defer></script>
</body>

</html>
