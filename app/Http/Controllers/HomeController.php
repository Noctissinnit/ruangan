<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $rooms = Room::with(["bookings" => function($query){
            $query->where('bookings.date', Carbon::today());
        }])->get();
        return view('home', compact('rooms'));
    }
}
