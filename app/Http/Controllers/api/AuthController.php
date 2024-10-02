<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\helpers\AuthHelper;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\LogOutRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\SignUpRequest;
use App\Mail\InitialColaboratorPasswordMail;
use App\Models\User;
use App\Services\MailingService;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected $authHelper;
    protected $mailingService;

    public function __construct()
    {
        $this->middleware('auth:sanctum', ['except' => ['mobileTokenBasedLogin', 'signUp']]);
        $this->middleware('ability:password_reset', ['only' => ['resetPassword']]);

        $this->authHelper = new AuthHelper();
        $this->mailingService = new MailingService();
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
            [
                $enterprise,
                $registered_owner,
                $colaborators_passwords
            ] = $this->authHelper->processSignUpTransaction($request);

            foreach ($colaborators_passwords as $colaborator) {
                $this->mailingService->sendEmail(
                    $colaborator['user']->email,
                    new InitialColaboratorPasswordMail($enterprise, $colaborator['user'], $colaborator['password'])
                );
            }

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

    public function resetPassword(ResetPasswordRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        try {
            $user->update(['password' => Hash::make($request->password)]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update the password.',
                'error' => $e->getMessage()
            ], 500);
        }

        $used_token = $user->tokens()->where('name', "password_reset_{$user->id}")->first();
        if ($used_token) {
            $used_token->delete();
        } else {
            return response()->json([
                'message' => 'Password reset token not found or already used.',
            ], 404);
        }

        return response()->json([
            'message' => 'Password updated successfully.',
        ], 200);
    }


    public function refreshUserToken() {}
}
