@extends('layouts.app')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h2 class="text-dark pb-2 fw-bold">Dashboard Perusahaan</h2>
                <h5 class="text-dark op-7 mb-2">Ringkasan aktivitas dan manajemen siswa magang.</h5>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon"><div class="icon-big text-center icon-primary bubble-shadow-small"><i class="fas fa-users-cog"></i></div></div>
                            <div class="col col-stats ms-3 ms-sm-0"><div class="numbers"><p class="card-category">Kelompok Aktif</p><h4 class="card-title">{{ $groupCount }}</h4></div></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon"><div class="icon-big text-center icon-info bubble-shadow-small"><i class="fas fa-user-graduate"></i></div></div>
                            <div class="col col-stats ms-3 ms-sm-0"><div class="numbers"><p class="card-category">Siswa Magang</p><h4 class="card-title">{{ $studentCount }}</h4></div></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon"><div class="icon-big text-center icon-warning bubble-shadow-small"><i class="fas fa-user-clock"></i></div></div>
                            <div class="col col-stats ms-3 ms-sm-0"><div class="numbers"><p class="card-category">Kehadiran Pending</p><h4 class="card-title">{{ $pendingAttendanceCount }}</h4></div></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon"><div class="icon-big text-center icon-danger bubble-shadow-small"><i class="fas fa-clipboard-list"></i></div></div>
                            <div class="col col-stats ms-3 ms-sm-0"><div class="numbers"><p class="card-category">Aktivitas Pending</p><h4 class="card-title">{{ $pendingActivityCount }}</h4></div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"><h4 class="card-title">Menu Utama</h4></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-body d-flex">
                                        <div class="d-flex align-items-center me-3"><i class="fas fa-users-cog fa-2x text-primary"></i></div>
                                        <div class="flex-grow-1">
                                            <h5 class="card-title mb-1">Daftar Siswa & Kelompok</h5>
                                            <p class="card-text text-muted small">Lihat semua siswa magang dan kelompoknya di perusahaan Anda.</p>
                                        </div>
                                        <div class="d-flex align-items-center ms-3">
                                            <a href="{{ route('internship-groups.index') }}" class="btn btn-outline-primary btn-sm">Lihat Detail</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-body d-flex">
                                        <div class="d-flex align-items-center me-3"><i class="fas fa-user-check fa-2x text-warning"></i></div>
                                        <div class="flex-grow-1">
                                            <h5 class="card-title mb-1">Verifikasi Kehadiran</h5>
                                            <p class="card-text text-muted small">Validasi data kehadiran harian dari para siswa magang.</p>
                                        </div>
                                        <div class="d-flex align-items-center ms-3">
                                            <a href="{{ route('attendances.index') }}" class="btn btn-outline-warning btn-sm">Lihat Detail</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-body d-flex">
                                        <div class="d-flex align-items-center me-3"><i class="fas fa-clipboard-check fa-2x text-danger"></i></div>
                                        <div class="flex-grow-1">
                                            <h5 class="card-title mb-1">Verifikasi Aktivitas</h5>
                                            <p class="card-text text-muted small">Validasi jurnal dan laporan kegiatan harian siswa.</p>
                                        </div>
                                        <div class="d-flex align-items-center ms-3">
                                            <a href="{{ route('activities.index') }}" class="btn btn-outline-danger btn-sm">Lihat Detail</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-body d-flex">
                                        <div class="d-flex align-items-center me-3"><i class="fas fa-award fa-2x text-success"></i></div>
                                        <div class="flex-grow-1">
                                            <h5 class="card-title mb-1">Penilaian Siswa</h5>
                                            <p class="card-text text-muted small">Berikan penilaian akhir kepada siswa yang telah menyelesaikan magang.</p>
                                        </div>
                                        <div class="d-flex align-items-center ms-3">
                                            <a href="{{ route('internship-assessments.index') }}" class="btn btn-outline-success btn-sm">Lihat Detail</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header"><h4 class="card-title">Kehadiran Menunggu Verifikasi</h4></div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead><tr><th>Tanggal</th><th>Siswa</th><th class="text-center">Status</th></tr></thead>
                                <tbody>
                                    @forelse($recentPendingAttendances as $attendance)
                                    <tr>
                                        <td>{{ $attendance->date->format('d M Y') }}</td>
                                        <td>
                                            <a href="{{ route('students.attendances.show', $attendance->internship->student->id) }}">

                                                {{ $attendance->internship->student->user->nama }}</td>
                                            </a>
                                        <td class="text-center"><span class="badge bg-warning text-dark">Menunggu</span></td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="3" class="text-center py-4">Tidak ada data kehadiran yang perlu diverifikasi.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header"><h4 class="card-title">Aktivitas Terbaru</h4></div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead><tr><th>Tanggal</th><th>Siswa</th><th>Judul</th></tr></thead>
                                <tbody>
                                    @forelse($recentPendingActivities as $activity)
                                    <tr>
                                        <td>{{ $activity->date->format('d M Y') }}</td>
                                        <td>
                                            <a href="{{ route('activities.show', $activity->internship->student->id) }}">

                                                {{ $activity->internship->student->user->nama }}</td>
                                            </a>
                                        <td>{{ Str::limit($activity->title, 25) }}</td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="3" class="text-center py-4">Tidak ada data aktivitas .</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
