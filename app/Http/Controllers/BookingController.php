<?php

namespace App\Http\Controllers;

use App\Exports\BookingsExport;
use App\Mail\BookingApprovedMail;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Mail\InvitationMail;
use Google\Client as GoogleClient;
use Google\Service\Calendar as GoogleCalendar;
use Google\Service\Calendar\Event;
use Google\Service\Calendar\EventDateTime;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function list(Request $request)
    {
        $bookings = Booking::with('room')->with('department')->with('user:id,nis,name');
        if ($request->date) $bookings = $bookings->whereDate('date', $request->date);
        if ($request->room_id) $bookings = $bookings->where('room_id', $request->room_id);

        $bookings = $bookings->get();

        return response()->json($bookings);
    }
    // Hanya menampilkan form booking untuk user biasa
    public function create(Request $request, int $id)
    {
        $roomId = $id;
        $room = Room::where('id', $roomId)->first();
        $users = User::all();

        $user_department = null;
        if (session('google_bookings_user_id') && session('google_bookings_date') && session('google_access_token')) {
            $user_department = User::find(session('google_bookings_user_id'))->department;
        }
        $officeMode = false;
        if ($request->has('office')) {
            $officeMode = true;
        }

        return view("bookings.create", compact("room", "roomId", "users", 'user_department', 'officeMode'));
    }

    public function resetSession(Request $request)
    {
        $request->session()->remove('google_access_token');
        $request->session()->remove('google_bookings_user_id');
        $request->session()->remove('google_bookings_date');
        $request->session()->remove('google_bookings_room_id');
        $request->session()->save();

        return response()->json(['success' => true]);
    }

    public function roomAvailable(Room $room)
    {
        $available = $room->bookings()->where('date', Carbon::today())
            ->where('end_time', '>', Carbon::now())->exists();
        return response()->json(['available' => !$available]);
    }

    public function login(Request $request)
    {
        $request->validate([
            "nis" => ["required"],
            "password" => ["required"],
        ]);
        $user = User::where("nis", $request->nis)->where('pin', $request->password)->with('department')->first();
        if ($user === null) {
            return response()->json(['success' => false, 'message' => 'NIS atau PIN salah. Silakan coba lagi.']);
        }
        if ($user->isUser()) {
            return response()->json(['success' => false, 'message' => 'Maaf, kamu tidak memiliki akses untuk menambahkan booking.']);
        }

        return response()->json([
            "success" => true,
            "data" => $user,
        ]);
    }

    // Menyimpan booking baru
    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required',
            'password' => 'required|numeric',
            "room_id" => "required",
            "date" => "required|date",
            "start_time" => "required",
            "end_time" => "required",
            "description" => "required",
            "department_id" => "required|numeric",
            "users" => "nullable",
            "date" => "nullable",
        ]);


        // $user = User::find(session('google_bookings_user_id'));
        $user = User::where('nis', $request->nis)->where('pin', $request->password)->first();
        if ($user === null || $user->isUser()) {
            return back()->with('error', 'Failed to validate user');
        }

        $booking = Booking::create(array_merge($request->all('room_id', 'date', 'start_time', 'end_time', 'description', 'department_id'), [
            "user_id" => $user->id,
            // 'approved' => false, // Menunggu approval
            "approved" => true, // Otomatis approve
        ]));
        if ($request->users) {
            $users = $request->users;

            $syncData = [];
            foreach ($users as $userId) {
                $syncData[$userId] = [
                    'unique_id' => Str::random(20),
                ];
            }
            $booking->users()->sync($syncData);
        }
        $users = Booking::where('id', $booking->id)->first()->users;

        foreach ($users as $user) {
            Mail::to($user)->send(new InvitationMail($booking, $user));
        }

        return response()->json();
    }



    public function destroy(Request $request)
    {
        $booking = Booking::where('id', $request->id);
        $roomId = $booking->first()->room_id;
        $booking->delete();

        if (url()->previous() == route('admin.bookings.index', $roomId)) {
            return redirect()->route('admin.bookings.index', $roomId)
                ->with('success', 'Booking berhasil dihapus');
        }

        return redirect()
            ->route("bookings.create", $roomId)
            ->with("success", "Booking berhasil dihapus.");
    }

    // Menampilkan booking untuk admin
    public function indexAdmin(Request $request, Room $room)
    {
        $bookings = $room->bookings();
        if ($request->has('date')) {
            $bookings = $bookings->where('date', $request->date);
        } else {
            $bookings = $bookings->where('date', Carbon::today());
        }

        $bookings = $bookings->get();

        return view("admin.bookings.index", compact('room', "bookings"));
    }

    // Proses approve booking oleh admin
    public function approve($id)
    {
        if (Auth::user()->role !== "admin") {
            return redirect()
                ->route("home")
                ->with("error", "Unauthorized access");
        }

        $booking = Booking::find($id);
        if ($booking) {
            $booking->approved = true;
            $booking->save();

            // Kurangi 7 jam dari waktu mulai dan selesai
            $startDateTime = Carbon::parse(
                $booking->date . " " . $booking->start_time
            )->subHours(7);
            $endDateTime = Carbon::parse(
                $booking->date . " " . $booking->end_time
            )->subHours(7);

            // Buat event di sistem Anda (misal dengan Spatie Google Calendar)
            // $event = new Event;
            // $event->name = 'Meeting Room Booking: ' . $booking->room->name;
            // $event->startDateTime = $startDateTime;
            // $event->endDateTime = $endDateTime;
            // $event->description = $booking->description;
            // $event->save();

            // Kirim email notifikasi setelah approve
            Mail::to($booking->user->email)->send(
                new BookingApprovedMail($booking)
            );

            // Ambil token OAuth dari session
            $accessToken = session("google_access_token");

            // Jika token tidak ada, arahkan pengguna untuk login ulang dengan Google
            if (!$accessToken) {
                return redirect()
                    ->route("login.google")
                    ->with(
                        "error",
                        "Please login with Google to sync your calendar."
                    );
            }

            // Inisialisasi Google Client dengan token
            $client = new \Google_Client();
            $client->setAccessToken($accessToken);

            $service = new \Google_Service_Calendar($client);

            // Membuat event untuk disimpan di kalender pengguna
            $googleEvent = new \Google_Service_Calendar_Event([
                "summary" => "Meeting Room Booking: " . $booking->room->name,
                "start" => [
                    "dateTime" => $startDateTime->toRfc3339String(), // Gunakan waktu yang sudah dikurangi 7 jam
                    "timeZone" => "Asia/Jakarta",
                ],
                "end" => [
                    "dateTime" => $endDateTime->toRfc3339String(), // Gunakan waktu yang sudah dikurangi 7 jam
                    "timeZone" => "Asia/Jakarta",
                ],
                "attendees" => [
                    ["email" => $booking->user->email], // email pengguna yang diundang
                ],
            ]);

            // Simpan event ke kalender utama pengguna
            $service->events->insert("primary", $googleEvent);

            return redirect()
                ->route("admin.bookings.index")
                ->with(
                    "success",
                    "Booking approved and event created in Google Calendar."
                );
        }

        return redirect()
            ->route("admin.bookings.index")
            ->with("error", "Booking not found.");
    }

    public function export(Request $request, Room $room)
    {
        $export = new BookingsExport($room);
        if ($request->date) {
            $export->date = $request->date;
        }
        return Excel::download(
            $export,
            'AtmiBookingRooms.' . ($request->type === 'pdf' ? 'pdf' : 'xlsx'),
            $request->type === 'pdf' ? \Maatwebsite\Excel\Excel::DOMPDF : \Maatwebsite\Excel\Excel::XLSX
        );
    }
}
