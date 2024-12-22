<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopSphere</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
<body>
<header class="header">
    <div class="container">
        <div class="logo">ShopSphere</div>
        <button class="menu-toggle" aria-label="Toggle Navigation">
            ☰
        </button>
        <nav>
            <ul class="nav-menu">
                <li><a href="#">Home</a></li>
                <li><a href="#">Products</a></li>
                <li><a href="#">Contact</a></li>
                <li><a href="#">About</a></li>
            </ul>
        </nav>
    </div>
</header>

<section class="hero">
    <div class="container">
        <h1>Welcome to ShopSphere</h1>
        <p>Your one-stop shop for everything you need.</p>
        <button class="cta-button">Shop Now</button>
    </div>
</section>

<main class="content">
    <section class="featured">
        <h2>Featured Products</h2>
        <div class="grid">
            <div class="product-card">
                <img src="{{ Vite::asset('resources/images/icons8-product-50-low.png') }}" alt="Product 1">
                <h3>Product 1</h3>
                <p>$19.99</p>
            </div>
            <div class="product-card">
                <img src="{{ Vite::asset('resources/images/icons8-product-64-mid.png') }}" alt="Product 2">
                <h3>Product 2</h3>
                <p>$29.99</p>
            </div>
            <div class="product-card">
                <img src="{{ Vite::asset('resources/images/icons8-product-50-high.png') }}" alt="Product 3">

                <h3>Product 3</h3>
                <p>$39.99</p>
            </div>
        </div>
    </section>
</main>

<footer class="footer">
    <div class="container">
        <p>&copy; 2024 ShopSphere. All rights reserved.</p>
    </div>
</footer>
</body>
</html>
