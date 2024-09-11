<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', length: 100);
            $table->string('description', length: 100)->nullable();
            $table->enum('status', ['deleted', 'unavailable', 'available'])->default('available');
            $table->integer('stock')->default(0);
            $table->decimal('supplier_price', 10, 2);
            $table->decimal('sale_price', 10, 2);
            $table->string('thumbnail', length: 100)->nullable();
            $table->string('barcode',length: 100)->nullable();
            $table->integer('minimal_safe_stock')->default(0);
            $table->double('discount')->nullable();
            $table->foreignId('enterprise_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('supplier_id')->constrained()->onUpdate('cascade')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
