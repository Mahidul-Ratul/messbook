<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Mess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::where('user_id', Auth::id())->orderByDesc('date')->get();
        return view('member.expenses.index', compact('expenses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mess_id' => 'required|exists:messes,id',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        Expense::create([
            'mess_id' => $request->mess_id,
            'user_id' => Auth::id(),
            'date' => $request->date,
            'amount' => $request->amount,
            'description' => $request->description,
        ]);

        return redirect()->route('member.expenses.index')->with('success', 'Expense added successfully.');
    }

    public function edit(Expense $expense)
    {
        if ($expense->user_id !== Auth::id()) abort(403);
        $messes = Auth::user()->memberships;
        return view('member.expenses.edit', compact('expense', 'messes'));
    }

    public function update(Request $request, Expense $expense)
    {
        if ($expense->user_id !== Auth::id()) abort(403);

        $request->validate([
            'mess_id' => 'required|exists:messes,id',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $expense->update($request->only('mess_id', 'date', 'amount', 'description'));

        return redirect()->route('member.expenses.index')->with('success', 'Expense updated.');
    }

    public function destroy(Expense $expense)
    {
        if ($expense->user_id !== Auth::id()) abort(403);
        $expense->delete();

        return redirect()->route('member.expenses.index')->with('success', 'Expense deleted.');
    }
}
