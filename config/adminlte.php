```php
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

    'use_route_url' => true,
    'dashboard_url' => 'dashboard',
    'logout_url' => 'logout',
    'login_url' => 'login',

    'menu' => [

        // 🔍 Navbar
        ['type' => 'navbar-search', 'text' => 'Rechercher', 'topnav_right' => true],
        ['type' => 'fullscreen-widget', 'topnav_right' => true],

        // 📊 Dashboard
        ['text' => 'Tableau de bord', 'route' => 'dashboard', 'icon' => 'fas fa-fw fa-tachometer-alt'],

        // 👨‍🎓 STAGIAIRES
        ['header' => 'GESTION DES STAGIAIRES'],

        ['text' => 'Stagiaires', 'route' => 'trainees.index', 'icon' => 'fas fa-fw fa-users'],

        ['text' => 'Diplômés', 'route' => 'diplomes.prets', 'icon' => 'fas fa-fw fa-graduation-cap', 'label' => 'NEW', 'label_color' => 'success'],

        ['text' => 'Importer Excel', 'route' => 'trainees.import', 'icon' => 'fas fa-fw fa-file-excel'],

        // 📄 DOCUMENTS
        ['header' => 'GESTION DES DOCUMENTS'],

        [
            'text' => 'Baccalauréat',
            'icon' => 'fas fa-fw fa-graduation-cap',
            'submenu' => [
<<<<<<< HEAD
                ['text' => 'Liste', 'url' => 'documents/bac', 'icon' => 'fas fa-fw fa-list'],
                ['text' => 'Retraits temporaires', 'url' => 'documents/bac/temp-out', 'icon' => 'fas fa-fw fa-clock', 'label' => '!', 'label_color' => 'warning'],
                ['text' => 'Écoulé', 'url' => 'documents/bac/ecoule', 'icon' => 'fas fa-fw fa-exclamation-triangle', 'label' => '!', 'label_color' => 'danger'],
                ['text' => 'Retraits définitifs', 'url' => 'documents/bac/final-out', 'icon' => 'fas fa-fw fa-sign-out-alt', 'label' => '!', 'label_color' => 'danger'],
=======
                ['text' => 'Liste', 'route' => 'documents.bac'],
                ['text' => 'Retraits temporaires', 'route' => 'documents.bac.temp-out'],
                ['text' => 'Retraits définitifs', 'route' => 'documents.bac.final-out'],
>>>>>>> c60b001b9a0eea696220532c2e90993880ff71ce
            ],
        ],

        [
            'text' => 'Diplômes',
            'icon' => 'fas fa-fw fa-certificate',
            'submenu' => [
                ['text' => 'Liste', 'route' => 'documents.diplome'],
                ['text' => 'Prêts à remettre', 'route' => 'documents.diplome.prets'],
            ],
        ],

        ['text' => 'Bulletins de notes', 'route' => 'documents.bulletin', 'icon' => 'fas fa-fw fa-file-alt'],
        ['text' => 'Attestations', 'route' => 'documents.attestation', 'icon' => 'fas fa-fw fa-file-contract'],

        // 🔄 MOUVEMENTS
        ['header' => 'MOUVEMENTS'],

        ['text' => 'Historique', 'route' => 'movements.index', 'icon' => 'fas fa-fw fa-exchange-alt'],
        ['text' => "Aujourd'hui", 'route' => 'movements.today', 'icon' => 'fas fa-fw fa-calendar-day'],

        [
            'text' => 'Calendrier',
            'route' => 'calendrier',
            'icon' => 'fas fa-fw fa-calendar-alt',
            'label' => '!',
            'label_color' => 'danger'
        ],

        // ✅ VALIDATIONS
        ['header' => 'VALIDATIONS'],
        ['text' => 'Registre', 'route' => 'validations.index', 'icon' => 'fas fa-fw fa-check-double'],

        // ⚙️ ADMIN
        ['header' => 'ADMINISTRATION'],

        ['text' => 'Utilisateurs', 'route' => 'users.index', 'icon' => 'fas fa-fw fa-user-cog'],

        [
            'text' => 'Paramètres',
            'icon' => 'fas fa-fw fa-cogs',
            'submenu' => [
                ['text' => 'Secteurs', 'route' => 'secteurs.index'],
                ['text' => 'Filières', 'route' => 'filieres.index'],
            ],
        ],
    ],

];
