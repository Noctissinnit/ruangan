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
    public function homeYayasan()
    {
        $rooms = Room::where('type', 'home')
            ->with(["bookings" => function ($query) {
                $query->whereDate('bookings.date', \Carbon\Carbon::today());
            }])
            ->get();

        return view('home_yayasan', compact('rooms'));
    }


    public function homeMikael()
    {
        $rooms = Room::where('type', 'alternate')
            ->with(["bookings" => function ($query) {
                $query->whereDate('bookings.date', \Carbon\Carbon::today());
            }])
            ->get();

        return view('home_mikael', compact('rooms'));
    }


    public function homeAll()
    {
        $roomTypes = ['home', 'alternate'];
        $rooms = Room::whereIn('type', $roomTypes)
            ->orWhere(function ($query) use ($roomTypes) {
                foreach ($roomTypes as $type) {
                    $query->orWhere('type', 'like', "%$type%");
                }
            })
            ->with(['bookings' => function ($query) {
                $query->whereDate('bookings.date', Carbon::today());
            }])
            ->get();

        $homeRooms = $rooms->filter(fn($room) => str_contains($room->type, 'home'));
        $alternateRooms = $rooms->filter(fn($room) => str_contains($room->type, 'alternate'));

        return view('home_all', compact('rooms', 'homeRooms', 'alternateRooms'));
    }

    private function getRoomsByType($type)
    {
        return Room::where('type', $type)
            ->orWhere('type', 'like', "%$type%")
            ->with(["bookings" => function ($query) {
                $query->whereDate('bookings.date', Carbon::today());
            }])
            ->get();
    }
}
