<?php

namespace App\Http\Controllers\api;

use App\Http\Resources\RoleResource;
use Illuminate\Routing\Controller;
use App\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['data' => RoleResource::collection(Role::all())], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        return response()->json(['data' => new RoleResource($role)], 200);
    }
}
