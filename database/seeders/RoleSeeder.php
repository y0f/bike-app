<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::factory(4)
            ->state(new Sequence(
                [
                    'name' => 'admin',
                    'description' => 'admin'
                ],
                [
                    'name' => 'mechanic',
                    'description' => 'monteur'
                ],
                [
                    'name' => 'owner',
                    'description' => 'eigenaar'
                ],
                [
                    'name' => 'staff',
                    'description' => 'leidinggevende in bedrijf'
                ],
            ))->create();
    }
}
