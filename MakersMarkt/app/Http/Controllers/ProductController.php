<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

class ProductController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Display a listing of the products.
     */
    public function index()
    {
        $products = Product::all();
        return view('maker.products.index', compact('products'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(ProductRequest $request)
    {
        $this->authorize('create', Product::class);

        $product = new Product($request->validated());
        $product->maker_id = Auth::id();
        $product->save();

        return response()->json($product, 201);
    }

    /**
     * Display the specified product.
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);

        $this->authorize('view', $product);

        return view('maker.products.show', compact('product'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(ProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);

        $this->authorize('update', $product);

        $product->fill($request->validated());
        $product->save();

        return response()->json($product);
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        $this->authorize('delete', $product);

        // $product->delete();

        return response()->json(null, 204);
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);

        $this->authorize('update', $product);

        return view('maker.products.edit', compact('product'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $this->authorize('create', Product::class);

        return view('maker.products.create');
    }
}
