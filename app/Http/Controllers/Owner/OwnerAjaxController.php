<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Mess;
use App\Models\User;
use Illuminate\Http\Request;

class OwnerAjaxController extends Controller
{
    public function toggleMealManagerRole(Request $request, User $user)
    {
        $mess = Mess::where('owner_id', auth()->id())->firstOrFail();

        if (!$mess->memberships()->where('user_id', $user->id)->exists()) {
            return response()->json(['error' => 'This user is not a member of your mess.'], 403);
        }

        if ($user->hasRole('meal_manager')) {
            $user->removeRole('meal_manager');
            $message = 'Meal manager permissions revoked.';
        } else {
            $user->assignRole('meal_manager');
            $message = 'Meal manager permissions granted.';
        }

        return response()->json(['success' => $message]);
    }
} 