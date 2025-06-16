@extends('layouts.app')

@section('content')
<div class="container">
    <div class="page-inner">
        <h2 class="page-title">Profil Saya</h2>
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Informasi Profil</div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4 text-muted">Nama Lengkap</div>
                            <div class="col-md-8">{{ $user->nama }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 text-muted">Email</div>
                            <div class="col-md-8">{{ $user->email }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 text-muted">Role</div>
                            <div class="col-md-8"><span class="badge bg-primary">{{ ucfirst($user->role) }}</span></div>
                        </div>
                        <hr>

                        @if ($user->role === 'student' && $user->student)
                            <div class="row mb-3"><div class="col-md-4 text-muted">NIS</div><div class="col-md-8">{{ $user->student->nis }}</div></div>
                            <div class="row mb-3"><div class="col-md-4 text-muted">Kelas</div><div class="col-md-8">{{ $user->student->classRoom->name ?? '-' }}</div></div>
                            <div class="row mb-3"><div class="col-md-4 text-muted">Jurusan</div><div class="col-md-8">{{ $user->student->department->nama ?? '-' }}</div></div>
                            <div class="row mb-3"><div class="col-md-4 text-muted">Jenis Kelamin</div><div class="col-md-8">{{ $user->student->jenkel == 'L' ? 'Laki-laki' : 'Perempuan' }}</div></div>
                            <div class="row mb-3"><div class="col-md-4 text-muted">No. HP</div><div class="col-md-8">{{ $user->student->no_hp ?? '-' }}</div></div>
                            <div class="row mb-3"><div class="col-md-4 text-muted">Alamat</div><div class="col-md-8">{{ $user->student->alamat ?? '-' }}</div></div>

                        @elseif ($user->role === 'teacher' && $user->teacher)
                             <div class="row mb-3"><div class="col-md-4 text-muted">NIP</div><div class="col-md-8">{{ $user->teacher->nip }}</div></div>
                             <div class="row mb-3"><div class="col-md-4 text-muted">Jenis Kelamin</div><div class="col-md-8">{{ $user->teacher->jenkel == 'L' ? 'Laki-laki' : 'Perempuan' }}</div></div>
                             <div class="row mb-3"><div class="col-md-4 text-muted">Mata Pelajaran</div><div class="col-md-8">{{ $user->teacher->mata_pelajaran ?? '-' }}</div></div>
                             <div class="row mb-3"><div class="col-md-4 text-muted">No. HP</div><div class="col-md-8">{{ $user->teacher->no_hp ?? '-' }}</div></div>
                             <div class="row mb-3"><div class="col-md-4 text-muted">Alamat</div><div class="col-md-8">{{ $user->teacher->alamat ?? '-' }}</div></div>

                        @elseif ($user->role === 'company' && $user->company)
                             <div class="row mb-3"><div class="col-md-4 text-muted">Nama Perusahaan</div><div class="col-md-8">{{ $user->company->nama }}</div></div>
                             <div class="row mb-3"><div class="col-md-4 text-muted">Nama Pimpinan</div><div class="col-md-8">{{ $user->company->nama_pimpinan ?? '-' }}</div></div>
                             <div class="row mb-3"><div class="col-md-4 text-muted">No. HP Perusahaan</div><div class="col-md-8">{{ $user->company->no_hp ?? '-' }}</div></div>
                             <div class="row mb-3"><div class="col-md-4 text-muted">PIC</div><div class="col-md-8">{{ $user->company->pic_nama ?? '-' }} ({{ $user->company->pic_phone ?? '' }})</div></div>
                             <div class="row mb-3"><div class="col-md-4 text-muted">Alamat</div><div class="col-md-8">{{ $user->company->alamat ?? '-' }}</div></div>

                        @elseif ($user->role === 'admin' && $user->admin)
                             <div class="row mb-3"><div class="col-md-4 text-muted">Jenis Kelamin</div><div class="col-md-8">{{ $user->admin->jenkel == 'L' ? 'Laki-laki' : 'Perempuan' }}</div></div>
                             <div class="row mb-3"><div class="col-md-4 text-muted">No. HP</div><div class="col-md-8">{{ $user->admin->no_hp ?? '-' }}</div></div>
                             <div class="row mb-3"><div class="col-md-4 text-muted">Alamat</div><div class="col-md-8">{{ $user->admin->alamat ?? '-' }}</div></div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Ubah Password</div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('password.update') }}">
                            @csrf
                            @method('put')

                            <div class="mb-3">
                                <label for="current_password" class="form-label">Password Saat Ini</label>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
                                @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password Baru</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Simpan Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
