<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'productDetail'])->paginate(10);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $product = new Product();
        return view('products.create', compact('product'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        // Load related data
        $product->load('category', 'productDetail');

        return view('products.show', compact('product'));
    }


    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        // Define validation rules
        $rules = [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'stock' => 'sometimes|required|integer|min:0',
            'category_id' => 'sometimes|required|exists:categories,id',
        ];

        // Validate only the fields present in the request
        $validated = $request->validate($rules);

        // Update only the fields provided
        $product->update($validated);

        return response()->json([
            'message' => 'Product updated successfully.',
            'product' => $product,
        ]);
    }


    public function destroy($product): \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
    {
        // Check if $product is an instance of Product or an ID
        if ($product instanceof Product) {
            // Direct model binding (e.g., route model binding)
            $product->delete();
        } else {
            // $product is an ID; find the product and delete it
            $product = Product::findOrFail($product);
            $product->delete();
        }

        // Handle response based on the request type (AJAX or normal HTTP request)
        if (request()->expectsJson()) {
            return response()->json([
                'message' => 'Product deleted successfully.'
            ]);
        }

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }


}
