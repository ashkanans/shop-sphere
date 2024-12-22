@extends('layouts.base')

@section('title', $product->name)

@section('content')
    <h1>{{ $product->name }}</h1>
    <p>{{ $product->description }}</p>
    <p>Price: ${{ $product->price }}</p>
    <p>Stock: {{ $product->stock }}</p>
    <a href="{{ route('products.index') }}">Back to Products</a>
@endsection
