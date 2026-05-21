<?php


use App\Models\User;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Owner\MessController;
use App\Http\Controllers\Owner\DailyMealController;
use App\Http\Controllers\Member\MessJoinController;
use App\Http\Controllers\Owner\OwnerDashboardController;
use App\Http\Controllers\Owner\MessMemberController;
use App\Http\Controllers\Member\MemberDashboardController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Member\ExpenseController;
use App\Http\Controllers\Owner\MemberExpenseController;
use App\Http\Controllers\Owner\MemberMealController;
use App\Http\Controllers\Owner\MonthlyReportController;
use App\Http\Controllers\Owner\OwnerAjaxController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Member\MemberMealController as MemberMemberMealController;

// Routes

// Home

// Dashboard (everyone after login)
// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('dashboard');
//     })->name('dashboard');
// });

// Profile Routes
// Place the welcome route at the top
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/{user}', [UserProfileController::class, 'show'])->name('profile.show');

    // Dashboard route - redirects based on user role
    Route::get('/dashboard', function () {
        /** @var User $user */
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }
        if ($user->hasRole('mess_owner')) {
            return redirect()->route('owner.dashboard');
        }
        if ($user->hasRole('member')) {
            return redirect()->route('member.dashboard');
        }
        return redirect()->route('welcome');
    })->name('dashboard');

    // General Reports
    Route::get('/reports', [App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{mess}', [App\Http\Controllers\ReportController::class, 'show'])->name('reports.show');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // User Management
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::post('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'destroy'])->name('users.destroy');
    Route::get('/users/{user}/assign-mess', [AdminController::class, 'assignMessForm'])->name('users.assign-mess');
    Route::post('/users/{user}/assign-mess', [AdminController::class, 'assignMessStore'])->name('users.assign-mess.store');

    // Mess Management
    Route::get('/messes', [AdminController::class, 'messes'])->name('messes');
    Route::get('/messes/{mess}', [AdminController::class, 'showMess'])->name('messes.show');
    Route::delete('/messes/{mess}', [AdminController::class, 'destroyMess'])->name('messes.destroy');
});

// Publicly viewable Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile/{user}', [UserProfileController::class, 'show'])->name('profile.show');
});

// Owner Routes
Route::middleware(['auth', 'role:mess_owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [OwnerDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/mess', [MessController::class, 'show'])->name('mess.show');
    Route::get('/mess/edit', [MessController::class, 'edit'])->name('mess.edit');
    Route::put('/mess', [MessController::class, 'update'])->name('mess.update');
    Route::get('/mess/members', [MessMemberController::class, 'index'])->name('members.index');
    Route::get('/mess/members/create', [MessMemberController::class, 'create'])->name('members.create');
    Route::post('/mess/members', [MessMemberController::class, 'store'])->name('members.store');
    Route::delete('/mess/members/{member}', [MessMemberController::class, 'destroy'])->name('members.destroy');
    Route::get('/members/search-ajax', [MessMemberController::class, 'searchAjax'])->name('members.search-ajax');
    Route::get('/members/requests', [MessMemberController::class, 'requests'])->name('members.requests');
    Route::get('/members/{user}/meals', [MemberMealController::class, 'index'])->name('members.meals');
    Route::post('/members/{user}/meals', [MemberMealController::class, 'store'])->name('members.meals.store');
    Route::get('/members/{user}/expenses', [MemberExpenseController::class, 'index'])->name('members.expenses');
    Route::post('/ajax/members/{user}/toggle-meal-role', [OwnerAjaxController::class, 'toggleMealManagerRole'])->name('ajax.members.toggle_meal_role');
    Route::resource('reports', MonthlyReportController::class);
    
    // Mess Management Routes
    Route::resource('messes', MessController::class);

    // Approve/Reject Join Requests
    Route::post('/members/requests/{id}/approve', [MessMemberController::class, 'approve'])->name('members.approve');
    Route::post('/members/requests/{id}/reject', [MessMemberController::class, 'reject'])->name('members.reject');
});

// Daily Meals Routes (accessible by both mess_owner and meal_manager)
Route::middleware(['auth', 'role:mess_owner|meal_manager'])->prefix('owner')->name('owner.')->group(function () {
    Route::resource('daily_meals', DailyMealController::class);
});

// Member Routes
Route::middleware(['auth', 'role:member'])->prefix('member')->name('member.')->group(function () {
    Route::get('/dashboard', [MemberDashboardController::class, 'dashboard'])->name('dashboard');

    // Mess Joining
    Route::get('/messes/join', [MessJoinController::class, 'index'])->name('messes.join');
    Route::post('/messes/{mess}/send-request', [MessJoinController::class, 'sendRequest'])->name('messes.send-request');

    // My Messes
    Route::get('/my-messes', [MemberDashboardController::class, 'myMesses'])->name('messes.my');
    Route::get('/my-messes/{mess}', [MemberDashboardController::class, 'showMyMess'])->name('messes.show');
    Route::delete('/my-messes/{mess}/leave', [MessJoinController::class, 'leaveMess'])->name('messes.leave');
    
    // Expenses
    Route::get('expenses', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::post('expenses', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::get('expenses/{expense}/edit', [ExpenseController::class, 'edit'])->name('expenses.edit');
    Route::put('expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
    Route::delete('expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');
    // Billing view
    Route::get('bill/{id}', [ExpenseController::class, 'showBill'])->name('bill.show');
    
    // Member Meals
    Route::get('meals', [MemberMemberMealController::class, 'index'])->name('meals.index');
    Route::get('meals/create', [MemberMemberMealController::class, 'create'])->name('meals.create');
    Route::post('meals', [MemberMemberMealController::class, 'store'])->name('meals.store');
    Route::get('meals/{memberMeal}/edit', [MemberMemberMealController::class, 'edit'])->name('meals.edit');
    Route::put('meals/{memberMeal}', [MemberMemberMealController::class, 'update'])->name('meals.update');
    Route::delete('meals/{memberMeal}', [MemberMemberMealController::class, 'destroy'])->name('meals.destroy');
});

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/redirect-after-login', function () {
    /** @var User $user */
    $user = \Illuminate\Support\Facades\Auth::user();



    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    }
    if ($user->hasRole('mess_owner')) {
        return redirect()->route('owner.dashboard');
    }
    if ($user->hasRole('member')) {
        return redirect()->route('member.dashboard');
    }

    return '/';
})->middleware('auth');


require __DIR__.'/auth.php';
