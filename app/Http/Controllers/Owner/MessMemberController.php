<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Mess;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessMemberController extends Controller
{
    public function index()
    {
        $mess = Mess::where('owner_id', Auth::id())->firstOrFail();
        $members = $mess->memberships()->with('roles')->get();
        return view('owner.members.index', compact('mess', 'members'));
    }

    public function create()
    {
        $mess = Mess::where('owner_id', Auth::id())->firstOrFail();
        return view('owner.members.create', compact('mess'));
    }

    public function store(Request $request)
    {
        $mess = Mess::where('owner_id', Auth::id())->firstOrFail();
        
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        if ($mess->memberships()->where('user_id', $request->user_id)->exists()) {
            return back()->with('error', 'User is already a member of this mess.');
        }

        $mess->memberships()->attach($request->user_id);

        return redirect()->route('owner.members.index')->with('success', 'Member added successfully!');
    }

    public function destroy(User $member)
    {
        $mess = Mess::where('owner_id', Auth::id())->firstOrFail();
        
        if (!$mess->memberships()->where('user_id', $member->id)->exists()) {
            return back()->with('error', 'This user is not a member of your mess.');
        }

        $mess->memberships()->detach($member->id);

        return redirect()->route('owner.members.index')->with('success', 'Member removed successfully.');
    }

    public function toggleMealManagerRole(User $user)
    {
        $mess = Mess::where('owner_id', Auth::id())->firstOrFail();
        
        if (!$mess->memberships()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'This user is not a member of your mess.');
        }

        if ($user->hasRole('meal_manager')) {
            $user->removeRole('meal_manager');
            $message = 'Meal manager permissions revoked.';
        } else {
            $user->assignRole('meal_manager');
            $message = 'Meal manager permissions granted.';
        }

        return back()->with('success', $message);
    }

    public function searchAjax(Request $request)
    {
        $mess = Mess::where('owner_id', Auth::id())->firstOrFail();
        $query = $request->q;

        $users = User::role('member')
            ->where('email', 'like', '%' . $query . '%')
            ->whereDoesntHave('memberships', function ($q) use ($mess) {
                $q->where('mess_id', $mess->id);
            })
            ->limit(10)
            ->get(['id', 'name', 'email']);

        return response()->json($users);
    }

    public function requests()
    {
        $mess = Mess::where('owner_id', Auth::id())->firstOrFail();
        $requests = $mess->pendingJoinRequests;
        return view('owner.members.requests', compact('requests'));
    }
    
    public function approve($id)
    {
        $request = \App\Models\MessJoinRequest::findOrFail($id);
        $mess = Mess::where('owner_id', Auth::id())->firstOrFail();
        
        if ($request->mess_id !== $mess->id) {
            abort(403);
        }
        
        $request->status = 'approved';
        $request->save();

        DB::table('mess_members')->updateOrInsert(
            [
                'mess_id' => $request->mess_id,
                'user_id' => $request->user_id,
            ],
            [
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        return redirect()->route('owner.members.requests')->with('success', 'Request approved.');
    }

    public function reject($id)
    {
        $request = \App\Models\MessJoinRequest::findOrFail($id);
        $mess = Mess::where('owner_id', Auth::id())->firstOrFail();
        
        if ($request->mess_id !== $mess->id) {
            abort(403);
        }
        
        $request->status = 'rejected';
        $request->save();

        return redirect()->route('owner.members.requests')->with('success', 'Request rejected.');
    }
}
