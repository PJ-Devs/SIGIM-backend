<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Enterprise;
use App\Models\Role;
use App\Models\Supplier;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

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
      "phone_number" => "nosee", 
      "currency" => "USD"
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

    Category::create([
      "name" => "nose",
      "description" => "tampocose",
    ]);
    Supplier::create([
      "name" => "nose",
      "email" => "tampocose",
      "phone_number" => "tampocose",
      "NIT" => "nose",
    ]);
    
    for ($i = 0; $i < 5; $i++) {
      Product::create([
          'name' => 'Product ' . ($i + 1),
          'description' => 'Description for Product ' . ($i + 1),
          'status' => 'available',
          'stock' => rand(1, 100),
          'supplier_price' => rand(50, 200),
          'sale_price' => rand(100, 300),
          'thumbnail' => 'https://via.placeholder.com/150',
          'barcode' => '123456789' . $i,
          'minimal_safe_stock' => rand(5, 20),
          'discount' => rand(0, 20),
          'enterprise_id' => $enterprise->id,
          'category_id' => 1, 
          'supplier_id' =>1
      ]);
  }
  }
}
