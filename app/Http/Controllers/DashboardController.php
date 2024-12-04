<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $approvedBookings = Booking::where('is_approved', true)->get();
        return view('dashboard', compact('approvedBookings'));
    }

    public function indexUser()
    {
        $bookings = Auth::user()->bookings()->orderBy('date', 'desc')->get();
        return view('user.dashboard', compact('bookings'));
    }
}
