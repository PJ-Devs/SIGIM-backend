<?php

namespace App\Http\Controllers\api;

use Illuminate\Routing\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller {

    public function __construct() {
        $this->middleware( 'auth:sanctum' );
    }

    /**
    * Display a listing of the resource.
    */

    public function index() {
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

    public function show( string $id ) {
        $invoice = Invoice::find( $id );
        return response()->json( [ 'data' => $invoice ], 200 );
    }

    /**
    * Update the specified resource in storage.
    */

    public function update( Request $request, Invoice $enterprise ) {
      

    }

    /**
    * Remove the specified resource from storage.
    */

    public function destroy( string $id ) {
        $product->delete();
        return response( null, 204 );
    }

    public function getInvoices(Request $request) {
        $user = $request->user();
        $invoices = $user->invoices;
        return response()->json(['data' => $invoices], 200);
    }
}
