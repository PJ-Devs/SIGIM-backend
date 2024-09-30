<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\helpers\AuthHelper;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\LogOutRequest;
use App\Http\Requests\SignUpRequest;

use App\Models\User;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected $authHelper;

    public function __construct()
    {
        $this->middleware('auth:sanctum', ['except' => ['mobileTokenBasedLogin', 'signUp']]);
        $this->authHelper = new AuthHelper();
    }

    /**
     * Login the user using the email and password and return the access token.
     */
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


    /**
     * Register a new enterprise, its owner and its colaborators.
     */
    public function signUp(SignUpRequest $request)
    {
        try {
            [$registered_owner, $colaborators_passwords] = $this->authHelper->processSignUpTransaction($request);
            $accessToken = $registered_owner->createToken($request->device_name)->plainTextToken;
            $registered_owner->update(['is_first_login' => false]);

            return response()->json([
                'access_token' => $accessToken,
            ], 200);
        } catch (\Exception $e) {
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
