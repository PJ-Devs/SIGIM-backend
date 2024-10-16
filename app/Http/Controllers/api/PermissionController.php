<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $permissions = Permission::orderBy('name', 'asc')->get();
        return response()->json(['data' => $permissions], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $permission = Permission::create($request->all());
        return response()->json(['data' => $permission], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission) {
        return response()->json(['data' => $permission], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission) {
        $permission->update($request->all());
        return response()->json(['data' => $permission], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission) {
        $permission->delete();
        return response(null, 204);
    }
}
