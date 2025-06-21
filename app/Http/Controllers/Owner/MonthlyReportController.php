<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\DailyMeal;
use App\Models\Expense;
use App\Models\Mess;
use App\Models\MemberMeal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MonthlyReportController extends Controller
{
    public function index(Request $request)
    {
        $owner = Auth::user();

        // Automatically get owner's mess
        $mess = Mess::where('owner_id', $owner->id)->firstOrFail();

        // Default month to current month
        $selectedMonth = $request->month ?? now()->format('Y-m');

        $report = null;

        if ($request->has('month')) {
            $report = $this->generateReport($mess, $selectedMonth);
        }

        return view('owner.reports.index', compact('selectedMonth', 'report'));
    }

    private function generateReport(Mess $mess, $month)
    {
        // Parse month
        $startDate = $month . '-01';
        $endDate = date('Y-m-t', strtotime($startDate));

        // Get members of mess
        $members = $mess->memberships;

        $totalMeals = DailyMeal::where('mess_id', $mess->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('total_meal');

        $totalExpenses = Expense::where('mess_id', $mess->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');

        $mealRate = $totalMeals > 0 ? round($totalExpenses / $totalMeals, 2) : 0;

        $memberReports = [];

        foreach ($members as $member) {
            // Use MemberMeal instead of DailyMeal for individual member meals
            $memberMeals = MemberMeal::where('mess_id', $mess->id)
                ->where('user_id', $member->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->sum('meal_count');

            $memberExpenses = Expense::where('mess_id', $mess->id)
                ->where('user_id', $member->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->sum('amount');

            $bill = round($memberMeals * $mealRate, 2);
            $balance = round($memberExpenses - $bill, 2);

            $memberReports[] = [
                'member' => $member,
                'meals' => $memberMeals,
                'expenses' => $memberExpenses,
                'bill' => $bill,
                'balance' => $balance,
            ];
        }

        return [
            'totalMeals' => $totalMeals,
            'totalExpenses' => $totalExpenses,
            'mealRate' => $mealRate,
            'memberReports' => $memberReports,
        ];
    }
}
