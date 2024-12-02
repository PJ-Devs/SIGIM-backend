<?php

namespace App\Http\Controllers\api;

use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Category;
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
        $products = Product::where('enterprise_id', $enterpriseId)
            ->where('status', '!=', 'deleted')
            ->orderBy('is_favorite', 'desc')
            ->orderBy('id', 'desc');

        if ($request->query('search')) {
            $searchTerm = '%' . $request->query('search') . '%';
            $products->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', $searchTerm)
                    ->orWhere('description', 'like', $searchTerm);
            });
        }

        if ($request->query('status')) {
            switch ($request->query('status')) {
                case 'available':
                    $products->where('status', 'available');
                    break;
                case 'unavailable':
                    $products->where('status', 'unavailable');
                    break;
                case 'low_stock':
                    $products->whereColumn('stock', '<=', 'minimal_safe_stock');
                    break;
                case 'out_of_stock':
                    $products->whereColumn('stock', '=', '0');
                    break;
            }
        }

        if ($request->query('categor')) {
            $products->where('category_id', $request->query('category'));
        }

        return new ProductCollection($products->paginate(10));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductStoreRequest $request)
    {
        $enterpriseId = $request->user()->enterprise_id;

        $category = Category::find($request->category_id);
        if ($category->enterprise_id != $enterpriseId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'sale_price' => $request->sale_price,
            'supplier_price' => $request->supplier_price,
            'stock' => $request->stock,
            'minimal_safe_stock' => $request->minimal_safe_stock,
            'discount' => $request->discount,
            'enterprise_id' => $enterpriseId,
            'category_id' => $request->category_id,
            'supplier_id' => $request->supplier_id,
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

        if ($product->status !== 'available') {
            if (!$request->status) {
                return response()->json(['error' => 'A product which is not available cant be updated.'], 400);
            }

            $product->update(['status' => $request->status]);
            return response()->json(['data' => new ProductResource($product)], 200);
        }

        $updateData = $request->except('thumbnail', 'stock_change', 'added_stock', 'stock');

        DB::beginTransaction();
        try {
            $stockChange = $request->stock_change;
            $isAddedStock = $request->added_stock; // true if added, false if decreased
            $newStock = $request->stock;

            if (!$newStock) {
                if ($isAddedStock) {
                    $product->stock += $stockChange;
                } else {
                    if ($product->stock < $stockChange) {
                        return response()->json(['error' => 'Not enough stock'], 400);
                    }
                    $product->stock -= $stockChange;
                }

                $updateData['stock'] = $product->stock;
            } else {
                $updateData['stock'] = $newStock;
            }


            if ($request->hasFile('thumbnail')) {
                $img = $request->file('thumbnail');
                $fileName = uniqid($product->id, false) . '.' . $img->getClientOriginalExtension();
                $imgPath = 'product_thumbnails/' . $fileName;
                Storage::put($imgPath, file_get_contents($img));
                $updateData['thumbnail'] = $imgPath;
            }

            $product->update($updateData);
            DB::commit();
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
