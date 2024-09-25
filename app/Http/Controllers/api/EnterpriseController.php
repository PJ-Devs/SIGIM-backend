<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Enterprise;
use Illuminate\Http\Request;

class EnterpriseController extends Controller {
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
        $enterprise = Enterprise::create( $request->all() );
        return response()->json( [ 'data' => $enterprise ], 201 );
    }

    /**
    * Display the specified resource.
    */

    public function show( Enterprise $product ) {
        //
    }

    /**
    * Update the specified resource in storage.
    */

    public function update( Request $request, Enterprise $enterprise ) {
       
    }

    /**
    * Remove the specified resource from storage.
    */

    public function destroy( Enterprise $product ) {
        $product->delete();
        return response( null, 204 );
    }
}
