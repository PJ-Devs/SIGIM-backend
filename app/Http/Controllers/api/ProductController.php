<?php

namespace App\Http\Controllers\api;

use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use Illuminate\Routing\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $enterpriseId = $request->user()->enterprise_id;
        // where('enterprise_id', $enterpriseId)
        $products = Product::where('status', 'available')
            ->orderBy('id', 'desc');

        if ($request->query('search')) {
            $searchTerm = '%' . $request->query('search') . '%';
            $products->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', $searchTerm)
                    ->orWhere('description', 'like', $searchTerm);
            });
        }

        return new ProductCollection($products->paginate(20));
    }


    public function indexLowStock(Request $request)
    {
        $enterpriseId = $request->user()->enterprise_id;
        $products = Product::where('enterprise_id', $enterpriseId)
            ->where('status', 'available')
            ->whereColumn('stock', '<=', 'minimal_safe_stock')
            ->orderBy('stock', 'asc');

        return new ProductCollection($products->get());
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductStoreRequest $request)
    {
        $enterpriseId = $request->user()->enterprise_id;
        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'sale_price' => $request->sale_price,
            'supplier_price' => $request->supplier_price,
            'stock' => $request->stock,
            'minimal_safe_stock' => $request->minimal_safe_stock,
            'category_id' => $request->category_id,
            'enterprise_id' => $enterpriseId,
        ]);

        if ($request->hasFile('thumbnail')) {
            $img = $request->file('thumbnail');
            $fileName = uniqid($product->id, false) . '.' . $img->getClientOriginalExtension();
            $imgPath = 'product_thumbnails/' . $fileName;
            Storage::put($imgPath, file_get_contents($img));
            $product->update(['thumbnail' => $imgPath]);
        }

        return response()->json(['data' => new ProductResource($product)], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(Request $request, Product $product)
    {
        $enterpriseId = $request->user()->enterprise_id;
        if ($product->enterprise_id != $enterpriseId) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json([
            'data' => new ProductResource($product)
        ], 200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(ProductUpdateRequest $request, Product $product)
    {
        $enterpriseId = $request->user()->enterprise_id;
        if ($product->enterprise_id !== $enterpriseId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        DB::beginTransaction();
        try {
            $addedStock = $request->added_stock;
            $decreasedStock = $request->decreased_stock;
            $newStock = $product->stock;

            if ($addedStock) {
                $newStock += $addedStock;
            }
            if ($decreasedStock) {
                if ($newStock < $decreasedStock) {
                    return response()->json(['error' => 'Not enough stock'], 400);
                }
                $newStock -= $decreasedStock;
            }

            $updateData = $request->except('thumbnail', 'added_stock', 'decreased_stock');
            $updateData['stock'] = $newStock;
            if ($newStock == 0) {
                $updateData['status'] = 'unavailable';
            }

            if ($request->hasFile('thumbnail')) {
                $img = $request->file('thumbnail');
                $fileName = uniqid($product->id, false) . '.' . $img->getClientOriginalExtension();
                $imgPath = 'product_thumbnails/' . $fileName;
                Storage::put($imgPath, file_get_contents($img));
                $updateData['thumbnail'] = $imgPath;
            }

            $product->update($updateData);
            return response()->json(['data' => new ProductResource($product)], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update product'], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Product $product)
    {
        $enterpriseId = $request->user()->enterprise_id;

        if ($product->enterprise_id != $enterpriseId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $product->update(['status' => 'deleted']);
        return response(null, 204);
    }
}
