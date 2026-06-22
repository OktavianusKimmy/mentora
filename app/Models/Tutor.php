<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tutor extends Model
{
    protected $fillable = [
        'user_id',
        'bio',
        'tps',
        'literasi',
        'numerasi',
        'rating',
        'total_reviews'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
