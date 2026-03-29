<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    protected $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('users.index', ['users' => $this->service->listUsers()]);
    }

    public function create()
    {
        $modules = config('permissions.modules', []);
        return view('users.create', compact('modules'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:admin,sortir,supervisor,pengemasan'],
            'permissions' => ['nullable', 'array'],
        ]);

        $this->service->createUser($data);

        return redirect()->route('users.index')->with('success', 'User berhasil dibuat.');
    }

    public function edit(User $user)
    {
        $modules = config('permissions.modules', []);
        return view('users.edit', compact('user', 'modules'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'role' => ['required', 'string', 'in:admin,sortir,supervisor,pengemasan'],
            'permissions' => ['nullable', 'array'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $this->service->updateUser($user, $data);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if (!$this->service->deleteUser($user)) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
