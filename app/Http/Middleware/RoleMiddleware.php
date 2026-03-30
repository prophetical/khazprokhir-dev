<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        $userRole = $user->role;

        // 1. Admin selalu memiliki akses penuh
        if ($userRole === 'admin') {
            return $next($request);
        }

        // 2. Jangan beri akses Manajemen Target dan Akun untuk role non admin
        if ($request->is('targets*') || $request->is('users*')) {
            abort(403, 'Hanya Administrator yang dapat mengakses halaman ini.');
        }
        // 3. Logika role supervisor: hanya izinkan GET, jangan izinkan POST, PUT, DELETE
        if ($userRole === 'supervisor') {
            if ($request->isMethod('GET')) {
                return $next($request);
            }
            abort(403, 'Anda login dengan role SUPERVISOR. Anda tidak memiliki hak akses untuk melakukan perubahan data.');
        }

        // 4. Sortir & Kemas logic: akses ke semua halaman kecuali manajemen target
        if (in_array($userRole, ['sortir', 'kemas'])) {
            return $next($request);
        }

        // 5. Role Unassigned (User Baru): Terbatas pada Dashboard & Laporan Harian
        $allowedRoutes = [
            'dashboard',
            'laporan-harian*',
            'profile*',
            'logout'
        ];

        foreach ($allowedRoutes as $pattern) {
            if ($request->is($pattern)) {
                return $next($request);
            }
        }

        abort(403, 'Akun Anda belum memiliki peran (role). Silakan hubungi Administrator untuk aktivasi.');
    }
}
