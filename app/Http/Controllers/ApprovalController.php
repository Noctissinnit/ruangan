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
    public function accept(Request $request)
    {
        // Mengambil user_id dari request
        $userId = $request->input('user_id');

        // Ambil data user berdasarkan user_id
        $user = User::findOrFail($userId);

        // Update status menjadi "hadir"
        $user->status = 'hadir';
        $user->save();

        return redirect()->route('approval.index', ['user_id' => $userId])->with('success', 'Invitation accepted.');
    }

    /**
     * Handle rejecting the invitation.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(Request $request)
    {
        // Mengambil user_id dari request
        $userId = $request->input('user_id');

        // Ambil data user berdasarkan user_id
        $user = User::findOrFail($userId);

        // Update status menjadi "no response"
        $user->status = 'no response';
        $user->save();

        return redirect()->route('approval.index', ['user_id' => $userId])->with('success', 'Invitation declined.');
    }
}
