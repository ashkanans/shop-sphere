@extends('layouts.base')

@section('title', $product->exists ? 'Edit Product' : 'Create Product')

@section('content')
    <h1>{{ $product->exists ? 'Edit' : 'Create' }} Product</h1>
    <form method="POST" action="{{ $product->exists ? route('products.update', $product) : route('products.store') }}">
        @csrf
        @if ($product->exists)
            @method('PUT')
        @endif

        <label>Name</label>
        <input type="text" name="name" value="{{ old('name', $product->name) }}" required>

        <label>Description</label>
        <textarea name="description">{{ old('description', $product->description) }}</textarea>

        <label>Price</label>
        <input type="number" name="price" value="{{ old('price', $product->price) }}" required>

        <label>Stock</label>
        <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required>

        <button type="submit">Save</button>
    </form>
@endsection
