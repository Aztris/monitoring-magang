@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="page-inner">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <h4 class="card-title">Daftar Siswa Magang</h4>
                                @if (Auth::user()->role == 'admin')
                                    <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal"
                                        data-bs-target="#addStudentModal">
                                        <i class="fa fa-plus"></i>
                                        Tambah Siswa
                                    </button>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            @if ($internshipGroup->internships->isEmpty())
                                <div class="text-center py-4">
                                    <i class="fas fa-exclamation-circle fa-3x text-muted mb-2"></i>
                                    <p>Belum ada siswa yang ditambahkan ke kelompok ini.</p>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>NIS</th>
                                                <th>Nama Siswa</th>
                                                <th>Jurusan</th>
                                                <th>Posisi</th>
                                                @if (Auth::user()->role == 'admin' || Auth::user()->role == 'company')
                                                <th>Aksi</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($internshipGroup->internships as $internship)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $internship->student->nis }}</td>
                                                    <td>{{ $internship->student->nama }}</td>
                                                    <td>{{ $internship->student->department->nama }}</td>
                                                    <td>{{ $internship->posisi ?? 'N/A' }}</td>
                                                    @if (Auth::user()->role == 'admin' || Auth::user()->role == 'company')
                                                    <td>
                                                        <div class="btn-group">
                                                                <button class="btn btn-sm btn-primary"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#updatePositionModal{{ $internship->id }}">
                                                                    <i class="fa fa-pen"></i>
                                                                </button>
                                                                @if (Auth::user()->role == 'admin')
                                                                <form
                                                                action="{{ route('internships.destroy', $internship->id) }}"
                                                                method="POST" class="delete-form">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </form>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="card-title">Detail Kelompok</h4>
                                <a href="{{ route('internship-group.print', $internshipGroup->id) }}"
                                    class="btn btn-danger btn-sm btn-round" target="_blank">
                                    <i class="fa fa-print"></i> Cetak
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex align-items-start">
                                    <i class="fas fa-users-cog fa-lg me-3 mt-2 text-primary"></i>
                                    <div>
                                        <small class="text-muted">Nama Kelompok</small>
                                        <h5 class="mb-0">{{ $internshipGroup->nama }}</h5>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex align-items-start">
                                    <i class="fas fa-building fa-lg me-3 mt-2 text-primary"></i>
                                    <div>
                                        <small class="text-muted">Perusahaan (DUDIKA)</small>
                                        <h5 class="mb-0">{{ $internshipGroup->company->nama }}</h5>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex align-items-start">
                                    <i class="fas fa-chalkboard-teacher fa-lg me-3 mt-2 text-primary"></i>
                                    <div>
                                        <small class="text-muted">Guru Pembimbing</small>
                                        <h5 class="mb-0">{{ $internshipGroup->teacher->nama }}</h5>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex align-items-start">
                                    <i class="fas fa-calendar-alt fa-lg me-3 mt-2 text-primary"></i>
                                    <div>
                                        <small class="text-muted">Tahun Akademik</small>
                                        <h5 class="mb-0">{{ $internshipGroup->academicYear->name }}</h5>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex align-items-start">
                                    <i class="fas fa-calendar-check fa-lg me-3 mt-2 text-primary"></i>
                                    <div>
                                        <small class="text-muted">Periode Magang</small>
                                        <h5 class="mb-0">{{ $internshipGroup->start_date->format('d M Y') }} -
                                            {{ $internshipGroup->end_date->format('d M Y') }}</h5>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex align-items-start">
                                    <i class="fas fa-info-circle fa-lg me-3 mt-2 text-primary"></i>
                                    <div>
                                        <small class="text-muted">Status</small>
                                        <div class="mb-0">
                                            @if ($internshipGroup->enum == 'active')
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary">Tidak Aktif</span>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <small class="text-muted">Deskripsi</small>
                                    <p class="mb-0">{{ $internshipGroup->deskripsi ?? 'Tidak ada deskripsi.' }}</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addStudentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Students to Internship Group</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('internships.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>Select students to add to this internship group:</p>
                        @if ($allStudents->isEmpty())
                            <p>No students available to add.</p>
                        @else
                            <table id="basic-datatables" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>NIS</th>
                                        <th>Name</th>
                                        <th>Gender</th>
                                        <th>Kelas</th>
                                        <th>Jurusan</th>
                                        <th>SELECT</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($allStudents as $student)
                                        <tr>
                                            <td>{{ $student->nis }}</td>
                                            <td>{{ $student->nama }}</td>
                                            <td>{{ $student->jenkel }}</td>
                                            <td>{{ $student->classRoom->name }}</td>
                                            <td>{{ $student->department->nama }}</td>
                                            <td>
                                                <input type="checkbox" name="student_ids[]" value="{{ $student->id }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                        <input type="hidden" name="internship_group_id" value="{{ $internshipGroup->id }}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Students</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach ($internshipGroup->internships as $internship)
        <div class="modal fade" id="updatePositionModal{{ $internship->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Position for
                            {{ $internship->student->nama }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="updatePositionForm{{ $internship->id }}"
                        action="{{ route('internships.update', $internship->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <input type="hidden" name="internship_id" value="{{ $internship->id }}">
                            <div class="mb-3">
                                <label for="position" class="form-label">Position</label>
                                <input type="text" class="form-control" id="position" name="position"
                                    value="{{ $internship->posisi ?? '' }}" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update
                                Position</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach


    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.delete-form').forEach(form => {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Hapus Siswa dari Kelompok?',
                            text: "Tindakan ini hanya akan menghapus data penempatan siswa, bukan data siswanya.",
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
            });
        </script>
    @endpush
@endsection
