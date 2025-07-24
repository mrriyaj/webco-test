# Product Management System

A simple and powerful product management system built with Laravel 12 and Filament 3.

## 🔗 Project Links

**GitHub Repository**: [https://github.com/mrriyaj/webco-test](https://github.com/mrriyaj/webco-test)

**Live Demo**: [https://webco-test-main-yxfwlw.laravel.cloud/admin](https://webco-test-main-yxfwlw.laravel.cloud/admin)

### Demo Login Credentials

-   **Admin**: admin@example.com
-   **User**: user@example.com
-   **Password**: password

---

## What You Get

✅ **Product Management** - Add, edit, delete products with categories and colors  
✅ **Admin Dashboard** - Beautiful Filament admin panel  
✅ **Background Jobs** - Process products with queue system  
✅ **Real-time Notifications** - Get notified when jobs complete  
✅ **Custom Components** - Enhanced UI with Livewire  
✅ **Statistics Widgets** - View product metrics on dashboard

## Quick Start Checklist

Before you begin, make sure you have:

-   [ ] PHP 8.2 or higher
-   [ ] Composer installed
-   [ ] Node.js & NPM installed
-   [ ] Git installed

## Project Tasks Checklist

-   [x] Setting up Laravel (using SQLite to keep things simple)
-   [x] Installing and Configuring Filament
-   [x] Building complete, valid models using an ERD as reference
-   [x] Importing seeded data
-   [x] Installing a custom theme
-   [x] Changing the Theme CSS to override the sidebar background color using Tailwind CSS types
-   [x] Creating a basic CRUD to manage specific models
-   [x] Creating a complex CRUD for the main model (specification in the project)
-   [x] Creating an ‘infolist’ for a read-only view
-   [] Using a ‘suffix Action’ to fetch or validate a field using an external API
-   [x] Building a simple custom field (Create a status bar on the ‘Product’ model that says ‘Hello’ and the background color of the bar is mapped against the product)
-   [x] Creating a simple job
-   [x] Creating an action on a model list that will use the ‘simple job’ and process the request
-   [ ] Creating a ‘loading’ symbol when the state is changed in the required text field that appears in the suffix field. Perform an external integration within the action
-   [x] Finding a neat way to show the amount of models created (that are custom to the project) on the Dashboard

_Currently working on tasks 10 and 14 to learn and implement them._

## Installation

## Installation

Follow these simple steps to get started:

### 1. Clone & Navigate

```bash
git clone <repository-url>
cd webco-test
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Setup Environment

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Setup Database

```bash
touch database/database.sqlite
php artisan migrate --seed
```

### 5. Build Assets & Start

```bash
npm run build
php artisan serve
```

### 6. Access Admin Panel

-   **Local**: `http://localhost:8000/admin`
-   **Live Demo**: [https://webco-test-main-yxfwlw.laravel.cloud/admin](https://webco-test-main-yxfwlw.laravel.cloud/admin)
-   Login with:
    -   **Admin**: admin@example.com / password
    -   **User**: user@example.com / password

That's it! 🎉 You now have a fully working product management system.

## Laravel Cloud Deployment

This project is deployed on Laravel Cloud and optimized for production:

-   ✅ Admin panel accessible at `/admin` path
-   ✅ Database notifications configured
-   ✅ Background job processing
-   ✅ Real-time notifications
-   ✅ Production-ready authentication

## How to Use

**Products**: Create and manage your product catalog  
**Categories**: Organize products by type (Electronics, Clothing, etc.)  
**Colors**: Add colors with visual picker and hex codes  
**Processing**: Use "Process Product" button to queue background jobs  
**Notifications**: Check the bell icon for job completion alerts

## Tech Stack

-   **Laravel 12** - Modern PHP framework
-   **Filament 3** - Beautiful admin panel
-   **SQLite** - Simple database (MySQL/PostgreSQL ready)
-   **Livewire** - Interactive components
-   **Vite** - Fast asset building
-   **Laravel Cloud** - Deployment platform

## Features in Detail

### Product Management

-   Add products with name, description, price
-   Assign categories and colors
-   Process products with background jobs
-   View detailed product information

### Background Processing

-   Queue system for heavy operations
-   Real-time notifications when jobs complete
-   Process product descriptions automatically

### Admin Dashboard

-   Statistics widgets showing product counts
-   Modern, responsive interface
-   Built-in search and filtering

## Testing

Run tests to make sure everything works:

```bash
# Run all tests
php artisan test

# Run specific tests
php artisan test --testsuite=Feature
```

## Contributing

Want to contribute? Great!

1. Fork the project
2. Make your changes
3. Follow PSR-12 coding standards
4. Add tests for new features
5. Submit a pull request

## License

Open-sourced under the [MIT license](https://opensource.org/licenses/MIT).
