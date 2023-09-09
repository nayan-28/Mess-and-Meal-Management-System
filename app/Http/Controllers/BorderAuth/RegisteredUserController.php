<?php

namespace App\Http\Controllers\BorderAuth;

use App\Http\Controllers\BorderController;
use App\Models\Border;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends BorderController
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('border.auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:'.Border::class],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    $user = Border::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    event(new Registered($user));

    return redirect()->route('border.login')->with('success', 'Registration successful. Please log in.');
}
}
