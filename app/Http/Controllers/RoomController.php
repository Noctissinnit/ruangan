<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::all();
        return view('rooms.index', compact('rooms'));
    }
    
    public function list(){
        return response()->json(Room::with('bookings')->get());
    }

    public function create()
    {
        return view('rooms.create');
    }

    public function store(Request $request)
{
    // Validasi input
    $request->validate([
        'name' => 'required',
        'image' => 'required|image',  // Pastikan file yang di-upload adalah gambar
        'description' => 'required',
        'type' => 'required|in:home,alternate' // Validasi tipe ruangan
    ]);

    // Ambil file gambar yang di-upload
    $image = $request->image;

    // Mengonversi gambar ke format Base64 (tanpa informasi MIME type)
    $imageData = base64_encode(file_get_contents($image));  // Encode gambar ke Base64

    // Menyimpan data room ke database dengan Base64 string gambar
    Room::create([
        'name' => $request->name,  // Menyimpan 'name' dari request
        'description' => $request->description,  // Menyimpan 'description' dari request
        'image' => $imageData,  // Menyimpan Base64 gambar (tanpa informasi MIME)
        'type' => $request->type // Menyimpan tipe ruangan
    ]);

    // Redirect dengan pesan sukses
    return redirect()->route('rooms.index')->with('success', 'Room created successfully.');
}


    public function edit(Room $room)
    {
        return view('rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        // Validasi input
        $request->validate([
            'name' => 'required',
            'image' => 'nullable|image',  // Gambar bersifat opsional (nullable)
            'description' => 'required'
        ]);

        // Persiapkan array fields untuk update
        $fields = $request->only('name', 'description'); // Ambil hanya 'name' dan 'description'

        // Jika ada gambar yang di-upload, konversi ke Base64
        if ($request->hasFile('image')) {
            $image = $request->image;
            $imageData = base64_encode(file_get_contents($image)); // Encode gambar ke Base64
            $fields['image'] = $imageData; // Tambahkan Base64 gambar ke array fields
        }

        // Update data room dengan fields yang telah disiapkan
        $room->update($fields);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('rooms.index')->with('success', 'Room updated successfully.');
        }   

    // Hapus room (Admin only)
    public function destroy(Room $room)
    {
        Storage::disk('public')->delete($room->image ?? '');
        $room->delete();
        return redirect()->route('rooms.index')->with('success', 'Room deleted successfully.');
    }
}
