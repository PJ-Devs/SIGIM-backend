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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->enum('payment_method', ['cash', 'debit', 'credit', 'transferency'])->nullable();
            $table->string('user_id', 26);
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->nullable();
            $table->double('total_price')->default(0);
            $table->foreignId('client_id')->constrained()->onUpdate('cascade')->onDelete('restrict')->default(1)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
