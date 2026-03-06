<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@khazprokhir.com',
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Sortir User',
            'email' => 'sortir@khazprokhir.com',
            'role' => 'sortir',
        ]);

        User::factory()->create([
            'name' => 'Supervisor User',
            'email' => 'supervisor@khazprokhir.com',
            'role' => 'supervisor',
        ]);
    }
}
