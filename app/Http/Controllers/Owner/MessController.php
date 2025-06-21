<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mess;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Services\MonthlyBillingService;
use App\Models\DailyMeal;
use App\Models\Expense;
use Carbon\Carbon;

class MessController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $messes = Mess::where('owner_id', Auth::id())->get();
        return view('owner.messes.index', compact('messes'));
    }

    public function show()
    {
        $mess = Mess::where('owner_id', Auth::id())->firstOrFail();
        return view('owner.mess.show', compact('mess'));
    }

    public function create()
    {
        $existingMess = Mess::where('owner_id', Auth::id())->first();

        if ($existingMess) {
            return redirect()->route('owner.messes.index')->with('error', 'You already have a mess.');
        }

        return view('owner.messes.create');
    }

    public function store(Request $request)
    {
        // Check if owner already has a mess
        if (Mess::where('owner_id', Auth::id())->exists()) {
            return back()->with('error', 'You already have a mess. You cannot create another one.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $mess = Mess::create([
            'name' => $request->name,
            'location' => $request->location,
            'description' => $request->description,
            'owner_id' => Auth::id(),
        ]);

        return redirect()->route('owner.mess.show')->with('success', 'Mess created successfully.');
    }

    public function edit()
    {
        $mess = Mess::where('owner_id', Auth::id())->firstOrFail();
        return view('owner.mess.edit', compact('mess'));
    }

    public function update(Request $request)
    {
        $mess = Mess::where('owner_id', Auth::id())->firstOrFail();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $mess->update($request->only(['name', 'location', 'description']));

        return redirect()->route('owner.mess.show')->with('success', 'Mess details updated successfully.');
    }

    public function destroy(Mess $mess)
    {
        $this->authorize('delete', $mess);
        $mess->delete();

        return redirect()->route('owner.messes.index')->with('success', 'Mess deleted successfully.');
    }
}
