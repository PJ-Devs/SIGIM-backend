<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;


class SupplierController extends Controller {
    /**
    * Display a listing of the resource.
    */

    public function index() {
        $suppliers = Supplier::all();
        return response()->json(['data' => $suppliers], 200);
    }

    /**
    * Store a newly created resource in storage.
    */

    public function store( StoreSupplierRequest $request ) {
        $supplier = Supplier::create( $request->all() );
        return response()->json(['data' => $supplier], 201);
    }

    /**
    * Display the specified resource.
    */

    public function show(string $id) {
        $supplier = Supplier::find($id);
        return response()->json(['data' => $supplier], 200);
    }

    /**
    * Update the specified resource in storage.
    */

    public function update( UpdateSupplierRequest $request, string $id ) {
        $supplier = Supplier::find($id);
        $supplier->update($request->all());
        return response()->json(['data' => $supplier], 200);
        
    }

    /**
    * Remove the specified resource from storage.
    */

    public function destroy( Supplier $supplier ) {
        $supplier->delete();
        return response( null, 204 );
    }
}
