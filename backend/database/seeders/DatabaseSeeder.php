<?php

namespace Database\Seeders;

use App\Models\ContactMessage;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Service;
use App\Models\Skill;
use App\Models\SocialLink;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ---- Admin user --------------------------------------------------
        User::updateOrCreate(
            ['email' => 'admin@huseinjaber.com'],
            [
                'name' => 'Hussein Jaber',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // ---- Profile (singleton) ----------------------------------------
        Profile::updateOrCreate(['id' => 1], [
            'name' => 'Hussein Jaber',
            'title' => 'Full Stack Developer',
            'headline' => 'I build fast, secure & beautiful web applications that grow your business.',
            'bio' => 'Full Stack Developer specialising in Laravel and modern JavaScript frameworks. I help startups and businesses turn ideas into polished, production-ready products.',
            'about' => "I'm a passionate Full Stack Developer with a strong focus on crafting high-performance, secure and maintainable web applications. From elegant front-end experiences to robust back-end architecture, I take ownership of the full product lifecycle.\n\nI love working closely with clients to understand their goals and deliver solutions that not only look great but also drive real results. Let's build something remarkable together.",
            'email' => 'hello@huseinjaber.com',
            'phone' => '+961 70 000 000',
            'location' => 'Beirut, Lebanon',
            'resume_url' => '/files/hussein-jaber-cv.pdf',
            'years_experience' => 5,
            'projects_completed' => 48,
            'happy_clients' => 32,
            'available_for_work' => true,
            'meta_title' => 'Hussein Jaber — Full Stack Developer',
            'meta_description' => 'Full Stack Developer building fast, secure and beautiful web applications with Laravel, React and Next.js.',
        ]);

        // ---- Social links ------------------------------------------------
        $socials = [
            ['platform' => 'github', 'label' => 'GitHub', 'url' => 'https://github.com/HuseinJaber', 'icon' => 'github', 'sort_order' => 1],
            ['platform' => 'linkedin', 'label' => 'LinkedIn', 'url' => 'https://linkedin.com/in/huseinjaber', 'icon' => 'linkedin', 'sort_order' => 2],
            ['platform' => 'twitter', 'label' => 'X / Twitter', 'url' => 'https://twitter.com/huseinjaber', 'icon' => 'twitter', 'sort_order' => 3],
            ['platform' => 'whatsapp', 'label' => 'WhatsApp', 'url' => 'https://wa.me/96170000000', 'icon' => 'whatsapp', 'sort_order' => 4],
        ];
        foreach ($socials as $s) {
            SocialLink::updateOrCreate(['platform' => $s['platform']], $s);
        }

        // ---- Skills ------------------------------------------------------
        $skills = [
            ['name' => 'Laravel', 'category' => 'Backend', 'level' => 95, 'icon' => 'laravel'],
            ['name' => 'PHP', 'category' => 'Backend', 'level' => 92, 'icon' => 'php'],
            ['name' => 'MySQL', 'category' => 'Backend', 'level' => 88, 'icon' => 'mysql'],
            ['name' => 'REST APIs', 'category' => 'Backend', 'level' => 90, 'icon' => 'api'],
            ['name' => 'React', 'category' => 'Frontend', 'level' => 90, 'icon' => 'react'],
            ['name' => 'Next.js', 'category' => 'Frontend', 'level' => 88, 'icon' => 'nextjs'],
            ['name' => 'TypeScript', 'category' => 'Frontend', 'level' => 85, 'icon' => 'typescript'],
            ['name' => 'Tailwind CSS', 'category' => 'Frontend', 'level' => 93, 'icon' => 'tailwind'],
            ['name' => 'Livewire', 'category' => 'Frontend', 'level' => 87, 'icon' => 'livewire'],
            ['name' => 'Alpine.js', 'category' => 'Frontend', 'level' => 84, 'icon' => 'alpine'],
            ['name' => 'Docker', 'category' => 'DevOps', 'level' => 80, 'icon' => 'docker'],
            ['name' => 'Git', 'category' => 'DevOps', 'level' => 90, 'icon' => 'git'],
        ];
        foreach ($skills as $i => $s) {
            Skill::updateOrCreate(
                ['name' => $s['name']],
                array_merge($s, ['sort_order' => $i + 1, 'is_active' => true])
            );
        }

        // ---- Services ----------------------------------------------------
        $services = [
            ['title' => 'Web Application Development', 'icon' => 'code', 'description' => 'Custom, scalable web applications built with Laravel and modern JavaScript frameworks — tailored exactly to your business needs.'],
            ['title' => 'API Development & Integration', 'icon' => 'plug', 'description' => 'Secure, well-documented REST & JSON APIs and third-party integrations (payments, CRMs, social, AI).'],
            ['title' => 'Frontend & UI/UX', 'icon' => 'sparkles', 'description' => 'Pixel-perfect, responsive and animated interfaces using React, Next.js, Tailwind CSS and GSAP.'],
            ['title' => 'E-commerce Solutions', 'icon' => 'cart', 'description' => 'Complete online stores with payments, inventory and dashboards that are fast and easy to manage.'],
            ['title' => 'Performance & Security', 'icon' => 'shield', 'description' => 'Auditing, hardening and optimising existing applications for speed, SEO and security best practices.'],
            ['title' => 'Maintenance & Support', 'icon' => 'wrench', 'description' => 'Ongoing support, feature development and reliable maintenance so your product keeps growing.'],
        ];
        foreach ($services as $i => $s) {
            Service::updateOrCreate(
                ['slug' => Str::slug($s['title'])],
                array_merge($s, ['sort_order' => $i + 1, 'is_active' => true])
            );
        }

        // ---- Projects ----------------------------------------------------
        $projects = [
            [
                'title' => 'NovaShop — E-commerce Platform',
                'category' => 'E-commerce',
                'short_description' => 'A full-featured online store with Stripe payments and an admin dashboard.',
                'description' => 'NovaShop is a complete e-commerce platform featuring product management, cart, checkout with Stripe, order tracking and a powerful admin dashboard. Built for speed and scale.',
                'tech_stack' => ['Laravel', 'Next.js', 'MySQL', 'Stripe', 'Tailwind CSS'],
                'live_url' => 'https://example.com',
                'source_url' => 'https://github.com/HuseinJaber',
                'client' => 'NovaShop Inc.',
                'year' => 2025,
                'is_featured' => true,
            ],
            [
                'title' => 'TaskFlow — SaaS Project Manager',
                'category' => 'SaaS',
                'short_description' => 'Real-time team collaboration and project management SaaS.',
                'description' => 'TaskFlow helps teams plan, track and ship work with boards, real-time updates, role-based access and analytics. Built with a Laravel API and a React front-end.',
                'tech_stack' => ['Laravel', 'React', 'WebSockets', 'MySQL', 'Redis'],
                'live_url' => 'https://example.com',
                'source_url' => 'https://github.com/HuseinJaber',
                'client' => 'TaskFlow',
                'year' => 2024,
                'is_featured' => true,
            ],
            [
                'title' => 'Medina — Restaurant Booking',
                'category' => 'Web',
                'short_description' => 'Reservation and menu management system for restaurants.',
                'description' => 'A booking platform allowing customers to reserve tables, browse menus and leave reviews, with a management dashboard for staff.',
                'tech_stack' => ['Laravel', 'Livewire', 'Alpine.js', 'Tailwind CSS'],
                'live_url' => 'https://example.com',
                'client' => 'Medina Restaurant',
                'year' => 2024,
                'is_featured' => true,
            ],
            [
                'title' => 'Pulse — Analytics Dashboard',
                'category' => 'Dashboard',
                'short_description' => 'A real-time analytics dashboard with beautiful charts.',
                'description' => 'Pulse aggregates data from multiple sources into a single, fast and elegant dashboard with customisable widgets and exports.',
                'tech_stack' => ['Next.js', 'TypeScript', 'Laravel', 'Chart.js'],
                'live_url' => 'https://example.com',
                'client' => 'Pulse Analytics',
                'year' => 2023,
                'is_featured' => false,
            ],
        ];
        foreach ($projects as $i => $p) {
            Project::updateOrCreate(
                ['slug' => Str::slug($p['title'])],
                array_merge($p, ['sort_order' => $i + 1, 'is_published' => true])
            );
        }

        // ---- Experience --------------------------------------------------
        $experiences = [
            ['role' => 'Senior Full Stack Developer', 'company' => 'Freelance', 'location' => 'Remote', 'start_date' => '2022', 'end_date' => 'Present', 'is_current' => true, 'description' => 'Designing and delivering end-to-end web applications for international clients using Laravel, React and Next.js.'],
            ['role' => 'Full Stack Developer', 'company' => 'TechWave Solutions', 'location' => 'Beirut, Lebanon', 'start_date' => '2020', 'end_date' => '2022', 'is_current' => false, 'description' => 'Built and maintained SaaS products and e-commerce platforms, led front-end architecture and mentored junior developers.'],
            ['role' => 'Web Developer', 'company' => 'Creative Agency', 'location' => 'Beirut, Lebanon', 'start_date' => '2019', 'end_date' => '2020', 'is_current' => false, 'description' => 'Developed responsive marketing websites and custom WordPress and Laravel solutions for diverse clients.'],
        ];
        foreach ($experiences as $i => $e) {
            Experience::updateOrCreate(
                ['role' => $e['role'], 'company' => $e['company']],
                array_merge($e, ['sort_order' => $i + 1])
            );
        }

        // ---- Education ---------------------------------------------------
        Education::updateOrCreate(
            ['degree' => 'BSc in Computer Science', 'institution' => 'Lebanese University'],
            ['location' => 'Beirut, Lebanon', 'start_date' => '2015', 'end_date' => '2019', 'description' => 'Focused on software engineering, algorithms and web technologies.', 'sort_order' => 1]
        );

        // ---- Testimonials ------------------------------------------------
        $testimonials = [
            ['name' => 'Sarah Mitchell', 'role' => 'CEO', 'company' => 'NovaShop Inc.', 'content' => 'Hussein delivered our e-commerce platform ahead of schedule and exceeded every expectation. Highly professional and a pleasure to work with.', 'rating' => 5],
            ['name' => 'David Chen', 'role' => 'Founder', 'company' => 'TaskFlow', 'content' => 'The quality of the code and attention to detail were outstanding. Our SaaS is fast, reliable and our users love it.', 'rating' => 5],
            ['name' => 'Layla Haddad', 'role' => 'Owner', 'company' => 'Medina Restaurant', 'content' => 'Our booking system transformed how we run the restaurant. Communication was clear and the result is beautiful.', 'rating' => 5],
        ];
        foreach ($testimonials as $i => $t) {
            Testimonial::updateOrCreate(
                ['name' => $t['name'], 'company' => $t['company']],
                array_merge($t, ['sort_order' => $i + 1, 'is_published' => true])
            );
        }

        // ---- Sample contact message --------------------------------------
        ContactMessage::firstOrCreate(
            ['email' => 'prospect@example.com'],
            ['name' => 'Potential Client', 'subject' => 'Need a website', 'message' => 'Hi Hussein, I would love to discuss building a website for my business.', 'is_read' => false]
        );
    }
}
