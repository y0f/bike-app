<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServicePoint;

class ServicePointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ServicePoint::factory(1)->create();
    }
}
