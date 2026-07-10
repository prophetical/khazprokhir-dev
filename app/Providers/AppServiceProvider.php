<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $router = $this->app->make(\Illuminate\Routing\Router::class);
        $router->aliasMiddleware('role', \App\Http\Middleware\RoleMiddleware::class);

        Password::defaults(fn () => Password::min(8)->mixedCase()->numbers()->symbols());

        // Otorisasi Laravel Pulse
        Gate::define('viewPulse', function (User $user) {
            return $user->role === 'admin';
        });
    }
}
