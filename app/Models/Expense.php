<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'mess_id',
        'user_id',
        'date',
        'amount',
        'description',
    ];

    // Relationships
    public function mess()
    {
        return $this->belongsTo(Mess::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
