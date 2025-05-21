<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{

    public function sponsor()
{
    return $this->belongsTo(Sponsor::class, 'sponsor_id');
}

    use HasFactory;

    protected $fillable = [
        'user_id',
        'sponsor_id',
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
        'is_completed',
        'is_reject',
    ];

    protected $table = 'proposals';

}
