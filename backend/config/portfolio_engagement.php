<?php

/**
 * Maps local project folders to development vs support, and Midis WordPress portfolio settings.
 *
 * engagement_type: development = built from scratch · support = maintenance / after launch
 *
 * Re-run `php artisan db:seed --class=DocumentProjectsSeeder` after editing folder lists.
 */
return [

    'midis' => [
        'sites_count' => 79,
        'client' => 'Midis Group',
        // Hide individual WordPress entries on the public site; show the aggregate project instead.
        'hide_individual_wordpress' => true,
    ],

    /** Laravel / app folders you built from scratch (explicit overrides). */
    'development_folders' => [
        'hussein_jaber_portfolio',
        'ecom_shop',
        'monaqasa_web',
        'pool_api',
        'ticketingv2_web',
        'mbcom',
        'smsa-corporate',
    ],

    /** Laravel folders where you only provide support (not original developer). */
    'support_folders' => [
        'dalya_asmar_old',
        'abc_om',
        'fatmonk',
        'bemamuseum',
        'pde',
    ],

    /** WordPress sites you built from scratch (shown individually on the portfolio). */
    'wordpress_development_folders' => [
        // Add folder names here, e.g. 'ac-care', 'ac-care-v2',
    ],

    /**
     * Per-folder contribution overrides (folder name under Documents).
     * Keys: frontend, backend, design, api, cms, devops
     */
    'contribution_folders' => [
        'abc_web' => ['frontend'],
        'abc-web' => ['frontend'],
    ],

];
