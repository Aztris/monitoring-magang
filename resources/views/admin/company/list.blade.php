@extends('layouts.app')
@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">{{ $title ?? 'List Companies' }}</h4>
                    {{-- Tombol Import --}}
                    <button class="btn btn-success btn-round ms-auto" data-bs-toggle="modal" data-bs-target="#importModal">
                        <i class="fa fa-file-import"></i>
                        Import DUDIKA
                    </button>
                    {{-- Tombol Tambah --}}
                    <button class="btn btn-primary btn-round ms-2" data-bs-toggle="modal" data-bs-target="#addCompanyModal">
                        <i class="fa fa-plus"></i>
                        Tambah DUDIKA
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
                                <th>No HP</th>
                                <th>Nama Pimpinan</th>
                                <th>Bidang Usaha</th>
                                <th style="width: 10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($companies as $company)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @if ($company->user->foto_profil)
                                            <img src="{{ Storage::url($company->user->foto_profil) }}" alt="Foto Profil"
                                                width="50" class="rounded-circle">
                                        @else
                                            <img src="{{ Storage::url('profile_photos/default_avatar.png') }}"
                                                alt="Foto Profil" width="50" class="rounded-circle">
                                        @endif
                                    </td>
                                    <td>{{ $company->nama }}</td>
                                    <td>{{ $company->email }}</td>
                                    <td>{{ $company->no_hp }}</td>
                                    <td>{{ $company->nama_pimpinan }}</td>
                                    <td>{{ $company->bidang_usaha }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#editCompanyModal{{ $company->id }}">
                                                <i class="fa fa-pen"></i>
                                            </button>
                                            <form action="{{ route('companies.destroy', $company->id) }}" method="POST"
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

                                <div class="modal fade" id="editCompanyModal{{ $company->id }}" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title">Edit DUDIKA</h5>
                                                <button type="button" class="close text-white" data-bs-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('companies.update', $company->id) }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Nama *</label>
                                                                <input type="text" class="form-control" name="nama"
                                                                    value="{{ $company->nama }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Email *</label>
                                                                <input type="email" class="form-control" name="email"
                                                                    value="{{ $company->email }}" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>No HP</label>
                                                                <input type="text" class="form-control" name="no_hp"
                                                                    value="{{ $company->no_hp }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Nama Pimpinan</label>
                                                                <input type="text" class="form-control"
                                                                    name="nama_pimpinan"
                                                                    value="{{ $company->nama_pimpinan }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Bidang Usaha</label>
                                                                <input type="text" class="form-control"
                                                                    name="bidang_usaha"
                                                                    value ="{{ $company->bidang_usaha }}">
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
                                                        <textarea class="form-control" name="alamat" rows="3">{{ $company->alamat }}</textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Deskripsi</label>
                                                        <textarea class="form-control" name="deskripsi" rows="3">{{ $company->deskripsi }}</textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Contact Person</label>
                                                        <input type="text" class="form-control" name="pic_nama"
                                                            value="{{ $company->pic_nama }}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Contact Phone</label>
                                                        <input type="text" class="form-control" name="pic_phone"
                                                            value="{{ $company->pic_phone }}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Contact Email</label>
                                                        <input type="email" class="form-control" name="pic_email"
                                                            value="{{ $company->pic_email }}">
                                                    </div>
                                                </div>
                                                <input type="hidden" name="user_id" value="{{ $company->user_id }}">
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

    {{-- Modal tambah --}}
    <div class="modal fade" id="addCompanyModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Tambah DUDIKA baru</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="companyForm" action="{{ route('companies.store') }}" method="POST"
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
                                    <label>No HP</label>
                                    <input type="text" class="form-control" name="no_hp">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama Pimpinan</label>
                                    <input type="text" class="form-control" name="nama_pimpinan">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Bidang Usaha</label>
                                    <input type="text" class="form-control" name="bidang_usaha">
                                </div>
                            </div>
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
                            <label>Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" rows=" 3"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Contact Person</label>
                            <input type="text" class="form-control" name="pic_nama">
                        </div>
                        <div class="form-group">
                            <label>Contact Phone</label>
                            <input type="text" class="form-control" name="pic_phone">
                        </div>
                        <div class="form-group">
                            <label>Contact Email</label>
                            <input type="email" class="form-control" name="pic_email">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">batal</button>
                        <button type="submit" class="btn btn-primary">simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- =================================================================== --}}
    {{-- ================== MODAL IMPORT PERUSAHAAN (BARU) ================= --}}
    {{-- =================================================================== --}}
    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Import Data Perusahaan (DUDIKA)</h5>
                    <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('companies.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Pilih file Excel (.xlsx atau .csv) <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" name="file" required accept=".xlsx, .csv">
                        </div>
                        <hr>
                        <p class="mt-2">
                            <a href="{{ route('companies.template') }}" class="btn btn-sm btn-info" download>
                                <i class="fa fa-download"></i> Download Template
                            </a>
                            <br>
                            <small class="form-text text-muted">Gunakan template ini. Kolom `nama` dan `email` wajib
                                diisi.</small>
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
                    title: 'Hapus Perusahaan?',
                    text: "Data perusahaan akan dihapus dan data yang terhubung akan ikut dihapus!",
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
