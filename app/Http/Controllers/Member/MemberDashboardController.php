<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Expense;
use App\Models\MemberMeal;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\Mess;
use App\Services\MonthlyBillingService;

class MemberDashboardController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $startOfMonth = Carbon::now()->startOfMonth();

        // Summary Cards Data
        $joinedMessCount = $user->memberships->count();
        $totalExpenseThisMonth = Expense::where('user_id', $user->id)
            ->where('date', '>=', $startOfMonth)
            ->sum('amount');
        $totalMealsThisMonth = MemberMeal::where('user_id', $user->id)
            ->where('date', '>=', $startOfMonth)
            ->sum('meal_count');

        // Recent Activities Data
        $recentExpenses = Expense::where('user_id', $user->id)->latest('date')->take(5)->get()->map(function ($expense) {
            $expense->activity_type = 'Expense';
            $expense->activity_date = $expense->date;
            return $expense;
        });

        $recentMeals = MemberMeal::where('user_id', $user->id)->latest('date')->take(5)->get()->map(function ($meal) {
            $meal->activity_type = 'Meals Added';
            $meal->activity_date = $meal->date;
            return $meal;
        });

        $activities = (new Collection($recentExpenses))
            ->merge($recentMeals)
            ->sortByDesc('activity_date')
            ->take(7);

        // Chart Data (Last 7 days of meals)
        $mealStats = MemberMeal::select(DB::raw('DATE(date) as date'), DB::raw('sum(meal_count) as count'))
            ->where('user_id', $user->id)
            ->where('date', '>=', Carbon::now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->pluck('count', 'date');

        $dates = collect();
        for ($i = 6; $i >= 0; $i--) {
            $dates->put(Carbon::now()->subDays($i)->format('Y-m-d'), 0);
        }
        $mealChartData = $dates->merge($mealStats);

        return view('member.dashboard', compact('joinedMessCount', 'totalExpenseThisMonth', 'totalMealsThisMonth', 'activities', 'mealChartData'));
    }

    public function myMesses()
    {
        $user = Auth::user();
        $myMesses = $user->memberships()->with('owner')->paginate(10);
        return view('member.messes.my_messes', compact('myMesses'));
    }

    public function showMyMess(Mess $mess, MonthlyBillingService $billingService)
    {
        $user = Auth::user();

        // Ensure the user is actually a member of this mess
        if (!$user->isMemberOf($mess)) {
            abort(403, 'You are not a member of this mess.');
        }

        $mess->load('members', 'owner');

        $reports = [];
        for ($i = 0; $i < 3; $i++) {
            $month = Carbon::now()->subMonths($i)->format('Y-m');
            $reports[] = $billingService->generateReport($mess->id, $month);
        }

        return view('member.messes.show', compact('mess', 'reports'));
    }
}
