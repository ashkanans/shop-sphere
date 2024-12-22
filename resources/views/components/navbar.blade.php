<header class="header">
    <div class="container">
        <div class="logo">ShopSphere</div>
        <nav>
            <ul class="nav-menu">
                <li><a href="/" class="{{ request()->is('/') ? 'active' : '' }}">Home</a></li>
                <li><a href="/products" class="{{ request()->is('products') ? 'active' : '' }}">Products</a></li>
                <li><a href="/contact" class="{{ request()->is('contact') ? 'active' : '' }}">Contact</a></li>
                <li><a href="/about" class="{{ request()->is('about') ? 'active' : '' }}">About</a></li>
            </ul>
        </nav>
    </div>
</header>
