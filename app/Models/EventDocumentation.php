<?php

namespace App\Models;

use App\Models\Proposal;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventDocumentation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'proposal_id',
        'file_path',
        'original_name',
        'mime_type',
        'size'
    ];

    /**
     * Get the proposal that owns the event documentation.
     */
    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }
}
