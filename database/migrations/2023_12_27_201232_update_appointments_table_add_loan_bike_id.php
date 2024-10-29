<?php

use App\Models\LoanBike;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->boolean('has_loan_bike')->default(false);
            $table->foreignIdFor(LoanBike::class, 'loan_bike_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn('has_loan_bike');
            $table->dropColumn('loan_bike_id');
        });
    }
};
