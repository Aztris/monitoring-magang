@extends('layouts.app')

@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">{{ $title ?? 'Daftar Siswa yang Magang' }}</h4>
                    {{-- <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal" data-bs-target="#addActivityModal">
                        <i class="fa fa-plus"></i>
                        Tambah Aktivitas
                    </button> --}}
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="basic-datatables" class="display table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>NIS</th>
                                <th>Nama</th>
                                <th>Posisi</th>
                                <th>Grup Magang</th>
                                <th>Jumlah Aktivitas</th>
                                <th style="width: 10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $student)
                                @foreach ($student->internships as $internship)
                                    <tr>
                                        <td>{{ $loop->parent->iteration }}</td>
                                        <td>{{ $student->nis }}</td>
                                        <td>{{ $student->nama }}</td>
                                        <td>{{ $internship->posisi }}</td>
                                        <td>{{ $internship->internshipGroup->nama }}</td>
                                        <td>{{ $student->internships->first()->activities->count() }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('activities.show', $student) }}" class="btn btn-sm btn-info">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Create -->
    <div class="modal fade" id="addActivityModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Tambah Aktivitas Baru</h5>
                    <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('activities.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="student_id">Pilih Siswa *</label>
                            <select name="student_id" class="form-control" required>
                                <option value="">-- Pilih Siswa --</option>
                                @foreach ($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Kegiatan *</label>
                            <input type="date" class="form-control" name="date" required>
                        </div>
                        <div class="form-group">
                            <label>Judul Kegiatan *</label>
                            <input type="text" class="form-control" name="title" required>
                        </div>
                        <div class="form-group">
                            <label>Deskripsi Kegiatan</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Foto Kegiatan</label>
                            <input type="file" class="form-control" name="activity_photo">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Tambah Aktivitas</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('.delete-form'). on('submit', function(e) {
                e.preventDefault();
                const form = this;

                Swal.fire({
                    title: 'Hapus Aktivitas?',
                    text: "Data aktivitas akan dihapus dan tidak dapat dikembalikan!",
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
