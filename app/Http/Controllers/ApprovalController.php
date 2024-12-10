<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingUser;
use App\Models\User;

use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function index(Request $request, string $id)
    {
        $bookingUsers = BookingUser::where('unique_id', $id)->first();
        $status = $bookingUsers->status;
        $booking = $bookingUsers->booking;
        $user = $bookingUsers->user;
        return view('approval.index', compact('booking', 'user', 'status'));
    }

    public function show(Request $request, string $id)
    {
        $bookingUsers = BookingUser::where('unique_id', $id)->first();
        $booking = $bookingUsers->booking;
        $user = $bookingUsers->user;
        $uniqueId = $id;
        return view('approval.confirm', compact('uniqueId', 'booking', 'user'));
    }

    /**
     * Handle accepting the invitation.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirm(Request $request, string $id, string $response)
    {
        $bookingUser = BookingUser::where('unique_id', $id);
        $bookingUser->update(['status' => $response]);

        $bookingUser = $bookingUser->first();
        return redirect()->route('approval.index', ['id' => $id])
            ->with('success', 'Invitation accepted.');
    }
}
