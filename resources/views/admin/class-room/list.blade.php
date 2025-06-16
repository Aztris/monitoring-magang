@extends('layouts.app')

@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Daftar Ruang Kelas</h4>
                    <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal"
                        data-bs-target="#addClassRoomModal">
                        <i class="fa fa-plus"></i>
                        Tambah Ruang Kelas
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="basic-datatables" class="display table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Ruang Kelas</th>
                                <th>Tingkat Kelas</th>
                                <th>Departemen</th>
                                {{-- <th>Tahun Akademik</th> --}}
                                <th style="width: 10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($classRooms as $classRoom)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $classRoom->name }}</td>
                                    <td>{{ $classRoom->grade_level }}</td>
                                    <td>{{ $classRoom->department->nama }}</td>
                                    {{-- <td>{{ $classRoom->academicYear->name }}</td> --}}
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#editClassRoomModal{{ $classRoom->id }}">
                                                <i class="fa fa-pen"></i>
                                            </button>
                                            <form action="{{ route('class-rooms.destroy', $classRoom->id) }}" method="POST"
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

                                <div class="modal fade" id="editClassRoomModal{{ $classRoom->id }}" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title">Edit Ruang Kelas</h5>
                                                <button type="button" class="close text-white" data-bs-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('class-rooms.update', $classRoom->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label>Nama Ruang Kelas *</label>
                                                        <input type="text" class="form-control" name="name"
                                                            value="{{ $classRoom->name }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Tingkat Kelas *</label>
                                                        <input type="text" class="form-control" name="grade_level"
                                                            value="{{ $classRoom->grade_level }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Departemen *</label>
                                                        <select class="form-control" name="department_id" required>
                                                            @foreach ($departments as $department)
                                                                <option value="{{ $department->id }}"
                                                                    {{ $classRoom->department_id == $department->id ? 'selected' : '' }}>
                                                                    {{ $department->kode . ' - ' . $department->nama }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    {{-- <div class="form-group">
                                                        <label>Tahun Akademik *</label>
                                                        <select class="form-control" name="academic_year_id" required>
                                                            @foreach ($academicYears as $academicYear)
                                                                <option value="{{ $academicYear->id }}"
                                                                    {{ $classRoom->academic_year_id == $academicYear->id ? 'selected' : '' }}>
                                                                    {{ $academicYear->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div> --}}
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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

    <div class="modal fade" id="addClassRoomModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Tambah Ruang Kelas</h5>
                    <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('class-rooms.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Ruang Kelas *</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="form-group">
                            <label>Tingkat Kelas *</label>
                            <input type="text" class="form-control" name="grade_level" required>
                        </div>
                        <div class="form-group">
                            <label>Jurusan *</label>
                            <select class="form-control" name="department_id" required>
                                <option value="">Pilih Jurusan</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->kode . ' - ' . $department->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        {{-- <div class="form-group text-dark">
                            <label>Tahun Akademik *</label>
                            <select class="form-control" name="academic_year_id" required>
                                <option value="">Pilih Tahun Akademik</option>
                                @foreach ($academicYears as $academicYear)
                                    <option value="{{ $academicYear->id }}">{{ $academicYear->name }}</option>
                                @endforeach
                            </select>
                        </div> --}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Tambah Ruang Kelas</button>
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
                    title: 'Hapus Kelas?',
                    text: "Data kelas akan dihapus dan data yang terhubung akan ikut dihapus !",
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
