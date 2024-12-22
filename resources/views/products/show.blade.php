@extends('layouts.base')

@section('title', $product->name)

@section('content')
    <h1>{{ $product->name }}</h1>
    <p><strong>Description:</strong> {{ $product->description }}</p>
    <p><strong>Price:</strong> ${{ number_format($product->price, 2) }}</p>
    <p><strong>Stock:</strong> {{ $product->stock }}</p>
    <p><strong>Category:</strong> {{ $product->category->name ?? 'N/A' }}</p>
    <p><strong>Specifications:</strong> {{ $product->productDetail->specifications ?? 'N/A' }}</p>
    <p><strong>Manufacturer:</strong> {{ $product->productDetail->manufacturer ?? 'N/A' }}</p>
    <a href="{{ route('products.index') }}" class="btn btn-primary">Back to Products</a>
@endsection
