# Bootstrap Integration Guide for Laravel

A comprehensive guide on how to integrate and use Bootstrap CSS framework in your Laravel CRUD application.

## 📋 Table of Contents

1. [What is Bootstrap?](#what-is-bootstrap)
2. [Installation Methods](#installation-methods)
   - [Method 1: CDN (Quick & Easy)](#method-1-cdn-quick--easy)
   - [Method 2: NPM (Recommended for Production)](#method-2-npm-recommended-for-production)
   - [Method 3: Composer Package](#method-3-composer-package)
3. [Setting Up Bootstrap with Vite](#setting-up-bootstrap-with-vite)
4. [Using Bootstrap in Blade Templates](#using-bootstrap-in-blade-templates)
5. [Bootstrap Components for Our CRUD App](#bootstrap-components-for-our-crud-app)
6. [Customizing Bootstrap](#customizing-bootstrap)
7. [Best Practices](#best-practices)
8. [Troubleshooting](#troubleshooting)

---

## What is Bootstrap?

**Bootstrap** is a free, open-source CSS framework that provides:
- ✅ Pre-built responsive components (buttons, forms, cards, modals, etc.)
- ✅ Grid system for responsive layouts
- ✅ Consistent styling across browsers
- ✅ JavaScript plugins for interactive elements
- ✅ Customizable through SASS variables

**Why use Bootstrap in Laravel?**
- Quickly build professional-looking UIs
- Mobile-responsive out of the box
- Consistent design across all pages
- Reduces custom CSS writing
- Large community and documentation

---

## Installation Methods

### Method 1: CDN (Quick & Easy)

**Best for:** Quick prototyping, learning, small projects

**Pros:**
- No build step needed
- Instant setup
- Works immediately

**Cons:**
- Can't customize Bootstrap
- Loads from external source (dependency)
- Larger file sizes downloaded every page

#### Step 1: Add Bootstrap CSS to Base Layout

Edit your main layout file (e.g., `resources/views/layouts/app.blade.php` or add directly to views):

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel CRUD App</title>
    
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        @yield('content')
    </div>

    <!-- Bootstrap JS CDN (for interactive components) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```

**Key CDN Links:**
```html
<!-- Bootstrap 5.3 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap 5.3 JS Bundle (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
```

#### Step 2: Use Bootstrap Classes in Your Views

```blade
<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Register</h5>
                    <form action="/register" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
```

---

### Method 2: NPM (Recommended for Production)

**Best for:** Production applications, customization, optimization

**Pros:**
- Can customize Bootstrap variables
- Included in your build (faster loading)
- No external CDN dependency
- Optimize unused styles

**Cons:**
- Requires build step (`npm run dev`)
- More setup initially

#### Step 1: Install Bootstrap via NPM

```bash
npm install bootstrap
```

This adds Bootstrap to `node_modules/bootstrap` and updates `package.json`.

#### Step 2: Import Bootstrap in Your App CSS

Edit `resources/css/app.css`:

```css
/* Import Bootstrap */
@import '../../node_modules/bootstrap/scss/bootstrap';

/* Your custom styles below */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.custom-card {
    border-left: 4px solid #007bff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
```

#### Step 3: Update Your Blade Layout

Edit `resources/views/layouts/app.blade.php` (create if doesn't exist):

```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel CRUD App</title>
    
    <!-- Vite CSS Import -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">Laravel CRUD</a>
        </div>
    </nav>

    <main class="container mt-5">
        @yield('content')
    </main>

    <footer class="bg-dark text-white text-center py-4 mt-5">
        <p>&copy; 2026 Laravel CRUD App. All rights reserved.</p>
    </footer>
</body>
</html>
```

#### Step 4: Run Build Command

```bash
# Development build (fast, uncompressed)
npm run dev

# Production build (optimized, minified)
npm run build

# Watch mode (rebuilds on file changes)
npm run dev -- --watch
```

#### Step 5: Use Bootstrap in Your Views

```blade
@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Create Post</h5>
            </div>
            <div class="card-body">
                <form action="/create-post" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="title" class="form-label">Post Title</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" placeholder="Enter post title" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="body" class="form-label">Post Content</label>
                        <textarea class="form-control @error('body') is-invalid @enderror" 
                                  id="body" name="body" rows="5" placeholder="Write your post..." required></textarea>
                        @error('body')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Create Post</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
```

---

### Method 3: Composer Package

**Best for:** Laravel-specific Bootstrap integration

#### Install Bootstrap Utilities for Laravel

```bash
composer require twbs/bootstrap
```

Then import in your CSS file like Method 2.

---

## Setting Up Bootstrap with Vite

Laravel uses **Vite** as the default build tool (replaces Mix). Here's how Bootstrap integrates:

### Vite Configuration

File: `vite.config.js`

```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
```

### CSS File with Bootstrap

File: `resources/css/app.css`

```css
/* Import Bootstrap */
@import '../../node_modules/bootstrap/scss/bootstrap';

/* Define custom colors */
:root {
    --primary-color: #007bff;
    --secondary-color: #6c757d;
}

/* Custom classes */
.navbar-brand {
    font-weight: bold;
    font-size: 1.5rem;
}

.card-hover {
    transition: transform 0.2s, box-shadow 0.2s;
}

.card-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

/* Responsive utilities */
@media (max-width: 768px) {
    .container {
        padding: 0 10px;
    }
}
```

### JavaScript File for Bootstrap Components

File: `resources/js/app.js`

```javascript
// Import Bootstrap
import * as bootstrap from 'bootstrap'

// Initialize tooltips
const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
})

// Initialize popovers
const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
const popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
  return new bootstrap.Popover(popoverTriggerEl)
})

// Custom JavaScript
console.log('Bootstrap initialized');
```

---

## Using Bootstrap in Blade Templates

### Basic Structure

```blade
<!-- Container: Fixed width responsive container -->
<div class="container">
    <!-- Row: Defines grid row -->
    <div class="row">
        <!-- Column: 12-column grid system -->
        <div class="col-md-6">
            Content here
        </div>
        <div class="col-md-6">
            Content here
        </div>
    </div>
</div>
```

### Common Bootstrap Classes

#### Spacing (Margin & Padding)

```
m = margin
p = padding
t = top
b = bottom
l = left
r = right
x = left & right
y = top & bottom

Examples:
mt-5    = margin-top: 3rem
mb-3    = margin-bottom: 1rem
p-4     = padding: 1.5rem
px-2    = padding-left & padding-right
my-auto = margin-top & margin-bottom: auto
```

#### Text & Colors

```blade
<!-- Text colors -->
<p class="text-primary">Primary text</p>
<p class="text-danger">Danger text</p>
<p class="text-success">Success text</p>

<!-- Background colors -->
<div class="bg-primary text-white p-3">Primary background</div>
<div class="bg-danger text-white p-3">Danger background</div>

<!-- Text alignment -->
<p class="text-center">Centered text</p>
<p class="text-end">Right aligned</p>

<!-- Font weight -->
<p class="fw-bold">Bold text</p>
<p class="fw-normal">Normal weight</p>
<p class="fw-light">Light text</p>
```

#### Responsive Classes

```blade
<!-- Show/hide on different screen sizes -->
<div class="d-none d-md-block">
    Visible on medium screens and up
</div>

<div class="d-md-none">
    Hidden on medium screens and up
</div>

<!-- Responsive columns -->
<div class="row">
    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
        Full width on mobile, 1/2 on tablet, 1/3 on desktop, 1/4 on large
    </div>
</div>
```

---

## Bootstrap Components for Our CRUD App

### Navigation Bar

```blade
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="/">Laravel CRUD</a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                @auth
                    <li class="nav-item">
                        <span class="nav-link">{{ auth()->user()->name }}</span>
                    </li>
                    <li class="nav-item">
                        <form action="/logout" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link">Logout</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="#login">Login</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
```

### Alert Messages

```blade
<!-- Success alert -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Error alert -->
@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <h5 class="alert-heading">Validation Errors</h5>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
```

### Form Validation

```blade
<form action="/create-post" method="POST">
    @csrf
    
    <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input 
            type="text" 
            class="form-control @error('title') is-invalid @enderror" 
            id="title" 
            name="title"
            value="{{ old('title') }}"
            required>
        @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="mb-3">
        <label for="body" class="form-label">Content</label>
        <textarea 
            class="form-control @error('body') is-invalid @enderror" 
            id="body" 
            name="body"
            rows="5"
            required>{{ old('body') }}</textarea>
        @error('body')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <button type="submit" class="btn btn-primary">Create Post</button>
    <a href="/" class="btn btn-secondary">Cancel</a>
</form>
```

### Cards (Post Display)

```blade
<div class="row g-4">
    @forelse($posts as $post)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">{{ $post->title }}</h5>
                    <p class="card-text text-muted">{{ Str::limit($post->body, 100) }}</p>
                </div>
                <div class="card-footer bg-white border-top">
                    <small class="text-muted">By {{ $post->user->name }}</small>
                </div>
                <div class="card-footer bg-white border-top">
                    <a href="/edit-post/{{ $post->id }}" class="btn btn-sm btn-primary">Edit</a>
                    
                    <form action="/delete-post/{{ $post->id }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" 
                                onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">No posts found.</div>
        </div>
    @endforelse
</div>
```

### Modal (Confirmation Dialog)

```blade
<!-- Button to trigger modal -->
<button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
    Delete Post
</button>

<!-- Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this post? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="/delete-post/{{ $post->id }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
```

### Pagination

```blade
<div class="d-flex justify-content-center">
    {{ $posts->links() }}
</div>

<!-- Customize pagination view -->
<!-- Add to config/app.php or use:  -->
{{ $posts->links('pagination::bootstrap-4') }}
```

### Table (For Records List)

```blade
<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($posts as $post)
                <tr>
                    <td>{{ $post->title }}</td>
                    <td>{{ $post->user->name }}</td>
                    <td>{{ $post->created_at->format('M d, Y') }}</td>
                    <td>
                        <a href="/edit-post/{{ $post->id }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="/delete-post/{{ $post->id }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">No posts found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
```

---

## Customizing Bootstrap

### Method 1: CSS Overrides (Simple)

Add custom CSS after Bootstrap import in `resources/css/app.css`:

```css
@import '../../node_modules/bootstrap/scss/bootstrap';

/* Override Bootstrap defaults */
:root {
    --bs-primary: #0d6efd;
    --bs-secondary: #6c757d;
    --bs-body-font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Custom component */
.btn-custom {
    border-radius: 50px;
    padding: 0.75rem 2rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

/* Custom navbar */
.navbar-custom {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
```

### Method 2: SASS Variables (Recommended)

Create `resources/css/bootstrap-custom.scss`:

```scss
// Override Bootstrap defaults BEFORE import
$primary: #0d6efd;
$secondary: #6c757d;
$success: #198754;
$danger: #dc3545;
$warning: #ffc107;
$info: #0dcaf0;

// Font customization
$font-family-base: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
$font-size-base: 1rem;
$line-height-base: 1.5;

// Spacing
$spacer: 1rem;

// Border radius
$border-radius: 0.375rem;
$border-radius-lg: 0.5rem;
$border-radius-sm: 0.25rem;

// Import Bootstrap after customization
@import '../../node_modules/bootstrap/scss/bootstrap';

// Add custom components after import
.card-custom {
    border: none;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;

    &:hover {
        transform: translateY(-5px);
    }
}

.btn-custom {
    border-radius: 50px;
    padding: 0.75rem 2rem;
    font-weight: 600;
}
```

Then in `resources/css/app.css`:

```css
@import './bootstrap-custom.scss';
```

---

## Best Practices

### 1. Use Semantic HTML with Bootstrap Classes

```blade
<!-- ❌ Don't: Meaningless divs -->
<div class="btn btn-primary">Click me</div>

<!-- ✅ Do: Use semantic HTML -->
<button class="btn btn-primary">Click me</button>
```

### 2. Mobile-First Approach

```blade
<!-- ❌ Don't: Desktop first -->
<div class="col-md-12 col-sm-6 col-12">

<!-- ✅ Do: Mobile first (no prefix = mobile) -->
<div class="col-12 col-sm-6 col-md-4">
```

### 3. Use Bootstrap's Utility Classes

```blade
<!-- ❌ Don't: Write custom CSS for everything -->
<style>
    .my-box {
        padding: 20px;
        margin-top: 10px;
        border-radius: 5px;
    }
</style>
<div class="my-box">Content</div>

<!-- ✅ Do: Use Bootstrap utilities -->
<div class="p-5 mt-2 rounded">Content</div>
```

### 4. Consistent Spacing

```blade
<!-- Use Bootstrap spacing scale: 0, 1, 2, 3, 4, 5 (0-3rem) -->
<div class="mt-5 mb-3 p-4">
    Content with consistent spacing
</div>
```

### 5. Accessibility

```blade
<!-- Include proper labels and ARIA attributes -->
<label for="email" class="form-label">Email</label>
<input type="email" id="email" class="form-control" aria-label="Email address">

<!-- Use semantic buttons -->
<button type="button" class="btn btn-primary" aria-label="Delete post">Delete</button>
```

---

## Troubleshooting

### Bootstrap Styles Not Showing

**Problem:** CSS classes have no effect

**Solutions:**
1. Check `npm run dev` has been run
2. Verify `@vite()` is in your layout
3. Hard refresh browser (Ctrl+Shift+R)
4. Check browser console for CSS errors
5. Ensure CSS file is imported in `app.css`

### JavaScript Components Not Working (Modals, Dropdowns, etc.)

**Problem:** Modals, tooltips, etc. don't work

**Solutions:**
```javascript
// Make sure Bootstrap JS is imported in app.js
import * as bootstrap from 'bootstrap'

// Or via CDN in blade
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
```

### Conflicting CSS

**Problem:** Bootstrap styles conflict with custom styles

**Solution:**
```css
/* Load Bootstrap first, then custom styles */
@import '../../node_modules/bootstrap/scss/bootstrap';

/* Custom styles override Bootstrap if more specific */
.container {
    /* Your custom container styles */
}
```

### Vite Not Detecting Changes

**Problem:** CSS/JS changes don't appear

**Solutions:**
```bash
# Restart Vite dev server
npm run dev

# Or use watch mode
npm run dev -- --watch

# Clear Laravel cache
php artisan cache:clear
php artisan config:clear
```

---

## Complete Example: Updated Home.blade.php

Here's how to update your `resources/views/Home.blade.php` with Bootstrap:

```blade
@extends('layouts.app')

@section('content')
<div class="container py-5">
    @auth
        <!-- Welcome Section -->
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto">
                <div class="alert alert-info" role="alert">
                    <h4 class="alert-heading">Welcome, {{ auth()->user()->name }}!</h4>
                    <p>Create a new post or manage your existing posts below.</p>
                </div>
            </div>
        </div>

        <!-- Create Post Form -->
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Create New Post</h5>
                    </div>
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Validation Errors:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="/create-post" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="title" class="form-label">Post Title</label>
                                <input 
                                    type="text" 
                                    class="form-control @error('title') is-invalid @enderror" 
                                    id="title" 
                                    name="title"
                                    placeholder="Enter post title"
                                    value="{{ old('title') }}"
                                    required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="body" class="form-label">Post Content</label>
                                <textarea 
                                    class="form-control @error('body') is-invalid @enderror" 
                                    id="body" 
                                    name="body"
                                    rows="5"
                                    placeholder="Write your post content here..."
                                    required>{{ old('body') }}</textarea>
                                @error('body')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid gap-2 d-sm-flex">
                                <button type="submit" class="btn btn-primary btn-lg">Create Post</button>
                                <a href="/records" class="btn btn-secondary btn-lg">View All Posts</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- User's Posts -->
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <h3 class="mb-4">Your Posts</h3>
                
                @if(isset($posts) && $posts->count() > 0)
                    <div class="row g-4">
                        @foreach($posts as $post)
                            <div class="col-md-6">
                                <div class="card h-100 shadow-sm">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $post->title }}</h5>
                                        <p class="card-text">{{ Str::limit($post->body, 100) }}</p>
                                        <small class="text-muted">
                                            Posted: {{ $post->created_at->format('M d, Y') }}
                                        </small>
                                    </div>
                                    <div class="card-footer bg-white">
                                        <a href="/edit-post/{{ $post->id }}" class="btn btn-sm btn-primary">Edit</a>
                                        <form action="/delete-post/{{ $post->id }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('Delete this post?')">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info" role="alert">
                        <p class="mb-0">You haven't created any posts yet. <strong>Create one above to get started!</strong></p>
                    </div>
                @endif
            </div>
        </div>

    @else
        <!-- Guest: Show Login & Register Forms -->
        <div class="row">
            <!-- Register Form -->
            <div class="col-lg-5">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Register</h5>
                    </div>
                    <div class="card-body">
                        @if($errors->has('name') || $errors->has('email') || $errors->has('password'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Registration Error:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach(['name', 'email', 'password'] as $field)
                                        @error($field)
                                            <li>{{ $message }}</li>
                                        @enderror
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="/register" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="reg-name" class="form-label">Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="reg-name" name="name" value="{{ old('name') }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="reg-email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="reg-email" name="email" value="{{ old('email') }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="reg-password" class="form-label">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="reg-password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Register</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Login Form -->
            <div class="col-lg-5 ms-lg-5">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Login</h5>
                    </div>
                    <div class="card-body">
                        @error('loginname')
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ $message }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @enderror

                        <form action="/login" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="login-name" class="form-label">Username</label>
                                <input type="text" class="form-control @error('loginname') is-invalid @enderror" 
                                       id="login-name" name="loginname" value="{{ old('loginname') }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="login-password" class="form-label">Password</label>
                                <input type="password" class="form-control @error('loginpassword') is-invalid @enderror" 
                                       id="login-password" name="loginpassword" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endauth
</div>
@endsection
```

---

## Summary

Bootstrap integration in Laravel provides:
- **Quick:** CDN method is instant
- **Professional:** Pre-built, tested components
- **Responsive:** Mobile-first design system
- **Customizable:** SASS variables for theming
- **Accessible:** Built-in accessibility features

Choose **NPM method (Method 2)** for production applications as it provides better customization and optimization options!
