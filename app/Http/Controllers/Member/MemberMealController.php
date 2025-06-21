<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\MemberMeal;
use App\Models\Mess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberMealController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $meals = MemberMeal::where('user_id', $user->id)
            ->with('mess')
            ->orderBy('date', 'desc')
            ->paginate(15);

        return view('member.meals.index', compact('meals'));
    }

    public function create()
    {
        $user = Auth::user();
        $messes = $user->memberships;

        return view('member.meals.create', compact('messes'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'mess_id' => 'required|exists:messes,id',
            'date' => 'required|date|before_or_equal:today',
            'meal_count' => 'required|numeric|min:0|max:10',
        ]);

        // Ensure the user is a member of the selected mess
        if (!$user->isMemberOf(Mess::find($request->mess_id))) {
            return back()->with('error', 'You are not a member of this mess.');
        }

        // Check if a meal entry already exists for this user, mess, and date
        $existingMeal = MemberMeal::where('user_id', $user->id)
            ->where('mess_id', $request->mess_id)
            ->where('date', $request->date)
            ->first();

        if ($existingMeal) {
            return back()->with('error', 'A meal entry already exists for this date. Please edit the existing entry instead.');
        }

        MemberMeal::create([
            'mess_id' => $request->mess_id,
            'user_id' => $user->id,
            'date' => $request->date,
            'meal_count' => $request->meal_count,
        ]);

        return redirect()->route('member.meals.index')->with('success', 'Meal entry added successfully.');
    }

    public function edit(MemberMeal $memberMeal)
    {
        $user = Auth::user();

        // Ensure the meal belongs to the authenticated user
        if ($memberMeal->user_id !== $user->id) {
            abort(403, 'You can only edit your own meal entries.');
        }

        $messes = $user->memberships;

        return view('member.meals.edit', compact('memberMeal', 'messes'));
    }

    public function update(Request $request, MemberMeal $memberMeal)
    {
        $user = Auth::user();

        // Ensure the meal belongs to the authenticated user
        if ($memberMeal->user_id !== $user->id) {
            abort(403, 'You can only update your own meal entries.');
        }

        $request->validate([
            'mess_id' => 'required|exists:messes,id',
            'date' => 'required|date|before_or_equal:today',
            'meal_count' => 'required|numeric|min:0|max:10',
        ]);

        // Ensure the user is a member of the selected mess
        if (!$user->isMemberOf(Mess::find($request->mess_id))) {
            return back()->with('error', 'You are not a member of this mess.');
        }

        // Check if another meal entry already exists for this user, mess, and date (excluding current entry)
        $existingMeal = MemberMeal::where('user_id', $user->id)
            ->where('mess_id', $request->mess_id)
            ->where('date', $request->date)
            ->where('id', '!=', $memberMeal->id)
            ->first();

        if ($existingMeal) {
            return back()->with('error', 'Another meal entry already exists for this date. Please edit that entry instead.');
        }

        $memberMeal->update([
            'mess_id' => $request->mess_id,
            'date' => $request->date,
            'meal_count' => $request->meal_count,
        ]);

        return redirect()->route('member.meals.index')->with('success', 'Meal entry updated successfully.');
    }

    public function destroy(MemberMeal $memberMeal)
    {
        $user = Auth::user();

        // Ensure the meal belongs to the authenticated user
        if ($memberMeal->user_id !== $user->id) {
            abort(403, 'You can only delete your own meal entries.');
        }

        $memberMeal->delete();

        return redirect()->route('member.meals.index')->with('success', 'Meal entry deleted successfully.');
    }
} 