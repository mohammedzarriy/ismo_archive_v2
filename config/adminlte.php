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

    'layout_fixed_sidebar' => true,
    'layout_fixed_navbar' => true,

    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_container' => 'container',

    'sidebar_mini' => 'lg',

    'use_route_url' => false,
    'dashboard_url' => 'dashboard',
    'logout_url' => 'logout',
    'login_url' => 'login',

    'menu' => [

        ['type' => 'navbar-search', 'text' => 'Rechercher', 'topnav_right' => true],
        ['type' => 'fullscreen-widget', 'topnav_right' => true],

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
                ['text' => 'Retraits temporaires', 'url' => 'documents/bac/temp-out', 'icon' => 'fas fa-fw fa-clock'],
                ['text' => 'Retraits définitifs', 'url' => 'documents/bac/final-out', 'icon' => 'fas fa-fw fa-sign-out-alt'],
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
        [
            'text' => 'Calendrier',
            'url' => 'calendrier',
            'icon' => 'fas fa-fw fa-calendar-alt',
            'label' => '!',
            'label_color' => 'danger'
        ],

        ['header' => 'VALIDATIONS'],
        ['text' => 'Registre', 'url' => 'validations', 'icon' => 'fas fa-fw fa-check-double'],

        ['header' => 'ADMINISTRATION'],
        ['text' => 'Utilisateurs', 'url' => 'users', 'icon' => 'fas fa-fw fa-user-cog'],
        [
            'text' => 'Paramètres',
            'icon' => 'fas fa-fw fa-cogs',
            'submenu' => [
                ['text' => 'Secteurs', 'url' => 'secteurs', 'icon' => 'fas fa-fw fa-building'],
                ['text' => 'Filières', 'url' => 'filieres', 'icon' => 'fas fa-fw fa-code-branch'],
            ],
        ],
    ],

];