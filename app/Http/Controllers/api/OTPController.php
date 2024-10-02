<?php

namespace App\Http\Controllers\api;

use App\Http\Requests\PostPasswordResetOTPRequest;
use App\Http\Requests\VerifyOTPRequest;
use App\Mail\PasswordResetOTPMail;
use App\Models\User;
use App\Services\OTPService;
use App\Services\MailingService;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;

use function Laravel\Prompts\error;

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
     * Generate an OTP to reset the user's password.
     */
    public function generatePasswordResetOTP(PostPasswordResetOTPRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        $otp = $this->OTPService->generateOTP($user->email);
        if (!$otp || !$otp->status) {
            return response()->json([
                'message' => 'An error occurred while generating the OTP.',
            ], 500);
        }

        $this->mailingService->sendEmail(
            $user->email,
            new PasswordResetOTPMail($otp->token, $user)
        );

        return response()->json([
            'message' => 'An OTP was sent to your email.',
        ], 200);
    }


    /**
     * Verify the OTP to reset the user's password.
     */
    public function verifyPasswordResetOT(VerifyOTPRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        if ($user->is_is_first_login) {
            return response()->json([
                'message' => 'This user is not allowed to reset his passwords yet.',
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
        ], 200);
    }
}
