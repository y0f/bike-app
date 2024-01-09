<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ServicePoint;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        $firstServicePoint = ServicePoint::first();
        $avatarUrl = asset('images/logo.png');

        $admin = User::factory()->role('admin')->create([
            'name' => 'Admin',
            'email' => 'admin@email.com',
            'phone' => '5555551234',
            'avatar_url' => $avatarUrl
        ]);
        $admin->servicePoints()->attach($firstServicePoint->id);

        $owner = User::factory()->role('owner')->create([
            'name' => 'Owner',
            'email' => 'owner@email.com',
            'phone' => '1155551234',
            'avatar_url' => $avatarUrl
        ]);
        $owner->servicePoints()->attach($firstServicePoint->id);

        $mechanic = User::factory()->role('mechanic')->create([
            'name' => 'Mechanic',
            'email' => 'mechanic@email.com',
            'phone' => '5544551234',
            'avatar_url' => $avatarUrl
        ]);
        $mechanic->servicePoints()->attach($firstServicePoint->id);
    }
}
