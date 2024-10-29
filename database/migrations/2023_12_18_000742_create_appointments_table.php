<?php

use App\Models\Slot;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->text('description')->nullable();
            $table->foreignId('customer_bike_id')
            ->constrained('customer_bikes')
            ->cascadeOnDelete();
            $table->foreignIdFor(Slot::class);
            $table->string('status')->default('created');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
