# Product Management System

A simple and powerful product management system built with Laravel 12 and Filament 3.

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

- [x] Setting up Laravel (using SQLite to keep things simple)
- [x] Installing and Configuring Filament
- [x] Building complete, valid models using an ERD as reference
- [x] Importing seeded data
- [x] Installing a custom theme
- [x] Changing the Theme CSS to override the sidebar background color using Tailwind CSS types
- [x] Creating a basic CRUD to manage specific models
- [x] Creating a complex CRUD for the main model (specification in the project)
- [x] Creating an ‘infolist’ for a read-only view
- [] Using a ‘suffix Action’ to fetch or validate a field using an external API
- [x] Building a simple custom field (Create a status bar on the ‘Product’ model that says ‘Hello’ and the background color of the bar is mapped against the product)
- [x] Creating a simple job
- [x] Creating an action on a model list that will use the ‘simple job’ and process the request
- [ ] Creating a ‘loading’ symbol when the state is changed in the required text field that appears in the suffix field. Perform an external integration within the action
- [x] Finding a neat way to show the amount of models created (that are custom to the project) on the Dashboard

*Currently working on tasks 10 and 14 to learn and implement them.*

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

-   Open: `http://localhost:8000`
-   Login with:
    -   **Admin**: admin@example.com
    -   **User**: user@example.com

That's it! 🎉 You now have a fully working product management system.

Open-sourced under the [MIT license](https://opensource.org/licenses/MIT).
