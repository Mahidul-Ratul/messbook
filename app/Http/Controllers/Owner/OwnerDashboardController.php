<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\DailyMeal;
use App\Models\Expense;
use App\Models\Mess;
use App\Models\MessJoinRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class OwnerDashboardController extends Controller
{
    public function dashboard()
    {
        $owner = Auth::user();
        $messes = Mess::where('owner_id', $owner->id)->get();
        $messIds = $messes->pluck('id');

        // Check if owner has any messes
        if ($messes->isEmpty()) {
            // Return empty dashboard for owners without messes
            return view('owner.dashboard', [
                'totalMembers' => 0,
                'totalMealsThisMonth' => 0,
                'totalExpensesThisMonth' => 0,
                'pendingJoinRequestsCount' => 0,
                'mealChartData' => collect(),
                'expenseChartData' => collect(),
                'activities' => collect(),
                'pendingRequests' => collect(),
            ]);
        }

        // Summary Cards Data
        $totalMembers = User::whereHas('memberships', fn($q) => $q->whereIn('mess_id', $messIds))->count();
        $totalMealsThisMonth = DailyMeal::whereIn('mess_id', $messIds)->where('date', '>=', now()->startOfMonth())->sum('total_meal');
        $totalExpensesThisMonth = Expense::whereIn('mess_id', $messIds)->where('date', '>=', now()->startOfMonth())->sum('amount');
        $pendingJoinRequestsCount = MessJoinRequest::whereIn('mess_id', $messIds)->where('status', 'pending')->count();

        // Chart Data: Last 7 days meal summary
        $mealStats = DailyMeal::select(DB::raw('DATE(date) as date'), DB::raw('sum(total_meal) as count'))
            ->whereIn('mess_id', $messIds)
            ->where('date', '>=', now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()->pluck('count', 'date');
        
        $dates = collect();
        for ($i = 6; $i >= 0; $i--) {
            $dates->put(now()->subDays($i)->format('Y-m-d'), 0);
        }
        $mealChartData = $dates->merge($mealStats);

        // Chart Data: Last 7 days expense summary
        $expenseStats = Expense::select(DB::raw('DATE(date) as date'), DB::raw('sum(amount) as total'))
            ->whereIn('mess_id', $messIds)
            ->where('date', '>=', now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()->pluck('total', 'date');
        $expenseChartData = $dates->merge($expenseStats);

        // Recent Activities
        $recentMeals = DailyMeal::whereIn('mess_id', $messIds)->with('user', 'mess')->latest()->take(5)->get();
        $recentExpenses = Expense::whereIn('mess_id', $messIds)->with('user', 'mess')->latest()->take(5)->get();
        $recentJoins = MessJoinRequest::whereIn('mess_id', $messIds)->where('status', 'approved')->with('user', 'mess')->latest('updated_at')->take(5)->get();

        $activities = new Collection([...$recentMeals, ...$recentExpenses, ...$recentJoins]);
        $activities = $activities->sortByDesc(fn($activity) => $activity->created_at ?? $activity->updated_at)->take(7);

        $pendingRequests = MessJoinRequest::whereIn('mess_id', $messIds)->where('status', 'pending')->with('user', 'mess')->latest()->get();

        return view('owner.dashboard', compact(
            'totalMembers', 
            'totalMealsThisMonth', 
            'totalExpensesThisMonth',
            'pendingJoinRequestsCount',
            'mealChartData',
            'expenseChartData',
            'activities',
            'pendingRequests'
        ));
    }
}
