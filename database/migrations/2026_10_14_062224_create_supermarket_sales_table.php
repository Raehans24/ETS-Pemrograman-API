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
        Schema::create('supermarket_sales', function (Blueprint $table) {
            $table->id();
            $table->string('invoice')->nullable();
            $table->string('stock_code')->nullable();
            $table->text('description')->nullable();
            $table->integer('quantity')->nullable();
            $table->dateTime('invoice_date')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->string('customer_id')->nullable();
            $table->string('country')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supermarket_sales');
    }
};
