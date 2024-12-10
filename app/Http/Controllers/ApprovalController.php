<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;

use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function index(Request $request, Booking $booking, User $user)
    {
        $bookingUsers = $booking->users()->where('user_id', $user->id)->first();
        $status = $bookingUsers->pivot->status;
        return view('approval.index', compact('booking', 'user', 'status'));
    }

    public function show(Request $request, Booking $booking, User $user)
    {
        return view('approval.confirm', compact('booking', 'user'));
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
        return redirect()->route('approval.index', ['booking' => $booking, 'user' => $user])->with('success', 'Invitation accepted.');
    }
}
