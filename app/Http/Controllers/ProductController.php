<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Services\ProductService;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        return Product::all();
    }

    public function store(Request $request)
    {
        try {

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|integer|min:0',
                'stock' => 'required|integer|min:0',
                'description' => 'nullable|string'
            ]);

            $product = $this->productService->createProduct($validated);

            return $product;

        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Terjadi kesalahan',
                'error' => $e->getMessage()
            ], 500);

        }
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $product->update($request->all());

        return $product;
    }

    public function destroy($id)
{
    $product = Product::findOrFail($id);

    $product->delete();

    return response()->json([
        'message' => 'Produk berhasil dihapus'
    ]);
}
}