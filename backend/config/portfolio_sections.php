<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Home page sections
    |--------------------------------------------------------------------------
    | Toggle visibility and edit on-page copy from Admin → Sections.
    | Hero (#home) is always shown and uses Profile fields.
    */
    'about' => [
        'label' => 'About',
        'description' => 'Bio and education block.',
        'default' => true,
        'copy' => [
            'nav_label' => 'About',
            'eyebrow' => 'About',
            'title' => 'Building Laravel Products That Perform',
            'subtitle' => 'Laravel developer in Beirut with 3+ years building e-commerce platforms, corporate websites, and custom web applications for businesses across the Middle East.',
            'align' => 'left',
        ],
    ],
    'services' => [
        'label' => 'Services',
        'description' => 'What you offer clients.',
        'default' => true,
        'copy' => [
            'nav_label' => 'Services',
            'eyebrow' => 'Services',
            'title' => 'Laravel Development Services',
            'subtitle' => 'End-to-end Laravel development — from architecture and Livewire admin panels to JavaScript frontends, integrations, and long-term support.',
            'align' => 'center',
        ],
    ],
    'skills' => [
        'label' => 'Skills',
        'description' => 'Technical skills grid.',
        'default' => true,
        'copy' => [
            'nav_label' => 'Skills',
            'eyebrow' => 'Technical Expertise',
            'title' => 'Skills & Technologies',
            'subtitle' => 'A Laravel-first stack — PHP, Livewire, Filament, JavaScript, and MySQL — built for fast, secure, and maintainable web applications.',
            'align' => 'center',
        ],
    ],
    'work' => [
        'label' => 'Work',
        'description' => 'Portfolio / projects showcase.',
        'default' => true,
        'copy' => [
            'nav_label' => 'Work',
            'eyebrow' => 'Portfolio',
            'title' => 'Featured Projects',
            'subtitle' => 'Laravel e-commerce platforms, corporate websites, and ongoing support engagements for brands such as Midis Group — built to ship and scale.',
            'align' => 'center',
        ],
    ],
    'experience' => [
        'label' => 'Experience',
        'description' => 'Work history timeline.',
        'default' => true,
        'copy' => [
            'nav_label' => 'Experience',
            'eyebrow' => 'Professional Experience',
            'title' => 'Career Highlights',
            'subtitle' => 'From GIS internships to full-time Laravel development — delivering production-ready PHP applications for regional and international clients.',
            'align' => 'center',
        ],
    ],
    'certifications' => [
        'label' => 'Certifications',
        'description' => 'Credentials and course certificates.',
        'default' => true,
        'copy' => [
            'nav_label' => 'Certifications',
            'eyebrow' => 'Certifications',
            'title' => 'Professional Credentials',
            'subtitle' => 'Verified training in front-end development, JavaScript, responsive design, and GIS — with certificates available to view on this site.',
            'align' => 'center',
        ],
    ],
    'testimonials' => [
        'label' => 'Testimonials',
        'description' => 'Client quotes and reviews.',
        'default' => true,
        'copy' => [
            'nav_label' => 'Testimonials',
            'eyebrow' => 'Client Feedback',
            'title' => 'Trusted by Clients',
            'subtitle' => 'Delivering polished, reliable work that helps teams launch faster and look their best.',
            'align' => 'center',
        ],
    ],
    'contact' => [
        'label' => 'Contact',
        'description' => 'Contact form and details.',
        'default' => true,
        'copy' => [
            'nav_label' => 'Contact',
            'eyebrow' => 'Get in Touch',
            'title' => 'Start Your Next Project',
            'subtitle' => 'Need a Laravel developer for a new build, custom API, or ongoing support? Share your goals — I typically respond within 24 hours.',
            'align' => 'center',
        ],
    ],
    'newsletter' => [
        'label' => 'Newsletter',
        'description' => 'Footer email signup.',
        'default' => true,
        'copy' => [
            'nav_label' => 'Newsletter',
            'eyebrow' => 'Newsletter',
            'title' => 'Insights & Updates',
            'subtitle' => 'Occasional notes from {name} — new Laravel projects, JavaScript tips, and availability updates. No spam, ever.',
            'align' => 'center',
        ],
    ],
];
