@foreach ($products as $product)
    <tr>
        <td>{{ $product->id }}</td>
        <td>{{ $product->name }}</td>
        <td>{{ $product->description }}</td>
        <td>${{ number_format($product->price, 2) }}</td>
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
