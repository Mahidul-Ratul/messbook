<?php

namespace App\Http\Controllers;

use App\Models\Mess;
use App\Services\MonthlyBillingService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $messes = $user->memberships;

        if ($messes->count() === 1) {
            return redirect()->route('reports.show', $messes->first()->id);
        }

        return view('reports.index', compact('messes'));
    }

    public function show(Mess $mess, Request $request, MonthlyBillingService $billingService)
    {
        $month = $request->input('month');

        // Validate the month format, default to current month if invalid
        if (!$month || !preg_match('/^\d{4}-\d{2}$/', $month)) {
            $month = Carbon::now()->format('Y-m');
        }

        $report = $billingService->generateReport($mess->id, $month);

        $previousMonths = [];
        for ($i = 1; $i <= 12; $i++) {
            $previousMonths[] = Carbon::now()->subMonths($i)->format('Y-m');
        }

        return view('reports.show', compact('mess', 'report', 'previousMonths'));
    }
}
