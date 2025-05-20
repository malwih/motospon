<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductUser extends Model
{
    use HasFactory;

    protected $table = 'product_user';

    protected $fillable = [
        'product_id',
        'user_id',
        'is_active',
        'is_completed',
    ];

    // relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // relasi ke model Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
