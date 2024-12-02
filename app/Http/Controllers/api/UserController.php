<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\helpers\RoleAuthorizationHelper;
use Illuminate\Routing\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $roleAuthorizationHelper;

    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->roleAuthorizationHelper = new RoleAuthorizationHelper();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if (!$this->roleAuthorizationHelper->hasPermission($user->role, 'user.delete')) {
            return response()->json([
                'message' => 'No estás autorizado para realizar esta acción.'
            ], 401);
        }

        $user->delete();
        return response(null, 204);
    }

    public function showProfile()
    {
        $user = User::find(Auth::user()->id);
        if (!$user) {
            return response()->json([
                'message' => 'Usuario no encontrado.'
            ], 404);
        }

        if (!$this->roleAuthorizationHelper->hasPermission($user->role, 'user.read')) {
            return response()->json([
                'message' => 'No estás autorizado para realizar esta acción.'
            ], 401);
        }

        return response()->json([
            'data' => UserResource::make(Auth::user())
        ], 200);
    }

    public function updateProfile(UpdateUserRequest $request)
    {
        $user = User::find(Auth::user()->id);
        if (!$user) {
            return response()->json([
                'message' => 'Usuario no encontrado.'
            ], 404);
        }

        if (!$this->roleAuthorizationHelper->hasPermission($user->role, 'user.update')) {
            return response()->json([
                'message' => 'No estás autorizado para realizar esta acción.'
            ], 401);
        }

        $user->update($request->validated());

        return response()->json([
            'data' => UserResource::make($user)
        ], 200);
    }

    public function getMyEnterprise()
    {
        $user = Auth::user();
        $enterprise = $user->enterprise;

        if (!$user) {
            return response()->json([
                'message' => 'Usuario no encontrado.'
            ], 404);
        }

        if (!$this->roleAuthorizationHelper->hasPermission($user->role, 'enterprise.read')) {
            return response()->json([
                'message' => 'No estás autorizado para realizar esta acción.'
            ], 401);
        }

        return response()->json([
            'data' => $enterprise
        ], 200);
    }

    // /**
    //  * Store a newly created resource in storage.
    //  */
    // public function store(UpdateUserRequest $request)
    // {
    //     //
    // }

    // /**
    //  * Display the specified resource.
    //  */
    // public function show(string $id)
    // {
    //     //
    // }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(Request $request, string $id)
    // {
    //     //
    // }
}
