@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h2 class="text-dark pb-2 fw-bold">Selamat Datang, {{ $user->nama }}!</h2>
                    <h5 class="text-dark op-7 mb-2">Ini adalah pusat kendali untuk seluruh aktivitas magang Anda.</h5>
                </div>
            </div>

            @if ($internship)
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3 mb-md-0">
                                        <h5 class="card-title">Tempat Magang</h5>
                                        <p class="card-text fw-bold">{{ $internship->internshipGroup->company->nama }}</p>
                                    </div>
                                    <div class="col-md-4 mb-3 mb-md-0">
                                        <h5 class="card-title">Posisi / Departemen</h5>
                                        <p class="card-text fw-bold">{{ $internship->posisi ?? '-' }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <h5 class="card-title">Guru Pembimbing</h5>
                                        <p class="card-text fw-bold">{{ $internship->internshipGroup->teacher->nama }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Kehadiran Hari Ini
                                    ({{ \Carbon\Carbon::now()->translatedFormat('l, j F Y') }})</h4>
                            </div>
                            <div class="card-body text-center">
                                @if ($todaysAttendance)
                                    @if ($todaysAttendance->check_out_time)
                                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                        <h5 class="card-title">Kehadiran Hari Ini Lengkap</h5>
                                        <p class="text-muted">Check-in: {{ $todaysAttendance->check_in_time }} | Check-out:
                                            {{ $todaysAttendance->check_out_time }}</p>
                                    @else
                                        <i class="fas fa-arrow-right-to-bracket fa-3x text-info mb-3"></i>
                                        <h5 class="card-title">Anda Sudah Check-in</h5>
                                        <p class="text-muted">Tercatat pada pukul: {{ $todaysAttendance->check_in_time }}
                                        </p>
                                        <form action="{{ route('attendances.index') }}">
                                            @csrf
                                            <input type="hidden" name="action" value="check_out">
                                            <button type="submit" class="btn btn-danger btn-lg">Check-out Sekarang</button>
                                        </form>
                                    @endif
                                @else
                                    <i class="fas fa-arrow-left-to-bracket fa-3x text-primary mb-3"></i>
                                    <h5 class="card-title">Siap Memulai Hari?</h5>
                                    <p class="text-muted">Silakan lakukan check-in untuk mencatat kehadiran Anda.</p>
                                    <form action="{{ route('attendances.index') }}">
                                        @csrf
                                        <input type="hidden" name="action" value="check_in">
                                        <button type="submit" class="btn btn-primary btn-lg">Check-in Sekarang</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Jurnal Kegiatan Harian</h4>
                            </div>
                            <div class="card-body text-center d-flex flex-column justify-content-center">
                                <i class="fas fa-book-open fa-3x text-secondary mb-3"></i>
                                <h5 class="card-title">Jangan Lupa Mengisi Jurnal</h5>
                                <p class="text-muted">Catat semua kegiatan yang Anda lakukan hari ini agar dapat
                                    diverifikasi.</p>
                                <a href="{{ route('activities.index') }}" class="btn btn-secondary btn-lg">Isi Jurnal
                                    Sekarang</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title mb-1">Lihat Penilaian Magang</h5>
                                    <p class="card-text text-muted small mb-0">Cek nilai yang telah diberikan oleh guru dan
                                        pembimbing industri.</p>
                                </div>
                                <a href="{{ route('internship-assessments.index') }}"
                                    class="btn btn-outline-success btn-sm">Lihat Nilai</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Jurnal Terbaru Anda</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Judul Kegiatan</th>
                                                <th>Deskripsi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($recentActivities as $activity)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($activity->date)->translatedFormat('d M Y') }}
                                                    </td>
                                                    <td>{{ $activity->title }}</td>
                                                    <td>{{ $activity->description ?? '-' }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="text-center py-4">Anda belum mengisi jurnal
                                                        kegiatan.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="card">
                            <div class="card-body">
                                <i class="fas fa-info-circle fa-3x text-info mb-3"></i>
                                <h4 class="card-title">Anda Belum Ditempatkan</h4>
                                <p class="text-muted">Anda belum terdaftar dalam kelompok magang manapun pada tahun akademik
                                    ini. <br>Silakan hubungi administrator atau guru pembimbing Anda.</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
