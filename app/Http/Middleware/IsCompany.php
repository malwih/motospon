<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware untuk memeriksa apakah pengguna adalah perusahaan
 * 
 * Middleware ini memastikan hanya pengguna dengan role perusahaan 
 * yang dapat mengakses rute tertentu.
 */
class IsCompany
{
    /**
     * Menangani request yang masuk
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     * 
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah pengguna sudah login dan memiliki role perusahaan
        if (!auth()->check() || !auth()->user()->is_company) {
            // Jika tidak, kembalikan response 403 Forbidden
            abort(403, 'Unauthorized action. Only company users can access this resource.');
        }
        
        // Lanjutkan ke request berikutnya jika lolos validasi
        return $next($request);
    }
}