<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Enterprise;
use App\Models\Role;
use App\Models\Supplier;
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
    ]);

    Role::create([
      "name" => "Enterprise Owner",
      "description" => "Enterprise Owner",
    ]);

    Role::create([
      "name" => "Admin",
      "description" => "Administrator",
    ]);

    Role::create([
      "name" => "User",
      "description" => "User",
    ]);

    $user = $enterprise->users()->create([
      "name" => "Pedro Osorio",
      "email" => "pedroo@gmail.com",
      "password" => bcrypt("12345678"),
      "role_id" => 2,
    ]);

    error_log("Test token: " . $user->createToken("api_token")->plainTextToken);

    Category::create([
      "name" => "Telefonos",
      "description" => "Categoria de Telefonos",
    ]);

    Category::create([
      "name" => "Audio",
      "description" => "Categoria de Audio",
    ]);

    Category::create([
      "name" => "Computadoras",
      "description" => "Categoria de Computadoras",
    ]);

    Category::create([
      "name" => "Camaras",
      "description" => "Categoria de Camaras",
    ]);

    Category::create([
      "name" => "Videojuegos",
      "description" => "Categoria de Videojuegos",
    ]);

    Supplier::create([
      "name" => "nose",
      "email" => "tampocose",
      "phone_number" => "tampocose",
      "NIT" => "nose",
    ]);

    Product::create([
      'name' => 'Apple iPhone 14',
      'description' => 'Latest Apple smartphone with 128GB storage, 6.1-inch display, and A15 Bionic chip.',
      'status' => 'available',
      'stock' => 35,
      'supplier_price' => 800,
      'sale_price' => 999,
      'thumbnail' => 'storage/product_thumbnails/iphone14.jpg',
      'barcode' => '1234567890',
      'minimal_safe_stock' => 40,
      'discount' => 5,
      'enterprise_id' => $enterprise->id,
      'category_id' => 1,
      'supplier_id' => 1
    ]);

    Product::create([
      'name' => 'Samsung Galaxy S23',
      'description' => 'Samsung Galaxy S23 with 256GB storage, 6.2-inch AMOLED display, and Snapdragon 8 Gen 2 processor.',
      'status' => 'available',
      'stock' => 50,
      'supplier_price' => 750,
      'sale_price' => 950,
      'thumbnail' => 'storage/product_thumbnails/s23.jpg',
      'barcode' => '1234567891',
      'minimal_safe_stock' => 15,
      'discount' => 10,
      'enterprise_id' => $enterprise->id,
      'category_id' => 1,
      'supplier_id' => 1
    ]);

    Product::create([
      'name' => 'Sony WH-1000XM5',
      'description' => 'Sony noise-cancelling wireless headphones with 30-hour battery life and premium sound quality.',
      'status' => 'available',
      'stock' => 75,
      'supplier_price' => 280,
      'sale_price' => 349,
      'thumbnail' => 'storage/product_thumbnails/sonyhw.jpg',
      'barcode' => '1234567892',
      'minimal_safe_stock' => 20,
      'discount' => 15,
      'enterprise_id' => $enterprise->id,
      'category_id' => 2,
      'supplier_id' => 1
    ]);

    Product::create([
      'name' => 'Dell XPS 13',
      'description' => 'Dell XPS 13 laptop with Intel i7 processor, 16GB RAM, 512GB SSD, and 13.3-inch 4K display.',
      'status' => 'available',
      'stock' => 20,
      'supplier_price' => 1000,
      'sale_price' => 1200,
      'thumbnail' => 'storage/product_thumbnails/dell.jpg',
      'barcode' => '1234567893',
      'minimal_safe_stock' => 8,
      'discount' => 7,
      'enterprise_id' => $enterprise->id,
      'category_id' => 3,
      'supplier_id' => 1
    ]);

    Product::create([
      'name' => 'Google Pixel 8',
      'description' => 'Google Pixel 8 with 128GB storage, Google Tensor G3 processor, and 50MP dual camera system.',
      'status' => 'available',
      'stock' => 40,
      'supplier_price' => 650,
      'sale_price' => 799,
      'thumbnail' => 'storage/product_thumbnails/pixel.jpg',
      'barcode' => '1234567894',
      'minimal_safe_stock' => 10,
      'discount' => 12,
      'enterprise_id' => $enterprise->id,
      'category_id' => 1,
      'supplier_id' => 1
    ]);

    Product::create([
      'name' => 'Apple MacBook Pro 16"',
      'description' => 'Apple MacBook Pro 16-inch with M2 Max chip, 32GB RAM, and 1TB SSD.',
      'status' => 'available',
      'stock' => 15,
      'supplier_price' => 2000,
      'sale_price' => 2499,
      'thumbnail' => 'storage/product_thumbnails/mb16.jpg',
      'barcode' => '1234567895',
      'minimal_safe_stock' => 5,
      'discount' => 8,
      'enterprise_id' => $enterprise->id,
      'category_id' => 3,
      'supplier_id' => 1
    ]);

    Product::create([
      'name' => 'Nikon Z7 II Camera',
      'description' => 'Nikon Z7 II mirrorless camera with 45.7MP full-frame sensor and 4K video recording.',
      'status' => 'available',
      'stock' => 10,
      'supplier_price' => 2600,
      'sale_price' => 2999,
      'thumbnail' => 'storage/product_thumbnails/nikon.jpg',
      'barcode' => '1234567896',
      'minimal_safe_stock' => 3,
      'discount' => 5,
      'enterprise_id' => $enterprise->id,
      'category_id' => 4,
      'supplier_id' => 1
    ]);

    Product::create([
      'name' => 'Bose SoundLink Revolve',
      'description' => 'Bose portable Bluetooth speaker with 360-degree sound and water-resistant design.',
      'status' => 'available',
      'stock' => 60,
      'supplier_price' => 160,
      'sale_price' => 199,
      'thumbnail' => 'storage/product_thumbnails/bsr.jpg',
      'barcode' => '1234567897',
      'minimal_safe_stock' => 15,
      'discount' => 12,
      'enterprise_id' => $enterprise->id,
      'category_id' => 3,
      'supplier_id' => 1
    ]);

    Product::create([
      'name' => 'Sony PlayStation 5',
      'description' => 'Sony PlayStation 5 console with ultra-fast SSD and 4K gaming capabilities.',
      'status' => 'available',
      'stock' => 25,
      'supplier_price' => 450,
      'sale_price' => 499,
      'thumbnail' => 'storage/product_thumbnails/ps5.jpg',
      'barcode' => '1234567898',
      'minimal_safe_stock' => 8,
      'discount' => 3,
      'enterprise_id' => $enterprise->id,
      'category_id' => 5,
      'supplier_id' => 1
    ]);

    Product::create([
      'name' => 'GoPro HERO11 Black',
      'description' => 'GoPro HERO11 Black action camera with 5.3K video and waterproof design.',
      'status' => 'available',
      'stock' => 45,
      'supplier_price' => 400,
      'sale_price' => 499,
      'thumbnail' => 'storage/product_thumbnails/gopro.jpg',
      'barcode' => '1234567899',
      'minimal_safe_stock' => 12,
      'discount' => 10,
      'enterprise_id' => $enterprise->id,
      'category_id' => 4,
      'supplier_id' => 1
    ]);
  }
}
