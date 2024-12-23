### **Document: User Authentication in Laravel**

---

### **Table of Contents**

1. [**Overview**](#1-overview)  
   - [Introduction to User Authentication in Laravel](#introduction-to-user-authentication-in-laravel)  
   - [Approaches to Implement Authentication](#approaches-to-implement-authentication)  

2. [**Using Laravel’s Built-in Authentication**](#2-using-laravels-built-in-authentication)  
   - [Setting Up Laravel Breeze](#setting-up-laravel-breeze)  
   - [Registering Users](#registering-users)  
   - [Logging in Users](#logging-in-users)  
   - [Customizing Views with Blade](#customizing-views-with-blade)  
   - [Protecting Routes with Middleware](#protecting-routes-with-middle)  

3. [**Custom Implementation of Authentication**](#3-custom-implementation-of-authentication)  
   - [Creating the User Model and Migration](#creating-the-user-model-and-migration)  
   - [Creating Custom Registration](#creating-custom-registration)  
   - [Creating Custom Login](#creating-custom-login)  
   - [Using Middleware for Route Protection](#using-middleware-for-route-protection)  

4. [**Conclusion**](#4-conclusion)

---

### **1. Overview**

#### **Introduction to User Authentication in Laravel**
User authentication ensures secure access to application features and data. Laravel provides a built-in, customizable authentication system, but you can also create your own for greater flexibility.

#### **Approaches to Implement Authentication**
- **Built-in Authentication**: Quick setup with pre-designed functionality.
- **Custom Authentication**: Tailored to specific requirements with full control.

---

### **2. Using Laravel’s Built-in Authentication**

#### **Setting Up Laravel Breeze**

1. Install Laravel Breeze:
   ```bash
   composer require laravel/breeze --dev
   php artisan breeze:install
   npm install && npm run dev
   php artisan migrate
   ```
2. This provides pre-built routes, controllers, and views for:
   - Registering users.
   - Logging in and out.
   - Password resets.

#### **Registering Users**

1. **Registration View (Blade)**:
   Found in `resources/views/auth/register.blade.php`:
   ```html
   <form method="POST" action="{{ route('register') }}">
       @csrf
       <label for="name">Name</label>
       <input type="text" id="name" name="name" required autofocus>
       
       <label for="email">Email</label>
       <input type="email" id="email" name="email" required>
       
       <label for="password">Password</label>
       <input type="password" id="password" name="password" required>
       
       <label for="password_confirmation">Confirm Password</label>
       <input type="password" id="password_confirmation" name="password_confirmation" required>
       
       <button type="submit">Register</button>
   </form>
   ```

2. Laravel automatically handles the registration logic via `App\Http\Controllers\Auth\RegisteredUserController`.

#### **Logging in Users**

1. **Login View (Blade)**:
   Found in `resources/views/auth/login.blade.php`:
   ```html
   <form method="POST" action="{{ route('login') }}">
       @csrf
       <label for="email">Email</label>
       <input type="email" id="email" name="email" required>
       
       <label for="password">Password</label>
       <input type="password" id="password" name="password" required>
       
       <button type="submit">Login</button>
   </form>
   ```

2. Laravel handles login logic via `App\Http\Controllers\Auth\AuthenticatedSessionController`.

---

#### **Customizing Views with Blade**

1. Modify HTML/CSS of registration and login views:
   ```html
   <div class="container">
       <h1>Register</h1>
       <form method="POST" action="{{ route('register') }}" class="form">
           @csrf
           <div>
               <label for="name">Name</label>
               <input type="text" id="name" name="name">
           </div>
           <div>
               <label for="email">Email</label>
               <input type="email" id="email" name="email">
           </div>
           <button type="submit" class="btn">Register</button>
       </form>
   </div>
   <style>
       .container { max-width: 500px; margin: 0 auto; padding: 20px; }
       .form div { margin-bottom: 15px; }
       .btn { background-color: blue; color: white; padding: 10px 20px; }
   </style>
   ```

---

#### **Protecting Routes with Middleware**

1. Protect routes using `auth` middleware:
   ```php
   Route::get('/dashboard', function () {
       return view('dashboard');
   })->middleware(['auth']);
   ```

2. Redirect unauthorized users:
   - If a user isn’t logged in, they’ll be redirected to the login page.

---

### **3. Custom Implementation of Authentication**

#### **Creating the User Model and Migration**

1. Generate a user model and migration:
   ```bash
   php artisan make:model User -m
   ```
2. Define the migration:
   ```php
   public function up()
   {
       Schema::create('users', function (Blueprint $table) {
           $table->id();
           $table->string('name');
           $table->string('email')->unique();
           $table->string('password');
           $table->timestamps();
       });
   }
   ```
3. Run the migration:
   ```bash
   php artisan migrate
   ```

---

#### **Creating Custom Registration**

1. Create a registration route:
   ```php
   Route::get('/register', [UserController::class, 'showRegisterForm']);
   Route::post('/register', [UserController::class, 'register']);
   ```

2. Create a controller:
   ```bash
   php artisan make:controller UserController
   ```

3. Implement logic in `UserController`:
   ```php
   public function showRegisterForm()
   {
       return view('auth.custom-register');
   }

   public function register(Request $request)
   {
       $request->validate([
           'name' => 'required',
           'email' => 'required|email|unique:users',
           'password' => 'required|min:6|confirmed',
       ]);

       User::create([
           'name' => $request->name,
           'email' => $request->email,
           'password' => bcrypt($request->password),
       ]);

       return redirect('/login')->with('success', 'Registration successful.');
   }
   ```

---

#### **Creating Custom Login**

1. Create login routes:
   ```php
   Route::get('/login', [UserController::class, 'showLoginForm']);
   Route::post('/login', [UserController::class, 'login']);
   ```

2. Implement logic in `UserController`:
   ```php
   public function showLoginForm()
   {
       return view('auth.custom-login');
   }

   public function login(Request $request)
   {
       $credentials = $request->only('email', 'password');

       if (Auth::attempt($credentials)) {
           return redirect('/dashboard');
       }

       return back()->withErrors(['email' => 'Invalid credentials.']);
   }
   ```

---

#### **Using Middleware for Route Protection**

1. Apply `auth` middleware to secure routes:
   ```php
   Route::get('/dashboard', function () {
       return view('dashboard');
   })->middleware('auth');
   ```

2. Redirect unauthorized users:
   - If not logged in, users will be redirected to `/login`.

---

### **4. Conclusion**

This document explains how to implement user authentication using Laravel’s built-in tools and a custom approach. By utilizing Blade templates, routes, and middleware, you can secure your application while offering a customized user experience.

---
