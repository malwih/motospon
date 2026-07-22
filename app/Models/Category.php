<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk tabel categories
 * 
 * Model ini merepresentasikan data kategori yang digunakan 
 * untuk mengkategorikan sponsor dalam aplikasi.
 */
class Category extends Model
{
    // Menggunakan trait HasFactory untuk factory model
    use HasFactory;

    /**
     * Kolom yang tidak boleh diisi secara massal
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    /**
     * Relasi one-to-many dengan model Sponsor
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sponsors()
    {
        return $this->hasMany(Sponsor::class);
    }
}
