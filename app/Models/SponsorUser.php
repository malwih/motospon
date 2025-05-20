<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SponsorUser extends Model
{
    use HasFactory;

    protected $table = 'sponsor_user';

    protected $fillable = [
        'sponsor_id',
        'user_id',
        'is_active',
        'is_completed',
    ];

    // relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // relasi ke model Sponsor
    public function sponsor()
    {
        return $this->belongsTo(Sponsor::class);
    }
}
