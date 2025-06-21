<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyMeal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'mess_id',
        'date',
        'total_meal',
        'notes',
    ];

    public function mess()
    {
        return $this->belongsTo(Mess::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
