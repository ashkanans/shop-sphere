### **Document: Advanced Database Schema Design in Laravel**

---

### **Table of Contents**

1. [**Overview**](#1-overview)  
   - [Introduction to Database Schema Design](#introduction-to-database-schema-design)  
   - [Normalization and Relationships](#normalization-and-relationships)  

2. [**Designing the Schema**](#2-designing-the-schema)  
   - [Schema for Products, Categories, and Users](#schema-for-products-categories-and-users)  
   - [Relationships Between Tables](#relationships-between-tables)  

3. [**Adding Relationships Using Eloquent**](#3-adding-relationships-using-eloquent)  
   - [Defining Relationships](#defining-relationships)  
   - [Code Examples for Eloquent Relationships](#code-examples-for-eloquent-relationships)  

4. [**Creating Seeders and Factories**](#4-creating-seeders-and-factories)  
   - [Creating Seeders](#creating-seeders)  
   - [Creating Factories](#creating-factories)  
   - [Populating the Database](#populating-the-database)  

5. [**Conclusion**](#5-conclusion)

---

### **1. Overview**

#### **Introduction to Database Schema Design**
Database schema design involves organizing data into tables and defining relationships between them. It ensures data integrity and improves query efficiency.

#### **Normalization and Relationships**
Normalization eliminates redundancy by dividing data into tables. Relationships connect these tables logically:
- **One-to-Many**: A category has many products.
- **Many-to-Many**: A user can have many products in their cart, and a product can belong to many users.

---

### **2. Designing the Schema**

#### **Schema for Products, Categories, and Users**

1. **Categories Table**
   ```php
   Schema::create('categories', function (Blueprint $table) {
       $table->id();
       $table->string('name');
       $table->timestamps();
   });
   ```

2. **Products Table**
   ```php
   Schema::create('products', function (Blueprint $table) {
       $table->id();
       $table->string('name');
       $table->text('description')->nullable();
       $table->decimal('price', 8, 2);
       $table->foreignId('category_id')->constrained()->onDelete('cascade');
       $table->timestamps();
   });
   ```

3. **Users Table**
   ```php
   Schema::create('users', function (Blueprint $table) {
       $table->id();
       $table->string('name');
       $table->string('email')->unique();
       $table->string('password');
       $table->timestamps();
   });
   ```

4. **User_Product Pivot Table (for Many-to-Many Relationship)**
   ```php
   Schema::create('user_product', function (Blueprint $table) {
       $table->id();
       $table->foreignId('user_id')->constrained()->onDelete('cascade');
       $table->foreignId('product_id')->constrained()->onDelete('cascade');
       $table->integer('quantity');
       $table->timestamps();
   });
   ```

---

### **3. Adding Relationships Using Eloquent**

#### **Defining Relationships**

1. **One-to-Many (Category to Products)**
   - Category Model:
     ```php
     public function products()
     {
         return $this->hasMany(Product::class);
     }
     ```
   - Product Model:
     ```php
     public function category()
     {
         return $this->belongsTo(Category::class);
     }
     ```

2. **Many-to-Many (Users to Products through Cart)**
   - User Model:
     ```php
     public function products()
     {
         return $this->belongsToMany(Product::class, 'user_product')->withPivot('quantity')->withTimestamps();
     }
     ```
   - Product Model:
     ```php
     public function users()
     {
         return $this->belongsToMany(User::class, 'user_product')->withPivot('quantity')->withTimestamps();
     }
     ```

#### **Code Examples for Eloquent Relationships**

**Fetching Products for a Category**
```php
$category = Category::find(1);
$products = $category->products;
```

**Adding a Product to a User's Cart**
```php
$user = User::find(1);
$product = Product::find(1);
$user->products()->attach($product->id, ['quantity' => 2]);
```

**Retrieving Users Who Purchased a Product**
```php
$product = Product::find(1);
$users = $product->users;
```

---

### **4. Creating Seeders and Factories**

#### **Creating Seeders**

1. Generate a seeder for categories:
   ```bash
   php artisan make:seeder CategorySeeder
   ```
2. Define sample data:
   ```php
   // database/seeders/CategorySeeder.php
   public function run()
   {
       Category::create(['name' => 'Electronics']);
       Category::create(['name' => 'Furniture']);
   }
   ```

3. Run the seeder:
   ```bash
   php artisan db:seed --class=CategorySeeder
   ```

---

#### **Creating Factories**

1. Generate factories:
   ```bash
   php artisan make:factory ProductFactory --model=Product
   php artisan make:factory UserFactory --model=User
   ```

2. Define factory logic:
   **ProductFactory:**
   ```php
   public function definition()
   {
       return [
           'name' => $this->faker->word,
           'description' => $this->faker->paragraph,
           'price' => $this->faker->randomFloat(2, 10, 1000),
           'category_id' => Category::factory(),
       ];
   }
   ```
   **UserFactory:**
   ```php
   public function definition()
   {
       return [
           'name' => $this->faker->name,
           'email' => $this->faker->unique()->safeEmail,
           'password' => bcrypt('password'),
       ];
   }
   ```

---

#### **Populating the Database**

1. Update the DatabaseSeeder:
   ```php
   public function run()
   {
       Category::factory(5)->create();
       User::factory(10)->create();
       Product::factory(50)->create();
   }
   ```

2. Run the database seeders:
   ```bash
   php artisan migrate:fresh --seed
   ```

---

### **5. Conclusion**

This document outlines the process of designing a normalized database schema in Laravel, defining relationships using Eloquent, and populating the database with seeders and factories. This approach ensures a well-structured database and simplifies backend operations.

---
