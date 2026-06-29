<?php

namespace App\Services;

use App\Support\ProjectContribution;
use Illuminate\Support\Str;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

class DocumentProjectScanner
{
    private const DOCUMENTS_ROOT = '/Library/WebServer/Documents';

    /** @var list<string> */
    private const SKIP_FOLDERS = [
        'phpmyadmin',
        'ecom_template_v3',
        'ecom-template-v2-web',
        'ecom_solution',
    ];

    /** @var list<string> */
    private const FEATURED_SLUGS = [
        'hussein-jaber-portfolio',
        'ecom-shop',
        'patchi-v2',
        'toys-r-us-web',
        'smsa-corporate',
        'rolex',
        'abc-web',
        'monaqasa-web',
        'pool-api',
        'horse-head-tea-ecom-web',
    ];

    /**
     * @return list<array<string, mixed>>
     */
    public function scan(?string $root = null): array
    {
        $root = $root ?? self::DOCUMENTS_ROOT;

        if (! is_dir($root)) {
            return [];
        }

        $projects = [];

        foreach (scandir($root) ?: [] as $entry) {
            if ($entry === '.' || $entry === '..' || in_array($entry, self::SKIP_FOLDERS, true)) {
                continue;
            }

            $path = $root.DIRECTORY_SEPARATOR.$entry;

            if (! is_dir($path)) {
                continue;
            }

            $meta = $this->analyseProject($entry, $path);

            if ($meta === null) {
                continue;
            }

            $slug = Str::slug($entry);
            $engagementType = $this->detectEngagementType($entry, $meta['category'], $meta['is_wordpress']);
            $isPublished = $this->shouldPublish($entry, $meta['category'], $engagementType);

            $projects[] = [
                'slug' => $slug,
                'title' => $meta['title'],
                'category' => $meta['category'],
                'engagement_type' => $engagementType,
                'contribution_areas' => $this->inferContributionAreas(
                    $entry,
                    $meta['tech_stack'],
                    (bool) ($meta['is_wordpress'] ?? false),
                    $engagementType,
                ),
                'short_description' => $meta['short_description'],
                'description' => $meta['description'],
                'tech_stack' => $meta['tech_stack'],
                'live_url' => null,
                'source_url' => null,
                'client' => $meta['client'],
                'year' => $meta['year'],
                'sites_count' => null,
                'is_featured' => in_array($slug, self::FEATURED_SLUGS, true),
                'is_published' => $isPublished,
                'local_path' => $path,
                'folder' => $entry,
            ];
        }

        usort($projects, fn (array $a, array $b) => strcmp($a['title'], $b['title']));

        foreach ($projects as $i => &$project) {
            $project['sort_order'] = $i + 1;
        }

        return $projects;
    }

    /**
     * @return array{title: string, category: string, short_description: string, description: string, tech_stack: list<string>, client: string, year: int}|null
     */
    private function analyseProject(string $folder, string $path): ?array
    {
        $composerPath = $this->findComposerJson($path);
        $packagePath = $this->findPackageJson($path);
        $isWordPress = $this->isWordPress($path);
        $isLaravel = $composerPath && $this->composerRequires($composerPath, 'laravel/framework');
        $isNext = $packagePath && $this->packageDepends($packagePath, 'next');

        if (! $isWordPress && ! $isLaravel && ! $isNext && ! $this->isLegacyPhpProject($path, $composerPath)) {
            return null;
        }

        $title = $this->titleFromFolder($folder);
        $techStack = $this->buildTechStack($path, $composerPath, $packagePath, $isWordPress, $isLaravel, $isNext);
        $category = $this->detectCategory($folder, $composerPath, $isWordPress, $isNext);
        $year = $this->estimateYear($folder, $path);
        $client = $this->clientFromTitle($folder, $title);

        $short = $this->shortDescription($title, $category, $techStack);
        $description = "{$short} Built with ".implode(', ', array_slice($techStack, 0, 5)).'.';

        return [
            'title' => $title,
            'category' => $category,
            'short_description' => $short,
            'description' => $description,
            'tech_stack' => $techStack,
            'client' => $client,
            'year' => $year,
            'is_wordpress' => $isWordPress,
        ];
    }

    private function detectEngagementType(string $folder, string $category, bool $isWordPress): string
    {
        $development = config('portfolio_engagement.development_folders', []);
        $support = config('portfolio_engagement.support_folders', []);
        $wpDevelopment = config('portfolio_engagement.wordpress_development_folders', []);

        if (in_array($folder, $development, true) || in_array($folder, $wpDevelopment, true)) {
            return 'development';
        }

        if (in_array($folder, $support, true)) {
            return 'support';
        }

        if ($isWordPress || $category === 'WordPress') {
            return in_array($folder, $wpDevelopment, true) ? 'development' : 'support';
        }

        if (preg_match('/(_old|legacy)/i', $folder)) {
            return 'support';
        }

        return 'development';
    }

    private function shouldPublish(string $folder, string $category, string $engagementType): bool
    {
        $hideWp = config('portfolio_engagement.midis.hide_individual_wordpress', false);
        $wpDevelopment = config('portfolio_engagement.wordpress_development_folders', []);

        if ($hideWp && $category === 'WordPress' && $engagementType === 'support') {
            return in_array($folder, $wpDevelopment, true);
        }

        return true;
    }

    /**
     * @param  list<string>  $techStack
     * @return list<string>
     */
    private function inferContributionAreas(
        string $folder,
        array $techStack,
        bool $isWordPress,
        string $engagementType,
    ): array {
        $overrides = config('portfolio_engagement.contribution_folders', []);

        if (isset($overrides[$folder]) && is_array($overrides[$folder])) {
            return ProjectContribution::sanitize($overrides[$folder]);
        }

        $areas = [];
        $stackText = strtolower(implode(' ', $techStack));
        $folderLower = strtolower($folder);

        if ($isWordPress || str_contains($stackText, 'wordpress')) {
            $areas[] = 'cms';
        }

        $frontendSignals = ['react', 'next.js', 'vue', 'inertia.js', 'tailwind css', 'javascript', 'html', 'css'];
        $backendSignals = ['laravel', 'livewire', 'filament', 'sanctum', 'php', 'mysql'];

        foreach ($frontendSignals as $signal) {
            if (str_contains($stackText, $signal)) {
                $areas[] = 'frontend';
                break;
            }
        }

        foreach ($backendSignals as $signal) {
            if (str_contains($stackText, $signal)) {
                $areas[] = 'backend';
                break;
            }
        }

        if (str_contains($folderLower, 'api') || str_contains($stackText, 'api')) {
            $areas[] = 'api';
        }

        if ($engagementType === 'support' && $isWordPress) {
            $areas = array_values(array_unique(array_merge($areas, ['cms', 'frontend'])));
        }

        if ($areas === []) {
            return ['frontend', 'backend'];
        }

        return ProjectContribution::sanitize($areas);
    }

    private function findComposerJson(string $path): ?string
    {
        foreach ([$path.'/composer.json', $path.'/backend/composer.json'] as $candidate) {
            if (is_file($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    private function findPackageJson(string $path): ?string
    {
        foreach ([$path.'/package.json', $path.'/frontend/package.json'] as $candidate) {
            if (is_file($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    private function isWordPress(string $path): bool
    {
        return is_file($path.'/wp-config.php')
            || is_file($path.'/wp-config-sample.php')
            || is_dir($path.'/wp-admin');
    }

    private function isLegacyPhpProject(string $path, ?string $composerPath): bool
    {
        if (! is_file($path.'/index.php') && count(glob($path.'/*.php') ?: []) < 2) {
            return false;
        }

        return $composerPath !== null
            || is_dir($path.'/api')
            || count(glob($path.'/*.php') ?: []) >= 2;
    }

    private function composerRequires(string $composerPath, string $package): bool
    {
        $data = json_decode((string) file_get_contents($composerPath), true);

        if (! is_array($data)) {
            return false;
        }

        $requires = array_merge(
            $data['require'] ?? [],
            $data['require-dev'] ?? [],
        );

        return isset($requires[$package]);
    }

    private function composerHasPackagePrefix(string $composerPath, string $prefix): bool
    {
        $data = json_decode((string) file_get_contents($composerPath), true);

        if (! is_array($data)) {
            return false;
        }

        $requires = array_merge($data['require'] ?? [], $data['require-dev'] ?? []);

        foreach (array_keys($requires) as $pkg) {
            if (str_starts_with($pkg, $prefix)) {
                return true;
            }
        }

        return false;
    }

    private function packageDepends(string $packagePath, string $name): bool
    {
        $data = json_decode((string) file_get_contents($packagePath), true);

        if (! is_array($data)) {
            return false;
        }

        $deps = array_merge($data['dependencies'] ?? [], $data['devDependencies'] ?? []);

        return isset($deps[$name]);
    }

    /**
     * @return list<string>
     */
    private function buildTechStack(
        string $path,
        ?string $composerPath,
        ?string $packagePath,
        bool $isWordPress,
        bool $isLaravel,
        bool $isNext,
    ): array {
        $stack = [];

        if ($isWordPress) {
            $stack[] = 'WordPress';
            if ($this->pathContains($path, 'easyappointments')) {
                $stack[] = 'Easy!Appointments';
            }
        }

        if ($isLaravel && $composerPath) {
            $stack[] = 'Laravel';

            if ($this->composerRequires($composerPath, 'livewire/livewire')) {
                $stack[] = 'Livewire';
            }
            if ($this->composerRequires($composerPath, 'filament/filament')) {
                $stack[] = 'Filament';
            }
            if ($this->composerRequires($composerPath, 'inertiajs/inertia-laravel')) {
                $stack[] = 'Inertia.js';
            }
            if ($this->composerRequires($composerPath, 'laravel/sanctum')) {
                $stack[] = 'Sanctum';
            }
            if ($this->composerHasPackagePrefix($composerPath, 'twa/ecom')) {
                $stack[] = 'TWA E-commerce';
            }
            if ($this->composerHasPackagePrefix($composerPath, 'twa/cms')) {
                $stack[] = 'TWA CMS';
            }
        }

        if ($packagePath) {
            if ($this->packageDepends($packagePath, 'next')) {
                $stack[] = 'Next.js';
            }
            if ($this->packageDepends($packagePath, 'react')) {
                $stack[] = 'React';
            }
            if ($this->packageDepends($packagePath, 'vue')) {
                $stack[] = 'Vue';
            }
        }

        if (! $isLaravel && ! $isWordPress && ! $isNext && is_file($path.'/index.php')) {
            $stack[] = 'PHP';
        }

        if ($isLaravel || $isNext || ($packagePath && $this->packageDepends($packagePath, 'vite'))) {
            $stack[] = 'Tailwind CSS';
        }

        if ($isLaravel || $isWordPress) {
            $stack[] = 'MySQL';
        }

        return array_values(array_unique($stack));
    }

    private function detectCategory(string $folder, ?string $composerPath, bool $isWordPress, bool $isNext): string
    {
        $lower = strtolower($folder);

        if ($isWordPress) {
            return 'WordPress';
        }

        if (in_array($lower, ['hussein_jaber_portfolio', 'anthonyabouantoun_web', 'charlessaliba_web', 'salim_assaf'], true)) {
            return 'Portfolio';
        }

        if ($lower === 'ecom_shop') {
            return 'E-Commerce';
        }

        if (in_array($lower, ['mbcom', 'pool_api'], true)) {
            return 'API';
        }

        if ($lower === 'ticketingv2_web') {
            return 'Web App';
        }

        if ($lower === 'monaqasa_web') {
            return 'Web App';
        }

        if ($composerPath && $this->composerHasPackagePrefix($composerPath, 'twa/ecom')) {
            return 'E-Commerce';
        }

        if (preg_match('/(shop|ecom|ecom_web|_shop|retail|store|collection|cosmetics|coco)/', $lower)) {
            return 'E-Commerce';
        }

        if (str_contains($lower, 'corporate') || str_contains($lower, 'api')) {
            return str_contains($lower, 'api') ? 'API' : 'Corporate';
        }

        return 'Corporate';
    }

    private function titleFromFolder(string $folder): string
    {
        $name = preg_replace('/(_web|_v\d+|_ecom_web|-v\d+)$/i', '', $folder) ?? $folder;
        $name = str_replace(['_', '-'], ' ', $name);
        $name = preg_replace('/\s+v\d+$/i', '', $name) ?? $name;

        return Str::title(trim($name));
    }

    private function clientFromTitle(string $folder, string $title): string
    {
        if (in_array($folder, ['hussein_jaber_portfolio', 'ecom_shop'], true)) {
            return 'Personal Project';
        }

        if (str_contains($folder, '_old') || str_contains($folder, 'legacy')) {
            return $title.' (Legacy)';
        }

        return $title;
    }

    private function estimateYear(string $folder, string $path): int
    {
        if (preg_match('/v(\d+)/', $folder, $m)) {
            return match ((int) $m[1]) {
                4, 5 => 2026,
                2, 3 => 2025,
                default => 2024,
            };
        }

        if (str_contains($folder, '_old')) {
            return 2024;
        }

        if ($folder === 'hussein_jaber_portfolio') {
            return 2026;
        }

        if ($folder === 'abc_om' || $folder === 'fatmonk' || $folder === 'bemamuseum') {
            return 2020;
        }

        $newest = $this->referenceFileTimestamp(
            $path,
            $this->findComposerJson($path),
            $this->findPackageJson($path),
        );

        if ($newest) {
            return (int) date('Y', $newest);
        }

        return 2025;
    }

    private function referenceFileTimestamp(string $path, ?string $composerPath, ?string $packagePath): ?int
    {
        $candidates = array_filter([
            $composerPath,
            $packagePath,
            $path.'/artisan',
            $path.'/index.php',
            $path.'/wp-config.php',
        ]);

        $newest = null;

        foreach ($candidates as $file) {
            if ($file && is_file($file)) {
                $mtime = filemtime($file) ?: null;
                $newest = $newest === null ? $mtime : max($newest, $mtime);
            }
        }

        return $newest;
    }

    /**
     * @param  list<string>  $techStack
     */
    private function shortDescription(string $title, string $category, array $techStack): string
    {
        return match ($category) {
            'E-Commerce' => "{$title} — Laravel e-commerce storefront and admin.",
            'WordPress' => "{$title} — WordPress corporate/marketing website.",
            'Portfolio' => "{$title} — Professional portfolio and content-managed site.",
            'API' => "{$title} — Custom web application with API and admin tooling.",
            'Web App' => "{$title} — Web application with dashboards and interactive UI.",
            default => "{$title} — Corporate website with CMS-driven content.",
        };
    }

    private function pathContains(string $path, string $needle): bool
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
        );

        /** @var SplFileInfo $file */
        foreach ($iterator as $file) {
            if ($file->isFile() && str_contains(strtolower($file->getPathname()), strtolower($needle))) {
                return true;
            }
        }

        return false;
    }
}
