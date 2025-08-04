@extends('layouts.app')
@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">{{ $title ?? 'List Students' }}</h4>
                    {{-- Tombol Import --}}
                    <button class="btn btn-success btn-round ms-auto" data-bs-toggle="modal" data-bs-target="#importModal">
                        <i class="fa fa-file-import"></i>
                        Import Siswa
                    </button>
                    {{-- Tombol Tambah --}}
                    <button class="btn btn-primary btn-round ms-2" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                        <i class="fa fa-plus"></i>
                        Tambah Siswa
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="basic-datatables" class="display table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Foto</th>
                                <th>Nama</th>
                                <th>NIS</th>
                                <th>Email</th>
                                <th>Kelas</th>
                                <th>Jurusan</th>
                                <th>Jenis Kelamin</th>
                                <th>No HP</th>
                                <th style="width: 10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $student)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @if ($student->user->foto_profil)
                                            <img src="{{ Storage::url($student->user->foto_profil) }}" alt="Foto Profil"
                                                width="50" class="rounded-circle">
                                        @else
                                            <img src="{{ Storage::url('profile_photos/default_avatar.png') }}"
                                                alt="Foto Profil" width="50" class="rounded-circle">
                                        @endif
                                    </td>
                                    <td>{{ $student->nama }}</td>
                                    <td>{{ $student->nis }}</td>
                                    <td>{{ $student->user->email }}</td>
                                    <td>{{ $student->classRoom->name ?? '-' }}</td>
                                    <td>{{ $student->department->nama ?? '-' }}</td>
                                    <td>{{ $student->jenkel == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                    <td>{{ $student->no_hp }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#editStudentModal{{ $student->id }}">
                                                <i class="fa fa-pen"></i>
                                            </button>
                                            <form action="{{ route('students.destroy', $student->id) }}" method="POST"
                                                class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <div class="modal fade" id="editStudentModal{{ $student->id }}" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title">Edit Siswa</h5>
                                                <button type="button" class="close text-white" data-bs-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('students.update', $student->id) }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <input type="hidden" name="user_id" value="{{ $student->user->id }}">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Nama *</label>
                                                                <input type="text" class="form-control" name="nama"
                                                                    value="{{ $student->nama }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>NIS *</label>
                                                                <input type="text" class="form-control" name="nis"
                                                                    value="{{ $student->nis }}" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Email *</label>
                                                                <input type="email" class="form-control" name="email"
                                                                    value="{{ $student->user->email }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Kelas *</label>
                                                                <select class="form-control" name="class_room_id" required>
                                                                    @foreach ($classRooms as $class)
                                                                        <option value="{{ $class->id }}"
                                                                            {{ $student->class_room_id == $class->id ? 'selected' : '' }}>
                                                                            {{ $class->name }}
                                                                            ({{ $class->grade_level }})
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Jurusan *</label>
                                                                <select class="form-control" name="department_id" required>
                                                                    @foreach ($departments as $department)
                                                                        <option value="{{ $department->id }}"
                                                                            {{ $student->department_id == $department->id ? 'selected' : '' }}>
                                                                            {{ $department->nama }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Jenis Kelamin *</label>
                                                                <select class="form-control" name="jenkel" required>
                                                                    <option value="L"
                                                                        {{ $student->jenkel == 'L' ? 'selected' : '' }}>
                                                                        Laki-laki</option>
                                                                    <option value="P"
                                                                        {{ $student->jenkel == 'P' ? 'selected' : '' }}>
                                                                        Perempuan</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>No HP</label>
                                                                <input type="text" class="form-control" name="no_hp"
                                                                    value="{{ $student->no_hp }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Tanggal Lahir</label>
                                                                <input type="date" class="form-control"
                                                                    name="tanggal_lahir"
                                                                    value="{{ $student->tanggal_lahir ? $student->tanggal_lahir->format('Y-m-d') : '' }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Tempat Lahir</label>
                                                                <input type="text" class="form-control"
                                                                    name="tempat_lahir"
                                                                    value="{{ $student->tempat_lahir }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Upload Foto Profil</label>
                                                                <input type="file" class="form-control"
                                                                    name="foto_profil" accept="image/*">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Alamat</label>
                                                        <textarea class="form-control" name="alamat" rows="3">{{ $student->alamat }}</textarea>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Nama Ayah</label>
                                                                <input type="text" class="form-control"
                                                                    name="nama_ayah" value="{{ $student->nama_ayah }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Nama Ibu</label>
                                                                <input type="text" class="form-control"
                                                                    name="nama_ibu" value="{{ $student->nama_ibu }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>No HP Orang Tua</label>
                                                                <input type="text" class="form-control"
                                                                    name="no_hp_ortu" value="{{ $student->no_hp_ortu }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Tutup</button>
                                                    <button type="submit" class="btn btn-primary">Simpan
                                                        Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- TAMBAH SISWA --}}
    <div class="modal fade" id="addStudentModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Tambah Siswa Baru</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="studentForm" action="{{ route('students.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama *</label>
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                        name="nama" value="{{ old('nama') }}" required>
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>NIS *</label>
                                    <input type="text" class="form-control @error('nis') is-invalid @enderror"
                                        name="nis" value="{{ old('nis') }}" required>
                                    @error('nis')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Kelas *</label>
                                    <select class="form-control @error('class_room_id') is-invalid @enderror"
                                        name="class_room_id" required>
                                        @foreach ($classRooms as $class)
                                            <option value="{{ $class->id }}"
                                                {{ old('class_room_id') == $class->id ? 'selected' : '' }}>
                                                {{ $class->name }} ({{ $class->grade_level }})</option>
                                        @endforeach
                                    </select>
                                    @error('class_room_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jurusan *</label>
                                    <select class="form-control @error('department_id') is-invalid @enderror"
                                        name="department_id" required>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}"
                                                {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                                {{ $department->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error('department_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jenis Kelamin *</label>
                                    <select class="form-control @error('jenkel') is-invalid @enderror" name="jenkel"
                                        required>
                                        <option value="L" {{ old('jenkel') == 'L' ? 'selected' : '' }}>Laki-laki
                                        </option>
                                        <option value="P" {{ old('jenkel') == 'P' ? 'selected' : '' }}>Perempuan
                                        </option>
                                    </select>
                                    @error('jenkel')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>No HP</label>
                                    <input type="text" class="form-control @error('no_hp') is-invalid @enderror"
                                        name="no_hp" value="{{ old('no_hp') }}">
                                    @error('no_hp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Lahir</label>
                                    <input type="date"
                                        class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                        name="tanggal_lahir" value="{{ old('tanggal_lahir') }}">
                                    @error('tanggal_lahir')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tempat Lahir</label>
                                    <input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror"
                                        name="tempat_lahir" value="{{ old('tempat_lahir') }}">
                                    @error('tempat_lahir')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Upload Foto Profil</label>
                                    <input type="file" class="form-control @error('foto_profil') is-invalid @enderror"
                                        name="foto_profil" accept="image/*">
                                    @error('foto_profil')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Alamat</label>
                            <textarea class="form-control" name="alamat" rows="3">{{ old('alamat') }}</textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama Ayah</label>
                                    <input type="text" class="form-control" name="nama_ayah"
                                        value="{{ old('nama_ayah') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama Ibu</label>
                                    <input type="text" class="form-control" name="nama_ibu"
                                        value="{{ old('nama_ibu') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>No HP Orang Tua</label>
                                    <input type="text" class="form-control" name="no_hp_ortu"
                                        value="{{ old('no_hp_ortu') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- =================================================================== --}}
    {{-- ==================== MODAL IMPORT SISWA (BARU) ==================== --}}
    {{-- =================================================================== --}}
    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Import Data Siswa</h5>
                    <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Pilih file Excel (.xlsx atau .csv) <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" name="file" required accept=".xlsx, .csv">
                        </div>
                        <hr>
                        <p class="mt-2">
                            <a href="{{ route('students.template') }}" class="btn btn-sm btn-info" download>
                                <i class="fa fa-download"></i> Download Template
                            </a>
                            <br>
                            <small class="form-text text-muted">Gunakan template ini. Pastikan kolom `nama_kelas` dan
                                `kode_jurusan` sudah ada di sistem.</small>
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-upload"></i> Import Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- =================================================================== --}}
    {{-- =================================================================== --}}

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('.delete-form').on('submit', function(e) {
                e.preventDefault();
                const form = this;

                Swal.fire({
                    title: 'Hapus Siswa?',
                    text: "Data siswa akan dihapus dan data yang terhubung akan ikut dihapus!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
