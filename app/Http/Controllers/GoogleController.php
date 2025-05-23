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
use Illuminate\Support\Facades\Log;


class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $findUser = User::where('email', $googleUser->email)->first();

            if ($findUser) {
                Auth::login($findUser);
                return $this->redirectToDashboard($findUser);
            } else {
                $avatarUrl = $googleUser->avatar;
                $avatarContents = file_get_contents($avatarUrl);
                $filename = 'avatars/' . Str::random(20) . '.jpg';
                Storage::disk('public')->put($filename, $avatarContents);

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
                return redirect()->route('choose.account.type');
            }
        } catch (Exception $e) {
        // Gantilah ini:
        // dd($e->getMessage());

        // Dengan log (untuk disimpan ke storage/logs/laravel.log)
        Log::error('Google OAuth callback failed: ' . $e->getMessage());

        // Redirect ke halaman login dengan error message
        return redirect()->route('login')->with('error', 'Login dengan Google gagal. Silakan coba lagi.');
    }
}

    protected function redirectToDashboard(User $user)
    {
        if ($user->is_admin) {
            return redirect()->route('dashboard.admin');
        } elseif ($user->is_company) {
            return redirect()->route('dashboard.company');
        } elseif ($user->is_community) {
            return redirect()->route('dashboard.community');
        } else {
            return redirect()->route('choose.account.type');
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

        return $this->redirectToDashboard($user);
    }
}
