<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;        // ← import the User model
use App\Models\DailyMeal;   // ← if you refer to DailyMeal in your relations

class Mess extends Model
{
    protected $fillable = [
        'name',
        'location',
        'description',
        'owner_id',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'mess_members');
    }

    public function dailyMeals()
    {
        return $this->hasMany(DailyMeal::class);
    }
    public function memberships()
{
    return $this->belongsToMany(User::class, 'mess_members', 'mess_id', 'user_id');
}


public function joinRequests()
{
    return $this->hasMany(MessJoinRequest::class, 'mess_id');
}

// Optionally, for only pending requests:
public function pendingJoinRequests()
{
    return $this->hasMany(MessJoinRequest::class, 'mess_id')->where('status', 'pending');
}

    
}
