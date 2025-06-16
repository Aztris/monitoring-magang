@extends('layouts.app')
@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">{{ $title ?? 'list data' }}</h4>
                    <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal"
                        data-bs-target="#addAcademicYearModal">
                        <i class="fa fa-plus"></i>
                        Add Row
                    </button>
                </div>
            </div>
            <div class="card-body">
                <x-add-academic-year-modal />

                <div class="table-responsive">
                <table id="basic-datatables" class="display table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tahun Akademik</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Berakhir</th>
                                <th>Status</th>
                                <th>Deskripsi</th>
                                <th style="width: 10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($academic_years as $year)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $year->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($year->start_date)->format('d M Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($year->end_date)->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge {{ $year->is_active ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $year->is_active ? 'Aktif' : 'Non-aktif' }}
                                        </span>
                                    </td>
                                    <td>{{ $year->deskripsi }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#editAcademicYearModal{{ $year->id }}">
                                                <i class="fa fa-pen"></i>
                                            </button>
                                            @if (!$year->is_active)
                                                <form action="{{ route('academic-years.destroy', $year->id) }}"
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
                                </tr>

                                <div class="modal fade" id="editAcademicYearModal{{ $year->id }}" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title">Edit Tahun Akademik</h5>
                                                <button type="button" class="close text-white" data-bs-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('academic-years.update', $year->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label>Tahun Akademik</label>
                                                        <input type="text" class="form-control" name="name"
                                                            value="{{ $year->name }}" required>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Tanggal Mulai</label>
                                                                <input type="date" class="form-control" name="start_date"
                                                                    value="{{ $year->start_date->format('Y-m-d') }}"
                                                                    required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Tanggal Berakhir</label>
                                                                <input type="date" class="form-control" name="end_date"
                                                                    value="{{ $year->end_date->format('Y-m-d') }}"
                                                                    required>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Deskripsi</label>
                                                        <textarea class="form-control" name="deskripsi" rows="3">{{ $year->deskripsi }}</textarea>
                                                    </div>

                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="is_active"
                                                            id="is_active_{{ $year->id }}" value="1"
                                                            {{ $year->is_active ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="is_active_{{ $year->id }}">
                                                            Jadikan Tahun Akademik Aktif
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Tutup</button>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('.delete-form').on('submit', function(e) {
                e.preventDefault();
                const form = this;

                Swal.fire({
                    title: 'Hapus Tahun Akademik?',
                    text: "Data tahun akan dihapus dan data yang terhubung akan ikut dihapus!",
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
