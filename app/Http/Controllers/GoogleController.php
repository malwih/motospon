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
    /**
     * Mengarahkan pengguna ke halaman login Google
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Menangani callback dari Google OAuth
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleGoogleCallback()
    {
        try {
            Log::info('Memulai proses callback Google OAuth');
            
            try {
                // Mendapatkan data user dari Google
                $googleUser = Socialite::driver('google')->user();
                Log::info('Data user dari Google:', ['email' => $googleUser->email, 'id' => $googleUser->id]);
            } catch (\Exception $e) {
                Log::error('Gagal mendapatkan data dari Google: ' . $e->getMessage());
                throw new \Exception('Failed to retrieve data from Google. Please try again.');
            }

            // Cek apakah user sudah terdaftar
            $findUser = User::where('email', $googleUser->email)->first();
            Log::info('Hasil pencarian user:', $findUser ? ['id' => $findUser->id] : ['status' => 'tidak ditemukan']);

            if ($findUser) {
                // Update Google ID jika belum ada
                if (empty($findUser->id_google)) {
                    $findUser->id_google = $googleUser->id;
                    if (!$findUser->save()) {
                        Log::error('Gagal memperbarui user dengan Google ID', ['user_id' => $findUser->id]);
                        throw new \Exception('Failed to update user data.');
                    }
                    Log::info('Berhasil memperbarui user dengan Google ID', ['user_id' => $findUser->id]);
                }
                
                // Login user
                Auth::login($findUser);
                
                // Cek apakah user sudah mengisi nomor WhatsApp
                if (empty($findUser->whatsapp_number)) {
                    return redirect()->route('profile.edit')->with('require_whatsapp', true);
                }
                
                return $this->redirectToDashboard($findUser);
            } else {
                // Proses untuk user baru
                // Simpan URL avatar Google langsung (tidak perlu download)
                $avatarUrl = $googleUser->avatar;
                
                // Buat user baru
                $userData = [
                    'name' => $googleUser->name,
                    'username' => $this->generateUniqueUsername($googleUser->name),
                    'email' => $googleUser->email,
                    'password' => Hash::make(Str::random(16)),
                    'email_verified_at' => now(),
                    'avatar' => $avatarUrl,
                ];
                
                Log::info('Membuat user baru dengan data:', $userData);
                
                try {
                    // Buat user baru
                    $newUser = User::create($userData);
                    
                    // Update Google ID
                    if ($newUser) {
                        $newUser->id_google = $googleUser->id;
                        if (!$newUser->save()) {
                            Log::error('Gagal menyimpan Google ID untuk user baru', ['user_id' => $newUser->id]);
                        }
                    }

                    Auth::login($newUser);
                    Log::info('User berhasil login', ['user_id' => $newUser->id]);
                    
                    // Arahkan ke halaman pemilihan tipe akun
                    return redirect()->route('choose.account.type');
                    
                } catch (\Exception $e) {
                    Log::error('Gagal membuat user baru: ' . $e->getMessage());
                    throw new \Exception('Failed to create a new account. ' . $e->getMessage());
                }
            }
        } catch (Exception $e) {
            Log::error('Proses OAuth Google gagal: ' . $e->getMessage());
            
            $errorMessage = 'Login with Google failed. Please try again.';
            if (config('app.debug')) {
                $errorMessage .= ' ' . $e->getMessage();
            }
            
            return redirect()->route('login')
                ->with('error', $errorMessage);
        }
    }

    /**
     * Generate username unik dari nama
     * 
     * @param string $name
     * @return string
     */
    protected function generateUniqueUsername($name)
    {
        $baseUsername = Str::slug($name);
        $username = $baseUsername . '_' . Str::random(5);
        
        // Pastikan username unik
        $count = 1;
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . '_' . Str::random(5);
            $count++;
            
            if ($count > 10) {
                $username = $baseUsername . '_' . time();
                break;
            }
        }
        
        return $username;
    }

    /**
     * Mengarahkan user ke dashboard yang sesuai
     * 
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectToDashboard(User $user)
    {
        if ($user->is_admin) {
            return redirect()->route('dashboard.admin');
        } elseif ($user->is_company) {
            return redirect()->route('dashboard.company');
        } elseif ($user->is_community) {
            return redirect()->route('dashboard.community');
        }
        return redirect()->route('choose.account.type');
    }

    /**
     * Menampilkan halaman pemilihan tipe akun
     * 
     * @return \Illuminate\View\View
     */
    public function chooseAccountType()
    {
        return view('auth.choose-account-type');
    }

    /**
     * Menyimpan tipe akun yang dipilih user
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeAccountType(Request $request)
    {
        $request->validate([
            'account_type' => 'required|in:company,community',
        ]);

        $user = Auth::user();
        
        // Set tipe akun
        $user->is_company = ($request->account_type === 'company') ? 1 : 0;
        $user->is_community = ($request->account_type === 'community') ? 1 : 0;
        $user->save();

        // Arahkan ke halaman profil jika nomor WhatsApp belum diisi
        if (empty($user->whatsapp_number)) {
            return redirect()->route('profile.edit')
                ->with('require_whatsapp', true)
                ->with('warning', 'Please enter your WhatsApp number to continue.');
        }

        return $this->redirectToDashboard($user);
    }
}
