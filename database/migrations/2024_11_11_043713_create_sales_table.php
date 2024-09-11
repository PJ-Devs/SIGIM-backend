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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->double('discount', 10, 2)->nullable();
            $table->double('total_price', 10, 2);
            $table->foreignId('invoice_id')->constrained()->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('client_id')->constrained()->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('product_id')->constrained()->onUpdate('cascade')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
