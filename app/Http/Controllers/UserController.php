<?php

namespace App\Http\Controllers;

use App\Imports\UsersImport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function get(Request $request)
    {
        return response()->json(User::where('id', $request->id)->first());
    }

    public function import(Request $request)
    {
        $request->validate([
            'excel' => 'required|mimes:xlsx,xls'
        ]);

        $import = Excel::import(new UsersImport, $request->excel);
        if ($import) {
            return redirect()->route('admin.dashboard')->with('success', 'Berhasil mengimpor users.');
        } else {
            return redirect()->route('admin.dashboard')->with('error', 'Gagal mengimpor users.');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            "name" => "required",
            "email" => "required|email",
            "nis" => "required|numeric",
            "password" => "required",
            "pin" => "required",
            "role" => "required",
            "department_id" => 'required|numeric',
            "jabatan_id" => 'required|numeric'
        ]);

        User::insert($request->except("_token", "_method"));

        return redirect()->route("admin.dashboard");
    }

    public function update(Request $request)
    {
        $request->validate([
            "id" => "required|numeric",
            "name" => "required",
            "email" => "required|email",
            "nis" => "required|numeric",
            "pin" => "required|numeric",
            "department_id" => 'required|numeric',
            "jabatan_id" => 'required|numeric'
        ]);

        User::where("id", $request->id)->update($request->except("_token", "_method", "id", "password"));

        return redirect()->route("admin.dashboard");
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route("admin.dashboard");
    }
    // Tampilkan halaman untuk melakukan booking
    // public function index()
    // {
    //     $rooms = Room::all();  // Ambil semua room yang tersedia
    //     $bookings = Booking::where('user_id', Auth::id())->get(); // Booking yang dibuat oleh user

    //     return view('user.bookings', compact('rooms', 'bookings'));
    // }

    // // Store booking dari user
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'room_id' => 'required|exists:rooms,id',
    //         'start_time' => 'required|date',
    //         'end_time' => 'required|date|after:start_time',
    //         'description' => 'nullable|string',
    //     ]);

    //     Booking::create([
    //         'user_id' => Auth::id(),
    //         'room_id' => $request->room_id,
    //         'start_time' => $request->start_time,
    //         'end_time' => $request->end_time,
    //         'description' => $request->description,
    //         'is_approved' => false, // Booking menunggu persetujuan admin
    //     ]);

    //     return redirect()->back()->with('success', 'Booking berhasil diajukan, menunggu persetujuan admin.');
    // }
}
