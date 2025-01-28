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
                            <th scope="col">Jam Mulai</th>
                            <th scope="col">Jam Selesai</th>
                            <th scope="col">Deskripsi</th>
                            @admin
                                <th scope="col">Aksi</th>
                            @endadmin
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data Dummy -->
                        <tr>
                            
                            @admin
                                <td>
                                    <button class="btn btn-danger">Hapus</button>
                                </td>
                            @endadmin
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- End Collapse Section -->
    </a>
    @endforeach
</div>



@endsection
