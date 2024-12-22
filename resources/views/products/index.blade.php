@extends('layouts.base')

@section('title', 'Products')

@section('content')
    <h1>Products</h1>
    <div class="mb-4 text-right">
        <a href="{{ route('products.create') }}" class="btn btn-primary">+ Create Product</a>
    </div>
    <div style="display: flex; align-items: center; margin-bottom: 20px; margin-top: 20px;">
        <input type="text" id="search" placeholder="Search products by name or description" class="form-control" style="flex: 1; margin-right: 20px;">
        <button style="width: 50px; padding: 10px" class="btn btn-blue ml-2 search-btn ">Go</button>
    </div>

    <!-- Product Table -->
    <table class="table table-bordered table-striped w-100">
        <thead>
        <tr>
            <th data-column="id" data-order="asc" class="sortable">ID</th>
            <th data-column="name" data-order="asc" class="sortable">Name</th>
            <th data-column="description" data-order="asc">Description</th>
            <th data-column="price" data-order="asc" class="sortable">Price</th>
            <th data-column="stock" data-order="asc" class="sortable">Stock</th>
            <th data-column="category" data-order="asc">Category</th>
            <th data-column="specifications" data-order="asc">Specifications</th>
            <th data-column="manufacturer" data-order="asc">Manufacturer</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody id="product-table-body">
        @forelse ($products as $product)
            <tr>
                <td>
                    <a href="{{ route('products.show', $product) }}" title="View Product Details">{{ $product->id }}</a>
                </td>
                <td contenteditable="true" class="editable" data-id="{{ $product->id }}" data-field="name">{{ $product->name }}</td>
                <td>{{ $product->description }}</td>
                <td contenteditable="true" class="editable" data-id="{{ $product->id }}" data-field="price">{{ $product->price }}</td>
                <td>
                    <input type="number" class="stock-input form-control" value="{{ $product->stock }}" data-id="{{ $product->id }}">
                </td>
                <td>{{ $product->category->name ?? 'N/A' }}</td>
                <td>{{ $product->productDetail->specifications ?? 'N/A' }}</td>
                <td>{{ $product->productDetail->manufacturer ?? 'N/A' }}</td>
                <td>
                    <div class="d-flex gap-2">
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-green btn-sm">Edit</a>
                        <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                        <button class="btn btn-blue btn-sm delete-btn" data-id="{{ $product->id }}">Delete (AJAX)</button>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-center">No products available.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <!-- Pagination Links -->
    <div class="mt-4 text-center">
        {{ $products->links() }}
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            // Editable Fields
            $(document).on('blur', '.editable', function () {
                const id = $(this).data('id');
                const field = $(this).data('field');
                const value = $(this).text();

                $.ajax({
                    url: `/products/${id}`,
                    type: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}',
                        [field]: value,
                    },
                    success: function () {
                        alert('Product updated successfully!');
                    },
                    error: function () {
                        alert('Failed to update product.');
                    }
                });
            });

            // Stock Update
            $(document).on('change', '.stock-input', function () {
                const id = $(this).data('id');
                const value = $(this).val();

                $.ajax({
                    url: `/products/${id}`,
                    type: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}',
                        stock: value,
                    },
                    success: function () {
                        alert('Stock updated successfully!');
                    },
                    error: function () {
                        alert('Failed to update stock.');
                    }
                });
            });

            // AJAX Delete
            $(document).on('click', '.delete-btn', function () {
                const id = $(this).data('id');
                if (confirm('Are you sure you want to delete this product?')) {
                    $.ajax({
                        url: `/products/${id}`,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}',
                        },
                        success: function () {
                            $(`button[data-id="${id}"]`).closest('tr').remove();
                            alert('Product deleted successfully!');
                        },
                        error: function () {
                            alert('Failed to delete product.');
                        }
                    });
                }
            });

            // Search
            $(document).on('click', '.search-btn', function () {
                const query = $('#search').val();
                $.ajax({
                    url: '{{ route('products.search') }}',
                    type: 'GET',
                    data: { query },
                    success: function (data) {
                        $('#product-table-body').html(data.trim() || '<tr><td colspan="9" class="text-center">No products found.</td></tr>');
                    },
                    error: function () {
                        alert('Failed to fetch products.');
                    }
                });
            });

            // Sortable Columns
            $('.sortable').on('click', function () {
                const column = $(this).data('column');
                let order = $(this).data('order');
                order = order === 'asc' ? 'desc' : 'asc';
                $(this).data('order', order);

                $('.sortable').removeClass('sorted-asc sorted-desc');
                $(this).addClass(order === 'asc' ? 'sorted-asc' : 'sorted-desc');

                $.ajax({
                    url: '{{ route('products.index') }}',
                    type: 'GET',
                    data: { column, order },
                    success: function (data) {
                        $('#product-table-body').html(data);
                    },
                    error: function () {
                        alert('Failed to fetch sorted products.');
                    }
                });
            });
        });
    </script>
@endsection
