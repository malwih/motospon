<?php

namespace App\Http\Controllers;

use App\Models\Proposal;  // Hanya menggunakan model yang diperlukan
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard berdasarkan tipe akun
     * 
     * @param string|null $accountType Tipe akun (admin/company/community)
     * @return \Illuminate\Http\Response
     */
    public function dashboard($accountType = null)
    {
        // Ambil data user yang sedang login
        $user = Auth::user();

        // Jika tipe akun tidak ditentukan, arahkan otomatis
        if (!$accountType) {
            if ($user->is_admin) {
                return redirect('/dashboard/admin');
            } elseif ($user->is_company) {
                return redirect('/dashboard/company');
            } elseif ($user->is_community) {
                return redirect('/dashboard/community');
            }
            return redirect()->route('choose.account.type');
        }

        // Validasi tipe akun yang diizinkan
        if (!in_array($accountType, ['admin', 'company', 'community'])) {
            abort(404);
        }

        // Ambil data sponsorships user
        $sponsorships = $user->sponsorships ?? collect();
        
        // Inisialisasi query berdasarkan tipe akun
        if ($accountType === 'company') {
            // Untuk company: tampilkan semua proposal yang ditujukan ke sponsorship mereka
            $sponsorshipIds = $user->sponsorships->pluck('id');
            $proposals = Proposal::whereIn('sponsorship_id', $sponsorshipIds)
                               ->with(['sponsorship', 'user']);
            
            // Filter berdasarkan sponsor (jika ada)
            if (request()->filled('sponsor')) {
                $proposals->whereHas('sponsorship', function($query) {
                    $query->where('title', 'like', '%' . request('sponsor') . '%');
                });
            }
            
            // Filter berdasarkan komunitas (jika ada)
            if (request()->filled('community')) {
                $proposals->where('name_community', 'like', '%' . request('community') . '%');
            }
        } else {
            // Untuk community: tampilkan hanya proposal milik mereka sendiri
            $proposals = Proposal::where('user_id', $user->id)
                               ->with(['sponsorship']);
        }
        
        // Filter berdasarkan event (untuk semua tipe akun)
        if (request()->filled('event')) {
            $proposals->where('name_event', 'like', '%' . request('event') . '%');
        }
        
        // Filter berdasarkan lokasi
        if (request()->filled('location')) {
            $proposals->where('location', 'like', '%' . request('location') . '%');
        }
        
        // Filter berdasarkan tanggal
        if (request()->filled('date')) {
            $proposals->whereDate('date_event', request('date'));
        }
        
        // Filter berdasarkan status
        if (request()->filled('status')) {
            $status = request('status');
            if ($status === 'active') {
                $proposals->where('is_active', true);
            } elseif ($status === 'accepted') {
                $proposals->where('is_accept', true);
            } elseif ($status === 'rejected') {
                $proposals->where('is_reject', true);
            } elseif ($status === 'inactive') {
                $proposals->where('is_active', false)
                         ->where('is_accept', false)
                         ->where('is_reject', false);
            }
        }
        
        // Pengurutan data
        if (request()->has('sort') && request()->has('order')) {
            $sort = request('sort');
            $order = request('order');
            
            if ($sort === 'sponsor') {
                $proposals->select('proposals.*')
                         ->join('sponsorships', 'proposals.sponsorship_id', '=', 'sponsorships.id')
                         ->orderBy('sponsorships.title', $order);
            } elseif ($sort === 'community') {
                $proposals->orderBy('name_community', $order);
            } elseif ($sort === 'event') {
                $proposals->orderBy('name_event', $order);
            } elseif ($sort === 'date') {
                $proposals->orderBy('date_event', $order);
            }
        } else {
            // Pengurutan default
            $proposals->latest('created_at');
        }
        
        try {
            // Untuk company, sembunyikan proposal yang diatur sebagai tersembunyi
            if ($accountType === 'company') {
                $proposals->where(function($query) {
                    $query->where('hidden_from_company', false)
                          ->orWhereNull('hidden_from_company');
                });
            }
            
            // Paginasi hasil query
            $proposals = $proposals->paginate(10)->withQueryString();
            
            // Tampilkan view dashboard sesuai tipe akun
            return view('dashboard.' . $accountType, [
                'user' => $user,
                'sponsorships' => $sponsorships,
                'proposals' => $proposals,
            ]);
            
        } catch (\Exception $e) {
            // Log error untuk keperluan debugging
            Log::error('Dashboard error: ' . $e->getMessage());
            
            // Kembali ke halaman dashboard dengan pesan error
            return redirect()->route('dashboard', $accountType)
                             ->with('error', 'An error occurred while loading the data. Please try again.');
        }
    }
}
