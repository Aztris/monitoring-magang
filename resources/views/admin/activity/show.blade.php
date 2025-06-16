@extends('layouts.app')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="row">

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Daftar Aktivitas Harian</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="15%">Tanggal</th>
                                        <th width="25%">Judul</th>
                                        <th width="35%">Deskripsi</th>
                                        <th width="20%" class="text-center">Foto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($activities as $activity)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $activity->date->translatedFormat('d M Y') }}</td>
                                            <td>{{ $activity->title }}</td>
                                            <td>
                                                <div class="activity-description">
                                                    {{ $activity->description }}
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @if($activity->activity_photo)
                                                    <a href="{{ asset('storage/' . $activity->activity_photo) }}" target="_blank">
                                                        <img src="{{ asset('storage/' . $activity->activity_photo) }}"
                                                             alt="Foto Kegiatan"
                                                             class="img-thumbnail activity-photo">
                                                    </a>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <i class="fas fa-search-minus fa-2x text-muted mb-2"></i><br>
                                                Tidak ada aktivitas yang ditemukan
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Informasi Siswa</h4>
                            <a href="{{ route('activities.print', $student->id) }}" class="btn btn-danger btn-sm" target="_blank">
                                <i class="fa fa-print"></i> Cetak
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @php
                            $internship = $student->internships->first();
                        @endphp

                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex align-items-start">
                                <i class="fas fa-user fa-lg me-3 mt-2 text-primary"></i>
                                <div>
                                    <small class="text-muted">Nama Siswa</small>
                                    <h5 class="mb-0">{{ $student->nama }}</h5>
                                </div>
                            </li>
                             <li class="list-group-item d-flex align-items-start">
                                <i class="fas fa-id-card fa-lg me-3 mt-2 text-primary"></i>
                                <div>
                                    <small class="text-muted">NIS</small>
                                    <h5 class="mb-0">{{ $student->nis }}</h5>
                                </div>
                            </li>
                             <li class="list-group-item d-flex align-items-start">
                                <i class="fas fa-building fa-lg me-3 mt-2 text-primary"></i>
                                <div>
                                    <small class="text-muted">DUDIKA Tempat Magang</small>
                                    <h5 class="mb-0">{{ $internship?->internshipGroup?->company?->nama ?? 'N/A' }}</h5>
                                </div>
                            </li>
                            <li class="list-group-item d-flex align-items-start">
                                <i class="fas fa-users-cog fa-lg me-3 mt-2 text-primary"></i>
                                <div>
                                    <small class="text-muted">Kelompok Magang</small>
                                    <h5 class="mb-0">{{ $internship?->internshipGroup?->nama ?? 'N/A' }}</h5>
                                </div>
                            </li>
                             <li class="list-group-item d-flex align-items-start">
                                <i class="fas fa-chalkboard-teacher fa-lg me-3 mt-2 text-primary"></i>
                                <div>
                                    <small class="text-muted">Guru Pembimbing</small>
                                    <h5 class="mb-0">{{ $internship?->internshipGroup?->teacher?->nama ?? 'N/A' }}</h5>
                                </div>
                            </li>
                        </ul>
                    </div>
                     <div class="card-footer text-center">
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('styles')
    <style>
        .img-thumbnail { max-width: 100px; height: auto; border: 1px solid #dee2e6; border-radius: 4px; padding: 2px; }
        .activity-photo { transition: transform 0.2s; cursor: zoom-in; }
        .activity-photo:hover { transform: scale(2.5); z-index: 1000; position: absolute; }
        .activity-description { max-height: 100px; overflow-y: auto; padding-right: 5px; font-size: 0.9em; }
        .card-header { background-color: #f8f9fa; border-bottom: 1px solid rgba(0,0,0,.125); }
        .table th { background-color: #f8f9fa; }
    </style>
@endpush
