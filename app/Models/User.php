<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Model untuk tabel users
 * 
 * Model ini merepresentasikan data pengguna dalam sistem,
 * baik itu perusahaan maupun komunitas.
 */
class User extends Authenticatable
{
    // Menggunakan trait untuk API token, factory, dan notifikasi
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Kolom yang boleh diisi secara massal
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'whatsapp_number',
        'is_company',
        'is_community',
        'id_google',
        'avatar',
    ];
    
    /**
     * Kolom yang tidak boleh diisi secara massal
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    /**
     * Kolom yang harus disembunyikan dari array/JSON
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Tipe data yang akan di-cast
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relasi one-to-many dengan model Proposal
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function proposals()
    {
        return $this->hasMany(Proposal::class);
    }
    
    /**
     * Relasi one-to-many dengan model Sponsorship
     * Menampilkan daftar sponsorship yang dibuat oleh user ini
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sponsorships()
    {
        return $this->hasMany(Sponsorship::class, 'user_id');
    }
    
    /**
     * Relasi many-to-many dengan model Sponsorship melalui tabel proposals
     * Menampilkan daftar sponsorship yang diikuti oleh user ini
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function sponsoredBy()
    {
        return $this->belongsToMany(
            Sponsorship::class, 
            'proposals', 
            'user_id', 
            'sponsorship_id'
        )->withPivot('is_active', 'is_accept', 'is_reject')
         ->withTimestamps();
    }
}
