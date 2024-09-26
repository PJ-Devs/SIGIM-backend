<?php

namespace App\Http\Controllers\api;

use Illuminate\Routing\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\LogOutRequest;
use App\Http\Requests\SignUpRequest;
use App\Models\Enterprise;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum', ['except' => ['mobileTokenBasedLogin', 'signUp']]);
    }

    public function mobileTokenBasedLogin(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        // Check if the user exists and the password is correct
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Verify if the device name is already in use
        if ($user->tokens()->where('name', $request->device_name)->exists()) {
            return response()->json([
                'message' => 'This device have an active session.',
            ], 409);
        }

        return response()->json([
            'access_token' => $user->createToken($request->device_name)->plainTextToken,
        ]);
    }

    public function signUp(SignUpRequest $request)
    {
        try {
            $registered_owner = DB::transaction(function () use ($request) {
                $enterprise = Enterprise::create([
                    'name' => $request->enterprise_name,
                    'NIT' => $request->enterprise_NIT,
                    'email' => $request->enterprise_email,
                    'phone_number' => $request->phone_number,
                    'currency' => 'COP',
                ]);

                $owner = User::create([
                    'name' => $request->owner_name,
                    'email' => $request->owner_email,
                    'password' => $request->owner_password,
                    'enterprise_id' => $enterprise->id,
                    'role_id' => 1,
                ]);

                foreach ($request->colaborators as $colaborator) {
                    User::create([
                        'name' => $colaborator['name'],
                        'email' => $colaborator['email'],
                        'password' => Str::password(16),
                        'enterprise_id' => $enterprise->id,
                        'role_id' => $colaborator['role'],
                    ]);
                }

                return $owner;
            });

            $accessToken = $registered_owner->createToken($request->device_name)->plainTextToken;
            return response()->json([
                'access_token' => $accessToken,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'An error occurred while trying to create the enterprise.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function logout(LogOutRequest $request)
    {
        $device_token = $request->user()->tokens()->where('name', $request->device_name)->first();

        if (!$device_token) {
            return response()->json([
                'message' => 'This device does not have an active session.',
            ], 404);
        }

        $device_token->delete();

        return response()->json([
            'message' => 'Session closed successfully.',
        ], 200);
    }

    public function refreshUserToken() {}
}
