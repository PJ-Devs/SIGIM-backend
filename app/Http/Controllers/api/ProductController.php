<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\helpers\RoleAuthorizationHelper;
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
    protected $roleAuthorizationHelper;

    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->roleAuthorizationHelper = new RoleAuthorizationHelper();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $enterpriseId = $user->enterprise_id;
        if (!$this->roleAuthorizationHelper->hasPermission($user->role, 'product.read')) {
            return response()->json(['message' => 'No estás autorizado para realizar esta acción.'], 401);
        }

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

        if ($status = $request->query('status')) {
            switch ($status) {
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
                    $products->where('stock', 0);
                    break;
            }
        } else {
            $products->where('status', 'available');
        }

        if ($categoryId = $request->query('category')) {
            $products->where('category_id', $categoryId);
        }

        return new ProductCollection($products->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductStoreRequest $request)
    {
        $user = $request->user();
        $enterpriseId = $user->enterprise_id;

        $category = Category::find($request->category_id);
        if ($category->enterprise_id != $enterpriseId) {
            return response()->json(['message' => 'No estás autorizado para realizar esta acción.'], 401);
        }

        if (!$this->roleAuthorizationHelper->hasPermission($user->role, 'product.create')) {
            return response()->json(['message' => 'No estás autorizado para realizar esta acción.'], 401);
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
            $imgPath = 'public/product_thumbnails/' . $fileName;
            Storage::put($imgPath, file_get_contents($img));
            $product->update(['thumbnail' => "storage/product_thumbnails/" . $fileName]);
        }

        return response()->json(['data' => new ProductResource($product)], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Product $product)
    {
        $user = $request->user();
        $enterpriseId = $user->enterprise_id;
        if ($product->enterprise_id != $enterpriseId) {
            return response()->json(['message' => 'No estás autorizado para realizar esta acción.'], 401);
        }

        if (!$this->roleAuthorizationHelper->hasPermission($user->role, 'product.read')) {
            return response()->json(['message' => 'No estás autorizado para realizar esta acción.'], 401);
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
        $user = $request->user();
        $enterpriseId = $user->enterprise_id;
        if ($product->enterprise_id !== $enterpriseId) {
            return response()->json(['message' => 'No estás autorizado para realizar esta acción.'], 401);
        }

        if (!$this->roleAuthorizationHelper->hasPermission($user->role, 'product.update')) {
            return response()->json(['message' => 'No estás autorizado para realizar esta acción.'], 401);
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
                        return response()->json(['message' => 'No hay suficiente stock'], 400);
                    }
                    $product->stock -= $stockChange;
                }
                $updateData['stock'] = $product->stock;
                if ($updateData['stock'] === 0) {
                    $updateData['status'] = 'unavailable';
                }
            } else {
                $updateData['stock'] = $newStock;
            }

            if ($request->hasFile('thumbnail')) {
                // Eliminar el archivo existente si lo hay
                if ($product->thumbnail && Storage::exists(str_replace('storage/', 'public/', $product->thumbnail))) {
                    Storage::delete(str_replace('storage/', 'public/', $product->thumbnail));
                }

                // Guardar el nuevo archivo
                $img = $request->file('thumbnail');
                $fileName = uniqid($product->id, false) . '.' . $img->getClientOriginalExtension();
                $imgPath = 'public/product_thumbnails/' . $fileName;
                Storage::put($imgPath, file_get_contents($img));
                $updateData['thumbnail'] = "storage/product_thumbnails/" . $fileName;
            }

            $product->update($updateData);
            DB::commit();
            return response()->json(['data' => new ProductResource($product)], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'No se pudo actualizar el producto'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Product $product)
    {
        $user = $request->user();
        $enterpriseId = $user->enterprise_id;
        if ($product->enterprise_id != $enterpriseId) {
            return response()->json(['message' => 'No estás autorizado para realizar esta acción.'], 401);
        }

        if (!$this->roleAuthorizationHelper->hasPermission($user->role, 'product.delete')) {
            return response()->json(['message' => 'No estás autorizado para realizar esta acción.'], 401);
        }

        $product->update(['status' => 'deleted']);
        return response(null, 204);
    }
}
