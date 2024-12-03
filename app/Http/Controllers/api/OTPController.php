<?php

namespace App\Http\Controllers\api;

use App\Http\Requests\PostPasswordResetOTPRequest;
use App\Http\Requests\VerifyOTPRequest;
use App\Mail\PasswordResetOTPMail;
use App\Models\User;
use App\Services\OTPService;
use App\Services\MailingService;
use Illuminate\Routing\Controller;

class OTPController extends Controller
{
    protected $OTPService;
    protected $mailingService;

    public function __construct()
    {
        $this->OTPService = new OTPService();
        $this->mailingService = new MailingService();
    }

    /**
     * Generate a One-Time Password (OTP) for password reset.
     *
     * @param PostPasswordResetOTPRequest $request The request object containing necessary data for generating the OTP.
     * @return \Illuminate\Http\JsonResponse The response containing the generated OTP or an error message.
     */
    public function generatePasswordResetOTP(PostPasswordResetOTPRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'message' => 'No se encontró un usuario con el correo proporcionado.',
            ], 404);
        }

        $otp = $this->OTPService->generateOTP($user->email);
        if (!$otp || !$otp->status) {
            return response()->json([
                'message' => 'Ocurrió un error al generar el código OTP. Inténtalo de nuevo más tarde.',
            ], 500);
        }

        $this->mailingService->sendEmail(
            $user->email,
            new PasswordResetOTPMail($otp->token, $user)
        );

        return response()->json([
            'message' => 'Se ha enviado un código OTP a tu correo electrónico.',
        ], 200);
    }

    /**
     * Verify the OTP (One-Time Password) for password reset.
     *
     * @param \App\Http\Requests\VerifyOTPRequest $request The request object containing the OTP and other necessary data.
     * @return \Illuminate\Http\JsonResponse The response indicating the result of the OTP verification.
     */
    public function verifyPasswordResetOT(VerifyOTPRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'message' => 'No se encontró un usuario con el correo proporcionado.',
            ], 404);
        }

        if ($user->is_is_first_login) {
            return response()->json([
                'message' => 'Este usuario no tiene permitido restablecer su contraseña en este momento.',
            ], 400);
        }

        $otp_validation = $this->OTPService->verifyOTP($user->email, $request->token);
        if (!$otp_validation->status) {
            return response()->json([
                'message' => $otp_validation->message,
            ], 400);
        }

        $reset_password_token = $user->createToken(
            "password_reset_{$user->id}",
            ['password_reset']
        )->plainTextToken;

        return response()->json([
            'reset_password_token' => $reset_password_token,
            'valid' => true,
            'message' => 'El código OTP es válido. Aquí tienes tu token para restablecer la contraseña.',
        ], 200);
    }
}
