<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    // ── Login ─────────────────────────────────────────────────────────────────

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate(
            [
                'email'    => ['required', 'email'],
                'password' => ['required'],
            ],
            [],               // custom messages (optional)
        );

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('home'));
        }

        return back()
            ->withErrors(['email' => 'These credentials do not match our records.'], 'login')
            ->withInput($request->only('email'))
            ->withFragment('loginModal'); // keeps the anchor so JS auto-opens the modal
    }

    // ── Register ──────────────────────────────────────────────────────────────

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate(
            [
                'first_name'      => ['required', 'string', 'max:100'],
                'last_name'       => ['required', 'string', 'max:100'],
                'email'           => ['required', 'email', 'max:255', 'unique:users,email'],
                'contact_number'  => ['nullable', 'string', 'max:20'],
                'address'         => ['nullable', 'string', 'max:500'],
                'profile_picture' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
                'password'        => ['required', 'confirmed', Password::min(8)],
            ],
            [
                'email.unique'            => 'An account with this email already exists.',
                'profile_picture.image'   => 'The profile photo must be an image.',
                'profile_picture.max'     => 'The profile photo must not exceed 2 MB.',
                'password.confirmed'      => 'The passwords do not match.',
            ],
        );

        // Store profile picture if provided
        if ($request->hasFile('profile_picture')) {
            $data['profile_picture'] = $request->file('profile_picture')
                ->store('profile_pictures', 'public');
        }

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->route('home');
    }

    // ── Logout ────────────────────────────────────────────────────────────────

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}