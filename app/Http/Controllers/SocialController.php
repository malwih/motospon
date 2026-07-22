<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;

class SocialController extends Controller
{
    /**
     * Mengarahkan pengguna ke halaman autentikasi Google
     * 
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirect()
    {
        // Mengarahkan ke halaman login Google
        return Socialite::driver('google')->redirect();
    }

    /**
     * Menangani callback dari Google setelah autentikasi
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function googleCallback(Request $request)
    {
        // Mendapatkan data user dari Google
        $user = Socialite::driver('google')->user();
        
        // Menampilkan halaman setelah login dengan data user
        return view('google-logged-in', [
            'user' => $user
        ]);
    }
}
