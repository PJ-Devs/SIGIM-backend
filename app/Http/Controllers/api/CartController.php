<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\helpers\RoleAuthorizationHelper;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Services\PushNotificationService;
use App\Models\Sale;
use App\Models\Invoice;
use Carbon\Factory;

class CartController extends Controller
{
    protected $roleAuthorizationHelper;
    protected $pushNotificationService;

    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->roleAuthorizationHelper = new RoleAuthorizationHelper();
        $this->pushNotificationService = new PushNotificationService();
    }

    public function concludeSale(Request $request)
    {
        $user = $request->user();
        $products = $user->products;

        if ($products->count() == 0) {
            return response()->json(['message' => 'No products in cart'], 400);
        }

        $invoice = Invoice::create([
            'payment_method' => $request->payment_method,
            'user_id' => $user->id,
            'total_price' => 0,
            'client_id' => $request->client_id
        ]);

        $total = 0;

        foreach ($products as $product) {
            #generate each sale
            $product_price = $product->sale_price;
            $discount = $request->discount;

            $sale = Sale::create([
                'quantity' => $product->pivot->quantity,
                'price' => $product->sale_price,
                'discount' => $discount,
                'total_price' => ($product_price - ($product_price * $discount)) * $product->pivot->quantity,
                'invoice_id' => $invoice->id,
                'client_id' => $request->client_id,
                'product_id' => $product->id
            ]);
            $total += $product->sale_price * $product->pivot->quantity;
        }

        $total = $total - ($total * $request->discount);
        $invoice->total_price = $total;
        $user->invoices()->save($invoice);

        $user->products()->detach();
        return response()->json(['message' => 'Sale concluded', 'total' => $total], 200);
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
        if ($product->stock < $request->quantity) {
            return response()->json(['message' => 'Product out of stock'], 400);
        }

        $user_products = $user->products;

        if ($user_products->contains($product)) {
            #update quantity
            $product_quantity = $user->products()->where('product_id', $product->id)->first()->pivot->quantity;
            $user->products()->updateExistingPivot($product->id, ['quantity' => $product_quantity + $request->quantity]);
            $product->stock = $product->stock - $request->quantity;
            $product->save();
            return response()->json(['message' => 'Product quantity updated'], 200);
        }

        $user->products()->attach($product->id, ['quantity' => $request->quantity]);

        #update the stock
        $product->stock = $product->stock - $request->quantity;
        $product->save();

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
        if ($request->quantity < $product->minimal_safe_stock) {
            $this->pushNotificationService->sendNotification(
                'Alerta de stock bajo',
                "Quedan '{$product->queantity}' existencias del producto '{$product->name}' !",
                ['product_id' => $product->id]
            );
        }

        $user->products()->updateExistingPivot($product->id, ['quantity' => $request->quantity]);
        return response()->json(['message' => 'Cantidad del producto actualizada.'], 200);
    }

    private function sendProductLowStockNotification($product)
    {
        $firebase = (new Factory)->withServiceAccount(__DIR__ . '/../../../../config/firebase_config.json');
        $messaging = $firebase->createMessaging();
        $message = CloudMessage::withTarget('token', env('FIREBASE_TOKEN'))
            ->withNotification([
                'title' => 'Alerta de stock bajo',
                'body' => "Te quedan '{$product->quantity}' del producto '{$product->name}'!"
            ]);

        $messaging->send($message);
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
