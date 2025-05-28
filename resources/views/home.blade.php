@extends('layouts.app')

@section('head')
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<link rel="stylesheet" href="/css/home.css">
<script src="/js/bookings/create.js"></script>
<script>async function updateBookingsByRoom(roomId) {
    const url = new URL(listUrl);
    url.searchParams.set("date", new Date().toISOString().substring(0, 10)); // Tanggal hari ini
    url.searchParams.set("room_id", roomId); // Sesuai room_id

    try {
        const bookingsData = await $.get(url.toString());
        const currentBookings = $(`#current-bookings-${roomId}>tbody`);
        currentBookings.empty(); // Kosongkan data sebelumnya

        if (bookingsData.length === 0) {
            currentBookings.append(
                `<tr><td colspan="4">Tidak ada peminjaman hari ini...</td></tr>`
            );
        } else {
            bookingsData.forEach((booking) => {
                currentBookings.append(`
                    <tr>
                        <td>${formatTime(booking.start_time)}</td>
                        <td>${formatTime(booking.end_time)}</td>
                        <td>${booking.description}</td>
                        ${
                            isAdmin
                                ? `<td><button class="btn btn-danger btn-sm" onclick="deleteBooking(${booking.id})">Hapus</button></td>`
                                : ""
                        }
                    </tr>
                `);
            });
        }
    } catch (error) {
        console.error(`Gagal memuat booking untuk room ${roomId}:`, error);
    }
}

function initializeBookings() {
    $(".card").each(function () {
        const roomId = $(this).data("room-id");
        updateBookingsByRoom(roomId); // Panggil untuk setiap ruangan
    });
}

$(document).ready(function () {
    initializeBookings(); // Jalankan saat halaman selesai dimuat
});
</script>


@endsection

@section('content')

<div class="banner">  
</div>

<div class="banner-content">
    <h1>Selamat Datang di Booking Room</h1>
    <p>Temukan ruang terbaik untuk kebutuhan Anda</p>
</div>


<div class="card-container">
    @foreach($rooms as $room)
    <a href="{{ route('bookings.create', $room->id) }}" class="card">
            <img src="data:image/jpeg;base64,{{ $room->image }}" alt="{{ $room->name }}">
            <div class="card-content">
                <h5>{{ $room->name }}</h5>
                <p>{{ $room->description }}</p>
            </div>

            <!-- Collapse Section -->
            <div class="collapse show" id="currentBookings">
                <table id="current-bookings" class="table">
                    <thead>
                        <tr>
<<<<<<< HEAD
                            <th scope="col">No</th>
                            <th scope="col">Jam Peminjaman</th>
                            <th scope="col">Deskripsi</th>
                            {{-- @admin
                                <th scope="col">Aksi</th>
                            @endadmin --}}
=======
                            <th scope="col">Jam Mulai</th>
                            <th scope="col">Jam Selesai</th>
                            <th scope="col">Deskripsi</th>
                            @admin
                                <th scope="col">Aksi</th>
                            @endadmin
>>>>>>> 68c542265b2ea7c9304d2db3eff6e826da40a690
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data Dummy -->
                        @foreach ($room->bookings as $booking)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
<<<<<<< HEAD
                                <td>
                                    {{ $booking->date }} 
                                    (
                                        {{ substr($booking->start_time, 0, 5) }} - 
                                        {{ \Carbon\Carbon::parse($booking->end_time)->addMinute()->format('H:i') }}
                                    )
                                </td>
                                <td>{{ $booking->description }}</td> 
                                <td>
                                    {{-- Check if $booking->date and $booking->start_time are less than the current date and time --}}
                                    {{-- @if(!Carbon\Carbon::parse($booking->date . ' ' . $booking->start_time)->isPast())
                                        <a href="{{ route('bookings.destroy', ['id' => $booking->id]) }}">
                                            <button class="btn btn-danger">Hapus</button>
                                        </a>
                                    @endif --}}
=======
                                <td>{{ $booking->date }} ({{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }})</td>
                                <td>{{ $booking->description }}</td> 
                                <td>
                                    {{-- Check if $booking->date and $booking->start_time are less than the current date and time --}}
                                    @if(!Carbon\Carbon::parse($booking->date . ' ' . $booking->start_time)->isPast())
                                        <a href="{{ route('bookings.destroy', ['id' => $booking->id]) }}">
                                            <button class="btn btn-danger">Hapus</button>
                                        </a>
                                    @endif
>>>>>>> 68c542265b2ea7c9304d2db3eff6e826da40a690
                                </td>                
                                {{-- <td>
                                    @if ($booking->approved)
                                        <span class="badge bg-success">Approved</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    @if (!$booking->approved)
                                        <form action="{{ route('admin.bookings.approve', $booking->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm">Approve</button>
                                        </form>
                                    @else
                                        <button class="btn btn-secondary btn-sm" disabled>Already Approved</button>
                                    @endif
                                </td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- End Collapse Section -->
    </a>
    @endforeach
</div>



@endsection
