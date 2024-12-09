<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;

use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function index(Request $request, Booking $booking, User $user)
    {
        return view('approval.index', compact('booking', 'user'));
    }
  
    /**
     * Handle accepting the invitation.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirm(Request $request, Booking $booking, User $user, string $response)
    {
        $booking->users()->updateExistingPivot($user->id, ['status' => $response]);
        return redirect()->route('home')->with('success', 'Invitation accepted.');
    }
}
