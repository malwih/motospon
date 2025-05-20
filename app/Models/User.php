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

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    // ];

    protected $guarded = ['id', 'id_google'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // public function sponsors()
    // {
    //     return $this->hasMany(Sponsor::class);
    // }

    // Sponsor
    public function sponsors()
    {
        return $this->belongsToMany(Sponsor::class)
            ->withPivot('is_active', 'is_completed')
            ->withTimestamps();
    }

    public function activeSponsors()
    {
        return $this->sponsors()->wherePivot('is_active', true);
    }

    public function completedSponsors()
    {
        return $this->sponsors()->wherePivot('is_completed', true);
    }
}
