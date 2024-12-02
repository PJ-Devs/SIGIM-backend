<?php

namespace App\Http\Controllers\api;

use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use Illuminate\Routing\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
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
        $statusSearch = $request->query('status');
        $categories = Category::where('enterprise_id', $enterpriseId)
            ->where('status', $statusSearch ?? 'available')
            ->orderBy('id', 'desc');

        if ($request->query('search')) {
            $searchTerm = '%' . $request->query('search') . '%';
            $categories->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', $searchTerm)
                    ->orWhere('description', 'like', $searchTerm);
            });
        }

        return new CategoryCollection($categories->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryStoreRequest $request)
    {
        $enterpriseId = $request->user()->enterprise_id;
        $category = Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'enterprise_id' => $enterpriseId,
        ]);

        return response()->json([
            'data' => new CategoryResource($category),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Category $category)
    {
        $enterpriseId = $request->user()->enterprise_id;
        if ($category->enterprise_id !== $enterpriseId) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json([
            'data' => new CategoryResource($category),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryUpdateRequest $request, Category $category)
    {
        $enterpriseId = $request->user()->enterprise_id;
        if ($category->enterprise_id !== $enterpriseId) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $category->update($request->validated());

        return response()->json([
            'data' => new CategoryResource($category),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Category $category)
    {
        $enterpriseId = $request->user()->enterprise_id;
        if ($category->enterprise_id !== $enterpriseId) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if ($category->status === 'available') {
            $category->update(['status' => 'unavailable']);

            $products = $category->products();
            // Updates products status to unavailable when category status is set to unavailable
            $products->each(function ($product) {
                $product->update(['status' => 'unavailable']);
            });
        } else if ($category->status === 'unavailable') {
            $category->delete();
        }

        return response()->json(null, 204);
    }
}
