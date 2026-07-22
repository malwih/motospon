<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Menampilkan halaman login
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('login.index', [
            'title' => 'Login',
            'active' => 'login'
        ]);
    }

    /**
     * Memproses login user
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function authenticate(Request $request)
    {
        // Validasi input email dan password
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Coba melakukan autentikasi
        if (Auth::attempt($credentials)) {
            // Regenerasi session untuk mencegah serangan session fixation
            $request->session()->regenerate();
            
            // Redirect ke halaman yang diminta sebelumnya atau dashboard
            return redirect()->intended('/dashboard');
        }

        // Jika autentikasi gagal, kembali ke halaman login dengan pesan error
        return back()
            ->withInput($request->only('email'))
            ->with('loginError', 'Invalid email or password. Please try again.');
    }

    /**
     * Logout user
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        // Logout user
        Auth::logout();

        // Invalidasi session
        request()->session()->invalidate();

        // Regenerasi CSRF token
        request()->session()->regenerateToken();

        // Redirect ke halaman home
        return redirect('/');
    }
}
