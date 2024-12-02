<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Enterprise;
use App\Models\Product;
use App\Models\Supplier;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    $this->call([
      PermissionsSeeder::class,
      RolesSeeder::class,
    ]);

    // ----------------------------

    $enterprise = Enterprise::create([
      "name" => "Enterprise 1",
      "NIT" => "1234567890",
      "email" => "pgosorio13@gmail.com",
      "phone_number" => "nosee",
    ]);

    $ownerUser = $enterprise->users()->create([
      "name" => "Pedro Osorio",
      "email" => "pedroo@gmail.com",
      "password" => bcrypt("12345678"),
      "role_id" => 1,
    ]);

    $adminUser = $enterprise->users()->create([
      "name" => "Marina Guzman",
      "email" => "marinitagz@gmail.com",
      "password" => bcrypt("12345678"),
      "role_id" => 2,
    ]);

    $inventoryUser = $enterprise->users()->create([
      "name" => "Roberto Carlos",
      "email" => "robertico@gmail.com",
      "password" => bcrypt("12345678"),
      "role_id" => 3,
    ]);

    $adminUser = $enterprise->users()->create([
      "name" => "Andrea Giraldo",
      "email" => "andreeehg@gmail.com",
      "password" => bcrypt("12345678"),
      "role_id" => 4,
    ]);

    $baseUser = $enterprise->users()->create([
      "name" => "Juan Perez",
      "email" => "juanperez121@gmail.com",
      "password" => bcrypt("12345678"),
      "role_id" => 5,
    ]);

    error_log("Owner Role test token: " . $ownerUser->createToken("api_token")->plainTextToken);
    error_log("Admin Role test token: " . $adminUser->createToken("api_token")->plainTextToken);
    error_log("Inventory Role test token: " . $inventoryUser->createToken("api_token")->plainTextToken);
    error_log("Sales Role test token: " . $adminUser->createToken("api_token")->plainTextToken);
    error_log("Base Role test token: " . $baseUser->createToken("api_token")->plainTextToken);

    // ----------------------------

    Category::create([
      "name" => "Telefonos",
      "description" => "Categoria de Telefonos",
      'enterprise_id' => $enterprise->id,
    ]);

    Category::create([
      "name" => "Audio",
      "description" => "Categoria de Audio",
      'enterprise_id' => $enterprise->id,
    ]);

    Category::create([
      "name" => "Computadoras",
      "description" => "Categoria de Computadoras",
      'enterprise_id' => $enterprise->id,
    ]);

    Category::create([
      "name" => "Camaras",
      "description" => "Categoria de Camaras",
      'enterprise_id' => $enterprise->id,
    ]);

    Category::create([
      "name" => "Videojuegos",
      "description" => "Categoria de Videojuegos",
      'enterprise_id' => $enterprise->id,
    ]);

    // ----------------------------

    Supplier::create([
      "name" => "nose",
      "email" => "tampocose",
      "phone_number" => "tampocose",
      "NIT" => "nose",
    ]);

    // ----------------------------

    Product::create([
      'name' => 'Apple iPhone 14',
      'description' => 'Latest Apple smartphone with 128GB storage, 6.1-inch display, and A15 Bionic chip.',
      'status' => 'available',
      'stock' => 35,
      'supplier_price' => 3499000,
      'sale_price' => 3999000,
      'thumbnail' => 'storage/product_thumbnails/iphone14.jpg',
      'barcode' => '1234567890',
      'minimal_safe_stock' => 40,
      'discount' => 5 / 100,
      'enterprise_id' => $enterprise->id,
      'category_id' => 1,
      'supplier_id' => 1
    ]);

    Product::create([
      'name' => 'Samsung Galaxy S23',
      'description' => 'Samsung Galaxy S23 with 256GB storage, 6.2-inch AMOLED display, and Snapdragon 8 Gen 2 processor.',
      'status' => 'available',
      'stock' => 50,
      'supplier_price' => 3990000,
      'sale_price' => 4490000,
      'thumbnail' => 'storage/product_thumbnails/s23.jpg',
      'barcode' => '1234567891',
      'minimal_safe_stock' => 15,
      'discount' => 10 / 100,
      'enterprise_id' => $enterprise->id,
      'category_id' => 1,
      'supplier_id' => 1
    ]);

    Product::create([
      'name' => 'Sony WH-1000XM5',
      'description' => 'Sony noise-cancelling wireless headphones with 30-hour battery life and premium sound quality.',
      'status' => 'available',
      'stock' => 75,
      'supplier_price' => 620000,
      'sale_price' => 699000,
      'thumbnail' => 'storage/product_thumbnails/sonyhw.jpg',
      'barcode' => '1234567892',
      'minimal_safe_stock' => 20,
      'discount' => 0,
      'enterprise_id' => $enterprise->id,
      'category_id' => 2,
      'supplier_id' => 1
    ]);

    Product::create([
      'name' => 'Dell XPS 13',
      'description' => 'Dell XPS 13 laptop with Intel i7 processor, 16GB RAM, 512GB SSD, and 13.3-inch 4K display.',
      'status' => 'available',
      'stock' => 20,
      'supplier_price' => 4499000,
      'sale_price' => 4999000,
      'thumbnail' => 'storage/product_thumbnails/dell.jpg',
      'barcode' => '1234567893',
      'minimal_safe_stock' => 8,
      'discount' => 7 / 100,
      'enterprise_id' => $enterprise->id,
      'category_id' => 3,
      'supplier_id' => 1
    ]);

    Product::create([
      'name' => 'Google Pixel 8',
      'description' => 'Google Pixel 8 with 128GB storage, Google Tensor G3 processor, and 50MP dual camera system.',
      'status' => 'available',
      'stock' => 40,
      'supplier_price' => 3299000,
      'sale_price' => 3799000,
      'thumbnail' => 'storage/product_thumbnails/pixel.jpg',
      'barcode' => '1234567894',
      'minimal_safe_stock' => 10,
      'discount' => 12 / 100,
      'enterprise_id' => $enterprise->id,
      'category_id' => 1,
      'supplier_id' => 1,
      'is_favorite' => true
    ]);

    Product::create([
      'name' => 'Apple MacBook Pro 16"',
      'description' => 'Apple MacBook Pro 16-inch with M2 Max chip, 32GB RAM, and 1TB SSD.',
      'status' => 'available',
      'stock' => 15,
      'supplier_price' => 8399000,
      'sale_price' => 8999000,
      'thumbnail' => 'storage/product_thumbnails/mb16.jpg',
      'barcode' => '1234567895',
      'minimal_safe_stock' => 5,
      'discount' => 3 / 100,
      'enterprise_id' => $enterprise->id,
      'category_id' => 3,
      'supplier_id' => 1
    ]);

    Product::create([
      'name' => 'Nikon Z7 II Camera',
      'description' => 'Nikon Z7 II mirrorless camera with 45.7MP full-frame sensor and 4K video recording.',
      'status' => 'available',
      'stock' => 10,
      'supplier_price' => 11999000,
      'sale_price' => 12999000,
      'thumbnail' => 'storage/product_thumbnails/nikon.jpg',
      'barcode' => '1234567896',
      'minimal_safe_stock' => 3,
      'discount' => 5 / 100,
      'enterprise_id' => $enterprise->id,
      'category_id' => 4,
      'supplier_id' => 1,
      'is_favorite' => true
    ]);

    Product::create([
      'name' => 'Bose SoundLink Revolve',
      'description' => 'Bose portable Bluetooth speaker with 360-degree sound and water-resistant design.',
      'status' => 'available',
      'stock' => 60,
      'supplier_price' => 480000,
      'sale_price' => 549000,
      'thumbnail' => 'storage/product_thumbnails/bsr.jpg',
      'barcode' => '1234567897',
      'minimal_safe_stock' => 15,
      'discount' => 0,
      'enterprise_id' => $enterprise->id,
      'category_id' => 3,
      'supplier_id' => 1
    ]);

    Product::create([
      'name' => 'Sony PlayStation 5',
      'description' => 'Sony PlayStation 5 console with ultra-fast SSD and 4K gaming capabilities.',
      'status' => 'available',
      'stock' => 25,
      'supplier_price' => 2150000,
      'sale_price' => 2490000,
      'thumbnail' => 'storage/product_thumbnails/ps5.jpg',
      'barcode' => '1234567898',
      'minimal_safe_stock' => 8,
      'discount' => 5 / 100,
      'enterprise_id' => $enterprise->id,
      'category_id' => 5,
      'supplier_id' => 1
    ]);

    Product::create([
      'name' => 'GoPro HERO11 Black',
      'description' => 'GoPro HERO11 Black action camera with 5.3K video and waterproof design.',
      'status' => 'available',
      'stock' => 45,
      'supplier_price' => 1600000,
      'sale_price' => 1790000,
      'thumbnail' => 'storage/product_thumbnails/gopro.jpg',
      'barcode' => '1234567899',
      'minimal_safe_stock' => 12,
      'discount' => 10 / 100,
      'enterprise_id' => $enterprise->id,
      'category_id' => 4,
      'supplier_id' => 1,
      'is_favorite' => true
    ]);
  }
}
