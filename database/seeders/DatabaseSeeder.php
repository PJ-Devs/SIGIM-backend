<?php

namespace Database\Seeders;

use App\Models\Enterprise;
use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    $enterprise = Enterprise::create([
      "name" => "Enterprise 1",
      "NIT" => "1234567890",
      "email" => "pgosorio13@gmail.com",
    ]);

    $role = Role::create([
      "name" => "Admin",
      "description" => "Administrator",
    ]);

    User::create([
      "name" => "Pedro Osorio",
      "email" => "pgosorio14@gmail.com",
      "password" => bcrypt("621327481"),
      "enterprise_id" => $enterprise->id,
      "role_id" => $role->id,
    ]);
  }
}
