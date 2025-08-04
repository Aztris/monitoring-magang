@extends('layouts.app')
@section('content')
    @php
        $selectedYear = request()->attributes->get('selected_academic_year');
    @endphp

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">{{ $title ?? 'List Internship Groups' }}</h4>
                    <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal"
                        data-bs-target="#addInternshipGroupModal">
                        <i class="fa fa-plus"></i>
                        Kelompok Magang
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="basic-datatables" class="display table table-striped table-hover overflow-auto">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Perusahaan</th>
                                <th>Pengajar</th>
                                <th>Tahun Akademik</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th>Status</th>
                                <th style="width: 10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($internshipGroups as $group)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $group->nama }}</td>
                                    <td>{{ $group->company->nama ?? '-' }}</td>
                                    <td>{{ $group->teacher->nama ?? '-' }}</td>
                                    <td>{{ $group->academicYear->name ?? '-' }}</td>
                                    <td>{{ $group->start_date->format('d M Y') }}</td>
                                    <td>{{ $group->end_date->format('d M Y') }}</td>

                                    <td>{{ ucfirst($group->enum) }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('internship-groups.show', $group->id) }}"
                                                class="btn btn-sm btn-info" title="Show Details">
                                                <i class="fa fa-eye"></i>
                                            </a>

                                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#editInternshipGroupModal{{ $group->id }}">
                                                <i class="fa fa-pen"></i>
                                            </button>

                                            <form action="{{ route('internship-groups.destroy', $group->id) }}"
                                                method="POST" class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <div class="modal fade" id="editInternshipGroupModal{{ $group->id }}" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title">Edit Kelompok Magang</h5>
                                                <button type="button" class="close text-white" data-bs-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('internship-groups.update', $group->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label>Nama *</label>
                                                        <input type="text" class="form-control" name="nama"
                                                            value="{{ $group->nama }}" required>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col">
                                                            <div class="form-group">
                                                                <label>Perusahaan *</label>
                                                                <select class="form-control" name="company_id" required>
                                                                    @foreach ($companies as $company)
                                                                        <option value="{{ $company->id }}"
                                                                            {{ $group->company_id == $company->id ? 'selected' : '' }}>
                                                                            {{ $company->nama }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="form-group">
                                                                <label>Pembimbing *</label>
                                                                <select class="form-control" name="teacher_id" required>
                                                                    @foreach ($teachers as $teacher)
                                                                        <option value="{{ $teacher->id }}"
                                                                            {{ $group->teacher_id == $teacher->id ? 'selected' : '' }}>
                                                                            {{ $teacher->nama }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col">
                                                            <div class="form-group">
                                                                <label>Tahun Akademik *</label>
                                                                <select class="form-control" name="academic_year_id"
                                                                    required>
                                                                    @foreach ($academicYears as $academicYear)
                                                                        <option value="{{ $academicYear->id }}"
                                                                            {{ $group->academic_year_id == $academicYear->id ? 'selected' : '' }}>
                                                                            {{ $academicYear->name }}
                                                                            @if ($academicYear->is_active)
                                                                                (Aktif)
                                                                            @endif
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="form-group">
                                                                <label>Status *</label>
                                                                <select class="form-control" name="enum" required>
                                                                    <option value="active"
                                                                        {{ $group->enum == 'active' ? 'selected' : '' }}>
                                                                        Aktif
                                                                    </option>
                                                                    <option value="inactive"
                                                                        {{ $group->enum == 'inactive' ? 'selected' : '' }}>
                                                                        Tidak Aktif
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col">
                                                            <div class="form-group">
                                                                <label>Tanggal Mulai *</label>
                                                                <input type="date" class="form-control" name="start_date"
                                                                    value="{{ $group->start_date->format('Y-m-d') }}"
                                                                    required>
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="form-group">
                                                                <label>Tanggal Selesai *</label>
                                                                <input type="date" class="form-control" name="end_date"
                                                                    value="{{ $group->end_date->format('Y-m-d') }}"
                                                                    required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Deskripsi</label>
                                                        <textarea class="form-control" name="deskripsi">{{ $group->deskripsi }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Tutup</button>
                                                    <button type="submit" class="btn btn-primary">Simpan</button>
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

    <div class="modal fade" id="addInternshipGroupModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Tambah kelompok magang</h5>
                    <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('internship-groups.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama *</label>
                            <input type="text" class="form-control" name="nama" required>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Perusahaan *</label>
                                    <select class="form-control" name="company_id" required>
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}">{{ $company->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Pengajar *</label>
                                    <select class="form-control" name="teacher_id" required>
                                        @foreach ($teachers as $teacher)
                                            <option value="{{ $teacher->id }}">{{ $teacher->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Tahun Akademik *</label>
                                    <select class="form-control" name="academic_year_id" required>
                                        @foreach ($academicYears as $academicYear)
                                            <option value="{{ $academicYear->id }}"
                                                @if (isset($group) && $group->academic_year_id == $academicYear->id) selected
                                                @elseif(!isset($group) && $selectedYear && $selectedYear->id == $academicYear->id)
                                                    selected @endif>
                                                {{ $academicYear->name }}
                                                @if ($academicYear->is_active)
                                                    (Aktif)
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Status *</label>
                                    <select class="form-control" name="enum" required>
                                        <option value="active">Aktif</option>
                                        <option value="inactive">Tidak Aktif</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Tanggal Mulai *</label>
                                    <input type="date" class="form-control" name="start_date" required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Tanggal Selesai *</label>
                                    <input type="date" class="form-control" name="end_date" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Deskripsi</label>
                            <textarea class="form-control" name="deskripsi"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
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
                    title: 'Hapus Grup Magang?',
                    text: "Data grup magang akan dihapus dan data yang terhubung akan ikut dihapus !",
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
