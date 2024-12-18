@extends('layouts.app')

@section('head')
<link rel="stylesheet" href="/css/admin/dashboard.css">
<script>
    const userGetUrl = "{{ route('user.get') }}";
    const userStoreUrl = "{{ route('user.store') }}";
    const userUpdateUrl = "{{ route('user.update') }}";
</script>
<script src="/js/admin/dashboard.js"></script>
@endsection

@section('content')
<div class="container">
    <form id="form-user-import" action="{{ route('user.import') }}" method="post" enctype="multipart/form-data">
        @csrf
        <button class="btn btn-primary" id="btn-add-user" type="button" data-bs-toggle="modal" data-bs-target="#userModal">Tambah User</button>
        <input type="file" name="excel" accept=".xls,.xlsx" style="display: none">
        <button id="form-user-btn-import" class="btn btn-success" type="button">Import Users</button>
    </form>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>No.</th>
                <th>Name</th>
                <th>Email</th>
                <th>NIS</th>
                <th>Pin</th>
                <th>Password</th>
                <th>Department</th>
                <th>Jabatan</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->nis }}</td>
                <td>{{ $user->pin }}</td>
                <td>{{ $user->password }}</td>
                <td>{{ $user->department->name }}</td>
                <td>{{ $user->jabatan->name ?? 'Tidak ada' }}</td>
                <td>{{ ucfirst($user->role) }}</td>
                <td>
                    <button class="btn btn-warning btn-edit-user" id="{{ $user->id }}">Edit</button>
                    <a href="{{ route('user.destroy', $user->id) }}"><button class="btn btn-danger">Hapus</button></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="form-user" class="modal-content" method="POST" action="{{ route('user.store') }}">
            @csrf
            <input type="hidden" name="id">
<div class="modal-header">
    <h5 class="modal-title" id="userModalLabel">Tambah User</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="form-group mb-3">
        
        <small class="form-text text-muted">Nama</small>
        <input type="text" class="form-control" id="name" placeholder="Name" name="name" required />
    </div>
    <div class="form-group mb-3">
        
        <small class="form-text text-muted">Masukkan alamat email yang valid, contoh: user@example.com.</small>
        <input type="email" class="form-control" id="email" placeholder="Email" name="email" required />
    </div>
    <div class="form-group mb-3">
        
        <small class="form-text text-muted">Masukkan NIS (Nomor Induk Siswa) pengguna.</small>
        <input type="text" class="form-control" id="nis" placeholder="NIS" name="nis" required />
    </div>
    <div class="form-group mb-3">
        
        <small class="form-text text-muted">Masukkan PIN pengguna.</small>
        <input type="text" class="form-control" id="pin" placeholder="NIS" name="nis" required />
    </div>
    <div class="form-group mb-3">
       
        <small class="form-text text-muted">Masukkan kata sandi yang kuat, minimal 8 karakter.</small>
        <input type="password" class="form-control" id="password" placeholder="Password" name="password" required />
    </div>
    <div class="form-group mb-3">
        
        <small class="form-text text-muted">Pilih department tempat pengguna bekerja.</small>
        <select class="form-control" id="department" name="department_id" required>
            <option value="" disabled selected>Pilih Department</option>
            @foreach ($departments as $department)
                <option value="{{ $department->id }}">{{ $department->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group mb-3">
        
        <small class="form-text text-muted">Pilih jabatan pengguna di dalam organisasi.</small>
        <select class="form-control" id="jabatan" name="jabatan_id" required>
            <option value="" disabled selected>Pilih Jabatan</option>
            @foreach ($jabatans as $jabatan)
                <option value="{{ $jabatan->id }}">{{ $jabatan->name }}</option>
            @endforeach
        </select>
    </div>
</div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>
@endsection