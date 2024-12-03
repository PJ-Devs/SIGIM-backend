<?php

namespace App\Http\Controllers\api;

use App\Http\Resources\RoleResource;
use Illuminate\Routing\Controller;
use App\Models\Role;
use App\Http\Resources\RoleCollection;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new RoleCollection(Role::all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        return response()->json(['data' => new RoleResource($role)], 200);
    }
}
