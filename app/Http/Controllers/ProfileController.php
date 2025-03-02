<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function index()
{
    $user = auth()->user();

    return view('settings.profile-settings', [
        'full_name' => $user->full_name,
        'email'     => $user->email,
        'image'     => $user->img, // Adjust this if your field name differs
    ]);
}

public function updateProfile(Request $request)
{
    // Validate input data. Note: old_password is required if a new password is provided.
    $validated = $request->validate([
        'name'            => 'required|string|max:255',
        'email'           => 'required|email|max:255|unique:users,email,' . auth()->id(),
        'old_password'    => 'required_with:password', // required when password field is filled
        'password'        => 'nullable|confirmed|min:6',
        'profile_picture' => 'nullable|image|max:2048',
    ]);
    
    $user = auth()->user();
    $user->full_name = $validated['name'];
    $user->email     = $validated['email'];

    // If a new password is provided, check if the old password is correct.
    if ($request->filled('password')) {
        if (md5($validated['old_password']) !== $user->password) {
            return redirect()->back()
                             ->withErrors(['old_password' => 'Mật khẩu hiện tại không chính xác'])
                             ->withInput();
        }
        $user->password = md5($validated['password']);
    }

    // If a new profile picture is uploaded, store it and update the img column.
    if ($request->hasFile('profile_picture')) {
        $path = $request->file('profile_picture')->store('profile_pictures', 'public');
        $user->img = $path;
    }

    $user->save();

    return redirect()->back()->with('success', 'Thông tin cá nhân đã được cập nhật!');
}


}
