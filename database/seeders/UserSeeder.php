<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()
        ->create(
            [
          'role_id' => Role::whereName('admin')->first()->id,
          'phone'   => '0655443322',
        ]
        );
    }
}
