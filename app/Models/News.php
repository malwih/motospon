<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

/**
 * Model untuk tabel news
 * 
 * Model ini merepresentasikan berita atau artikel dalam aplikasi.
 * Mendukung fitur pencarian, filter, dan slug otomatis.
 */
class News extends Model
{
    // Menggunakan trait untuk factory dan fitur slug
    use HasFactory, Sluggable;

    /**
     * Relasi many-to-many dengan model User
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('is_active', 'is_completed')
            ->withTimestamps();
    }

    /**
     * Relasi belongs-to dengan model User (penulis berita)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Kolom yang tidak boleh diisi secara massal
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    /**
     * Kolom yang boleh diisi secara massal
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title', 
        'excerpt', 
        'body', 
        'user_id', 
        'image', 
        'published_at'
    ];

    /**
     * Scope untuk filter pencarian
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  array  $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, array $filters)
    {
        // Filter berdasarkan kata kunci pencarian
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where('title', 'like', '%' . $search . '%')
                ->orWhere('body', 'like', '%' . $search . '%');
        });

        // Filter berdasarkan nama penulis
        $query->when(
            $filters['author'] ?? false,
            fn ($query, $author) =>
            $query->whereHas(
                'author',
                fn ($query) =>
                $query->where('username', $author)
            )
        );
    }

    /**
     * Mendapatkan nama kolom yang digunakan untuk route model binding
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Konfigurasi untuk pembuatan slug otomatis
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
}
