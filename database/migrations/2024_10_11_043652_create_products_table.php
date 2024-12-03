<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create("products", function (Blueprint $table) {
      $table->id();
      $table->string("name", length: 100);
      $table->string("description", length: 100)->nullable();
      $table
        ->enum("status", ["unavailable", "available"])
        ->default("available");
      $table->integer("stock")->default(0);
      $table->integer("supplier_price")->min(0);
      $table->integer("sale_price")->min(0);
      $table->string("thumbnail", length: 100)->nullable();
      $table->string("barcode", length: 100)->nullable();
      $table->integer("minimal_safe_stock")->default(0);
      $table->double("discount")->default(0)->max(1);
      $table->boolean("is_favorite")->default(false);

      $table
        ->foreignUlid("enterprise_id")
        ->constrained()
        ->onUpdate("cascade")
        ->onDelete("cascade");
      $table
        ->foreignId("category_id")
        ->constrained()
        ->onUpdate("cascade")
        ->onDelete("restrict");
      $table
        ->foreignId("supplier_id")
        ->nullable()
        ->constrained()
        ->onUpdate("cascade")
        ->onDelete("set null");
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists("products");
  }
};
