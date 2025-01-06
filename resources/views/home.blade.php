@extends('layouts.app')

@section('head')
<link rel="stylesheet" href="/css/home.css">

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
                <h2>{{ $room->name }}</h2>
                <p>{{ $room->description }}</p>
            </div>
        </a>
    @endforeach
</div>

@endsection
