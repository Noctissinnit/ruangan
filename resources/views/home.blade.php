@extends('layouts.app')

@section('head')
<link rel="stylesheet" href="/css/home.css">
<script>
    $(document).ready(() => $.get("{{ route('bookings.reset-session') }}"));  
</script>

@endsection

@section('content')
<div class="card-container">
   
    @foreach($rooms as $room)
        <a href="{{ route('bookings.create', $room->id) }}" class="card">
            <img src="data:image/jpeg;base64,{{ $room->image }}" alt="{{ $room->name }}">
            <div class="card-content">
                <h2>{{ $room->name }}</h2>
                <div id="current-available-{{ $room->id }}">
                    Status: 
                    <span id="current-available-status-{{ $room->id }}" 
                          class="{{ $room->status == 'Tersedia' ? 'text-success' : 'text-danger' }}">
                        {{ $room->status }}
                    </span>
                </div>
                <p>{{ $room->description }}</p>
            </div>
        </a>
    @endforeach
</div>

@endsection

<script src="/js/bookings/create.js"></script>