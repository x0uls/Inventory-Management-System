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
            $table->id('sales_id');
            $table->unsignedBigInteger('batch_id');
            $table->integer('quantity');
            $table->decimal('unit_price', 10,2);
            $table->decimal('total_amount', 10,2)->default(0);
            $table->datetime('date');
            $table->timestamps();

            $table->foreign('batch_id')->references('batch_id')->on('batch');
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
