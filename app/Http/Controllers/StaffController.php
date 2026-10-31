<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Setting;
use App\Models\Staff;
use App\Models\Stock;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class StaffController extends Controller
{
    // ── Show forms ───────────────────────────────────────────────────────────

    public function showLogin(): View
    {
        return view('staffs.login', $this->brandingData());
    }

    public function showRegister(): View
    {
        return view('staffs.register', $this->brandingData());
    }

    // ── Dashboard (same screen as the admin dashboard) ───────────────────────

    public function dashboard(): View
    {
        // Same definitions as AdminController::index() so both dashboards agree.
        $lowStockItems = Stock::where('is_active', 1)
            ->where('quantity', '>', 0)
            ->where('quantity', '<=', 10)
            ->count();

        // Active batches with stock left that expire within the next 10 days
        // (today through today + 10). Already-expired batches are excluded.
        $expiringStocks = Stock::where('is_active', 1)
            ->where('quantity', '>', 0)
            ->whereNotNull('expiry_date')
            ->whereBetween('expiry_date', [now()->startOfDay(), now()->addDays(10)->endOfDay()])
            ->count();

        return view('staffs.dashboard', $this->brandingData() + [
            'staff'          => Auth::guard('staff')->user(),
            'map_link'       => Setting::get('map_link'),
            'totalMedicines' => Product::count(),
            'lowStockItems'  => $lowStockItems,
            'expiringStocks' => $expiringStocks,
        ]);
    }

    // ── Profile ───────────────────────────────────────────────────────────────

    public function profile(): View
    {
        return view('staffs.profile', $this->brandingData() + [
            'staff' => Auth::guard('staff')->user(),
        ]);
    }

    /**
     * Update the currently authenticated staff member's account details.
     */
    public function updateProfile(Request $request): RedirectResponse|JsonResponse
    {
        $staff = Auth::guard('staff')->user();

        $data = $request->validate([
            'first_name'     => ['required', 'string', 'max:100'],
            'last_name'      => ['required', 'string', 'max:100'],
            'email'          => ['required', 'email', 'max:255', 'unique:staff,email,' . $staff->id],
            'contact_number' => ['nullable', 'regex:/^9\d{9}$/'],
            'address'        => ['nullable', 'string', 'max:500'],
        ], [
            'email.unique'         => 'A staff account with this email already exists.',
            'contact_number.regex' => 'Enter a valid 10-digit PH mobile number (e.g. 9123456789).',
        ]);

        $data['contact_number'] = !empty($data['contact_number'])
            ? '+63' . $data['contact_number']
            : null;

        $staff->update($data);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Profile information updated.',
                'staff'   => [
                    'first_name'     => $staff->first_name,
                    'last_name'      => $staff->last_name,
                    'email'          => $staff->email,
                    'contact_number' => $staff->contact_number,
                    'address'        => $staff->address,
                ],
            ]);
        }

        return back()->with('status', 'Profile information updated.');
    }

    /**
     * Update the currently authenticated staff member's profile photo.
     */
    public function updatePhoto(Request $request): JsonResponse
    {
        $staff = Auth::guard('staff')->user();

        $request->validate([
            'profile_picture' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'profile_picture.required' => 'Please choose a photo to upload.',
            'profile_picture.image'    => 'The file must be an image.',
            'profile_picture.mimes'    => 'Allowed formats: JPG, PNG, WEBP.',
            'profile_picture.max'      => 'The photo must not exceed 2 MB.',
        ]);

        // Remove the old file from disk (only files we stored ourselves).
        if ($staff->profile_picture && Str::startsWith($staff->profile_picture, 'storage/')) {
            Storage::disk('public')->delete(Str::after($staff->profile_picture, 'storage/'));
        }

        $path = $request->file('profile_picture')->store('staff_profile_pictures', 'public');

        $staff->update(['profile_picture' => 'storage/' . $path]);

        return response()->json([
            'message'         => 'Profile photo updated.',
            'profile_picture' => asset($staff->profile_picture),
        ]);
    }

    /**
     * Update the currently authenticated staff member's password.
     */
    public function updatePassword(Request $request): RedirectResponse|JsonResponse
    {
        $staff = Auth::guard('staff')->user();

        $data = $request->validate([
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'confirmed', Password::min(8)],
        ], [
            'password.confirmed' => 'The passwords do not match.',
        ]);

        if (!$this->passwordMatches($data['current_password'], $staff->password)) {
            $errors = ['current_password' => ['The current password is incorrect.']];

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'The current password is incorrect.',
                    'errors'  => $errors,
                ], 422);
            }

            return back()->withErrors($errors);
        }

        // Hash explicitly with bcrypt. If the model also has a "hashed" cast,
        // Laravel sees the value is already hashed and won't hash it twice.
        $staff->update(['password' => Hash::driver('bcrypt')->make($data['password'])]);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Security settings updated.']);
        }

        return back()->with('status', 'Security settings updated.');
    }

    // ── Dashboard charts (JSON) ───────────────────────────────────────────────
    // These reuse AdminChartController so the staff and admin dashboards always
    // show identical numbers. Only the route/guard differs (auth:staff).

    public function weeklySales(Request $request): JsonResponse
    {
        return app(AdminChartController::class)->weeklySales($request);
    }

    public function orderStatus(Request $request): JsonResponse
    {
        return app(AdminChartController::class)->orderStatus($request);
    }

    public function revenueByProduct(Request $request): JsonResponse
    {
        return app(AdminChartController::class)->revenueByProduct($request);
    }

    public function recentTransactions(Request $request): JsonResponse
    {
        return app(AdminChartController::class)->recentTransactions($request);
    }

    // ── Login ─────────────────────────────────────────────────────────────────

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Tell a correctly-authenticated but still-pending applicant why they can't get in.
        $pending = Staff::where('email', $credentials['email'])->first();

        // Upgrade accounts whose password was stored as plain text (or any
        // non-bcrypt value) the first time the correct password is entered.
        if ($pending && $this->isLegacyPassword($pending->password)
            && hash_equals((string) $pending->password, $credentials['password'])) {
            $pending->forceFill(['password' => Hash::driver('bcrypt')->make($credentials['password'])])->save();
            $pending->refresh();
        }

        if ($pending
            && $pending->status === Staff::STATUS_PENDING
            && $this->passwordMatches($credentials['password'], $pending->password)) {
            return back()
                ->withErrors(['email' => 'Your account is still waiting for admin approval. Please try again once it has been accepted.'])
                ->withInput($request->only('email'));
        }

        // Only accepted ('registered') and active staff accounts may sign in.
        $credentials['status']    = Staff::STATUS_REGISTERED;
        $credentials['is_active'] = true;

        if (Auth::guard('staff')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('staff.dashboard'));
        }

        return back()
            ->withErrors(['email' => 'These credentials do not match our records, or the account is inactive or not yet approved.'])
            ->withInput($request->only('email'));
    }

    // ── Register ──────────────────────────────────────────────────────────────

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'first_name'      => ['required', 'string', 'max:100'],
            'last_name'       => ['required', 'string', 'max:100'],
            'email'           => ['required', 'email', 'max:255', 'unique:staff,email'],
            'contact_number'  => ['nullable', 'regex:/^9\d{9}$/'],
            'address'         => ['nullable', 'string', 'max:500'],
            'profile_picture' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'password'        => ['required', 'confirmed', Password::min(8)],
        ], [
            'email.unique'           => 'A staff account with this email already exists.',
            'contact_number.regex'   => 'Enter a valid 10-digit PH mobile number (e.g. 9123456789).',
            'password.confirmed'     => 'The passwords do not match.',
            'profile_picture.image'  => 'The profile photo must be an image.',
            'profile_picture.max'    => 'The profile photo must not exceed 2 MB.',
        ]);

        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('staff_profile_pictures', 'public');
            $data['profile_picture'] = 'storage/' . $path;
        } else {
            unset($data['profile_picture']);
        }

        $data['contact_number'] = !empty($data['contact_number'])
            ? '+63' . $data['contact_number']
            : null;

        // New sign-ups wait for an admin to accept them (Admin → Staffs → Requests).
        $data['status'] = Staff::STATUS_PENDING;

        // Hash explicitly with bcrypt (safe even if the model has a "hashed" cast).
        $data['password'] = Hash::driver('bcrypt')->make($data['password']);

        Staff::create($data);

        // Not logged in: the account can only be used once it is accepted.
        return redirect()->route('staff.login')
            ->with('status', 'Registration submitted! You can log in once an admin approves your account.');
    }

    // ── Logout ────────────────────────────────────────────────────────────────

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('staff')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('staff.login')
            ->with('status', 'You have been logged out.');
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    /**
     * True when the stored value is not a recognised password hash
     * (e.g. plain text).
     */
    private function isLegacyPassword(?string $stored): bool
    {
        return $stored !== null
            && $stored !== ''
            && password_get_info($stored)['algoName'] === 'unknown';
    }

    /**
     * Hash::check() that returns false instead of throwing when the stored
     * value isn't a bcrypt hash ("This password does not use the Bcrypt algorithm").
     */
    private function passwordMatches(string $plain, ?string $hash): bool
    {
        if (!$hash) {
            return false;
        }

        try {
            return Hash::check($plain, $hash);
        } catch (\RuntimeException $e) {
            return false;
        }
    }

    /**
     * Site branding shared by every staff screen (same Settings the admin
     * login/register pages use).
     */
    private function brandingData(): array
    {
        return [
            'logo'          => Setting::get('logo'),
            'logo2'          => Setting::get('logo2'),
            'siteName'      => Setting::get('site_name', 'Pharmacy'),
            'address_line1' => Setting::get('address_line1'),
            'address_line2' => Setting::get('address_line2'),
        ];
    }
}