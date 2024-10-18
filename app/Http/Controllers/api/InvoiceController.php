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
        $invoices = Invoice::orderBy('created_at', 'desc')->get();
        return response()->json(['data' => $invoices], 200);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(Request $request) {
        $invoice = Invoice::create($request->all());
        return response()->json(['data' => $invoice], 201);
    }

    /**
    * Display the specified resource.
    */
    public function show(Invoice $invoice) {
        return response()->json(['data' => $invoice], 200);
    }

    /**
    * Update the specified resource in storage.
    */
    public function update(Request $request, Invoice $invoice) {
        $invoice->update($request->all());
        return response()->json(['data' => $invoice], 200);
    }

    /**
    * Remove the specified resource from storage.
    */
    public function destroy(Invoice $invoice) {
        $invoice->delete();
        return response(null, 204);
    }
}
