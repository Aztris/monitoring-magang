@extends('layouts.app')
@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">{{ $title ?? 'List Teachers' }}</h4>
                    <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal" data-bs-target="#addTeacherModal">
                        <i class="fa fa-plus"></i>
                        Add Teacher
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
                                <th>Email</th>
                                <th>NIP</th>
                                <th>Jenis Kelamin</th>
                                <th>No HP</th>
                                <th>Mata Pelajaran</th>
                                <th style="width: 10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($teachers as $teacher)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @if ($teacher->user->foto_profil)
                                            <img src="{{ Storage::url($teacher->user->foto_profil) }}"
                                                alt="Foto Profil" width="50" class="rounded-circle">
                                        @else
                                            <img src="{{ Storage::url('profile_photos/default_avatar.png') }}" alt="Foto Profil"
                                                width="50" class="rounded-circle">
                                        @endif
                                    </td>
                                    <td>{{ $teacher->user->nama }}</td>
                                    <td>{{ $teacher->user->email }}</td>
                                    <td>{{ $teacher->nip }}</td>
                                    <td>{{ $teacher->jenkel == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                    <td>{{ $teacher->no_hp }}</td>
                                    <td>{{ $teacher->mata_pelajaran }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#editTeacherModal{{ $teacher->id }}">
                                                <i class="fa fa-pen"></i>
                                            </button>
                                            <form action="{{ route('teachers.destroy', $teacher->id) }}" method="POST"
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

                                <div class="modal fade" id="editTeacherModal{{ $teacher->id }}" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title">Edit Teacher</h5>
                                                <button type="button" class="close text-white" data-bs-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('teachers.update', $teacher->id) }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Nama *</label>
                                                                <input type="text" class="form-control" name="nama"
                                                                    value="{{ $teacher->user->nama }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Email *</label>
                                                                <input type="email" class="form-control" name="email"
                                                                    value="{{ $teacher->user->email }}" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>NIP *</label>
                                                                <input type="text" class="form-control" name="nip"
                                                                    value="{{ $teacher->nip }}" required>
                                                                <small class="form-text text-muted">NIP akan digunakan
                                                                    sebagai password.</small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Jenis Kelamin *</label>
                                                                <select class="form-control" name="jenkel" required>
                                                                    <option value="L"
                                                                        {{ $teacher->jenkel == 'L' ? 'selected' : '' }}>
                                                                        Laki-laki</option>
                                                                    <option value="P"
                                                                        {{ $teacher->jenkel == 'P' ? 'selected' : '' }}>
                                                                        Perempuan</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Tanggal Lahir</label>
                                                                <input type="date" class="form-control"
                                                                    name="tanggal_lahir"
                                                                    value="{{ $teacher->tanggal_lahir ? $teacher->tanggal_lahir->format('Y-m-d') : '' }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>No HP</label>
                                                                <input type="text" class="form-control" name="no_hp"
                                                                    value="{{ $teacher->no_hp }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
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
                                                        <textarea class="form-control" name="alamat" rows="3">{{ $teacher->alamat }}</textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Mata Pelajaran</label>
                                                        <input type="text" class="form-control" name="mata_pelajaran"
                                                            value="{{ $teacher->mata_pelajaran }}">
                                                    </div>
                                                </div>
                                                <input type="hidden" name="user_id" value="{{ $teacher->user->id }}">
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

    <div class="modal fade" id="addTeacherModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Add New Teacher</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="teacherForm" action="{{ route('teachers.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama *</label>
                                    <input type="text" class="form-control" name="nama" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email *</label>
                                    <input type="email" class="form-control" name="email" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>NIP *</label>
                                    <input type="text" class="form-control" name="nip" required>
                                    <small class="form-text text-muted">NIP akan digunakan sebagai password.</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jenis Kelamin *</label>
                                    <select class="form-control" name="jenkel" required>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Lahir</label>
                                    <input type="date" class="form-control" name="tanggal_lahir">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>No HP</label>
                                    <input type="text" class="form-control" name="no_hp">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Upload Foto Profil</label>
                                    <input type="file" class="form-control" name="foto_profil" accept="image/*">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Alamat</label>
                            <textarea class="form-control" name="alamat" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Mata Pelajaran</label>
                            <input type="text" class="form-control" name="mata_pelajaran">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Teacher</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('.delete-form').on('submit', function(e) {
                e.preventDefault();
                const form = this;

                Swal.fire({
                    title: 'Hapus Guru?',
                    text: "Data guru akan dihapus dan data yang terhubung akan ikut dihapus !",
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
