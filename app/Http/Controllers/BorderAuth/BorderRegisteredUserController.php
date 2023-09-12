<?php

namespace App\Http\Controllers\BorderAuth;

use App\Http\Controllers\Controller;
use App\Models\Border;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class BorderRegisteredUserController extends Controller
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
        'phone' => ['required', 'string', 'max:11', 'unique:borders', 'regex:/^0\d{10}$/'],
        'key' => [
            'required',
            'string',
            'size:8',
            'regex:/^(?=.*[A-Z])(?=.*[0-9])/',
        ],

        'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    $user = Border::create([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'key' => $request->key,
        'password' => Hash::make($request->password),
    ]);

    event(new Registered($user));

    return redirect()->route('border.login')->with('success', 'Registration successful. Please log in.');
}
}
