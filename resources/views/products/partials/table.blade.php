@foreach ($products as $product)
    <tr>
        <td><a href="{{ route('products.show', $product) }}" title="View Product Details">{{ $product->id }}</a></td>
        <td contenteditable="true" class="editable" data-id="{{ $product->id }}" data-field="name">{{ $product->name }}</td>
        <td>{{ $product->description }}</td>
        <td>${{ number_format($product->price, 2) }}</td>
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
@endforeach
