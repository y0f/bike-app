<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('brand')->nullable();
            $table->string('type')->nullable();
            $table->integer('stock')->default(0);
            $table->decimal('price', 10, 2)->default(0);
            $table->integer('low_stock_threshold')->default(10);
            $table->unsignedBigInteger('service_point_id'); 
            $table->foreign('service_point_id')->references('id')->on('service_points'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};
