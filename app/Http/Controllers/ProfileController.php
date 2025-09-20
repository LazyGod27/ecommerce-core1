<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'bio' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
            'preferred_language' => 'in:en,es,fr,de,ja,ko,zh',
            'timezone' => 'string|max:50',
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $avatarPath;
        }

        $user->update($validated);

        return redirect()->route('profile.index')->with('success', 'Profile updated successfully!');
    }

    public function changePassword()
    {
        return view('profile.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('profile.index')->with('success', 'Password changed successfully!');
    }

    public function orders()
    {
        $user = Auth::user();
        $orders = $user->orders()->with(['items.product'])->latest()->paginate(10);
        return view('profile.orders', compact('orders'));
    }

    public function addresses()
    {
        $user = Auth::user();
        return view('profile.addresses', compact('user'));
    }

    public function updateAddress(Request $request)
    {
        $validated = $request->validate([
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
        ]);

        $user = Auth::user();
        $user->update($validated);

        return redirect()->route('profile.addresses')->with('success', 'Address updated successfully!');
    }

    public function preferences()
    {
        $user = Auth::user();
        return view('profile.preferences', compact('user'));
    }

    public function updatePreferences(Request $request)
    {
        $validated = $request->validate([
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
            'preferred_language' => 'in:en,es,fr,de,ja,ko,zh',
            'timezone' => 'string|max:50',
        ]);

        $user = Auth::user();
        $user->update($validated);

        return redirect()->route('profile.preferences')->with('success', 'Preferences updated successfully!');
    }

    public function deleteAccount()
    {
        return view('profile.delete-account');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'confirmation_text' => 'required|in:DELETE',
            'password' => 'required|current_password',
        ]);

        $user = Auth::user();

        // Delete avatar if exists
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Logout and delete user
        Auth::logout();
        $user->delete();

        return redirect()->route('home')->with('success', 'Account deleted successfully.');
    }
}
