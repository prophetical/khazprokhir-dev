<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function listUsers(int $perPage = 15)
    {
        return User::paginate($perPage);
    }

    public function createUser(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'permissions' => $data['permissions'] ?? [],
        ]);
    }

    public function updateUser(User $user, array $data): bool
    {
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->role = $data['role'];
        $user->permissions = $data['permissions'] ?? [];
        
        return $user->save();
    }

    public function deleteUser(User $user): bool
    {
        if ($user->id === auth()->id()) {
            return false;
        }

        return $user->delete();
    }
}
