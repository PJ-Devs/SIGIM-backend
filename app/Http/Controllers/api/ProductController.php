<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller {
    /**
    * Display a listing of the resource.
    */

    public function index() {
   
$products = Product::orderBy('name', 'asc')->get();
return response()->json(['data' => $products], 200);

    }

    /**
    * Store a newly created resource in storage.
    */

    public function store( Request $request ) {
        $product = Product::create( $request->all() );
        return response()->json( [ 'data' => $product ], 201 );
    }

    /**
    * Display the specified resource.
    */

    public function show( Product $product ) {
        //
    }

    /**
    * Update the specified resource in storage.
    */

    public function update( Request $request, Product $product ) {
      
    }

    /**
    * Remove the specified resource from storage.
    */

    public function destroy( Product $product ) {
        $product->delete();
        return response( null, 204 );
    }
}
