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
        $sales = Sale::orderBy('created_at', 'desc')->get();
        return response()->json(['data' => $sales], 200);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(Request $request) {
        $sale = Sale::create($request->all());
        return response()->json(['data' => $sale], 201);
    }

    /**
    * Display the specified resource.
    */
    public function show(Sale $sale) {
        return response()->json(['data' => $sale], 200);
    }

    /**
    * Update the specified resource in storage.
    */
    public function update(Request $request, Sale $sale) {
        $sale->update($request->all());
        return response()->json(['data' => $sale], 200);
    }

    /**
    * Remove the specified resource from storage.
    */
    public function destroy(Sale $sale) {
        $sale->delete();
        return response(null, 204);
    }
}
