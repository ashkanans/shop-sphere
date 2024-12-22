<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/contact', function () {
    return view('contact');
});

//Route::get('/products/{id}', function ($id) {
//    $products = [
//        1 => ['name' => 'Product 1', 'price' => '$19.99'],
//        2 => ['name' => 'Product 2', 'price' => '$29.99'],
//        3 => ['name' => 'Product 3', 'price' => '$39.99'],
//    ];
//
//    if (!isset($products[$id])) {
//        abort(404);
//    }
//
//    return view('product', ['product' => $products[$id]]);
//});

Route::resource('products', ProductController::class);

Route::resource('categories', CategoryController::class);
Route::resource('products', ProductController::class);
Route::resource('orders', OrderController::class);
