<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil user_id dari request (misalnya melalui URL atau query string)
        $userId = $request->input('user_id');

        // Cek jika user_id ada dan ambil data user dari database
        if ($userId) {
            $user = User::findOrFail($userId);

            // Kirim data user ke view
            return view('approval.utama', compact('user'));
        }

        // Jika user_id tidak ditemukan dalam request
        return redirect()->route('home')->with('error', 'User ID is required.');
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

        return redirect()->route('approval.utama', ['user_id' => $userId])->with('success', 'Invitation accepted.');
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

        return redirect()->route('approval.utama', ['user_id' => $userId])->with('success', 'Invitation declined.');
    }
}