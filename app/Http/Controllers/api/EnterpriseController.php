<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Enterprise;
use Illuminate\Http\Request;
use App\Http\Requests\EnterpriseStoreRequest;
use App\Http\Requests\EnterpriseUpdateRequest;
use App\Http\Resources\EnterpriseResource;

class EnterpriseController extends Controller {
    /**
    * Display a listing of the resource.
    */

    public function index() {
        $enterprises = Enterprise::orderBy('name', 'asc')->get();
        return response()->json(['data' => EnterpriseResource::collection($enterprises)], 200);
    }

    /**
    * Store a newly created resource in storage.
    */

    public function store( EnterpriseStoreRequest $request ) {
        $enterprise = Enterprise::create( $request->all() );
        return response()->json( [ 'data' => $enterprise ], 201 );
    }

    /**
    * Display the specified resource.
    */

    public function show( Enterprise $product ) {
        return response()->json(['data' => new EnterpriseResource($enterprise)], 200);
    }

    /**
    * Update the specified resource in storage.
    */

    public function update( EnterpriseUpdateRequest $request, Enterprise $enterprise ) {
       $enterprise->update($request->all());
       return response()->json(['data' => $enterprise], 200);
    }

    /**
    * Remove the specified resource from storage.
    */

    public function destroy( Enterprise $product ) {
        $product->delete();
        return response( null, 204 );
    }
}
