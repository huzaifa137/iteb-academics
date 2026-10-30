<?php

/*
|--------------------------------------------------------------------------
| Public landing page (/users/home-page)
|--------------------------------------------------------------------------
| Everything an administrator is likely to want to edit lives here or in .env,
| so the page itself never needs to be touched for routine changes.
|
| .env keys (all optional):
|   LANDING_PHONE, LANDING_EMAIL, LANDING_ADDRESS
|   LANDING_FACEBOOK, LANDING_X, LANDING_YOUTUBE, LANDING_WHATSAPP   (full URLs; hidden when empty)
|   LANDING_SHOW_STATS=true         show the live headline numbers
|   LANDING_SHOW_TIMETABLE=true     show upcoming papers from the exam timetable
|   LANDING_RESULTS_PORTAL=false    show links to the public results portal
*/

return [

    'name' => 'Idaad & Thanawi Examination Board',
    'short_name' => 'ITEB',
    'parent' => 'Uganda Muslim Supreme Council (UMSC)',

    'contact' => [
        'phone' => env('LANDING_PHONE', '+256 702 595 554'),
        'email' => env('LANDING_EMAIL', 'info@iteb-ug.org'),
        'address' => env('LANDING_ADDRESS', 'Kawempe, Kampala, Uganda'),
    ],

    'social' => [
        'facebook' => env('LANDING_FACEBOOK'),
        'x' => env('LANDING_X'),
        'youtube' => env('LANDING_YOUTUBE'),
        'whatsapp' => env('LANDING_WHATSAPP'),
    ],

    // Public aggregate numbers (schools, students, results). Counts only: no personal data is ever exposed.
    'show_stats' => env('LANDING_SHOW_STATS', true),

    // The timetable table has no "published" flag, so anything entered there becomes visible. Turn off if you
    // prefer to publish timetables another way.
    'show_timetable' => env('LANDING_SHOW_TIMETABLE', true),

    // The existing /users/public-portal page currently shows demonstration data. Keep this off until a real
    // results lookup is connected.
    'results_portal_enabled' => env('LANDING_RESULTS_PORTAL', false),

    'results_release' => [
        'Idaad' => 'March',
        'Thanawi' => 'May',
    ],

    'leadership' => [
        ['name' => 'Prof. Dr. Ziyad Swaleh Lubanga', 'role' => 'Executive Secretary'],
        ['name' => 'Sheikh Hatimu Wamala', 'role' => 'Board Chairperson'],
        ['name' => 'Dr. Shaban Mubaje', 'role' => 'Mufti'],
    ],
];
