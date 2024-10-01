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
          'currency' => 'COP',
        ]);

        $enterprise_owner = User::create([
          'name' => $request->owner_name,
          'email' => $request->owner_email,
          'password' => Hash::make($request->owner_password),
          'enterprise_id' => $enterprise->id,
          'role_id' => 1,
        ]);

        // Crear los colaboradores
        $created_colaborators = [];
        if (is_array($request->colaborators)) {
          foreach ($request->colaborators as $colaboratorData) {
            $temp_password = $this->generateRandomPassword();

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
   * Generate a random alfanumeric password.
   */
  private function generateRandomPassword($length = 16)
  {
    return substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_'), 0, $length);
  }
}
