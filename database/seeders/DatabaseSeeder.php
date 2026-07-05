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
        $roles = ['admin', 'sortir', 'supervisor', 'kemas', 'khazai', 'khazverutas'];
        $password = \Illuminate\Support\Facades\Hash::make('Peruri4321');

        foreach ($roles as $role) {
            $user = User::factory()->create([
                'name' => ucfirst($role) . ' User',
                'username' => $role,
                'email' => $role . '@khazprokhir.com',
                'password' => $password,
            ]);
            $user->role = $role;
            $user->save();
        }

        // User tanpa role (tamu)
        $tamu = User::factory()->create([
            'name' => 'Tamu User',
            'username' => 'tamu',
            'email' => 'tamu@khazprokhir.com',
            'password' => $password,
        ]);
        $tamu->role = null;
        $tamu->save();
    }
}
