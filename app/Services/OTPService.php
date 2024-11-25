<?php

namespace App\Services;

use Ichtrojan\Otp\Otp;
use stdClass;

class OTPService
{
  public function generateOTP(string $identifier, string $type = 'numeric', int $length = 6, int $validity = 6): stdClass
  {
    return (new Otp)->generate($identifier, $type, $length, $validity);
  }

  public function verifyOTP(string $identifier, string $otp): stdClass
  {
    return (new Otp)->validate($identifier, $otp);
  }
}
