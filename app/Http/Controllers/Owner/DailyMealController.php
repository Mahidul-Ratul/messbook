<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\DailyMeal;
use App\Models\Mess;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DailyMealController extends Controller
{
    private function getMess()
    {
        $user = Auth::user();
        
        if ($user->hasRole('mess_owner')) {
            // For mess owners, get their own mess
            return Mess::where('owner_id', $user->id)->firstOrFail();
        } elseif ($user->hasRole('meal_manager')) {
            // For meal managers, get the mess they belong to
            $mess = $user->memberships()->first();
            if (!$mess) {
                abort(403, 'You are not a member of any mess.');
            }
            return $mess;
        }
        
        abort(403, 'User does not have the right roles.');
    }

    public function index()
    {
        $mess = $this->getMess();
        $meals = DailyMeal::where('mess_id', $mess->id)->with('user', 'mess')->orderBy('date', 'desc')->get();

        return view('owner.daily_meals.index', compact('meals'));
    }

    public function create()
    {
        $mess = $this->getMess();
        $user = Auth::user();

        if ($user->hasRole('mess_owner')) {
            $members = User::whereHas('memberships', function($q) use ($mess) {
                $q->where('mess_id', $mess->id);
            })->get();
        } else {
            // meal_manager: can only add for self
            $members = collect([$user]);
        }

        return view('owner.daily_meals.create', compact('mess', 'members'));
    }

    public function store(Request $request)
    {
        $mess = $this->getMess();
        $user = Auth::user();

        $request->validate([
            'mess_id' => 'required|exists:messes,id',
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'total_meal' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // Ensure the meal is being added to the correct mess
        if ($request->mess_id != $mess->id) {
            abort(403, 'You can only add meals to your own mess.');
        }

        // meal_manager can only add for self
        if ($user->hasRole('meal_manager') && !$user->hasRole('mess_owner')) {
            if ($request->user_id != $user->id) {
                abort(403, 'You can only add meals for yourself.');
            }
        }

        DailyMeal::create([
            'mess_id' => $request->mess_id,
            'user_id' => $request->user_id,
            'date' => $request->date,
            'total_meal' => $request->total_meal,
            'notes' => $request->notes,
        ]);

        return redirect()->route('owner.daily_meals.index')->with('success', 'Meal added.');
    }

    public function edit(DailyMeal $dailyMeal)
    {
        $mess = $this->getMess();
        $user = Auth::user();

        // Ensure the meal belongs to the user's mess
        if ($dailyMeal->mess_id != $mess->id) {
            abort(403, 'You can only edit meals from your own mess.');
        }

        if ($user->hasRole('mess_owner')) {
            $members = User::whereHas('memberships', function($q) use ($mess) {
                $q->where('mess_id', $mess->id);
            })->get();
        } else {
            // meal_manager: can only edit their own meals
            if ($dailyMeal->user_id != $user->id) {
                return redirect()->route('owner.daily_meals.index')
                    ->with('warning', 'You can only edit your own meals. This meal belongs to another member.');
            }
            $members = collect([$user]);
        }

        return view('owner.daily_meals.edit', compact('dailyMeal', 'mess', 'members'));
    }

    public function update(Request $request, DailyMeal $dailyMeal)
    {
        $mess = $this->getMess();
        $user = Auth::user();

        // Ensure the meal belongs to the user's mess
        if ($dailyMeal->mess_id != $mess->id) {
            abort(403, 'You can only update meals from your own mess.');
        }

        $request->validate([
            'mess_id' => 'required|exists:messes,id',
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'total_meal' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // Ensure the meal is being updated to the correct mess
        if ($request->mess_id != $mess->id) {
            abort(403, 'You can only update meals to your own mess.');
        }

        // meal_manager can only update their own meals
        if ($user->hasRole('meal_manager') && !$user->hasRole('mess_owner')) {
            if ($dailyMeal->user_id != $user->id || $request->user_id != $user->id) {
                abort(403, 'You can only update your own meals.');
            }
        }

        $dailyMeal->update($request->only('mess_id', 'user_id', 'date', 'total_meal', 'notes'));

        return redirect()->route('owner.daily_meals.index')->with('success', 'Meal updated.');
    }

    public function destroy(DailyMeal $dailyMeal)
    {
        $mess = $this->getMess();
        $user = Auth::user();
        
        // Ensure the meal belongs to the user's mess
        if ($dailyMeal->mess_id != $mess->id) {
            abort(403, 'You can only delete meals from your own mess.');
        }

        // meal_manager can only delete their own meals
        if ($user->hasRole('meal_manager') && !$user->hasRole('mess_owner')) {
            if ($dailyMeal->user_id != $user->id) {
                return redirect()->route('owner.daily_meals.index')
                    ->with('warning', 'You can only delete your own meals. This meal belongs to another member.');
            }
        }

        $dailyMeal->delete();

        return redirect()->route('owner.daily_meals.index')->with('success', 'Meal deleted.');
    }
}
