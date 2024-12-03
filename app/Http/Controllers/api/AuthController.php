<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\helpers\AuthHelper;
use App\Http\Controllers\helpers\RoleAuthorizationHelper;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\LogOutRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\SignUpRequest;
use App\Http\Requests\AddCollaboratorsRequest;
use App\Mail\InitialColaboratorPasswordMail;
use App\Models\User;
use App\Services\MailingService;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\Enterprise;

class AuthController extends Controller
{
    protected $authHelper;
    protected $roleAuthorizationHelper;
    protected $mailingService;

    public function __construct()
    {
        $this->middleware('auth:sanctum', ['except' => ['mobileTokenBasedLogin', 'signUp', 'resetPassword']]);
        // $this->middleware('ability:password_reset', ['only' => ['resetPassword']]);

        $this->authHelper = new AuthHelper();
        $this->roleAuthorizationHelper = new RoleAuthorizationHelper();
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
                'email' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        // Verify if the device name is already in use
        if ($user->tokens()->where('name', $request->device_name)->exists()) {
            return response()->json([
                'message' => 'Este dispositivo ya tiene una sesión activa.',
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
            ] = $this->authHelper->processSignUpTransaction($request);

            $accessToken = $registered_owner->createToken($request->device_name)->plainTextToken;
            $registered_owner->update(['is_first_login' => false]);

            return response()->json([
                'access_token' => $accessToken,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocurrió un error al intentar crear la empresa.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function addCollaborators(AddCollaboratorsRequest $request)
    {
        try {
            $enterprise = $request->input('enterprise');
            $enterprise = Enterprise::find($enterprise);
            echo "voy a mostrar la enterprise";
            echo json_encode($enterprise);
    
            if (!$enterprise) {
                $user = auth()->user();  
                if (!$user || !$user->enterprise_id) {
                    return response()->json([
                        'message' => 'Enterprise not found for authenticated user.',
                    ], 400);
                }
                $enterprise = $user->enterprise_id;
            }
    
            [
                $colaborators_passwords
            ] = $this->authHelper->processSignUpUsers($request, $enterprise->id);
    
            foreach ($colaborators_passwords as $colaborator) {
                $this->mailingService->sendEmail(
                    $colaborator['user']->email,
                    new InitialColaboratorPasswordMail($enterprise, $colaborator['user'], $colaborator['password'])
                );
            }
    
            return response()->json([
                'message' => 'Collaborators added successfully.',
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while trying to add the collaborators.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    

    public function logout(LogOutRequest $request)
    {
        $device_token = $request->user()->tokens()->where('name', $request->device_name)->first();

        if (!$device_token) {
            return response()->json([
                'message' => 'Este dispositivo no tiene una sesión activa.',
            ], 404);
        }

        $device_token->delete();

        return response()->json([
            'message' => 'Sesión cerrada con éxito.',
        ], 200);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$this->roleAuthorizationHelper->hasPermission($user->role, 'user.change_password')) {
            return response()->json([
                'message' => 'No autorizado.',
            ], 401);
        }

        if (!$user) {
            return response()->json([
                'message' => 'Usuario no encontrado.',
            ], 404);
        }

        try {
            $user->update(['password' => Hash::make($request->password)]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar la contraseña.',
                'error' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Contraseña actualizada con éxito.',
        ], 200);
    }

    public function refreshUserToken() {}
}
