<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($validated, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            if ($request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json(['success' => true], 200);
            }
            
            return redirect()->intended('/');
        }

        if ($request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json(['success' => false, 'errors' => ['email' => __('auth.failed')]], 422);
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    /**
     * Show the registration form
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'confirmed', Password::min(8)],
            'agree_terms' => ['required', 'accepted'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        $request->session()->regenerate();

        if ($request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json(['success' => true], 201);
        }

        return redirect('/');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
