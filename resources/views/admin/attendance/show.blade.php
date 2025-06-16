@extends('layouts.app')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="d-flex align-items-center mb-4">
            <h2 class="page-title mb-0">Riwayat Kehadiran Siswa</h2>
            <div class="ms-auto">
                <a href="{{ route('attendances.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
                <a href="{{ route('attendances.print', $internship) }}" class="btn btn-danger btn-sm" target="_blank"><i class="fas fa-print"></i> Cetak</a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Daftar Kehadiran</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th class="text-center">Verifikasi</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($attendances as $attendance)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <strong>{{ $attendance->date->translatedFormat('D, d M Y') }}</strong><br>
                                                <small class="text-muted">Masuk: {{ $attendance->check_in_time ?? '-' }} | Pulang: {{ $attendance->check_out_time ?? '-' }}</small>
                                            </td>
                                            <td>
                                                <span class="badge @if($attendance->status == 'hadir') bg-success @elseif($attendance->status == 'sakit') bg-warning text-dark @else bg-info @endif">{{ ucfirst($attendance->status) }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge @if($attendance->verification_status == 'verified') bg-success @elseif($attendance->verification_status == 'pending') bg-warning text-dark @else bg-danger @endif">{{ ucfirst($attendance->verification_status) }}</span>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#photoModal{{ $attendance->id }}" title="Lihat Foto Absensi">
                                                    <i class="fas fa-image"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">
                                                <i class="fas fa-calendar-times fa-2x mb-2"></i><br>
                                                Tidak ada data absensi ditemukan.
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
                        <h4 class="card-title">Informasi Siswa</h4>
                    </div>
                    <div class="card-body">
                        @php
                            $internship = $student->internships->first();
                        @endphp
                        <div class="text-center mb-4">
                            @php
                                $photoPath = $internship->student?->user?->foto_profil ?? 'profile_photos/default_avatar.png';
                            @endphp
                            <center>
                                <img src="{{ asset('storage/' . $photoPath) }}" alt="foto_profil" class="rounded-circle" width="100" height="100">
                            </center>
                                <h4 class="mt-2 mb-0">{{ $student->nama }}</h4>
                            <p class="text-muted">NIS: {{ $student->nis }}</p>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>Jenis Kelamin:</strong><br>{{ $student->jenkel == 'L' ? 'Laki-laki' : 'Perempuan' }}</li>
                            <li class="list-group-item"><strong>DUDIKA:</strong><br>{{ $internship?->internshipGroup?->company?->nama ?? 'N/A' }}</li>
                            <li class="list-group-item"><strong>Kelompok:</strong><br>{{ $internship?->internshipGroup?->nama ?? 'N/A' }}</li>
                            <li class="list-group-item"><strong>Pembimbing:</strong><br>{{ $internship?->internshipGroup?->teacher?->nama ?? 'N/A' }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@foreach ($attendances as $attendance)
<div class="modal fade" id="photoModal{{ $attendance->id }}" tabindex="-1" aria-labelledby="photoModalLabel{{ $attendance->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="photoModalLabel{{ $attendance->id }}">Foto Absensi: {{ $student->nama }} ({{ $attendance->date->translatedFormat('d M Y') }})</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 text-center">
                        <h6 class="mb-2">Foto Check-in ({{ $attendance->check_in_time }})</h6>
                        @if ($attendance->check_in_photo)
                            <img src="{{ asset('storage/' . $attendance->check_in_photo) }}" alt="Check-in Photo" class="img-fluid rounded border p-1">
                        @else
                            <div class="py-5 text-muted">Tidak ada foto.</div>
                        @endif
                    </div>
                    <div class="col-md-6 text-center">
                        <h6 class="mb-2">Foto Check-out ({{ $attendance->check_out_time }})</h6>
                         @if ($attendance->check_out_photo)
                            <img src="{{ asset('storage/' . $attendance->check_out_photo) }}" alt="Check-out Photo" class="img-fluid rounded border p-1">
                        @else
                            <div class="py-5 text-muted">Tidak ada foto.</div>
                        @endif
                    </div>
                </div>
                @if($attendance->notes)
                <div class="mt-4">
                    <strong>Catatan:</strong>
                    <p class="text-muted mb-0">{{ $attendance->notes }}</p>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection
