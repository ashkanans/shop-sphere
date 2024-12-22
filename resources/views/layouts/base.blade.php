<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - ShopSphere</title>
    @vite('resources/css/app.css')
</head>
<body>
<x-navbar />
<main class="container">
    @yield('content')
</main>
<x-footer />
@vite('resources/js/app.js')
</body>
</html>
