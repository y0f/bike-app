<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('loan_bikes', function (Blueprint $table) {
            $table->id();
            $table->string('identifier')->unique();
            $table->string('brand');
            $table->string('model');
            $table->string('type');
            $table->string('image')->nullable();
            $table->string('color');
            $table->text('specifications')->nullable();
            $table->foreignId('service_point_id')->constrained();
            $table->string('status')->default('available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_bikes');
    }
};
