<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
{
    try {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $findUser = User::where('email', $googleUser->email)->first();

        if ($findUser) {
            Auth::login($findUser);
            return redirect()->intended('dashboard');
        } else {
            $avatarUrl = $googleUser->avatar;
            $avatarContents = file_get_contents($avatarUrl);
            $filename = 'avatars/' . Str::random(20) . '.jpg';
            Storage::disk('public')->put($filename, $avatarContents);

            // Simpan data dasar user tanpa jenis akun
            $newUser = User::create([
                'name' => $googleUser->name,
                'username' => $googleUser->name . '_' . Str::random(5),
                'email' => $googleUser->email,
                'password' => Hash::make('12345'),
                'id_google' => $googleUser->id,
                'email_verified_at' => now(),
                'avatar' => $filename,
            ]);

            Auth::login($newUser);
            // Redirect ke halaman pemilihan jenis akun
            return redirect()->route('choose.account.type');
        }
    } catch (Exception $e) {
        dd($e->getMessage());
    }
}
public function chooseAccountType()
{
    return view('auth.choose-account-type');
}

public function storeAccountType(Request $request)
{
    $request->validate([
        'account_type' => 'required|in:company,community',
    ]);

    $user = Auth::user();

    if ($request->account_type === 'company') {
        $user->is_company = 1;
        $user->is_community = 0;
    } else {
        $user->is_company = 0;
        $user->is_community = 1;
    }

    $user->save();

    return redirect()->intended('dashboard');
}

}
