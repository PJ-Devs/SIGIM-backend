<?php

namespace App\Http\Controllers\helpers;

use App\Http\Requests\SignUpRequest;
use App\Models\Enterprise;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class AuthHelper
{
  /**
   * Register a new entterprise, including the fisrt instance information as owner and a set colaborators.
   * Processes the sign-up transaction.
   *
   * @param SignUpRequest $request The request object containing sign-up details.
   * @return void
   */
  public function processSignUpTransaction(SignUpRequest $request)
  {
    return DB::transaction(function () use ($request) {
      try {
        $enterprise = Enterprise::create([
          'name' => $request->enterprise_name,
          'NIT' => $request->enterprise_NIT,
          'email' => $request->enterprise_email,
          'phone_number' => $request->phone_number,
        ]);

        $enterprise_owner = User::create([
          'name' => $request->owner_name,
          'email' => $request->owner_email,
          'password' => Hash::make($request->owner_password),
          'enterprise_id' => $enterprise->id,
          'role_id' => 1,
        ]);

        // Creare the colaborators
        $created_colaborators = [];
        if (is_array($request->colaborators)) {
          foreach ($request->colaborators as $colaboratorData) {
            $temp_password = $this->generateRandomPassword();

            // Create the colaborator inccluding a random password and the role
            $colaborator = User::create([
              'name' => $colaboratorData['name'],
              'email' => $colaboratorData['email'],
              'enterprise_id' => $enterprise->id,
              'password' => Hash::make($temp_password),
              'role_id' => $colaboratorData['role'],
            ]);

            $created_colaborators[] = [
              'user' => $colaborator,
              'password' => $temp_password,
            ];
          }
        }

        return [$enterprise, $enterprise_owner, $created_colaborators];
      } catch (\Exception $e) {
        Log::error('Error al crear la empresa y colaboradores: ' . $e->getMessage());
        throw $e;
      }
    });
  }

  /**
   * Generate a random password.
   *
   * @param integer $length The length of the password.
   * @return string The generated password.
   */
  private function generateRandomPassword($length = 16)
  {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $specialCharacters = '@_!#$%&*';

    $randomPart = substr(str_shuffle($characters), 0, $length - 1);
    $specialChar = $specialCharacters[rand(0, strlen($specialCharacters) - 1)];
    $password = str_shuffle($randomPart . $specialChar);

    return $password;
  }
}
