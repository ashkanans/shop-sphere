### **Building a Basic Frontend with Blade in Laravel**

---
### **Table of Contents**

1. [**Overview**](#1-overview)  
   - [Introduction to Blade Templates](#introduction-to-blade-templates)  
   - [Benefits of Blade for Frontend Development](#benefits-of-blade-for-frontend-development)  

2. [**Creating a Basic HTML Template**](#2-creating-a-basic-html-template)  
   - [Structure: Header, Footer, and Navigation](#structure-header-footer-and-navigation)  
   - [Example: A Basic HTML Template](#example-a-basic-html-template)  

3. [**Styling with CSS**](#3-styling-with-css)  
   - [Using Flexbox and Grid for Responsive Design](#using-flexbox-and-grid-for-responsive-design)  
   - [Adding CSS to Blade Templates](#adding-css-to-blade-templates)  
   - [Example: Responsive Layout with Flexbox and Grid](#example-responsive-layout-with-flexbox-and-grid)  

4. [**Adding JavaScript for Interactivity**](#4-adding-javascript-for-interactivity)  
   - [Simple JavaScript for Navigation Toggle](#simple-javascript-for-navigation-toggle)  
   - [Example: A Blade Template with Interactive Navigation](#example-a-blade-template-with-interactive-navigation)  

5. [**Using Blade Components and Layouts**](#5-using-blade-components-and-layouts)  
   - [Creating a Reusable Navbar Component](#creating-a-reusable-navbar-component)  
   - [Using Layouts for Consistent Design](#using-layouts-for-consistent-design)  

6. [**Combining Components, Layouts, and Styles**](#6-combining-components-layouts-and-styles)  
   - [Example: Complete Page with Components and Layout](#example-complete-page-with-components-and-layout)  

7. [**Conclusion**](#7-conclusion)  


---

### **1. Overview**

#### **Introduction to Blade Templates**
Blade is Laravel's lightweight templating engine that allows embedding PHP code in views and enables template inheritance and reusable components.

---

### **2. Creating a Basic HTML Template**

#### **Structure: Header, Footer, and Navigation**

Example:

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Basic Template</title>
</head>
<body>
<header>
    <nav>
        <ul>
            <li><a href="/">Home</a></li>
            <li><a href="/about">About</a></li>
            <li><a href="/contact">Contact</a></li>
        </ul>
    </nav>
</header>

<main>
    <h1>Welcome to the Homepage</h1>
    <p>This is a basic HTML template.</p>
</main>

<footer>
    <p>&copy; 2024 My Website</p>
</footer>
</body>
</html>
```

---

### **3. Styling with CSS**

#### **Using Flexbox and Grid for Responsive Design**

Example:
```html
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }
    header nav ul {
        display: flex;
        justify-content: space-around;
        background-color: #333;
        padding: 1rem;
    }
    header nav ul li {
        list-style: none;
    }
    header nav ul li a {
        color: white;
        text-decoration: none;
    }
    main {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        padding: 20px;
    }
    footer {
        text-align: center;
        background-color: #f1f1f1;
        padding: 1rem;
    }
</style>
```

---

### **4. Adding JavaScript for Interactivity**

#### **Simple JavaScript for Navigation Toggle**

Example:
```html
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const navToggle = document.querySelector('.nav-toggle');
        const navMenu = document.querySelector('nav ul');

        navToggle.addEventListener('click', () => {
            navMenu.classList.toggle('active');
        });
    });
</script>
```

#### **Usage in Blade Template**
```html
<header>
    <button class="nav-toggle">Menu</button>
    <nav>
        <ul>
            <li><a href="/">Home</a></li>
            <li><a href="/about">About</a></li>
            <li><a href="/contact">Contact</a></li>
        </ul>
    </nav>
</header>
<style>
    nav ul {
        display: none;
        flex-direction: column;
    }
    nav ul.active {
        display: flex;
    }
</style>
```

---

### **5. Using Blade Components and Layouts**

#### **Creating a Reusable Navbar Component**

1. Create a new component:
   ```bash
   php artisan make:component Navbar
   ```
2. Update the component:
   ```php
   // resources/views/components/navbar.blade.php
   <nav>
       <ul>
           <li><a href="/">Home</a></li>
           <li><a href="/about">About</a></li>
           <li><a href="/contact">Contact</a></li>
       </ul>
   </nav>
   ```

3. Use the component in a view:
   ```php
   <!-- resources/views/home.blade.php -->
   <x-navbar />
   <main>
       <h1>Welcome</h1>
   </main>
   ```

#### **Using Layouts for Consistent Design**

1. Create a master layout:
   ```html
   <!-- resources/views/layouts/master.blade.php -->
   <!DOCTYPE html>
   <html lang="en">
   <head>
       <meta charset="UTF-8">
       <meta name="viewport" content="width=device-width, initial-scale=1.0">
       <title>@yield('title')</title>
   </head>
   <body>
       <x-navbar />
       <div class="container">
           @yield('content')
       </div>
       <footer>&copy; 2024 My Website</footer>
   </body>
   </html>
   ```

2. Extend the layout in a page:
   ```php
   <!-- resources/views/home.blade.php -->
   @extends('layouts.master')

   @section('title', 'Home')

   @section('content')
   <h1>Welcome to the Homepage</h1>
   <p>This content is specific to the homepage.</p>
   @endsection
   ```

---

### **6. Combining Components, Layouts, and Styles**

Complete Example:
```php
<!-- resources/views/components/navbar.blade.php -->
<nav>
    <ul>
        <li><a href="/">Home</a></li>
        <li><a href="/about">About</a></li>
        <li><a href="/contact">Contact</a></li>
    </ul>
</nav>

<!-- resources/views/layouts/master.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        nav ul {
            display: flex;
            justify-content: space-around;
            background-color: #333;
            padding: 1rem;
        }
        nav ul li {
            list-style: none;
        }
        nav ul li a {
            color: white;
            text-decoration: none;
        }
        .container {
            padding: 20px;
        }
        footer {
            text-align: center;
            background-color: #f1f1f1;
            padding: 1rem;
        }
    </style>
</head>
<body>
    <x-navbar />
    <div class="container">
        @yield('content')
    </div>
    <footer>&copy; 2024 My Website</footer>
</body>
</html>

<!-- resources/views/home.blade.php -->
@extends('layouts.master')

@section('title', 'Home')

@section('content')
<h1>Welcome</h1>
<p>This is a fully styled and interactive homepage using Blade components and layouts.</p>
@endsection
```

---
