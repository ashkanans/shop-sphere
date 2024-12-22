### **Document: Implementing User Role Management with JWT Authentication in Laravel APIs**

---

### **Table of Contents**

1. [**Overview**](#1-overview)  
   - [What is JWT?](#what-is-jwt)  
   - [Why Use JWT for APIs?](#why-use-jwt-for-apis)  

2. [**Setting Up Laravel with JWT Authentication**](#2-setting-up-laravel-with-jwt-authentication)  
   - [Install JWT Package](#install-jwt-package)  
   - [Generate JWT Secret Key](#generate-jwt-secret-key)  
   - [Update User Model for JWT](#update-user-model-for-jwt)  

3. [**Implementing User Role Management**](#3-implementing-user-role-management)  
   - [Database Schema for Roles and Permissions](#database-schema-for-roles-and-permissions)  
   - [Assigning Roles to Users](#assigning-roles-to-users)  
   - [Middleware for Role-Based Access Control](#middleware-for-role-based-access-control)  

4. [**API Implementation with JWT and Role Management**](#4-api-implementation-with-jwt-and-role-management)  
   - [Login and Token Generation](#login-and-token-generation)  
   - [Protecting Routes with JWT and Roles](#protecting-routes-with-jwt-and-roles)  
   - [Role-Specific API Endpoints](#role-specific-api-endpoints)  

5. [**Advanced Features**](#5-advanced-features)  
   - [Refresh Tokens](#refresh-tokens)  
   - [Token Blacklisting](#token-blacklisting)  
   - [Logout API](#logout-api)  

---

### **1. Overview**

#### **What is JWT?**
JWT (JSON Web Token) is a compact, self-contained method for securely transmitting information between parties as a JSON object. It’s commonly used for API authentication.

#### **Why Use JWT for APIs?**
- Stateless authentication, no need to store sessions.
- Compact and efficient for transmitting user data.
- Secure and widely supported in modern web and mobile applications.

---

### **2. Setting Up Laravel with JWT Authentication**

---

#### **Install JWT Package**

1. Install the `tymon/jwt-auth` package:
   ```bash
   composer require tymon/jwt-auth
   ```

2. Publish the JWT configuration file:
   ```bash
   php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
   ```

---

#### **Generate JWT Secret Key**

Run the following command to generate a secret key:
```bash
php artisan jwt:secret
```
This will update your `.env` file with:
```env
JWT_SECRET=your_generated_secret_key
```

---

#### **Update User Model for JWT**

1. Implement `JWTSubject` in the `User` model:
   ```php
   use Tymon\JWTAuth\Contracts\JWTSubject;

   class User extends Authenticatable implements JWTSubject
   {
       public function getJWTIdentifier()
       {
           return $this->getKey();
       }

       public function getJWTCustomClaims()
       {
           return [];
       }
   }
   ```

---

### **3. Implementing User Role Management**

---

#### **Database Schema for Roles and Permissions**

Create the migration for roles and permissions:
```bash
php artisan make:migration create_roles_and_permissions_tables
```

**Migration Example**:
```php
Schema::create('roles', function (Blueprint $table) {
    $table->id();
    $table->string('name')->unique();
    $table->timestamps();
});

Schema::create('permissions', function (Blueprint $table) {
    $table->id();
    $table->string('name')->unique();
    $table->timestamps();
});

Schema::create('role_user', function (Blueprint $table) {
    $table->id();
    $table->foreignId('role_id')->constrained()->onDelete('cascade');
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->timestamps();
});

Schema::create('permission_role', function (Blueprint $table) {
    $table->id();
    $table->foreignId('permission_id')->constrained()->onDelete('cascade');
    $table->foreignId('role_id')->constrained()->onDelete('cascade');
    $table->timestamps();
});
```

---

#### **Assigning Roles to Users**

1. **Define Relationships**:
   **User Model**:
   ```php
   public function roles()
   {
       return $this->belongsToMany(Role::class, 'role_user');
   }

   public function hasRole($role)
   {
       return $this->roles->contains('name', $role);
   }
   ```

2. **Assign a Role**:
   ```php
   $user->roles()->attach($roleId);
   ```

---

#### **Middleware for Role-Based Access Control**

1. **Create Middleware**:
   ```bash
   php artisan make:middleware RoleMiddleware
   ```

2. **Middleware Logic**:
   ```php
   public function handle($request, Closure $next, $role)
   {
       if (!$request->user() || !$request->user()->hasRole($role)) {
           return response()->json(['error' => 'Unauthorized'], 403);
       }

       return $next($request);
   }
   ```

3. **Register Middleware**:
   Add to `Kernel.php`:
   ```php
   protected $routeMiddleware = [
       'role' => \App\Http\Middleware\RoleMiddleware::class,
   ];
   ```

---

### **4. API Implementation with JWT and Role Management**

---

#### **Login and Token Generation**

1. **AuthController**:
   ```php
   use Tymon\JWTAuth\Facades\JWTAuth;

   public function login(Request $request)
   {
       $credentials = $request->only('email', 'password');

       if (!$token = JWTAuth::attempt($credentials)) {
           return response()->json(['error' => 'Invalid credentials'], 401);
       }

       return response()->json(['token' => $token]);
   }
   ```

2. **Route**:
   ```php
   Route::post('/login', [AuthController::class, 'login']);
   ```

---

#### **Protecting Routes with JWT and Roles**

1. **Secure Routes**:
   ```php
   Route::middleware(['auth:api', 'role:admin'])->group(function () {
       Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
   });
   ```

2. **Middleware Logic**:
   Combine JWT authentication and role verification.

---

#### **Role-Specific API Endpoints**

1. **Example Endpoint for Admin**:
   ```php
   public function manageUsers()
   {
       $users = User::all();
       return response()->json($users);
   }
   ```

2. **Route**:
   ```php
   Route::middleware(['auth:api', 'role:admin'])->get('/admin/users', [AdminController::class, 'manageUsers']);
   ```

---

### **5. Advanced Features**

---

#### **Refresh Tokens**

1. **Add Refresh Logic**:
   ```php
   public function refresh()
   {
       $newToken = JWTAuth::refresh();
       return response()->json(['token' => $newToken]);
   }
   ```

2. **Route**:
   ```php
   Route::post('/refresh', [AuthController::class, 'refresh']);
   ```

---

#### **Token Blacklisting**

1. **Enable Blacklisting**:
   In `config/jwt.php`, set:
   ```php
   'blacklist_enabled' => true,
   ```

2. **Blacklist a Token on Logout**:
   ```php
   public function logout()
   {
       JWTAuth::invalidate(JWTAuth::getToken());
       return response()->json(['message' => 'Logged out successfully']);
   }
   ```

---

#### **Logout API**

1. **Logout Logic**:
   ```php
   public function logout()
   {
       JWTAuth::invalidate(JWTAuth::getToken());
       return response()->json(['message' => 'Successfully logged out']);
   }
   ```

2. **Route**:
   ```php
   Route::post('/logout', [AuthController::class, 'logout']);
   ```

---

### **Complete Implementation Summary**

1. **JWT Authentication**:
   - Set up JWT for stateless API authentication.
   - Implement login, refresh, and logout endpoints.
2. **Role-Based Access**:
   - Create roles, permissions, and middleware for protection.
   - Use role-based logic for endpoints and content visibility.
3. **Advanced Features**:
   - Token blacklisting and refresh for secure session management.
   - Protect routes with middleware for roles and permissions.

---
