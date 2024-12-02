<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\helpers\RoleAuthorizationHelper;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    protected $roleAuthorizationHelper;

    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->roleAuthorizationHelper = new RoleAuthorizationHelper();
    }

    public function attachProduct(Request $request)
    {
        $user = $request->user();
        if (!$this->roleAuthorizationHelper->hasPermission($user->role, 'invoice.update')) {
            return response()->json([
                'message' => 'No tienes autorización para realizar esta acción.'
            ], 401);
        }

        $product = Product::find($request->product_id);
        $user->products()->attach($product->id, ['quantity' => $request->quantity]);
        return response()->json(['message' => 'Producto agregado al usuario.'], 200);
    }

    /**
     * Detach a product from the cart.
     *
     * @param \Illuminate\Http\Request $request The request instance containing the product details to be detached.
     * @return \Illuminate\Http\JsonResponse The response indicating the success or failure of the operation.
     */
    public function detachProduct(Request $request)
    {
        $user = $request->user();
        if (!$this->roleAuthorizationHelper->hasPermission($user->role, 'invoice.update')) {
            return response()->json([
                'message' => 'No tienes autorización para realizar esta acción.'
            ], 401);
        }

        $product = Product::find($request->product_id);
        $user->products()->detach($product->id);
        return response()->json(['message' => 'Producto eliminado del usuario.'], 200);
    }

    /**
     * Retrieve the list of products in the cart.
     *
     * @param \Illuminate\Http\Request $request The HTTP request instance.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the list of products.
     */
    public function getProducts(Request $request)
    {
        $user = $request->user();
        if (!$this->roleAuthorizationHelper->hasPermission($user->role, 'invoice.read')) {
            return response()->json([
                'message' => 'No tienes autorización para realizar esta acción.'
            ], 401);
        }

        $products = $user->products;
        return response()->json(["data" => $products], 200);
    }

    /**
     * Update the quantity of a product in the cart.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateProductQuantity(Request $request)
    {
        $user = $request->user();
        if (!$this->roleAuthorizationHelper->hasPermission($user->role, 'invoice.update')) {
            return response()->json([
                'message' => 'No tienes autorización para realizar esta acción.'
            ], 401);
        }

        $product = Product::find($request->product_id);
        $user->products()->updateExistingPivot($product->id, ['quantity' => $request->quantity]);
        return response()->json(['message' => 'Cantidad del producto actualizada.'], 200);
    }

    /**
     * Remove all products from the cart.
     *
     * @param \Illuminate\Http\Request $request The incoming request instance.
     * @return \Illuminate\Http\JsonResponse The response indicating the result of the operation.
     */
    public function cleanProducts(Request $request)
    {
        $user = $request->user();
        if (!$this->roleAuthorizationHelper->hasPermission($user->role, 'invoice.update')) {
            return response()->json([
                'message' => 'No tienes autorización para realizar esta acción.'
            ], 401);
        }

        $products = $user->products;
        foreach ($products as $product) {
            $user->products()->detach($product->id);
        }

        return response()->json(['message' => 'Productos eliminados.'], 200);
    }
}
