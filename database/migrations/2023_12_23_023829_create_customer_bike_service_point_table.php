<?php

use App\Models\CustomerBike;
use App\Models\ServicePoint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // these need to be in alphabetical order
        Schema::create('customer_bike_service_point', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(CustomerBike::class);
            $table->foreignIdFor(ServicePoint::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customerbike_servicepoint');
    }
};
