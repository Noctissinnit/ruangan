@extends('layouts.app')

@section('head')
<link rel="stylesheet" href="/css/home.css">
<script>
    $(document).ready(() => $.get("{{ route('bookings.reset-session') }}"));  
</script>

@endsection

@section('content')
<div class="container">
    <h1>Peminjaman Ruang</h1>
    <div class="card-container">
        @foreach($rooms as $room)
            <a href="{{ route('bookings.create', $room->id) }}" class="card">
                <img src="data:image/jpeg;base64,{{ $room->image }}" alt="{{ $room->name }}">
                <div class="card-content">
                    <h2>{{ $room->name }}</h2>
                    <div id="current-available-{{ $room->id }}">Status: 
                        <span id="current-available-status-{{ $room->id }}"></span>
                    </div>
                    <p>{{ $room->description }}</p>
                </div>
            </a>
        @endforeach

    </div>
</div>
@endsection

<script src="/js/bookings/create.js"></script>