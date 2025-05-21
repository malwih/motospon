<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'sponsor_id',
        'is_active',
        'is_completed',
        'is_reject',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_completed' => 'boolean',
        'is_reject' => 'boolean',
    ];

    public function sponsor()
    {
        return $this->belongsTo(Sponsor::class);
    }



}
