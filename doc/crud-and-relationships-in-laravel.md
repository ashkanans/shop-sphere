### **Document: Building CRUD with Laravel and Exploring Relationships**

---

### **Table of Contents**

1. [**Overview**](#1-overview)  
   - [Introduction to CRUD in Laravel](#introduction-to-crud-in-laravel)  
   - [Understanding Eloquent Relationships](#understanding-eloquent-relationships)  

2. [**Creating Models and Migrations**](#2-creating-models-and-migrations)  
   - [Product Model (Basic CRUD)](#product-model-basic-crud)  
   - [Category Model (One-to-Many Relationship)](#category-model-one-to-many-relationship)  
   - [User and Profile Models (One-to-One Relationship)](#user-and-profile-models-one-to-one-relationship)  
   - [Order and Products (Many-to-Many Relationship)](#order-and-products-many-to-many-relationship)  

3. [**CRUD Operations with Controllers**](#3-crud-operations-with-controllers)  
   - [ProductController](#productcontroller)  
   - [CategoryController](#categorycontroller)  
   - [UserProfileController](#userprofilecontroller)  
   - [OrderController](#ordercontroller)  

4. [**Routes for CRUD Operations**](#4-routes-for-crud-operations)  
   - [Routes for Products](#routes-for-products)  
   - [Routes for Categories](#routes-for-categories)  
   - [Routes for User Profiles](#routes-for-user-profiles)  
   - [Routes for Orders](#routes-for-orders)  

5. [**Conclusion**](#5-conclusion)

---

### **1. Overview**

#### **Introduction to CRUD in Laravel**
CRUD (Create, Read, Update, Delete) is the foundation of many web applications. Laravel makes CRUD operations simple with Eloquent, its ORM.

#### **Understanding Eloquent Relationships**
Eloquent supports three main types of relationships:
- **One-to-One**: A user has one profile.
- **One-to-Many**: A category has many products.
- **Many-to-Many**: An order can contain many products, and a product can belong to many orders.

---

### **2. Creating Models and Migrations**

#### **Product Model (Basic CRUD)**

1. Generate the model and migration:
   ```bash
   php artisan make:model Product -m
   ```
2. Define the migration:
   ```php
   // database/migrations/xxxx_xx_xx_create_products_table.php
   public function up()
   {
       Schema::create('products', function (Blueprint $table) {
           $table->id();
           $table->string('name');
           $table->text('description')->nullable();
           $table->decimal('price', 8, 2);
           $table->timestamps();
       });
   }
   ```
3. Run the migration:
   ```bash
   php artisan migrate
   ```

---

#### **Category Model (One-to-Many Relationship)**

1. Generate the model and migration:
   ```bash
   php artisan make:model Category -m
   ```
2. Define the migration:
   ```php
   // database/migrations/xxxx_xx_xx_create_categories_table.php
   public function up()
   {
       Schema::create('categories', function (Blueprint $table) {
           $table->id();
           $table->string('name');
           $table->timestamps();
       });
   }
   ```
3. Add the foreign key in the products migration:
   ```php
   $table->foreignId('category_id')->constrained()->onDelete('cascade');
   ```

4. Define the relationship in the models:
   ```php
   // app/Models/Category.php
   public function products()
   {
       return $this->hasMany(Product::class);
   }

   // app/Models/Product.php
   public function category()
   {
       return $this->belongsTo(Category::class);
   }
   ```

---

#### **User and Profile Models (One-to-One Relationship)**

1. Generate the models and migrations:
   ```bash
   php artisan make:model Profile -m
   php artisan make:model User -m
   ```
2. Define the migration for `profiles`:
   ```php
   // database/migrations/xxxx_xx_xx_create_profiles_table.php
   public function up()
   {
       Schema::create('profiles', function (Blueprint $table) {
           $table->id();
           $table->foreignId('user_id')->constrained()->onDelete('cascade');
           $table->string('bio')->nullable();
           $table->string('profile_picture')->nullable();
           $table->timestamps();
       });
   }
   ```
3. Define the relationships:
   ```php
   // app/Models/User.php
   public function profile()
   {
       return $this->hasOne(Profile::class);
   }

   // app/Models/Profile.php
   public function user()
   {
       return $this->belongsTo(User::class);
   }
   ```

---

#### **Order and Products (Many-to-Many Relationship)**

1. Generate the models and pivot table migration:
   ```bash
   php artisan make:model Order -m
   php artisan make:migration create_order_product_table
   ```
2. Define the pivot table:
   ```php
   // database/migrations/xxxx_xx_xx_create_order_product_table.php
   public function up()
   {
       Schema::create('order_product', function (Blueprint $table) {
           $table->id();
           $table->foreignId('order_id')->constrained()->onDelete('cascade');
           $table->foreignId('product_id')->constrained()->onDelete('cascade');
           $table->timestamps();
       });
   }
   ```
3. Define the relationships:
   ```php
   // app/Models/Order.php
   public function products()
   {
       return $this->belongsToMany(Product::class, 'order_product');
   }

   // app/Models/Product.php
   public function orders()
   {
       return $this->belongsToMany(Order::class, 'order_product');
   }
   ```

---

### **3. CRUD Operations with Controllers**

#### **ProductController**
1. Create the controller:
   ```bash
   php artisan make:controller ProductController --resource
   ```
2. Define CRUD methods in `ProductController`:
   ```php
   public function index()
   {
       return Product::all();
   }

   public function store(Request $request)
   {
       return Product::create($request->all());
   }

   public function show(Product $product)
   {
       return $product;
   }

   public function update(Request $request, Product $product)
   {
       $product->update($request->all());
       return $product;
   }

   public function destroy(Product $product)
   {
       $product->delete();
       return response()->noContent();
   }
   ```

---

### **4. Routes for CRUD Operations**

#### **Routes for Products**
```php
use App\Http\Controllers\ProductController;

Route::resource('products', ProductController::class);
```

#### **Routes for Categories**
```php
use App\Http\Controllers\CategoryController;

Route::resource('categories', CategoryController::class);
```

#### **Routes for User Profiles**
```php
use App\Http\Controllers\UserProfileController;

Route::get('users/{user}/profile', [UserProfileController::class, 'show']);
Route::post('users/{user}/profile', [UserProfileController::class, 'store']);
Route::put('users/{user}/profile', [UserProfileController::class, 'update']);
Route::delete('users/{user}/profile', [UserProfileController::class, 'destroy']);
```

#### **Routes for Orders**
```php
use App\Http\Controllers\OrderController;

Route::resource('orders', OrderController::class);
```

---

### **5. Conclusion**

This setup demonstrates CRUD operations and includes various relationships (one-to-one, one-to-many, and many-to-many). Laravel's Eloquent simplifies these interactions, enabling you to build robust backends efficiently.

---
