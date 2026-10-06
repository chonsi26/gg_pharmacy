<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Setting;
use App\Models\Staff;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $lowStockItems = Stock::where('is_active', 1)
            ->where('quantity', '<=', 10)
            ->count();

        return view('staffs.dashboard', $this->brandingData() + [
            'staff'          => Auth::guard('staff')->user(),
            'map_link'       => Setting::get('map_link'),
            'totalMedicines' => Product::count(),
            'totalCustomers' => User::count(),
            'lowStockItems'  => $lowStockItems,
        ]);
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

        // Only active staff accounts may sign in.
        $credentials['is_active'] = true;

        if (Auth::guard('staff')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('staff.dashboard'));
        }

        return back()
            ->withErrors(['email' => 'These credentials do not match our records, or the account is inactive.'])
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

        // The Staff model's "hashed" cast hashes the password on save.
        $staff = Staff::create($data);

        Auth::guard('staff')->login($staff);

        $request->session()->regenerate();

        return redirect()->route('staff.dashboard')
            ->with('status', 'Account created! Welcome to the team.');
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