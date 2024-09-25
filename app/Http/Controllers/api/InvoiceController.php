<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller {
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
        $invoice = Invoice::create( $request->all() );
        return response()->json( [ 'data' => $invoice ], 201 );
    }

    /**
    * Display the specified resource.
    */

    public function show( Invoice $product ) {
        //
    }

    /**
    * Update the specified resource in storage.
    */

    public function update( Request $request, Invoice $enterprise ) {
      

    }

    /**
    * Remove the specified resource from storage.
    */

    public function destroy( Invoice $product ) {
        $product->delete();
        return response( null, 204 );
    }
}
