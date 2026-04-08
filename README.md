# ISMO Archive

## 🚀 Nouvelles Fonctionnalités : Gestion des Délais de Retrait (Baccalauréat)

Afin d'améliorer le suivi des retraits temporaires et de respecter les délais stricts de l'administration, de nouvelles fonctionnalités de reporting et d'alerte ont été intégrées pour la gestion documentaire (Bac) :

### 🔴 Système d'Alerte Rouge (Pré-expiration 40h - 48h)
* Une alerte visuelle dynamique a été mise en place sur l'interface principale des **retraits temporaires**.
* Lorsqu'un document franchit le cap des 40 heures de retrait sans avoir été retourné, sa ligne est surlignée en rouge (`table-danger`) et un badge critique affiche le temps restant exact avant l'expiration légale de 48 heures.
* Les statistiques rapides au sommet de la page s'actualisent pour comptabiliser en temps réel les retraits nécessitant une intervention urgente de l'administration.

### ⏳ Nouvel Espace "Écoulé" (Post-expiration)
* Un filtre logique a été implémenté au niveau du Controller : tout document de type Baccalauréat dépassant strictement le délai autorisé de 48 heures est automatiquement retiré de la file active.
* Ces éléments sont basculés intelligemment (via l'historique des requêtes `movements`) vers un **nouvel espace dédié nommé "Écoulé"**.
* Accessible depuis le menu principal (`Gestion des Documents > Baccalauréat > Écoulé`), cet espace liste l'ensemble des retraits échus tout en affichant le retard de retour accumulé (calculé en jours, heures et minutes) afin de faciliter les rappels ou sanctions.

### 🛠 Modifications Techniques Apportées :
1. **Création du fichier vue `resources/views/documents/ecoule.blade.php`** affichant exclusivement la liste expirée.
2. **Ajout de la méthode `ecoule(Request $request)`** dans `app/Http/Controllers/DocumentController.php` pour filtrer les mouvements avec une date butoir (`deadline`) dépassée (`< now()`).
3. **Mise à jour de `tempOut(Request $request)`** pour ignorer les items périmés (`>= now()`).
4. **Enregistrement de la nouvelle route** `documents/bac/ecoule` dans `routes/web.php`.
5. **Ajout du menu interactif** dans la sidebar configuré via `config/adminlte.php`.

---

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
