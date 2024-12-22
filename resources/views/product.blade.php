@extends('layouts.base')

@section('title', $product['name'])

@section('content')
    <h1>{{ $product['name'] }}</h1>
    <p>Price: {{ $product['price'] }}</p>
    <a href="/">Back to Home</a>
@endsection
