<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Mess;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Services\MonthlyBillingService;
use App\Models\DailyMeal;
use App\Models\Expense;
use App\Models\MessJoinRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\MemberMeal;

class AdminController extends Controller
{
   public function dashboard()
    {
        // Summary Counts
        $userCount = User::count();
        $messCount = Mess::count();
        $ownerCount = User::role('mess_owner')->count();
        $memberCount = User::role('member')->count();
        $adminCount = User::role('admin')->count();

        // Fetch recent activities
        $newMesses = Mess::latest()->with('owner')->take(5)->get()->map(function ($mess) {
            $mess->activity_type = 'New Mess';
            $mess->activity_date = $mess->created_at;
            return $mess;
        });

        $joinRequests = MessJoinRequest::latest()->with(['user', 'mess'])->take(5)->get()->map(function ($request) {
            $request->activity_type = 'Join Request';
            $request->activity_date = $request->created_at;
            return $request;
        });
        
        $newUsers = User::latest()->take(5)->get()->map(function ($user) {
            $user->activity_type = 'New User';
            $user->activity_date = $user->created_at;
            return $user;
        });

        // Merge and sort activities
        $activities = (new Collection($newMesses))
            ->merge($joinRequests)
            ->merge($newUsers)
            ->sortByDesc('activity_date')
            ->take(10); // Take the 10 most recent overall

        // User registration chart data for the last 7 days
        $userStats = User::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', Carbon::now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->pluck('count', 'date');

        $dates = collect();
        for ($i = 6; $i >= 0; $i--) {
            $dates->put(Carbon::now()->subDays($i)->format('Y-m-d'), 0);
        }

        $userChartData = $dates->merge($userStats);
        
        // Role distribution data for pie chart
        $roleDistributionData = [
            'Admins' => $adminCount,
            'Owners' => $ownerCount,
            'Members' => $memberCount,
        ];

        return view('admin.dashboard', compact('userCount', 'messCount', 'ownerCount', 'memberCount', 'activities', 'userChartData', 'roleDistributionData'));
    }

    public function users(Request $request)
    {
        $query = User::with('roles')->latest();

        // Role filter
        if ($request->filled('role')) {
            $role = $request->input('role');
            if (in_array($role, ['mess_owner', 'member'])) {
                $query->whereHas('roles', function ($q) use ($role) {
                    $q->where('name', $role);
                });
            }
        }

        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(9)->withQueryString();

        if ($request->ajax()) {
            return response()->json([
                'users_html' => view('admin._user_list', compact('users'))->render(),
                'pagination_html' => $users->links()->toHtml(),
            ]);
        }

        return view('admin.users', compact('users'));
    }

    public function destroy(User $user)
    {
        // Prevent admin from deleting themselves
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        // Prevent deleting another admin if you are not the super admin (user id 1)
        if ($user->hasRole('admin') && Auth::id() !== 1) {
            return back()->with('error', 'You do not have permission to delete another administrator.');
        }
        
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User has been deleted successfully.');
    }

    public function editUser(User $user)
    {
        $roles = Role::all();
        return view('admin.edit_user', compact('user', 'roles'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name'
        ]);

        $user->syncRoles([$request->role]);

        return redirect()->route('admin.users')->with('success', 'User role updated.');
    }

    public function messes(Request $request)
    {
        $search = $request->input('search');

        $messes = Mess::with('owner')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('location', 'like', "%{$search}%")
                             ->orWhereHas('owner', function ($q) use ($search) {
                                 $q->where('name', 'like', "%{$search}%");
                             });
            })
            ->latest()
            ->paginate(9);

        if ($request->ajax()) {
            return view('admin._mess_list', compact('messes'))->render();
        }

        return view('admin.messes', compact('messes'));
    }

    public function showMess(Mess $mess, MonthlyBillingService $billingService)
    {
        $report = $billingService->generateReport($mess->id, date('Y-m'));

        $monthlyData = [];
        $startOfMonth = now()->startOfMonth();

        for ($i = 0; $i < 6; $i++) {
            $date = $startOfMonth->copy()->subMonths($i);
            $month = $date->format('Y-m');
            
            $totalMeals = MemberMeal::where('mess_id', $mess->id)
                ->whereYear('date', $date->year)
                ->whereMonth('date', $date->month)
                ->sum('meal_count');
            
            $totalExpenses = Expense::where('mess_id', $mess->id)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('amount');

            $monthlyData[] = [
                'month' => $date->format('F Y'),
                'total_meals' => $totalMeals,
                'total_expenses' => $totalExpenses
            ];
        }

        return view('admin.messes_show', compact('mess', 'report', 'monthlyData'));
    }

    public function destroyMess(Mess $mess)
    {
        $mess->delete();
        return redirect()->route('admin.messes')->with('success', 'Mess deleted successfully.');
    }

    public function assignMessForm(User $user)
{
    $messes = Mess::all();
    return view('admin.assign_mess', compact('user', 'messes'));
}

public function assignMessStore(Request $request, User $user)
{
    $request->validate([
        'mess_id' => 'required|exists:messes,id'
    ]);

    // prevent duplicate attach
    $user->memberships()->syncWithoutDetaching($request->mess_id);

    return redirect()->route('admin.users')->with('success', 'Mess assigned to user successfully.');
}

}
