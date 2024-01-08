<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CustomerBike;
use App\Models\User;
use App\Models\ServicePoint;

class CustomerBikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::whereName('Owner')->first();
        $firstServicePoint = ServicePoint::first();

        $customerBikes = CustomerBike::factory(5)->create([
            'owner_id' => $user->id,
        ]);

        // Attach each customer bike to the first service point
        foreach ($customerBikes as $customerBike) {
            $customerBike->servicePoints()->attach($firstServicePoint->id);
        }
    }
}
