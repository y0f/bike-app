<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customer_bikes', function (Blueprint $table) {
            $table->id();
            $table->string('identifier')->unique();
            $table->string('brand');
            $table->string('model');
            $table->string('type');
            $table->string('image')->nullable();
            $table->string('color');
            $table->text('specifications')->nullable();
            $table->foreignIdFor(User::class, 'owner_id')->nullable();
            $table->timestamps();

            $table->foreign('owner_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bikes');
    }
};
