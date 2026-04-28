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
        // Menggunakan route name agar tidak bergantung pada pola URL yang bisa berubah
        if ($request->routeIs('targets.*') || $request->routeIs('users.*')) {
            abort(403, 'Hanya Administrator yang dapat mengakses halaman ini.');
        }

        // 3. Logika role supervisor: hanya izinkan GET, jangan izinkan POST, PUT, DELETE
        if ($userRole === 'supervisor') {
            if ($request->isMethod('GET')) {
                return $next($request);
            }
            abort(403, 'Anda login dengan role SUPERVISOR. Anda tidak memiliki hak akses untuk melakukan perubahan data.');
        }

        // 4. Logika role khazai: CRUD penuh pada Registrasi HCS, read-only halaman lain
        if ($userRole === 'khazai') {
            // Boleh semua method pada route registrasi hcs
            if ($request->routeIs('hcs-khazai-registration.*')) {
                return $next($request);
            }
            // Halaman lain: hanya boleh GET
            if ($request->isMethod('GET')) {
                return $next($request);
            }
            abort(403, 'Role KHAZAI hanya dapat melakukan perubahan pada modul Registrasi Penerimaan HCS.');
        }

        // 5. Logika role khazverutas: CRUD penuh pada X Pengganti, read-only halaman lain
        if ($userRole === 'khazverutas') {
            // Boleh semua method pada route x-pengganti
            if ($request->routeIs('x-pengganti.*')) {
                return $next($request);
            }
            // Halaman lain: hanya boleh GET
            if ($request->isMethod('GET')) {
                return $next($request);
            }
            abort(403, 'Role KHAZVERUTAS hanya dapat melakukan perubahan pada modul X Pengganti.');
        }

        // 6. Sortir & Kemas logic: akses ke semua halaman kecuali manajemen target dan akun
        if (in_array($userRole, ['sortir', 'kemas'])) {
            // Sudah diproteksi di bagian admin (cek nomor 2)
            return $next($request);
        }

        // 7. Role Unassigned (User Baru): Terbatas pada Dashboard & Laporan Harian
        $allowedRoutes = [
            'dashboard',
            'laporan-harian.*',
            'profile.*',
        ];

        foreach ($allowedRoutes as $pattern) {
            if ($request->routeIs($pattern)) {
                return $next($request);
            }
        }

        // Izinkan juga route logout (tidak punya nama prefix khusus)
        if ($request->routeIs('logout')) {
            return $next($request);
        }

        abort(403, 'Akun Anda belum memiliki peran (role). Silakan hubungi Administrator untuk aktivasi.');
    }
}
