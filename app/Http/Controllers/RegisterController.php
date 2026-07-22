<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class RegisterController extends Controller
{
    /**
     * Menampilkan halaman registrasi
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('register.index', [
            'title' => 'Register',
            'active' => 'register'
        ]);
    }

    /**
     * Menyimpan data user baru
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validasi input dari form
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'username' => [
                'required', 
                'regex:/^[a-z0-9_.-]+$/', // Hanya huruf kecil, angka, titik, underscore, dan strip
                'min:3', 
                'max:255', 
                'unique:users'
            ],
            'email' => 'required|email:dns|unique:users',
            'whatsapp_number' => [
                'required',
                'string',
                'max:20',
                'regex:/^(\+\d{1,3}[- ]?)?\d{10,15}$/',
                function ($attribute, $value, $fail) {
                    // Menghapus semua karakter non-angka kecuali tanda +
                    $cleaned = preg_replace('/[^\d+]/', '', $value);
                    
                    // Cek format nomor internasional (diawali +)
                    if (str_starts_with($cleaned, '+')) {
                        // Validasi nomor Indonesia (+62)
                        if (str_starts_with($cleaned, '+62')) {
                            // Pastikan panjang nomor 10-13 digit setelah +62
                            if (strlen($cleaned) < 12 || strlen($cleaned) > 15) {
                                $fail('Indonesian WhatsApp number must be 10-13 digits after +62');
                            }
                        } 
                        // Validasi nomor internasional lainnya
                        else if (strlen($cleaned) < 10 || strlen($cleaned) > 16) {
                            $fail('Invalid international WhatsApp number format');
                        }
                    } 
                    // Format nomor lokal (tanpa +)
                    else {
                        // Hapus angka 0 di depan jika ada
                        if (str_starts_with($cleaned, '0')) {
                            $cleaned = substr($cleaned, 1);
                        }
                        
                        // Validasi panjang nomor Indonesia (10-13 digit)
                        if (strlen($cleaned) < 10 || strlen($cleaned) > 13) {
                            $fail('WhatsApp number must be 10-13 digits (example: 081234567890)');
                        }
                        
                        // Pastikan digit pertama valid (8 atau 9)
                        if (!in_array(substr($cleaned, 0, 1), ['8', '9'])) {
                            $fail('WhatsApp number must start with 8 or 9');
                        }
                    }
                },
            ],
            'password' => 'required|min:5|max:255',
            'is_company' => 'required|boolean',
            'is_community' => 'required|boolean',
            'account_type' => 'required|in:company,community'
        ]);

        // Pastikan hanya satu tipe akun yang dipilih (company ATAU community)
        if ($validatedData['is_company'] == $validatedData['is_community']) {
            return back()->with('error', 'Please select one account type');
        }

        // Enkripsi password sebelum disimpan
        $validatedData['password'] = Hash::make($validatedData['password']);

        // Hapus field account_type karena tidak ada di tabel users
        unset($validatedData['account_type']);

        // Simpan data user baru ke database
        User::create($validatedData);

        // Redirect ke halaman login dengan pesan sukses
        return redirect('/login')
            ->with('success', 'Registration successful! Please login');
    }
}