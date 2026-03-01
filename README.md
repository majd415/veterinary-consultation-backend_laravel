# Fluffy Admin - Premium Pet Care Backend

Empowering the Pet Haven mobile ecosystem with a robust Laravel-based administration panel and high-performance REST APIs.

## 🚀 Key Modules

### 🎨 Fluffy Admin Panel (Neon Series)
- **Modern Dashboard**: Real-time sales charts, user registration tracking, and revenue analytics.
- **Neon Redesign**: Premium dark-mode interface with vibrant neon accents and glassmorphism effects.
- **Multi-language Support**: Full English & Arabic localization with seamless Right-to-Left (RTL) layout switching.

### 🛡️ Secure Infrastructure
- **RBAC System**: Role-Based Access Control featuring Super Admin, Accountant, and Data Entry roles.
- **Audit Logs**: Transaction history and system activity monitoring.
- **Secure Payments**: Integrated Stripe API configuration and payment tracking.

### 📱 API Services
- **Real-time Chat**: Powering the "Vet Chat" feature with archived history and notification hooks.
- **Store & Inventory**: Headless management for products, categories, and orders.
- **Booking Engine**: Sophisticated management for Hotel stays and Grooming sessions.
- **FCM Notifications**: Integrated push notification service with emoji support.

## ⚙️ Tech Stack

- **Framework**: [Laravel 11.x](https://laravel.com/)
- **Database**: MySQL / MariaDB
- **Infrastructure**: Sanctum for API Auth, FCM for Notifications, Stripe for Payments.
- **Localization**: Custom `SetLocale` middleware with `spatie/laravel-translatable`.

## 🛠 Getting Started

### Prerequisites
- PHP 8.2+
- Composer
- MySQL

### Installation
1. Clone the repository
2. Run `composer install`
3. Copy `.env.example` to `.env` and configure your database
4. Run `php artisan key:generate`
5. Run `php artisan migrate --seed`
6. Start the server: `php artisan serve`

## 📄 License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

