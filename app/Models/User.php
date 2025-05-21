<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = ['id', 'id_google'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relasi: User memiliki banyak Proposal
    public function proposals()
    {
        return $this->hasMany(Proposal::class);
    }
    public function sponsors()
{
    return $this->belongsToMany(Sponsor::class, 'proposal_sponsor')
                ->withPivot('is_active', 'is_completed', 'is_reject')
                ->withTimestamps();
}

}
