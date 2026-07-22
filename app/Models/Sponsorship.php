<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

/**
 * Model untuk tabel sponsorships
 * 
 * Model ini merepresentasikan data sponsorship yang ditawarkan
 * oleh perusahaan kepada komunitas.
 */
class Sponsorship extends Model
{
    // Menggunakan trait untuk factory dan fitur slug
    use HasFactory, Sluggable;

    /**
     * Kolom yang boleh diisi secara massal
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title', 
        'body', 
        'user_id', 
        'image', 
        'published_at', 
        'created_at', 
        'category', 
        'event', 
        'slug'
    ];

    /**
     * Kolom yang tidak boleh diisi secara massal
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    /**
     * Relasi belongs-to dengan model User (pembuat sponsorship)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi belongs-to dengan model User (alias untuk user)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi many-to-many dengan model User melalui tabel proposals
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'proposals')
                    ->withPivot('is_active', 'is_accept', 'is_reject')
                    ->withTimestamps();
    }

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
     * Accessor untuk mendapatkan jumlah proposal
     * 
     * @return int
     */
    public function getProposalsCountAttribute()
    {
        return $this->proposals()->count();
    }

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
