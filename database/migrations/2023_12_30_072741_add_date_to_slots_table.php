<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    // need to revert this when i get back to this and add the date to the schedule
    public function up()
{
    Schema::table('slots', function (Blueprint $table) {
        $table->date('date')->nullable();
    });
}

public function down()
{
    Schema::table('slots', function (Blueprint $table) {
        $table->dropColumn('date');
    });
}
};
