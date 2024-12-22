@extends('layouts.base')

@section('title', 'Products')

@section('content')
    <h1>Products</h1>
    <div style="margin-bottom: 20px; text-align: right;">
        <a href="{{ route('products.create') }}" class="btn btn-primary">+ Create Product</a>
    </div>

    <!-- Product Table -->
    <table border="1" cellpadding="10" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Category</th>
            <th>Specifications</th>
            <th>Manufacturer</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($products as $product)
            <tr>
                <td>
                    <a href="{{ route('products.show', $product) }}" title="View Product Details">
                        {{ $product->id }}
                    </a>
                </td>
                <td contenteditable="true" class="editable" data-id="{{ $product->id }}" data-field="name">
                    {{ $product->name }}
                </td>
                <td>{{ $product->description }}</td>
                <td contenteditable="true" class="editable" data-id="{{ $product->id }}" data-field="price">
                    {{ $product->price }}
                </td>

                <td>{{ $product->stock }}</td>
                <td>{{ $product->category->name ?? 'N/A' }}</td>
                <td>{{ $product->productDetail->specifications ?? 'N/A' }}</td>
                <td>{{ $product->productDetail->manufacturer ?? 'N/A' }}</td>
                <td>
                    <div class="action-buttons" style="display: flex; gap: 5px;">
                        <!-- Edit Button -->
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-warn" style="padding: 5px 10px; background-color: #FFC107; color: white; text-decoration: none; border-radius: 3px;">Edit</a>

                        <!-- Delete Button -->
                        <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 5px 10px; background-color: #DC3545; color: white; border: none; border-radius: 3px; cursor: pointer;">
                                Delete
                            </button>
                        </form>


                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <!-- Pagination Links -->
    <div style="margin-top: 20px; text-align: center;">
        {{ $products->links() }}
    </div>

@endsection
@section('scripts')
    <script>
        $(document).on('blur', '.editable', function () {
            let id = $(this).data('id');
            let field = $(this).data('field');
            let value = $(this).text();

            $.ajax({
                url: `/products/${id}`,
                type: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    [field]: value,
                },
                success: function (response) {
                    alert('Product updated successfully!');
                },
                error: function (xhr) {
                    alert('Failed to update product.');
                }
            });
        });


    </script>
@endsection
