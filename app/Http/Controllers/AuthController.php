<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    // ── Login ─────────────────────────────────────────────────────────────────

    public function login(Request $request): RedirectResponse
    {
        $request->validate(
            [
                'login'    => ['required', 'string'],   // phone number OR email
                'password' => ['required'],
            ],
            [
                'login.required' => 'Please enter your phone number or email.',
            ],
        );

        $login = trim($request->input('login'));

        // An "@" means email; anything else is treated as a phone number.
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'contact_number';
        $value = $field === 'email' ? $login : $this->normalizePhone($login);

        $credentials = [
            $field     => $value,
            'password' => $request->input('password'),
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('home'));
        }

        return back()
            ->withErrors(['login' => 'These credentials do not match our records.'], 'login')
            ->withInput($request->only('login'))
            ->withFragment('loginModal'); // keeps the anchor so JS auto-opens the modal
    }

    // ── Register ──────────────────────────────────────────────────────────────

    public function register(Request $request): RedirectResponse
    {
        // Normalise the phone first so spaces/dashes can't bypass the unique check.
        if ($request->filled('contact_number')) {
            $request->merge(['contact_number' => $this->normalizePhone($request->input('contact_number'))]);
        }

        $data = $request->validateWithBag(
            'register',
            [
                // Names are optional: blank ones become "Customer" / the user's id below.
                'first_name'      => ['nullable', 'string', 'max:100'],
                'last_name'       => ['nullable', 'string', 'max:100'],
                'email'           => ['nullable', 'email', 'max:255', 'unique:users,email'],
                'contact_number'  => ['required', 'string', 'regex:/^\+?[0-9]{7,15}$/', 'max:20', 'unique:users,contact_number'],
                'address'         => ['nullable', 'string', 'max:500'],
                'profile_picture' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
                'password'        => ['required', 'confirmed', Password::min(8)],
            ],
            [
                'email.unique'            => 'An account with this email already exists.',
                'contact_number.required' => 'Phone number is required.',
                'contact_number.regex'    => 'Please enter a valid phone number (e.g. 09XXXXXXXXX).',
                'contact_number.unique'   => 'An account with this phone number already exists.',
                'profile_picture.image'   => 'The profile photo must be an image.',
                'profile_picture.max'     => 'The profile photo must not exceed 2 MB.',
                'password.confirmed'      => 'The passwords do not match.',
            ],
        );

        // Store profile picture if provided
        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')
                ->store('profile_pictures', 'public');
            $data['profile_picture'] = 'storage/' . $path;
        }

        $data['password'] = Hash::make($data['password']);

        // Blank names fall back to "Customer" + the new user's id (e.g. "Customer 9").
        // The id only exists after the insert, so a blank last name is filled in afterwards.
        $data['first_name'] = trim((string) ($data['first_name'] ?? '')) ?: 'Customer';
        $needsIdAsLastName  = trim((string) ($data['last_name'] ?? '')) === '';
        $data['last_name']  = $needsIdAsLastName ? '' : trim($data['last_name']);

        $user = DB::transaction(function () use ($data, $needsIdAsLastName) {
            $user = User::create($data);

            if ($needsIdAsLastName) {
                $user->update(['last_name' => (string) $user->id]);
            }

            return $user;
        });

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->route('home');
    }

    // ── Logout ────────────────────────────────────────────────────────────────

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    /** Strip spaces, dashes, dots and brackets; keep digits and a leading "+". */
    private function normalizePhone(string $phone): string
    {
        $phone = trim($phone);
        $plus  = str_starts_with($phone, '+') ? '+' : '';

        return $plus . preg_replace('/\D+/', '', $phone);
    }
}