<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\DailyMeal;
use App\Models\Mess;
use App\Models\User;


use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class MemberMealController extends Controller
{
  public function index(User $user)
{
    $owner = Auth::user();
    $mess = Mess::where('owner_id', $owner->id)->firstOrFail();

    $meals = \App\Models\MemberMeal::where('mess_id', $mess->id)
        ->where('user_id', $user->id)
        ->orderBy('date', 'desc')
        ->get();

    $totalMeals = $meals->sum('meal_count');

    // 🔧 This will make $member available in your view:
    $member = $user;

    return view('owner.members.meals', compact('member', 'mess', 'meals', 'totalMeals'));
}

   
// ...existing code...

public function store(Request $request, $userId)
{
    $request->validate([
        'date' => 'required|date',
        'meal_count' => 'required|numeric|min:0',
        'mess_id' => 'required|exists:messes,id',
    ]);

    \App\Models\MemberMeal::create([
        'user_id'    => $userId,
        'mess_id'    => $request->mess_id,
        'date'       => $request->date,
        'meal_count' => $request->meal_count,
    ]);

    return redirect()->route('owner.members.meals', $userId)->with('success', 'Meal added successfully!');
}

// ...existing code...


}
