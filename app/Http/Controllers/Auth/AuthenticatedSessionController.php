<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\CaptchaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function __construct(
        private readonly CaptchaService $captcha,
    ) {}

    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login', [
            'captchaQuestion' => $this->captcha->generate(),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        if (! $this->captcha->verify($request->integer('captcha'))) {
            $this->captcha->clear();

            throw ValidationException::withMessages([
                'captcha' => trans('auth.captcha'),
            ]);
        }

        // CAPTCHA benar — hapus dari Session agar tidak dapat dipakai ulang.
        $this->captcha->clear();

        $request->authenticate();

        $request->session()->regenerate();

        // Check if intended URL is the notification API to avoid incorrect redirects
        if ($request->session()->get('url.intended') && str_contains($request->session()->get('url.intended'), '/notifications/hcs-ready')) {
            $request->session()->forget('url.intended');
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
