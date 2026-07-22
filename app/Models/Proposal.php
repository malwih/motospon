<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk tabel proposals
 * 
 * Model ini merepresentasikan data proposal yang diajukan oleh komunitas
 * untuk mendapatkan sponsor dari perusahaan.
 */
class Proposal extends Model
{
    // Menggunakan trait HasFactory untuk factory model
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model
     *
     * @var string
     */
    protected $table = 'proposals';

    /**
     * Kolom yang dapat diisi secara massal
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'sponsorship_id',
        'date_event',
        'category',
        'event',
        'name_community',
        'name_event',
        'location',
        'feedback_benefit',
        'proposal_raw',
        'submit',
        'is_active',
        'is_accept',
        'is_reject',
        'hidden_from_company',
        'feedback',
        'status'
    ];

    /**
     * Get the event documentations for the proposal.
     */
    public function eventDocumentations()
    {
        return $this->hasMany(EventDocumentation::class);
    }

    /**
     * Relasi belongs-to dengan model User
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi belongs-to dengan model Sponsorship
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sponsorship()
    {
        return $this->belongsTo(Sponsorship::class, 'sponsorship_id');
    }
}
