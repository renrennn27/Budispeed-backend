<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Product::orderBy('created_at', 'desc')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'marketplace_url' => 'nullable|url',
            'rating' => 'nullable|numeric',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', 
        ]);

        $imageUrl = null;

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('products', 'public');
            
            $imageUrl = Storage::url($path);
        }

        $product = Product::create([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'description' => $validated['description'],
            'marketplace_url' => $validated['marketplace_url'],
            'rating' => $validated['rating'] ?? null,
            'image_url' => $imageUrl,
        ]);

        return response()->json($product, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return $product;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'marketplace_url' => 'nullable|url',
            'rating' => 'nullable|numeric',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $dataToUpdate = $request->except('image_file');

        if ($request->hasFile('image_file')) {
            if ($product->image_url) {
               $oldPath = str_replace(Storage::url(''), '', $product->image_url);
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('image_file')->store('products', 'public');
            $dataToUpdate['image_url'] = Storage::url($path);
        }

        $product->update($dataToUpdate);

        return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if ($product->image_url) {
            $path = str_replace(Storage::url(''), '', $product->image_url);
            Storage::disk('public')->delete($path);
        }

        $product->delete();

        return response()->json(null, 204);
    }
}
