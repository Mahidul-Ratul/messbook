<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Mess;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class MemberExpenseController extends Controller
{
    public function index(User $user)
    {
        $mess = Mess::where('owner_id', Auth::id())->firstOrFail();

        $expenses = Expense::where('mess_id', $mess->id)
            ->where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->get();

        return view('owner.members.expenses', compact('user', 'expenses'));
    }

    public function store(Request $request, User $user)
    {
        $mess = Mess::where('owner_id', Auth::id())->firstOrFail();

        $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'note' => 'nullable|string',
        ]);

        Expense::create([
            'mess_id' => $mess->id,
            'user_id' => $user->id,
            'date' => $request->date,
            'amount' => $request->amount,
            'note' => $request->note,
        ]);

        return redirect()->route('owner.members.expenses', $user->id)->with('success', 'Expense added successfully.');
    }
}
