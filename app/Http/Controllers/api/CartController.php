<?php

namespace App\Http\Controllers\api;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Invoice;

class CartController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum');
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

        error_log($product->stock);
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
