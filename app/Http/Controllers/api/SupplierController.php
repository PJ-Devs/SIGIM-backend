<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller {
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
        $supplier = Supplier::create( $request->all() );
        return response()->json( [ 'data' => $supplier ], 201 );
    }

    /**
    * Display the specified resource.
    */

    public function show( Supplier $supplier ) {
        //
    }

    /**
    * Update the specified resource in storage.
    */

    public function update( Request $request, Supplier $supplier ) {
        
    }

    /**
    * Remove the specified resource from storage.
    */

    public function destroy( Supplier $supplier ) {
        $supplier->delete();
        return response( null, 204 );
    }
}
