<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller {
    /**
    * Display a listing of the resource.
    */

    public function index() {
        //
    }

    /**
    * Store a newly created resource in storage.
    */

    public function store( Request $request ) {
        $sale = Sale::create( $request->all() );
        return response()->json( [ 'data' => $sale ], 201 );
    }

    /**
    * Display the specified resource.
    */

    public function show( Sale $sale ) {
        //
    }

    /**
    * Update the specified resource in storage.
    */

    public function update( Request $request, Sale $sale ) {
        
    }

    /**
    * Remove the specified resource from storage.
    */

    public function destroy( Sale $sale ) {
        $sale->delete();
        return response( null, 204 );
    }
}
