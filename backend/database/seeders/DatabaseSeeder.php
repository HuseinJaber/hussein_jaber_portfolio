<?php

namespace Database\Seeders;

use App\Models\Certification;
use App\Models\ContactMessage;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Profile;
use App\Models\Service;
use App\Models\Skill;
use App\Models\SocialLink;
use App\Models\Testimonial;
use App\Models\User;
use App\Support\PortfolioSections;
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
                'is_admin' => true,
            ]
        );

        // ---- Profile (singleton) ----------------------------------------
        Profile::updateOrCreate(['id' => 1], [
            'name' => 'Hussein Jaber',
            'title' => 'Laravel Full Stack Developer',
            'headline' => 'I design and build high-performance Laravel web applications — from e-commerce stores to corporate platforms — with clean PHP backends and modern JavaScript frontends.',
            'bio' => 'Laravel developer at TheWebAddicts in Beirut. I specialize in Laravel, Livewire, and PHP for e-commerce, corporate websites, and custom web applications — with JavaScript where the UI needs it.',
            'about' => "I'm a Laravel-focused Full Stack Developer based in Beirut, Lebanon, with a BSc in Computer Science from the Lebanese International University. Since graduating in 2022, I've progressed from GIS and JavaScript internships at Khatib & Alami and Helbawi Group into full-time Laravel development at TheWebAddicts, where I build and maintain applications for regional and international brands.\n\nMy work centers on Laravel — Livewire and Filament admin panels, REST APIs, e-commerce flows, search, payments, and deployment — complemented by JavaScript and Tailwind CSS on the frontend when projects call for it.\n\nWhether you need a new Laravel e-commerce platform, a corporate website, or ongoing support for an existing application, I focus on clean architecture, performance, and clear business outcomes.",
            'email' => 'HusseinJaber5@hotmail.com',
            'phone' => '+961 70 004 893',
            'location' => 'Beirut, Lebanon',
            'resume_url' => '/files/hussein-jaber-cv.pdf',
            'cv_download_label' => 'Download CV',
            'cv_view_label' => 'View custom CV',
            'years_experience' => 3,
            'projects_completed' => 90,
            'happy_clients' => 40,
            'available_for_work' => true,
            'meta_title' => 'Hussein Jaber | Laravel Full Stack Developer',
            'meta_description' => 'Hussein Jaber is a Laravel developer in Beirut, Lebanon, building secure e-commerce platforms, corporate websites, and custom web applications with PHP, Livewire, and JavaScript.',
            'section_copy' => PortfolioSections::defaultCopy(),
        ]);

        // ---- Social links ------------------------------------------------
        $socials = [
            ['platform' => 'github', 'label' => 'GitHub', 'url' => 'https://github.com/HuseinJaber', 'icon' => 'github', 'sort_order' => 1],
            ['platform' => 'linkedin', 'label' => 'LinkedIn', 'url' => 'https://www.linkedin.com/in/husseinjaberr/', 'icon' => 'linkedin', 'sort_order' => 2],
            ['platform' => 'facebook', 'label' => 'Facebook', 'url' => 'https://www.facebook.com/hussein.jaber.7505/', 'icon' => 'facebook', 'sort_order' => 3],
            ['platform' => 'whatsapp', 'label' => 'WhatsApp', 'url' => 'https://wa.me/96170004893', 'icon' => 'whatsapp', 'sort_order' => 4],
        ];
        foreach ($socials as $s) {
            SocialLink::updateOrCreate(['platform' => $s['platform']], $s);
        }
        SocialLink::where('platform', 'twitter')->delete();

        // ---- Skills ------------------------------------------------------
        $skills = [
            ['name' => 'Laravel', 'category' => 'Backend', 'level' => 95, 'icon' => 'laravel'],
            ['name' => 'PHP', 'category' => 'Backend', 'level' => 93, 'icon' => 'php'],
            ['name' => 'Livewire', 'category' => 'Backend', 'level' => 90, 'icon' => 'livewire'],
            ['name' => 'REST APIs', 'category' => 'Backend', 'level' => 90, 'icon' => 'api'],
            ['name' => 'MySQL', 'category' => 'Backend', 'level' => 88, 'icon' => 'mysql'],
            ['name' => 'Filament', 'category' => 'Backend', 'level' => 85, 'icon' => 'laravel'],
            ['name' => 'React', 'category' => 'Frontend', 'level' => 90, 'icon' => 'react'],
            ['name' => 'TypeScript', 'category' => 'Frontend', 'level' => 82, 'icon' => 'typescript'],
            ['name' => 'Tailwind CSS', 'category' => 'Frontend', 'level' => 92, 'icon' => 'tailwind'],
            ['name' => 'JavaScript', 'category' => 'Frontend', 'level' => 90, 'icon' => 'react'],
            ['name' => 'WordPress', 'category' => 'CMS', 'level' => 78, 'icon' => 'wordpress'],
            ['name' => 'Git', 'category' => 'DevOps', 'level' => 90, 'icon' => 'git'],
            ['name' => 'Docker', 'category' => 'DevOps', 'level' => 75, 'icon' => 'docker'],
        ];
        foreach ($skills as $i => $s) {
            Skill::updateOrCreate(
                ['name' => $s['name']],
                array_merge($s, ['sort_order' => $i + 1, 'is_active' => true])
            );
        }
        Skill::whereNotIn('name', collect($skills)->pluck('name'))->delete();

        // ---- Services ----------------------------------------------------
        $services = [
            ['title' => 'E-commerce Development', 'icon' => 'cart', 'description' => 'Build conversion-focused online stores with Laravel — product catalogues, secure checkout, payment gateways, search, and admin dashboards that scale with your business.'],
            ['title' => 'Corporate & Brand Websites', 'icon' => 'sparkles', 'description' => 'Launch polished, SEO-ready corporate sites with content management, multilingual support, and performant Livewire admin panels.'],
            ['title' => 'Web Application Development', 'icon' => 'code', 'description' => 'Custom Laravel applications designed around your workflow — internal tools, customer portals, and SaaS-style platforms built for growth.'],
            ['title' => 'API Development & Integration', 'icon' => 'plug', 'description' => 'Robust REST APIs that connect your product to payment processors, CRMs, search engines, and third-party services — securely and reliably.'],
            ['title' => 'Frontend & UI Engineering', 'icon' => 'sparkles', 'description' => 'Responsive, accessible JavaScript interfaces with Tailwind CSS and Livewire — fast load times, smooth interactions, and mobile-first design.'],
            ['title' => 'Maintenance & Support', 'icon' => 'wrench', 'description' => 'Dependable post-launch care — bug fixes, feature updates, and performance improvements so your application stays secure and competitive.'],
        ];
        foreach ($services as $i => $s) {
            Service::updateOrCreate(
                ['slug' => Str::slug($s['title'])],
                array_merge($s, ['sort_order' => $i + 1, 'is_active' => true])
            );
        }

        // ---- Experience (before projects so portfolio work can link to employer) -
        Experience::whereIn('company', ['TechWave Solutions', 'Creative Agency', 'Freelance'])->delete();

        $experiences = [
            [
                'role' => 'Full Stack Developer',
                'company' => 'TheWebAddicts',
                'location' => 'Beirut, Lebanon',
                'start_date' => 'Jun 2023',
                'end_date' => 'Present',
                'is_current' => true,
                'description' => 'Lead Laravel development for e-commerce platforms, corporate websites, and web applications serving regional brands. Own Livewire and Filament admin panels, JavaScript frontends, REST APIs, search, payments, and production deployments.',
            ],
            [
                'role' => 'Web Developer — Internship',
                'company' => 'Helbawi Group',
                'location' => 'Beirut, Lebanon',
                'start_date' => 'Oct 2022',
                'end_date' => 'Dec 2022',
                'is_current' => false,
                'description' => 'Developed production web applications using Node.js and JavaScript with HTML5 and CSS under senior developer mentorship.',
            ],
            [
                'role' => 'ArcGIS Web Developer — Internship',
                'company' => 'Khatib & Alami',
                'location' => 'Beirut, Lebanon',
                'start_date' => 'Aug 2022',
                'end_date' => 'Oct 2022',
                'is_current' => false,
                'description' => 'Built interactive mapping and GIS web solutions using JavaScript, ArcGIS products, web services APIs, and SQL for engineering and infrastructure clients.',
            ],
        ];
        foreach ($experiences as $i => $e) {
            Experience::updateOrCreate(
                ['role' => $e['role'], 'company' => $e['company']],
                array_merge($e, ['sort_order' => count($experiences) - $i])
            );
        }

        // ---- Projects (all folders in /Library/WebServer/Documents) -----
        $this->call(DocumentProjectsSeeder::class);

        // ---- Education ---------------------------------------------------
        Education::where('institution', 'Lebanese University')->delete();

        Education::updateOrCreate(
            ['degree' => 'BSc in Computer Science', 'institution' => 'Lebanese International University'],
            [
                'location' => 'Beirut, Lebanon',
                'start_date' => 'Sep 2019',
                'end_date' => 'Jun 2022',
                'description' => 'Bachelor of Science in Computer Science (CSCI). Coursework in software engineering, web technologies, algorithms, and applied problem solving.',
                'sort_order' => 1,
            ]
        );

        // ---- Certifications (from LinkedIn) ------------------------------
        $certifications = [
            ['title' => 'Front End Development Libraries', 'issuer' => 'freeCodeCamp', 'issued_at' => 'May 2023', 'credential_url' => 'https://www.freecodecamp.org/certification/HusseinJaber/front-end-development-libraries'],
            ['title' => 'JavaScript Algorithms and Data Structures', 'issuer' => 'freeCodeCamp', 'issued_at' => 'Apr 2023', 'credential_url' => 'https://www.freecodecamp.org/certification/HusseinJaber/javascript-algorithms-and-data-structures'],
            ['title' => 'Responsive Web Design', 'issuer' => 'freeCodeCamp', 'issued_at' => 'Apr 2023', 'credential_url' => 'https://www.freecodecamp.org/certification/HusseinJaber/responsive-web-design'],
            ['title' => 'Freelance Apprenticeship in Web Development', 'issuer' => 'Mercy Corps', 'issued_at' => 'Jan 2023', 'credential_url' => null],
            ['title' => 'Basics of JavaScript Web Apps', 'issuer' => 'Esri', 'issued_at' => 'Aug 2022', 'credential_url' => 'https://www.esri.com/training/TrainingRecord/Certificate/HusseinJaber/6305fbc3511285490d511f96/-180'],
            ['title' => 'GIS Basics', 'issuer' => 'Esri', 'issued_at' => 'Aug 2022', 'credential_url' => 'https://www.esri.com/training/TrainingRecord/Certificate/HusseinJaber/62e8b3069953847430627f6b/-180'],
            ['title' => 'HTML, CSS, and Javascript for Web Developers', 'issuer' => 'Coursera', 'issued_at' => 'Jun 2022', 'credential_url' => 'https://www.coursera.org/account/accomplishments/certificate/HDCPXZZQED7E'],
        ];
        foreach ($certifications as $i => $cert) {
            Certification::updateOrCreate(
                ['title' => $cert['title'], 'issuer' => $cert['issuer']],
                array_merge($cert, ['sort_order' => $i + 1, 'is_published' => true])
            );
        }

        // ---- Testimonials: remove placeholders (add real ones via admin) -
        Testimonial::whereIn('name', ['Sarah Mitchell', 'David Chen', 'Layla Haddad'])->delete();

        // ---- Remove sample contact message -------------------------------
        ContactMessage::where('email', 'prospect@example.com')->delete();
    }
}
