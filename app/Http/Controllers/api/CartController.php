<?php

namespace App\Http\Controllers\api;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Services\PushNotificationService;

class CartController extends Controller
{

    protected $pushNotificationService;

    public function __construct()
    {
        $this->middleware('auth:sanctum');

        $this->pushNotificationService = new PushNotificationService();
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
        if ($request->quantity < $product->minimal_safe_stock) {
            $pushNotificationService->sendNotification(
                'Alerta de stock bajo',
                "Quedan '{$product->queantity}' existencias del producto '{$product->name}' !",
                ['product_id' => $product->id]
            );
        }
        return response()->json(['message' => 'Product quantity updated'], 200);
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
