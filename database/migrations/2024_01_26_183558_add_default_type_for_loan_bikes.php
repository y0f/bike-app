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
        Schema::table('loan_bikes', function (Blueprint $table) {
            $table->string('type')->default('other')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_bikes', function (Blueprint $table) {
            $table->string('type')->default('')->change(); // You may need to specify the original default value here
        });
    }
};
