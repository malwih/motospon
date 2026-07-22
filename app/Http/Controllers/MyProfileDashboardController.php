<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MyProfileDashboardController extends Controller
{
    /**
     * Menampilkan halaman profil pengguna
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil data user yang sedang login
        $user = auth()->user();
        return view('dashboard.myprofile.index', compact('user'));
    }

    /**
     * Menampilkan form edit profil
     * 
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        // Ambil data user dan cek apakah wajib mengisi WhatsApp
        $user = auth()->user();
        $requireWhatsApp = session('require_whatsapp', false);
        return view('dashboard.myprofile.editprofile', compact('user', 'requireWhatsApp'));
    }

    /**
     * Memperbarui data profil pengguna
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        // Ambil user yang sedang login
        $user = auth()->user();

        // Aturan validasi
        $rules = [
            'name' => 'required|string|max:255',
            'username' => 'required|regex:/^[a-z0-9_.-]+$/|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20000',
            'whatsapp_number' => [
                'required',
                'string',
                'max:20',
                'regex:/^(\+\d{1,3}[- ]?)?\d{10,15}$/',
                function ($attribute, $value, $fail) {
                    // Hapus semua karakter selain angka dan tanda +
                    $cleaned = preg_replace('/[^\d+]/', '', $value);
                    
                    // Cek format nomor internasional (dimulai dengan +)
                    if (str_starts_with($cleaned, '+')) {
                        // Jika nomor Indonesia (+62)
                        if (str_starts_with($cleaned, '+62')) {
                            if (strlen($cleaned) < 12 || strlen($cleaned) > 15) {
                                $fail('Indonesian WhatsApp number must be 10-13 digits after +62');
                            }
                        } 
                        // Untuk nomor internasional lainnya
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
                            $fail('Phone number must be 10-13 digits (example: 081234567890)');
                        }
                        
                        // Pastikan diawali dengan 8 atau 9
                        if (!in_array(substr($cleaned, 0, 1), ['8', '9'])) {
                            $fail('Phone number must start with 8 or 9');
                        }
                    }
                },
            ],
        ];

        // Validasi input
        $validatedData = $request->validate($rules);

        // Proses upload avatar jika ada
        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada dan bukan dari Google
            if ($user->avatar && !$user->id_google && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Hitung jumlah file avatar dengan awalan username yang sama
            $existingFiles = Storage::disk('public')->files('avatars');
            $username = $validatedData['username'];
            $count = collect($existingFiles)->filter(function ($file) use ($username) {
                return Str::startsWith(basename($file), $username);
            })->count();

            // Buat nama file baru dengan format: username-avatar-nomor.ekstensi
            $filename = $username . '-avatar-' . ($count + 1) . '.' . $request->file('avatar')->getClientOriginalExtension();

            // Simpan file avatar
            $path = $request->file('avatar')->storeAs('avatars', $filename, 'public');
            $user->avatar = $path;
        }

        // Update data user
        $user->name = $validatedData['name'];
        $user->username = $validatedData['username'];
        $user->email = $validatedData['email'];
        $user->whatsapp_number = $validatedData['whatsapp_number'];

        // Update password jika diisi
        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }

        // Simpan perubahan
        $user->save();

        // Redirect berdasarkan konteks
        if (session('require_whatsapp')) {
            // Jika sebelumnya diwajibkan isi WhatsApp, arahkan ke home
            return redirect()->intended(route('home'))
                ->with('success', 'WhatsApp number has been saved!');
        }

        // Jika update profil biasa, kembali ke halaman profil
        return redirect()->route('myprofile.index')
            ->with('success', 'Profile has been updated!');
    }
}
