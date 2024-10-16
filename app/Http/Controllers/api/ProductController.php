<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductResource;


class ProductController extends Controller {
    /**
    * Display a listing of the resource.
    */

    public function index() {

        $products = Product::orderBy('name', 'asc')->get();
        return response()->json(['data' => ProductResource::collection($products)], 200);

    }

    /**
    * Store a newly created resource in storage.
    */

    public function store( ProductStoreRequest $request ) {
        $product = Product::create( $request->all() );
        return response()->json( [ 'data' => $product ], 201 );
    }

    /**
    * Display the specified resource.
    */

    public function show( Product $product ) {

        return response()->json(['data' => new ProductResource($product)], 200);
    }

    /**
    * Update the specified resource in storage.
    */

    public function update( ProductUpdateRequest $request, Product $product ) {
        $product->update($request->all());
        return response()->json(['data' => $product], 200);
    }

    /**
    * Remove the specified resource from storage.
    */

    public function destroy( Product $product ) {
        $product->delete();
        return response( null, 204 );
    }
}
