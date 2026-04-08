<?php

return [

    'title' => 'ISMO Archive',
    'title_prefix' => '',
    'title_postfix' => ' | ISMO',

    'use_ico_only' => false,
    'use_full_favicon' => false,

    'google_fonts' => [
        'allowed' => true,
    ],

    'logo' => '<b>ISMO</b> Archive',
    'logo_img' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'ISMO Logo',

    'auth_logo' => [
        'enabled' => false,
        'img' => [
            'path' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
            'alt' => 'ISMO Logo',
            'class' => '',
            'width' => 50,
            'height' => 50,
        ],
    ],

    'preloader' => [
        'enabled' => true,
        'mode' => 'fullscreen',
        'img' => [
            'path' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
            'alt' => 'ISMO Preloader',
            'effect' => 'animation__shake',
            'width' => 60,
            'height' => 60,
        ],
    ],

    'usermenu_enabled' => true,
    'usermenu_header' => true,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => false,
    'usermenu_desc' => false,
    'usermenu_profile_url' => false,

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => true,
    'layout_fixed_navbar' => true,
    'layout_fixed_footer' => null,
    'layout_dark_mode' => null,

    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary',

    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    'sidebar_mini' => 'lg',
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    'use_route_url' => true,
    'dashboard_url' => 'dashboard',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => false,
    'password_reset_url' => 'password/reset',
    'password_email_url' => 'password/email',
    'profile_url' => false,
    'disable_darkmode_routes' => false,

    'laravel_asset_bundling' => false,
    'laravel_css_path' => 'css/app.css',
    'laravel_js_path' => 'js/app.js',

    'menu' => [

        ['type' => 'navbar-search', 'text' => 'Rechercher', 'topnav_right' => true],
        ['type' => 'fullscreen-widget', 'topnav_right' => true],
        ['type' => 'sidebar-menu-search', 'text' => 'Rechercher'],

        ['text' => 'Tableau de bord', 'url' => 'dashboard', 'icon' => 'fas fa-fw fa-tachometer-alt'],

        ['header' => 'GESTION DES STAGIAIRES'],
        ['text' => 'Stagiaires', 'url' => 'trainees', 'icon' => 'fas fa-fw fa-users'],
        ['text' => 'Importer Excel', 'url' => 'trainees/import', 'icon' => 'fas fa-fw fa-file-excel'],

        ['header' => 'GESTION DES DOCUMENTS'],
        [
            'text' => 'Baccalauréat',
            'icon' => 'fas fa-fw fa-graduation-cap',
            'submenu' => [
                ['text' => 'Liste', 'url' => 'documents/bac', 'icon' => 'fas fa-fw fa-list'],
                ['text' => 'Retraits temporaires', 'url' => 'documents/bac/temp-out', 'icon' => 'fas fa-fw fa-clock', 'label' => '!', 'label_color' => 'warning'],
                ['text' => 'Écoulé', 'url' => 'documents/bac/ecoule', 'icon' => 'fas fa-fw fa-exclamation-triangle', 'label' => '!', 'label_color' => 'danger'],
                ['text' => 'Retraits définitifs', 'url' => 'documents/bac/final-out', 'icon' => 'fas fa-fw fa-sign-out-alt', 'label' => '!', 'label_color' => 'danger'],
            ],
        ],
        [
            'text' => 'Diplômes',
            'icon' => 'fas fa-fw fa-certificate',
            'submenu' => [
                ['text' => 'Liste', 'url' => 'documents/diplome', 'icon' => 'fas fa-fw fa-list'],
                ['text' => 'Prêts à remettre', 'url' => 'documents/diplome/prets', 'icon' => 'fas fa-fw fa-check-circle'],
            ],
        ],
        ['text' => 'Bulletins de notes', 'url' => 'documents/bulletin', 'icon' => 'fas fa-fw fa-file-alt'],
        ['text' => 'Attestations', 'url' => 'documents/attestation', 'icon' => 'fas fa-fw fa-file-contract'],

        ['header' => 'MOUVEMENTS'],
        ['text' => 'Historique', 'url' => 'movements', 'icon' => 'fas fa-fw fa-exchange-alt'],
        ['text' => "Aujourd'hui", 'url' => 'movements/today', 'icon' => 'fas fa-fw fa-calendar-day'],

        ['header' => 'VALIDATIONS'],
        ['text' => 'Registre', 'url' => 'validations', 'icon' => 'fas fa-fw fa-check-double'],

        ['header' => 'ADMINISTRATION'],
        ['text' => 'Utilisateurs', 'url' => 'users', 'icon' => 'fas fa-fw fa-user-cog', 'can' => 'manage-users'],
        [
            'text' => 'Paramètres',
            'icon' => 'fas fa-fw fa-cogs',
            'can' => 'manage-users',
            'submenu' => [
                ['text' => 'Secteurs', 'url' => 'secteurs', 'icon' => 'fas fa-fw fa-building'],
                ['text' => 'Filières', 'url' => 'filieres', 'icon' => 'fas fa-fw fa-code-branch'],
            ],
        ],
    ],

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    'plugins' => [
        'Datatables' => [
            'active' => true,
            'files' => [
                ['type' => 'js', 'asset' => false, 'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js'],
                ['type' => 'js', 'asset' => false, 'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js'],
                ['type' => 'css', 'asset' => false, 'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css'],
            ],
        ],
        'Select2' => [
            'active' => true,
            'files' => [
                ['type' => 'js', 'asset' => false, 'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js'],
                ['type' => 'css', 'asset' => false, 'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css'],
            ],
        ],
        'Sweetalert2' => [
            'active' => true,
            'files' => [
                ['type' => 'js', 'asset' => false, 'location' => '//cdn.jsdelivr.net/npm/sweetalert2@11'],
            ],
        ],
        'Chartjs' => [
            'active' => true,
            'files' => [
                ['type' => 'js', 'asset' => false, 'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js'],
            ],
        ],
        'Pace' => [
            'active' => false,
            'files' => [
                ['type' => 'css', 'asset' => false, 'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css'],
                ['type' => 'js', 'asset' => false, 'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js'],
            ],
        ],
    ],

    'iframe' => [
        'default_tab' => ['url' => null, 'title' => null],
        'buttons' => [
            'close' => true,
            'close_all' => true,
            'close_all_other' => true,
            'scroll_left' => true,
            'scroll_right' => true,
            'fullscreen' => true,
        ],
        'options' => [
            'loading_screen' => 1000,
            'auto_show_new_tab' => true,
            'use_navbar_items' => true,
        ],
    ],

    'livewire' => false,
];