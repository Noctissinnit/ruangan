@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Card untuk form create -->
            <div class="card">
                <div class="card-header">
                    <h4>Tambah Ruangan</h4>
                </div>
                <div class="card-body">
                    <!-- Tampilkan pesan error jika ada -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Form untuk membuat room -->
                    <form action="{{ route('rooms.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Input Nama Ruangan -->
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Nama Ruangan</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                        
                        <!-- Input Gambar Ruangan -->
                        <div class="form-group mb-3">
                            <label for="image" class="form-label">Gambar</label>
                            <input type="file" name="image" id="image" class="form-control" accept="image/*" required>
                        </div>
                        
                        <!-- Input Kapasitas Ruangan -->
                        <div class="form-group mb-3">
                            <label for="description" class="form-label">Kapasitas</label>
                            <textarea name="description" id="description" class="form-control" required>{{ old('description') }}</textarea>
                        </div>

                        <!-- Pilihan Tipe Ruangan -->
                        <div class="form-group mb-3">
                            <label for="type" class="form-label">Tipe Ruangan</label>
                            <select name="type" id="type" class="form-control" required>
                                <option value="" disabled selected>Pilih Tipe Ruangan</option>
                                <option value="home" {{ old('type') == 'home' ? 'selected' : '' }}>Yayasan</option>
                                <option value="alternate" {{ old('type') == 'alternate' ? 'selected' : '' }}>Mikael</option>
                            </select>
                        </div>

                        <!-- Tombol Submit dan Cancel -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('rooms.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-success">Tambah Ruangan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
