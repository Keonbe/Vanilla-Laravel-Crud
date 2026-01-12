# Laravel CRUD Application - User Authentication & Post Management

A complete **User Authentication & Post Management** system built with Laravel 12. This project demonstrates core Laravel concepts including database migrations, Eloquent ORM relationships, form validation, authentication, and authorization.

> Note: I followed this YouTube tutorial while learning Laravel as a beginner.

> [![Watch the tutorial on YouTube](https://img.youtube.com/vi/cDEVWbz2PpQ/hqdefault.jpg)](https://www.youtube.com/watch?v=cDEVWbz2PpQ)

<!--
<iframe width="560" height="315" src="https://www.youtube.com/embed/cDEVWbz2PpQ" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
-->

## 📋 Project Overview

This is a fully functional CRUD (Create, Read, Update, Delete) application that allows users to:
- ✅ Register and create accounts
- ✅ Login and manage sessions
- ✅ Create, read, edit, and delete posts
- ✅ View all posts from all users
- ✅ Manage only their own posts (authorization)

## 🚀 Quick Start

### Prerequisites
- PHP 8.2+
- MySQL Server
- Composer

### Installation

```bash
# Clone/navigate to project
cd your-project-directory

# Install dependencies
composer install

# Create environment file (if not exists)
cp .env.example .env

# Configure database in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=

# Generate app key
php artisan key:generate

# Run migrations
php artisan migrate

# Start development server
php artisan serve
```

Visit `http://localhost:8000` to access the application.

## 📁 Project Structure

### Models
- **[app/Models/User.php](app/Models/User.php)** - User model with authentication & relationships
  - See [DOCUMENTATION_EXPANDED.md - Section 5.1](DOCUMENTATION_EXPANDED.md#51-user-model---detailed-functions) for detailed function breakdown
  - Properties: `$fillable`, `$hidden`, `$casts`
  - Methods: `manyPosts()` relationship

- **[app/Models/Post.php](app/Models/Post.php)** - Post model for managing posts
  - See [DOCUMENTATION_EXPANDED.md - Section 5.2](DOCUMENTATION_EXPANDED.md#52-post-model---detailed-functions) for detailed function breakdown
  - Properties: `$table`, `$fillable`
  - Methods: `user()` relationship

### Controllers
- **[app/Http/Controllers/UserController.php](app/Http/Controllers/UserController.php)** - Authentication handler
  - See [DOCUMENTATION_EXPANDED.md - Section 6.1](DOCUMENTATION_EXPANDED.md#61-usercontroller---detailed-functions) for detailed breakdown
  - Methods: `register()`, `login()`, `logout()`

- **[app/Http/Controllers/PostController.php](app/Http/Controllers/PostController.php)** - Post CRUD operations
  - See [DOCUMENTATION_EXPANDED.md - Section 6.2](DOCUMENTATION_EXPANDED.md#62-postcontroller---detailed-functions) for detailed breakdown
  - Methods: `createPost()`, `showEditForm()`, `update()`, `deletePost()`, `index()`

### Routes
- **[routes/web.php](routes/web.php)** - All URL mappings
  - See [DOCUMENTATION_EXPANDED.md - Section 7.1](DOCUMENTATION_EXPANDED.md#71-route-functions-breakdown) for route function breakdown
  - 9 routes total: registration, login, logout, post CRUD operations

### Views
- **[resources/views/Home.blade.php](resources/views/Home.blade.php)** - Main page with create form
  - See [DOCUMENTATION_EXPANDED.md - Section 8](DOCUMENTATION_EXPANDED.md#8-views-blade-templates) for template details

- **[resources/views/edit-post.blade.php](resources/views/edit-post.blade.php)** - Post edit form

- **[resources/views/records.blade.php](resources/views/records.blade.php)** - List all posts view

### Database
- **[database/migrations/0001_01_01_000000_create_users_table.php](database/migrations/0001_01_01_000000_create_users_table.php)** - Users table schema
  - See [DOCUMENTATION_EXPANDED.md - Section 3](DOCUMENTATION_EXPANDED.md#3-database-migrations-schema-management) for migration details

- **[database/migrations/2026_01_05_063522_create_post_table.php](database/migrations/2026_01_05_063522_create_post_table.php)** - Posts table schema
  - Includes foreign key relationship to users table

## 🔑 Key Features

### Authentication
- User registration with validation
- Secure password hashing with `bcrypt()`
- User login with session management
- Session regeneration for security
- Logout with complete session clearing

See [DOCUMENTATION_EXPANDED.md - Section 10](DOCUMENTATION_EXPANDED.md#10-authentication--authorization) for authentication details.

### CRUD Operations
- **Create**: Users can create new posts with title and body validation
- **Read**: View personal posts on home page or all posts on records page
- **Update**: Edit own posts with authorization checks
- **Delete**: Remove own posts with confirmation

### Validation & Security
- Server-side form validation
- CSRF protection on all forms with `@csrf`
- XSS prevention with `strip_tags()`
- Password hashing with `bcrypt()`
- Authorization checks before modify/delete operations

See [DOCUMENTATION_EXPANDED.md - Section 9](DOCUMENTATION_EXPANDED.md#9-form-validation) for validation rules.

### Eloquent Relationships
- **One-to-Many**: User has many Posts
- Eager loading to prevent N+1 queries
- Automatic relationship resolution

See [DOCUMENTATION_EXPANDED.md - Section 11](DOCUMENTATION_EXPANDED.md#11-eloquent-orm-relationships) for relationship details.

## 📚 Comprehensive Documentation

For detailed explanations of every function, property, and concept used in this project, see **[DOCUMENTATION_EXPANDED.md](DOCUMENTATION_EXPANDED.md)**.

### Quick Links to Sections:
1. [Project Overview](DOCUMENTATION_EXPANDED.md#project-overview)
2. [Environment Setup](DOCUMENTATION_EXPANDED.md#2-environment-setup-env-file)
3. [Database Migrations](DOCUMENTATION_EXPANDED.md#3-database-migrations-schema-management)
4. [Models & Functions](DOCUMENTATION_EXPANDED.md#4-models-database-abstraction-layer)
5. [Controllers & Functions](DOCUMENTATION_EXPANDED.md#5-controllers-business-logic)
6. [Routes Breakdown](DOCUMENTATION_EXPANDED.md#6-routes-url-mapping)
7. [Blade Templates](DOCUMENTATION_EXPANDED.md#8-views-blade-templates)
8. [Validation Rules](DOCUMENTATION_EXPANDED.md#9-form-validation)
9. [Auth & Authorization](DOCUMENTATION_EXPANDED.md#10-authentication--authorization)
10. [Learning Points](DOCUMENTATION_EXPANDED.md#14-key-learning-points)

## 🛠 Useful Artisan Commands

```bash
# Check migration status
php artisan migrate:status

# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Create new migration
php artisan make:migration create_table_name

# Create new controller
php artisan make:controller ControllerName

# Create new model
php artisan make:model ModelName

# Start development server
php artisan serve
```

See [DOCUMENTATION_EXPANDED.md - Section 8](DOCUMENTATION_EXPANDED.md#8-laravel-artisan-commands-used) for more details.

## 📊 Database Schema

### Users Table
| Column | Type | Details |
|--------|------|---------|
| id | unsigned bigint | Primary key |
| name | varchar | User's name |
| email | varchar | User's email (unique) |
| password | varchar | Hashed password |
| created_at | timestamp | Creation date |
| updated_at | timestamp | Last update date |

### Posts Table
| Column | Type | Details |
|--------|------|---------|
| id | unsigned bigint | Primary key |
| title | varchar | Post title |
| body | longtext | Post content |
| user_id | unsigned bigint | Foreign key to users |
| created_at | timestamp | Creation date |
| updated_at | timestamp | Last update date |

See [DOCUMENTATION_EXPANDED.md - Section 13](DOCUMENTATION_EXPANDED.md#13-project-structure) for complete project structure.

## 🔐 Security Features

- ✅ CSRF protection with `@csrf` tokens
- ✅ XSS prevention with `strip_tags()`
- ✅ Password hashing with `bcrypt()`
- ✅ Authorization checks (ownership verification)
- ✅ Mass assignment protection with `$fillable`
- ✅ Session regeneration on login
- ✅ SQL injection prevention via Eloquent ORM

See [DOCUMENTATION_EXPANDED.md - Section 16](DOCUMENTATION_EXPANDED.md#16-common-errors--solutions) for error troubleshooting.

## 🧠 What Changed from Default Laravel

This project extends the default Laravel template with:

1. **Custom Database Migration** - `create_post_table.php` with foreign key to users
2. **Post Model** - Complete model with relationships and fillable properties
3. **User Model Enhancements** - Added `manyPosts()` relationship
4. **UserController** - Custom authentication with `register()`, `login()`, `logout()`
5. **PostController** - Full CRUD implementation for posts
6. **Routes** - 9 custom routes for authentication and post management
7. **Views** - 3 Blade templates for home page, edit form, and records listing
8. **.env Configuration** - Database credentials and session/cache drivers

See [DOCUMENTATION_EXPANDED.md - Section 1 & 2](DOCUMENTATION_EXPANDED.md#1-starting-point-default-laravel-template) for details on default Laravel vs custom implementation.

## 🎓 Learning Resource

This project is designed as a **learning resource** for understanding:
- Laravel MVC architecture
- Database migrations and schema management
- Eloquent ORM and relationships
- Form validation and error handling
- Authentication and authorization
- RESTful routing conventions
- Blade templating engine

Each file contains inline comments explaining the purpose and logic. Refer to [DOCUMENTATION_EXPANDED.md](DOCUMENTATION_EXPANDED.md) for deep dives into each component.
