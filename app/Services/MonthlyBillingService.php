<?php

namespace App\Services;

use App\Models\DailyMeal;
use App\Models\Expense;
use App\Models\MemberMeal;
use App\Models\Mess;
use App\Models\User;
use Carbon\Carbon;

class MonthlyBillingService
{
    public function generateReport($messId, $month)
    {
        $year = substr($month, 0, 4);
        $mon = substr($month, 5, 2);

        $mess = Mess::with('members')->findOrFail($messId);
        
        $totalMeals = DailyMeal::where('mess_id', $messId)
            ->whereYear('date', $year)
            ->whereMonth('date', $mon)
            ->sum('total_meal');

        $totalExpenses = Expense::where('mess_id', $messId)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $mon)
            ->sum('amount');

        $mealRate = $totalMeals > 0 ? $totalExpenses / $totalMeals : 0;
        
        $membersData = [];
        foreach ($mess->members as $member) {
            $memberMeals = MemberMeal::where('user_id', $member->id)
                ->where('mess_id', $messId)
                ->whereYear('date', $year)
                ->whereMonth('date', $mon)
                ->sum('meal_count');
            
            $memberExpenses = Expense::where('user_id', $member->id)
                ->where('mess_id', $messId)
                ->whereYear('date', $year)
                ->whereMonth('date', $mon)
                ->sum('amount');
            
            $costForMeals = $memberMeals * $mealRate;

            $membersData[] = [
                'user_id' => $member->id,
                'name' => $member->name,
                'total_meals' => $memberMeals,
                'total_expenses' => $memberExpenses,
                'cost_for_meals' => $costForMeals,
                'balance' => $memberExpenses - $costForMeals
            ];
        }

        return [
            'mess_id' => $messId,
            'mess_name' => $mess->name,
            'month' => Carbon::createFromDate($year, $mon)->format('F Y'),
            'total_meals' => $totalMeals,
            'total_expenses' => $totalExpenses,
            'meal_rate' => $mealRate,
            'member_count' => $mess->members->count(),
            'members_data' => $membersData,
        ];
    }
}
