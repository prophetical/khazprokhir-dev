<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function listUsers(int $perPage = 20)
    {
        return User::simplePaginate(20);
    }

    public function createUser(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
        ]);
        $user->role = $data['role'];
        $user->save();

        return $user;
    }

    public function updateUser(User $user, array $data): bool
    {
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->username = $data['username'];
        $user->role = $data['role'];
        
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
