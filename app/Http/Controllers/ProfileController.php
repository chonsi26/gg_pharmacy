<?php

namespace App\Http\Controllers;

use App\Models\NavItem;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Show the authenticated user's profile page.
     */
    public function show(): View
    {
        $settings = Setting::allAsArray();
        $navItems = NavItem::topLevel()->with('children')->get();

        return view('profile', compact('settings', 'navItems'));
    }

    /**
     * Update the authenticated user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'first_name'      => ['required', 'string', 'max:255'],
            'last_name'       => ['required', 'string', 'max:255'],
            'email'           => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'contact_number'  => ['nullable', 'string', 'max:20'],
            'address'         => ['nullable', 'string', 'max:1000'],
            'profile_picture' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
        ]);

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old picture from storage if it exists and is stored locally
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            $path = $request->file('profile_picture')
                            ->store('profile_pictures', 'public');

            $validated['profile_picture'] = 'storage/' . $path;
        }

        // If the email changed, reset email_verified_at
        if ($validated['email'] !== $user->email) {
            $validated['email_verified_at'] = null;
        }

        $user->update($validated);

        return redirect()
            ->route('profile')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Update the authenticated user's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validateWithBag('password', [
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = Auth::user();

        // Verify the current password is correct
        if (! Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors(['current_password' => 'The current password you entered is incorrect.'], 'password')
                ->withInput()
                ->with('tab', 'password');
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()
            ->route('profile', ['tab' => 'password'])
            ->with('success', 'Password changed successfully! Please use your new password on your next login.');
    }
}