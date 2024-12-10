@extends('layouts.app')

@section('head')
<link rel="stylesheet" href="/css/home.css">
<script>
    $(document).ready(() => {
        updateCurrentAvailable();
        $.get("{{ route('bookings.reset-session') }}");
    });
    const rooms = [
        @foreach($rooms as $room)
        { id: {{ $room->id }}, url: '{{ route('bookings.room-available', $room->id) }}' },
        @endforeach
    ];

    async function updateCurrentAvailable() {
        rooms.forEach(async room => {
            const res = await $.get(room.url);
            $(`#current-available-status-${room.id}`).html(res.available ? 'Tersedia' : 'Tidak Tersedia');
        });
    }
    setInterval(updateCurrentAvailable, 1000);
</script>

@endsection

@section('content')
<div class="card-container">
   
    @foreach($rooms as $room)
        <a href="{{ route('bookings.create', $room->id) }}" class="card">
            <img src="data:image/jpeg;base64,{{ $room->image }}" alt="{{ $room->name }}">
            <div class="card-content">
                <h2>{{ $room->name }}</h2>
                <div id="current-available">Status: <span id="current-available-status-{{ $room->id }}"></span></div>
                <p>{{ $room->description }}</p>
            </div>
        </a>
    @endforeach
</div>

@endsection
