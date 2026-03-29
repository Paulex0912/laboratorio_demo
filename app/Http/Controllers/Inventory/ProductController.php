<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\InventoryMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();
        return view('inventory.products.index', compact('products'));
    }

    public function create()
    {
        $categories = ProductCategory::all();
        return view('inventory.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:products',
            'name' => 'required',
            'unit_measure' => 'required',
            'stock_current' => 'numeric|min:0',
            'stock_min' => 'numeric|min:0',
            'stock_max' => 'numeric|min:0',
            'cost_price' => 'numeric|min:0',
            'category_id' => 'nullable|exists:product_categories,id',
        ]);

        DB::transaction(function () use ($validated) {
            $product = Product::create($validated);

            if ($validated['stock_current'] > 0) {
                InventoryMovement::create([
                    'product_id' => $product->id,
                    'type' => 'in',
                    'quantity' => $validated['stock_current'],
                    'unit_cost' => $validated['cost_price'] ?? 0,
                    'user_id' => Auth::id() ?? 1, // Fallback for tests
                    'date' => now()
                ]);
            }
        });

        return redirect()->route('inventory.products.index')->with('success', 'Producto registrado correctamente.');
    }

    public function show(Product $product)
    {
        return view('inventory.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = ProductCategory::all();
        return view('inventory.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'code' => 'required|unique:products,code,' . $product->id,
            'name' => 'required',
            'unit_measure' => 'required',
            'stock_min' => 'numeric|min:0',
            'stock_max' => 'numeric|min:0',
            'category_id' => 'nullable|exists:product_categories,id',
        ]);

        $product->update($validated);
        return redirect()->route('inventory.products.index')->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('inventory.products.index')->with('success', 'Producto eliminado.');
    }
}
