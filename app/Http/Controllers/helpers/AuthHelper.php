<?php

namespace App\Http\Controllers\helpers;

use App\Http\Requests\SignUpRequest;
use App\Models\Enterprise;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AuthHelper
{
  /**
   * Register a new entterprise, including the fisrt instance information as owner and a set colaborators.
   */
  public function processSignUpTransaction(SignUpRequest $request)
  {
    DB::transaction(function () use ($request) {
      $enterprise = Enterprise::create([
        'name' => $request->enterprise_name,
        'NIT' => $request->enterprise_NIT,
        'email' => $request->enterprise_email,
        'phone_number' => $request->phone_number,
        'currency' => 'COP',
      ]);

      $enterprise_owner = User::create([
        'name' => $request->owner_name,
        'email' => $request->owner_email,
        'password' => $request->owner_password,
        'enterprise_id' => $enterprise->id,
        'role_id' => 1,
      ]);

      $colaborators_passwords = [];
      if ($request->colaborators) {
        foreach ($request->colaborators as $colaborator) {
          $temp_password = $this->generateRandomPassword();
          User::create([
            'name' => $colaborator['name'],
            'email' => $colaborator['email'],
            'enterprise_id' => $enterprise->id,
            'password' => $temp_password,
            'role_id' => $colaborator['role'],
          ]);
          $colaborators_passwords[] = [
            'email' => $colaborator['email'],
            'password' => $temp_password,
          ];
        }
      }

      return [$enterprise_owner, $colaborators_passwords];
    });
  }

  /**
   * Generate a random alfanumeric password.
   */
  private function generateRandomPassword($length = 16)
  {
    return substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_'), 0, $length);
  }
}
