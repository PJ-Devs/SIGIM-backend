<?php

namespace App\Http\Controllers\api;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function attachProduct(Request $request)
    {
        $user = $request->user();
        $product = Product::find($request->product_id);
        error_log($request->product_id);
        $user->products()->attach($product->id, ['quantity' => $request->quantity]);
        return response()->json(['message' => 'Product attached to user'], 200);
    }

    public function detachProduct(Request $request)
    {
        $user = $request->user();
        $product = Product::find($request->product_id);
        $user->products()->detach($product->id);
        return response()->json(['message' => 'Product detached from user'], 200);
    }

    public function getProducts(Request $request){
        $user = $request->user();
        $products = $user->products;
        return response()->json(["data" => $products], 200);
    }

    public function updateProductQuantity(Request $request)
    {
        $user = $request->user();
        $product = Product::find($request->product_id);
        $user->products()->updateExistingPivot($product->id, ['quantity' => $request->quantity]);
        return response()->json(['message' => 'Product quantity updated'], 200);
    }

    public function cleanProducts(Request $request)
    {
        $user = $request->user();
        $products = $user->products;

        foreach ($products as $product) {
            $user->products()->detach($product->id);
        }

        return response()->json(['message' => 'Products cleaned'], 200);
    }
}
