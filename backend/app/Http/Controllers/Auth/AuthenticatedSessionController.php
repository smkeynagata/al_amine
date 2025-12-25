<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): RedirectResponse
    {
        return redirect()->route('home', ['modal' => 'login']);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();

        if ($user && $user->role === 'PATIENT' && is_null($user->email_verified_at)) {
            $user->forceFill(['email_verified_at' => now()])->save();
        }

        // Redirect based on user role
        $redirectRoute = match ($user?->role) {
            'ADMIN' => 'admin.dashboard',
            'PATIENT' => 'patient.dashboard',
            'PRATICIEN' => 'praticien.dashboard',
            'SECRETAIRE' => 'secretaire.dashboard',
            default => 'patient.dashboard',
        };

        // Debug log (à retirer en production)
        \Log::info("Login successful", [
            'user_id' => $user->id,
            'email' => $user->email,
            'role' => $user->role,
            'redirect_route' => $redirectRoute,
            'redirect_url' => route($redirectRoute)
        ]);

        return redirect()->route($redirectRoute);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
