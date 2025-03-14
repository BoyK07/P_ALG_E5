<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.register', [
            'method' => 'login'
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Set form view to 'login' for any errors
        session()->flash('_form_view', 'login');

        try {
            // Attempt to authenticate the user
            $request->authenticate();

            // If successful, regenerate the session
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));

        } catch (\Exception $e) {
            // Log the error
            Log::error('Login failed: ' . $e->getMessage());

            // Add the error with login context
            return back()
                ->withInput($request->except(['password']))
                ->with('_form_view', 'login')
                ->withErrors(['auth' => 'De opgegeven combinatie van inloggegevens is onjuist.']);
        }
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
