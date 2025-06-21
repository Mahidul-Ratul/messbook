<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mess;
use App\Models\MessJoinRequest;
use Illuminate\Support\Facades\Auth;

class MessJoinController extends Controller
{
    // Show all messes to member (search page)
    public function index(Request $request)
    {
        $user = Auth::user();
        $userMessCount = $user->memberships()->count();

        // Get IDs of messes the user is in, has requested, or owns
        $existingMessIds = $user->memberships()->pluck('messes.id');
        $requestedMessIds = $user->joinRequests()->pluck('mess_id');
        $ownedMessIds = $user->messes()->pluck('id');
        $allForbiddenIds = $existingMessIds->merge($requestedMessIds)->merge($ownedMessIds)->unique();

        $query = Mess::whereNotIn('id', $allForbiddenIds)->latest();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $availableMesses = $query->paginate(8);

        if ($request->ajax()) {
            return view('member.messes._join_mess_list', compact('availableMesses', 'userMessCount'))->render();
        }

        return view('member.messes.join', compact('availableMesses', 'userMessCount'));
    }

    // Submit request to join a mess
    public function sendRequest(Mess $mess)
    {
        $user = Auth::user();

        if ($user->memberships()->count() >= 2) {
            return back()->with('error', 'You can join a maximum of two messes.');
        }
        
        if ($user->joinRequests()->where('mess_id', $mess->id)->exists()) {
            return back()->with('info', 'You have already sent a join request to this mess.');
        }

        $mess->joinRequests()->create([
            'user_id' => auth()->id(),
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Join request sent successfully!');
    }

    public function leaveMess(Mess $mess)
    {
        $user = auth()->user();
        if ($user->isMemberOf($mess)) {
            $user->memberships()->detach($mess->id);
            return redirect()->route('member.messes.my')->with('success', 'You have successfully left the mess.');
        }

        return redirect()->route('member.messes.my')->with('error', 'You are not a member of this mess.');
    }

    // Show messes already joined by current member
    public function myMesses()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $messes = $user->memberships;
        return view('member.messes.my_messes', compact('messes'));
    }
}
