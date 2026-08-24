<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AdminController extends Controller
{
    // ── Dashboard ─────────────────────────────────────────────────────────────

    public function index(): View
    {
        return view('admin.index');
    }

    // ── Show forms ───────────────────────────────────────────────────────────

    public function showLogin(): View
    {
        return view('admin.login');
    }

    public function showRegister(): View
    {
        return view('admin.register');
    }

    // ── Login ─────────────────────────────────────────────────────────────────

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'login'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginField = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'username';

        $credentials = [
            $loginField => $request->input('login'),
            'password'  => $request->input('password'),
        ];

        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()
            ->withErrors(['login' => 'These credentials do not match our records.'])
            ->withInput($request->only('login'))
            ->withFragment('card');
    }

    // ── Register ──────────────────────────────────────────────────────────────

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'first_name'      => ['required', 'string', 'max:100'],
            'middle_name'     => ['nullable', 'string', 'max:100'],
            'last_name'       => ['required', 'string', 'max:100'],
            'email'           => ['required', 'email', 'max:255', 'unique:admins,email'],
            'phone_number'    => ['required', 'regex:/^9\d{9}$/'],
            'username'        => ['required', 'string', 'max:50', 'alpha_dash', 'unique:admins,username'],
            'password'        => ['required', 'confirmed', Password::min(8)],
            'profile_picture' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'email.unique'         => 'An account with this email already exists.',
            'username.unique'      => 'This username is already taken.',
            'phone_number.regex'   => 'Enter a valid 10-digit PH mobile number (e.g. 9123456789).',
            'password.confirmed'   => 'The passwords do not match.',
            'profile_picture.image'=> 'The profile photo must be an image.',
            'profile_picture.max'  => 'The profile photo must not exceed 2 MB.',
        ]);

        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('admin_profile_pictures', 'public');
            $data['profile_picture'] = 'storage/' . $path;
        } else {
            unset($data['profile_picture']);
        }

        $data['phone_number'] = '+63' . $data['phone_number'];
        $data['password'] = Hash::make($data['password']);

        $admin = Admin::create($data);

        Auth::guard('admin')->login($admin);

        $request->session()->regenerate();

        return redirect()->route('admin.dashboard')
            ->with('status', 'Account created! Welcome to GG Pharmacy.');
    }

    // ── Logout ────────────────────────────────────────────────────────────────

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('status', 'You have been logged out.');
    }
}
