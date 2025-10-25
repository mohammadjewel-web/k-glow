<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    /**
     * Display the admin profile page.
     */
    public function index()
    {
        $user = Auth::user();
        return view('admin.profile.index', compact('user'));
    }

    /**
     * Update profile information.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'bio' => 'nullable|string|max:1000',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->location = $request->location;
        $user->website = $request->website;
        $user->bio = $request->bio;
        $user->save();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully!'
            ]);
        }

        return redirect()->route('admin.profile.index')->with('success', 'Profile updated successfully!');
    }

    /**
     * Update profile avatar.
     */
    public function updateAvatar(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Delete old avatar if exists
        if ($user->avatar && Storage::disk('public')->exists('avatars/' . $user->avatar)) {
            Storage::disk('public')->delete('avatars/' . $user->avatar);
        }

        // Upload new avatar
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $avatarName = time() . '_' . Str::random(10) . '.' . $avatar->getClientOriginalExtension();
            
            // Store in public/avatars directory
            $avatar->move(public_path('admin-assets/avatars'), $avatarName);
            $user->avatar = $avatarName;
            $user->save();
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Avatar updated successfully!',
                'avatar_url' => asset('admin-assets/avatars/' . $user->avatar)
            ]);
        }

        return redirect()->route('admin.profile.index')->with('success', 'Avatar updated successfully!');
    }

    /**
     * Change password.
     */
    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current password is incorrect!'
                ], 422);
            }
            return redirect()->route('admin.profile.index')->with('error', 'Current password is incorrect!');
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Password changed successfully!'
            ]);
        }

        return redirect()->route('admin.profile.index')->with('success', 'Password changed successfully!');
    }

    /**
     * Get user statistics.
     */
    public function getStats()
    {
        $user = Auth::user();
        
        // Get statistics (you can customize these based on your needs)
        $stats = [
            'total_orders' => 0, // Replace with actual order count
            'total_products' => 0, // Replace with actual product count
            'total_customers' => 0, // Replace with actual customer count
            'total_revenue' => 0, // Replace with actual revenue
        ];

        return response()->json($stats);
    }
}


