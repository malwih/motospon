<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MyProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('dashboard.myprofile.index', compact('user'));
    }

    public function edit()
    {
        $user = auth()->user();
        return view('dashboard.myprofile.editprofile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20000',
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada dan bukan dari Google
            if ($user->avatar && !$user->id_google && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Hitung jumlah file avatar dengan awalan username
            $existingFiles = Storage::disk('public')->files('avatars');
            $username = $validatedData['username'];
            $count = collect($existingFiles)->filter(function ($file) use ($username) {
                return Str::startsWith(basename($file), $username);
            })->count();

            // Nama file avatar baru
            $filename = $username . '-avatar-' . ($count + 1) . '.' . $request->file('avatar')->getClientOriginalExtension();

            // Simpan file
            $path = $request->file('avatar')->storeAs('avatars', $filename, 'public');
            $user->avatar = $path;
        }

        // Update data user
        $user->name = $validatedData['name'];
        $user->username = $validatedData['username'];
        $user->email = $validatedData['email'];

        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }

        $user->save();

        return redirect()->route('myprofile.index')->with('success', 'Profile has been updated!');
    }
}
