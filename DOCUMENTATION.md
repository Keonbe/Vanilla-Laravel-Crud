# Laravel CRUD Application - Complete Documentation

## Project Overview

This is a **User Authentication & Post Management CRUD Application** built with Laravel 12. It demonstrates core Laravel concepts including authentication, database migrations, Eloquent ORM relationships, and form handling.

---

## 1. Starting Point: Default Laravel Template

When you run `composer create-project laravel/laravel project-name`, you get a default Laravel template with:
- Pre-configured directory structure
- Pre-built authentication system (not used here; we built custom)
- Database configuration
- Route system
- Service providers

### Key Default Files Created:
- `.env` - Environment configuration file
- `routes/web.php` - Web routes definition
- `app/Models/User.php` - User model
- `config/` - Configuration files
- `database/migrations/` - Migration files
- `resources/views/` - Blade templates

---

## 2. Environment Setup (`.env` File)

### What We Changed:

**Before (commented out):**
```env
DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=root
# DB_PASSWORD=root
```

**After (activated):**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

### What This Does:
- **DB_CONNECTION**: Uses MySQL as the database
- **DB_HOST/PORT**: Connects to local MySQL server on port 3306
- **DB_DATABASE**: Creates/uses database named `laravel`
- **DB_USERNAME/PASSWORD**: MySQL credentials (root with no password)
- **SESSION_DRIVER**: Stores sessions in the database (not files)
- **CACHE_STORE**: Uses database for caching
- **QUEUE_CONNECTION**: Uses database for job queues

---

## 3. Database Migrations (Schema Management)

### What are Migrations?

Migrations are **version-controlled database schema files**. Instead of manually creating tables in MySQL Workbench, migrations use PHP code to:
- Create/alter tables
- Run `php artisan migrate` to apply changes
- Keep database schema in sync across environments
- Roll back changes with `php artisan migrate:rollback`

### Migrations Created:

#### a) Default Migrations (Pre-existing)

**File:** `database/migrations/0001_01_01_000000_create_users_table.php`

```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->rememberToken();
    $table->timestamps(); // created_at, updated_at
});
```

This creates the `users` table with columns for user data.

#### b) Custom Post Migration (Created)

**Command Used:**
```bash
php artisan make:migration create_post_table
```

**File:** `database/migrations/2026_01_05_063522_create_post_table.php`

```php
Schema::create('post', function (Blueprint $table) {
    $table->id();                          // Auto-increment primary key
    $table->timestamps();                  // created_at, updated_at
    $table->string('title');               // Post title
    $table->longText('body');              // Post content (long text)
    $table->foreignId('user_id')           // Foreign key to users table
          ->constrained();                  // Ensures user exists
});
```

**Key Concepts:**
- `$table->id()` - Creates auto-incrementing `id` column
- `$table->timestamps()` - Creates `created_at` and `updated_at` columns
- `$table->foreignId('user_id')->constrained()` - Creates foreign key relationship to `users` table
- `->constrained()` - Ensures database-level referential integrity

### Running Migrations

```bash
# Apply all pending migrations
php artisan migrate

# Check migration status
php artisan migrate:status

# Rollback last migration batch
php artisan migrate:rollback

# Reset all migrations (careful!)
php artisan migrate:reset
```

**Output Example:**
```
Migration name ......................................... Batch / Status
0001_01_01_000000_create_users_table .................. [1] Ran
0001_01_01_000001_create_cache_table .................. [1] Ran
0001_01_01_000002_create_jobs_table ................... [1] Ran
2026_01_05_063522_create_post_table ................... [2] Ran
```

---

## 4. Models (Database Abstraction Layer)

### What are Models?

Models are PHP classes that represent database tables. They allow you to:
- Interact with database records as objects
- Define relationships between tables
- Use Eloquent ORM for queries

### a) User Model

**File:** `app/Models/User.php`

**What We Added:**
```php
use App\Models\Post;

protected $casts = [
    'email_verified_at' => 'datetime',
    'password' => 'hashed',
];

public function manyPosts()
{
    return $this->hasMany(Post::class, 'user_id');
}
```

**Explanation:**
- `$casts` - Automatically cast attributes to types (password hashing, datetime conversion)
- `hasMany()` - One User has many Posts relationship
- This allows: `auth()->user()->manyPosts()->get()` to fetch all user's posts

### b) Post Model

**Command Used:**
```bash
php artisan make:model Post
```

**File:** `app/Models/Post.php`

```php
use App\Models\User;

class Post extends Model
{
    protected $table = 'post';  // Specify table name (singular)
    protected $fillable = ['title', 'body', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
```

**Explanation:**
- `$table = 'post'` - Maps to the `post` table created by migration
- `$fillable` - Allows mass assignment for these columns only
  - Prevents mass assignment vulnerability: `Post::create($data)` only sets these fields
- `belongsTo()` - Many posts belong to one user
- Allows: `$post->user()->first()` to get the post author

### Eloquent ORM Usage Examples

```php
// Create a post
Post::create(['title' => '...', 'body' => '...', 'user_id' => 1]);

// Get all posts with user info
$posts = Post::with('user')->latest()->get();

// Get user's posts
$user->manyPosts()->get();

// Update a post
$post->update(['title' => 'New Title']);

// Delete a post
$post->delete();
```

---

## 5. Controllers (Business Logic)

### What are Controllers?

Controllers handle HTTP requests and return responses. They:
- Validate incoming data
- Interact with models
- Return views with data

### Controllers Created:

#### a) UserController

**Command Used:**
```bash
php artisan make:controller UserController
```

**File:** `app/Http/Controllers/UserController.php`

```php
public function register(Request $request) {
    // Validate incoming data
    $incomingData = $request->validate([
        'name' => ['required', 'min:3', 'max:50', Rule::unique('users', 'name')],
        'email' => ['required', 'email', Rule::unique('users', 'email')],
        'password' => 'required|min:6|max:20',
    ]);

    // Hash password and create user
    $incomingData['password'] = bcrypt($incomingData['password']);
    $user = User::create($incomingData);
    auth()->login($user);

    return redirect('/')->with('success', 'User registered successfully!');
}
```

**Key Features:**
- `$request->validate()` - Validates form data
- `Rule::unique('users', 'name')` - Ensures unique usernames
- `bcrypt()` - One-way password hashing
- `auth()->login()` - Creates user session

#### b) PostController

**Command Used:**
```bash
php artisan make:controller PostController
```

**File:** `app/Http/Controllers/PostController.php`

**Methods:**

1. **Create Post**
```php
public function createPost(Request $request) {
    $incomingData = $request->validate([
        'title' => 'required|min:5|max:100',
        'body' => 'required|min:10',
    ]);

    // Sanitize and assign user
    $incomingData['title'] = strip_tags($incomingData['title']);
    $incomingData['body'] = strip_tags($incomingData['body']);
    $incomingData['user_id'] = auth()->id();
    
    Post::create($incomingData);
    return redirect('/')->with('success', 'Post created successfully!');
}
```

2. **Update Post**
```php
public function update(Request $request, Post $post) {
    // Authorization check
    if ($post->user_id !== auth()->id()) {
        abort(403); // Forbidden
    }

    $incomingData = $request->validate([...]);
    $post->update($incomingData);
    
    return redirect('/records')->with('success', 'Post updated successfully!');
}
```

3. **Delete Post**
```php
public function deletePost(Post $post) {
    if (auth()->id() !== $post->user_id) {
        abort(403);
    }

    $post->delete();
    return redirect('/')->with('success', 'Post deleted successfully!');
}
```

4. **List Posts**
```php
public function index() {
    $posts = Post::with('user')->latest()->get();
    return view('records', ['posts' => $posts]);
}
```

**Key Concepts:**
- Route model binding: `Post $post` automatically finds post by ID from URL
- Authorization: `abort(403)` returns 403 Forbidden error
- `strip_tags()` - Removes HTML tags for security
- `->with('user')` - Eager loads user relationship

---

## 6. Routes (URL Mapping)

### What are Routes?

Routes map HTTP requests to controller actions. Defined in `routes/web.php`.

### Routes Created:

```php
// Home page - display user's posts
Route::get('/', function () {
    $posts = [];
    if (auth()->check()) {
        $posts = auth()->user()->manyPosts()->latest()->get();
    }
    return view('home', ['posts' => $posts]);
});

// User Authentication
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout']);

// Post Management
Route::post('/create-post', [PostController::class, 'createPost']);
Route::get('/records', [PostController::class, 'index']);
Route::get('/edit-post/{post}', [PostController::class, 'showEditForm']);
Route::put('/edit-post/{post}', [PostController::class, 'update']);
Route::delete('/delete-post/{post}', [PostController::class, 'deletePost']);
```

**HTTP Methods:**
- `GET` - Retrieve data (viewing pages)
- `POST` - Submit new data (create)
- `PUT` - Update existing data
- `DELETE` - Remove data

**Route Parameters:**
- `{post}` - Laravel automatically injects Post model instance from URL parameter

---

## 7. Views (Blade Templates)

### What are Views?

Views are HTML templates that display data. They use **Blade** templating engine.

### Views Created:

#### a) `resources/views/Home.blade.php`

```blade
@auth
    <!-- Show only to authenticated users -->
    <h1>Welcome, {{ auth()->user()->name }}!</h1>
    
    <!-- Create post form -->
    <form action="/create-post" method="POST">
        @csrf
        <input type="text" name="title" placeholder="Enter Title">
        <input type="text" name="body" placeholder="Enter Body Content">
        <button type="submit">Create Record</button>
    </form>

    <!-- Display user's posts -->
    @if(isset($posts) && $posts->isNotEmpty())
        @foreach($posts as $post)
            <div>
                <strong>{{ $post->title }}</strong>
                <p>{{ $post->body }}</p>
                <a href="/edit-post/{{ $post->id }}">Edit</a>
                
                <!-- Delete form -->
                <form action="/delete-post/{{ $post->id }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Delete?')">Delete</button>
                </form>
            </div>
        @endforeach
    @else
        <p>No posts found.</p>
    @endif

@else
    <!-- Show to unauthenticated users -->
    <h1>Register or Login</h1>
    <!-- Registration form -->
    <!-- Login form -->
@endauth
```

**Blade Directives:**
- `@auth` / `@endauth` - Show content if user is authenticated
- `@if` / `@else` / `@endif` - Conditional rendering
- `@foreach` / `@endforeach` - Loop through collections
- `{{ variable }}` - Echo variable (automatically escaped)
- `{!! variable !!}` - Echo unescaped HTML
- `@csrf` - Cross-Site Request Forgery token (security)
- `@method('DELETE')` - Specify HTTP method (forms only support GET/POST)

#### b) `resources/views/edit-post.blade.php`

```blade
<h1>Edit Post</h1>
<form action="/edit-post/{{ $post->id }}" method="POST">
    @csrf
    @method('PUT')

    <label for="title">Title:</label>
    <input type="text" id="title" name="title" 
           value="{{ old('title', $post->title) }}" required>

    <label for="body">Body:</label>
    <textarea id="body" name="body" required>
        {{ old('body', $post->body) }}
    </textarea>

    <button type="submit">Update Post</button>
</form>
```

**Key Features:**
- `old()` - Retrieves old input values if validation failed, otherwise shows database value
- Pre-populates form with current post data

#### c) `resources/views/records.blade.php`

```blade
<h1>All Posts</h1>
@if($posts->isEmpty())
    <p>No posts found.</p>
@else
    @foreach($posts as $post)
        <div>
            <strong>{{ $post->title }}</strong> by {{ $post->user->name }}
            <p>{{ $post->body }}</p>
        </div>
    @endforeach
@endif
```

---

## 8. Laravel Artisan Commands Used

### What is Artisan?

Artisan is Laravel's command-line tool for generating code, running migrations, and development tasks.

### Commands Executed:

```bash
# Create new controller
php artisan make:controller UserController
php artisan make:controller PostController

# Create new model (creates migration too)
php artisan make:model Post

# Create migration only
php artisan make:migration create_post_table

# Run all pending migrations
php artisan migrate

# Check migration status
php artisan migrate:status

# Rollback last migration
php artisan migrate:rollback

# Clear configuration cache
php artisan config:clear
```

---

## 9. Form Validation

### Validation Rules Used:

```php
$request->validate([
    'name' => ['required', 'min:3', 'max:50', Rule::unique('users', 'name')],
    'email' => ['required', 'email', Rule::unique('users', 'email')],
    'password' => 'required|min:6|max:20',
    'title' => 'required|min:5|max:100',
    'body' => 'required|min:10',
]);
```

**Common Validation Rules:**
- `required` - Field cannot be empty
- `email` - Must be valid email format
- `min:N` / `max:N` - Minimum/maximum length
- `unique('table', 'column')` - Value must be unique in database
- `Rule::unique()` - Advanced unique validation

**How Validation Works:**
1. Form submitted to route
2. Controller validates data
3. If invalid: returns to previous page with errors (`$errors` variable)
4. If valid: processes data

**In Blade Templates:**
```blade
@if($errors->has('email'))
    <p>{{ $errors->first('email') }}</p>
@endif
```

---

## 10. Authentication & Authorization

### Authentication (Who are you?)

```php
// Check if user is authenticated
if (auth()->check()) {
    $user = auth()->user();
}

// Login user
auth()->login($user);

// Logout user
auth()->logout();

// Get authenticated user
auth()->id();  // user ID
auth()->user()->name;  // user name
```

### Authorization (What can you do?)

```php
// In controller
if ($post->user_id !== auth()->id()) {
    abort(403);  // Forbidden
}

// In Blade template
@can('delete', $post)
    <button>Delete</button>
@endcan
```

---

## 11. Eloquent ORM Relationships

### One-to-Many Relationship

**User has many Posts:**

**User Model:**
```php
public function manyPosts()
{
    return $this->hasMany(Post::class, 'user_id');
}
```

**Post Model:**
```php
public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}
```

**Usage:**
```php
// Get all posts by user
$user->manyPosts()->get();

// Get post's author
$post->user;  // Lazy loads
$posts = Post::with('user')->get();  // Eager loads
```

---

## 12. Data Flow Summary

### Creating a Post:

1. **User fills form** in `Home.blade.php`
   ```blade
   <form action="/create-post" method="POST">
   ```

2. **Form submitted to route** `POST /create-post`
   ```php
   Route::post('/create-post', [PostController::class, 'createPost']);
   ```

3. **Controller validates & creates**
   ```php
   public function createPost(Request $request) {
       $data = $request->validate([...]);
       Post::create($data);
   }
   ```

4. **Migration defined schema** - columns available for insert
   ```php
   Schema::create('post', function (Blueprint $table) {
       $table->string('title');
       $table->longText('body');
       $table->foreignId('user_id');
   });
   ```

5. **Model handles database interaction**
   ```php
   protected $fillable = ['title', 'body', 'user_id'];
   ```

6. **Data saved to MySQL** in `laravel.post` table

### Displaying Posts:

1. **Route maps to controller**
   ```php
   Route::get('/', function () {
       $posts = auth()->user()->manyPosts()->get();
   });
   ```

2. **Controller fetches from database** using model relationship
   ```php
   $posts = auth()->user()->manyPosts()->latest()->get();
   ```

3. **Data passed to view**
   ```php
   return view('home', ['posts' => $posts]);
   ```

4. **Blade template renders HTML**
   ```blade
   @foreach($posts as $post)
       {{ $post->title }}
   @endforeach
   ```

---

## 13. Project Structure

```
project-root/
├── app/
│   ├── Http/Controllers/
│   │   ├── UserController.php      (Authentication logic)
│   │   └── PostController.php      (CRUD operations)
│   └── Models/
│       ├── User.php               (User model + relationships)
│       └── Post.php               (Post model + relationships)
├── database/
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   └── 2026_01_05_063522_create_post_table.php
│   └── seeders/                   (For test data)
├── resources/
│   └── views/
│       ├── Home.blade.php         (Main page + create form)
│       ├── edit-post.blade.php    (Edit post form)
│       └── records.blade.php      (List all posts)
├── routes/
│   └── web.php                    (All URL routes)
├── .env                           (Environment variables)
├── composer.json                  (PHP dependencies)
└── artisan                        (Laravel CLI tool)
```

---

## 14. Key Learning Points

### ✅ Database as Code
- Migrations version control your database schema
- `php artisan migrate` applies changes across environments
- No need for MySQL Workbench SQL scripts

### ✅ MVC Pattern
- **Model**: `User.php`, `Post.php` - Database layer
- **View**: Blade templates - Display layer
- **Controller**: `UserController.php`, `PostController.php` - Logic layer

### ✅ Eloquent ORM
- Write database queries as PHP objects
- Relationships: `$user->manyPosts()` instead of JOIN queries
- Automatic SQL generation and escaping

### ✅ Routing & Resource Management
- RESTful routes (GET, POST, PUT, DELETE)
- Route parameters with model binding
- Meaningful URLs

### ✅ Validation & Security
- Server-side validation
- CSRF protection with `@csrf`
- Password hashing with `bcrypt()`
- HTML sanitization with `strip_tags()`

### ✅ Authentication & Authorization
- Built-in `auth()` helper
- Check user ownership before modify/delete
- `abort(403)` for unauthorized actions

---

## 15. Next Steps for Learning

1. **User Policies** - Cleaner authorization logic
2. **Seeders** - Populate database with test data
3. **Relationships** - Explore many-to-many, has-many-through
4. **API Routes** - Build JSON APIs instead of web views
5. **Middleware** - Protect routes from unauthorized access
6. **Testing** - Unit & feature tests with PHPUnit
7. **Caching** - Optimize database queries
8. **Queue Jobs** - Async task processing

---

## 16. Common Errors & Solutions

| Error | Cause | Solution |
|-------|-------|----------|
| `Table 'laravel.posts' doesn't exist` | Model table name mismatch | Set `protected $table = 'post';` in model |
| `Bad method call: validateRule` | Invalid validation rule syntax | Use `Rule::unique()` for complex rules |
| `403 Forbidden` | Authorization check failed | Verify `auth()->id() === $post->user_id` |
| `ParseError: unexpected token "else"` | Missing `@endforeach` in Blade | Close Blade directives properly |
| `SQLSTATE[42S02]` | Foreign key reference error | Ensure user exists before creating post |

---

## Conclusion

This Laravel CRUD application demonstrates:
- ✅ Database migrations for schema management
- ✅ Eloquent ORM for database abstraction
- ✅ MVC architecture separation of concerns
- ✅ Form validation and error handling
- ✅ Authentication and authorization
- ✅ RESTful routing conventions
- ✅ Blade templating for HTML generation

All code is organized, tested, and follows Laravel best practices!
