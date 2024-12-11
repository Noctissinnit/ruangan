@extends('layouts.app')

@section('head')
<script>
    $(document).ready(() => {
        @if(Auth::user()->pin == null)
            $('#link-pin-modal').click(() => $('#pinModal').modal('show'));
        @endif
    });
</script>
@endsection

@section('content')
<div class="container mt-4">
    <div class="table-responsive mt-3">
        @if(Auth::user()->pin == null)
            <p>Kamu belum memiliki PIN. <a id="link-pin-modal" href="#">Klik Disini untuk Membuat PIN.</a></p>
        @endif
        <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th scope="col">No.</th>
                <th scope="col">Tanggal & Waktu</th>
                <th scope="col">Nama Kegiatan</th>
                <th scope="col">Peserta</th>
                {{-- <th scope="col">Status</th> --}}
                {{-- <th scope="col">Aksi</th> --}}
            </tr>
        </thead>
        <tbody>
            @if(count($bookings) < 1)
                <tr><td colspan="4">Tidak ada data peminjaman...</td></tr>
            @endif
            @foreach ($bookings as $booking)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $booking->date }} ({{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }})</td>
                    <td>{{ $booking->description }}</td> 
                    <td>
                        @foreach($booking->users as $user)
                            {{ $user->name }}@if(!$loop->last), @endif
                        @endforeach
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


@if(Auth::user()->pin === null)
    <div class="modal fade" id="pinModal" tabindex="-1" role="dialog" aria-labelledby="pinModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="form-pin" class="modal-content" method="POST" action="{{ route('user.store-pin') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">Tambah PIN</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="password" name="pin" class="form-control" placeholder="PIN" maxlength="6" required/>
                    </div>
                    <div class="form-group mt-2">
                        <input type="password" name="pin_confirm" class="form-control" placeholder="Konfirmasi PIN" maxlength="6" required/>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endif
@endsection