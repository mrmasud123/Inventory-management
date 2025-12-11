<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Services\ProductImageService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::orderBy('created_at', 'desc')->get();

        return Inertia::render('products/Products', [
            'products' => $products
        ]);
    }


    public function store(StoreProductRequest $request, ProductImageService $images)
    {
        return DB::transaction(function () use ($request, $images) {
            $data = $request->validated();

            if ($request->hasFile('thumbnail')) {
                $data['thumbnail_path'] = $images->store($request->file('thumbnail'));
            }

            $product = Product::create($data);

            return response()->json([
                'message' => 'Product created successfully',
                'product' => $product
            ], 201);
        });
    }

    public function edit(Product $product)
    {
        return Inertia::render('products/EditProduct', [
            'product' => $product
        ]);
    }

    public function create()
    {
        return Inertia::render('products/CreateProducts');
    }

    public function show(Product $product)
    {
        return response()->json($product);
    }

    public function update(UpdateProductRequest $request, Product $product, ProductImageService $images)
    {
        return DB::transaction(function () use ($request, $product, $images) {
            $data = $request->validated();

            if ($request->hasFile('thumbnail')) {
                $data['thumbnail_path'] = $images->replace($product->thumbnail_path, $request->file('thumbnail'));
            }

            $product->update($data);

            return response()->json($product);
        });
    }


    public function destroy(Product $product, ProductImageService $images)
    {
        $product->delete();

        return response()->json(['message' => 'Archived.']);
    }


}
