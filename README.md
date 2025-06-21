# MessBook Laravel Multi-Tenant App

This is a skeleton project for a multi-tenant mess management system built with Laravel, Breeze, Spatie Permission, Bootstrap, and CSS.

## Setup

1. Copy `.env.example` to `.env` and update database credentials.
2. Run `composer install`.
3. Run `php artisan migrate`.
4. Install Breeze scaffolding:
   ```bash
   composer require laravel/breeze --dev
   php artisan breeze:install blade
   npm install && npm run dev
   ```
5. Publish Spatie Permission:
   ```bash
   composer require spatie/laravel-permission
   php artisan vendor:publish --provider="Spatie\\Permission\\PermissionServiceProvider"
   php artisan migrate
   ```
6. Serve the app:
   ```bash
   php artisan serve
   ```
