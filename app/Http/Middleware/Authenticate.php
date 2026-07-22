<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

/**
 * Middleware untuk menangani autentikasi pengguna
 * 
 * Middleware ini mengecek apakah pengguna sudah login sebelum mengakses rute tertentu.
 * Jika belum login, pengguna akan diarahkan ke halaman login.
 */
class Authenticate extends Middleware
{
    /**
     * Mendapatkan path redirect ketika pengguna tidak terautentikasi
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo(Request $request): ?string
    {
        // Jika request mengharapkan response JSON, kembalikan null
        // Jika tidak, redirect ke route 'login'
        return $request->expectsJson() 
            ? null 
            : route('login');
    }
}
