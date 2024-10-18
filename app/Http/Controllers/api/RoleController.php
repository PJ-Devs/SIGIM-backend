<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $roles = Role::all();
        return response()->json(['data' => $roles], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $role = Role::create($request->all());
        return response()->json(['data' => $role], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role) {
        return response()->json(['data' => $role], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role) {
        $role->update($request->all());
        return response()->json(['data' => $role], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role) {
        $role->delete();
        return response(null, 204);
    }
}
