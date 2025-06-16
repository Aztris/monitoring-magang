@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">

            {{-- Row untuk Card Statistik --}}
            <div class="row">
                {{-- Card Admin --}}
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon"><div class="icon-big text-center icon-info bubble-shadow-small"><i class="fas fa-user-shield"></i></div></div>
                                <div class="col col-stats ms-3 ms-sm-0"><div class="numbers"><p class="card-category">Admin</p><h4 class="card-title">{{ $adminCount }}</h4></div></div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Card Siswa --}}
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon"><div class="icon-big text-center icon-primary bubble-shadow-small"><i class="fas fa-users"></i></div></div>
                                <div class="col col-stats ms-3 ms-sm-0"><div class="numbers"><p class="card-category">Siswa</p><h4 class="card-title">{{ $studentCount }}</h4></div></div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Card Guru --}}
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon"><div class="icon-big text-center icon-success bubble-shadow-small"><i class="fas fa-user-graduate"></i></div></div>
                                <div class="col col-stats ms-3 ms-sm-0"><div class="numbers"><p class="card-category">Guru</p><h4 class="card-title">{{ $teacherCount }}</h4></div></div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Card Dudika --}}
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon"><div class="icon-big text-center icon-warning bubble-shadow-small"><i class="far fa-building"></i></div></div>
                                <div class="col col-stats ms-3 ms-sm-0"><div class="numbers"><p class="card-category">Dudika</p><h4 class="card-title">{{ $companyCount }}</h4></div></div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Card Kelompok PKL --}}
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon"><div class="icon-big text-center icon-danger bubble-shadow-small"><i class="fas fa-users-cog"></i></div></div>
                                <div class="col col-stats ms-3 ms-sm-0"><div class="numbers"><p class="card-category">Kelompok PKL</p><h4 class="card-title">{{ $internshipGroupCount }}</h4></div></div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Card Jurusan --}}
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon"><div class="icon-big text-center icon-info bubble-shadow-small"><i class="fas fa-sitemap"></i></div></div>
                                <div class="col col-stats ms-3 ms-sm-0"><div class="numbers"><p class="card-category">Jurusan</p><h4 class="card-title">{{ $departmentCount }}</h4></div></div>
                            </div>
                        </div>
                    </div>
                </div>
                 {{-- Card Kelas --}}
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon"><div class="icon-big text-center icon-primary bubble-shadow-small"><i class="fas fa-school"></i></div></div>
                                <div class="col col-stats ms-3 ms-sm-0"><div class="numbers"><p class="card-category">Kelas</p><h4 class="card-title">{{ $classRoomCount }}</h4></div></div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Card Kriteria Penilaian --}}
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon"><div class="icon-big text-center icon-success bubble-shadow-small"><i class="far fa-check-circle"></i></div></div>
                                <div class="col col-stats ms-3 ms-sm-0"><div class="numbers"><p class="card-category">Kriteria Penilaian</p><h4 class="card-title">{{ $assessmentCriteriaCount }}</h4></div></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Row untuk Chart --}}
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-round">
                        <div class="card-header"><div class="card-head-row"><div class="card-title">Statistik Kehadiran</div></div></div>
                        <div class="card-body"><canvas id="attendanceChart" height="300"></canvas></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-round">
                        <div class="card-header"><div class="card-head-row"><div class="card-title">Distribusi Siswa per Jurusan</div></div></div>
                        <div class="card-body"><canvas id="departmentChart" height="300"></canvas></div>
                    </div>
                </div>
            </div>

            {{-- Row untuk Tabel Recent --}}
            <div class="row">
                {{-- Recent Activities --}}
                <div class="col-md-6">
                    <div class="card card-round">
                        <div class="card-header"><div class="card-head-row card-tools-still-right"><div class="card-title">Aktivitas Terbaru</div></div></div>
                        <div class="card-body p-0"><div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead><tr><th>Tanggal</th><th>Siswa</th><th>Judul</th><th>Status</th></tr></thead>
                                <tbody>
                                    @forelse ($recentActivities as $activity)
                                        <tr>
                                            <td>{{ $activity->date->format('d M Y') }}</td>
                                            <td>{{ $activity->internship->student->user->nama }}</td>
                                            <td>{{ Str::limit($activity->title, 25) }}</td>
                                            <td>
                                                @if ($activity->verification_status === 'verified')<span class="badge bg-success text-white">Terverifikasi</span>
                                                @elseif($activity->verification_status === 'rejected')<span class="badge bg-danger text-white">Ditolak</span>
                                                @else<span class="badge bg-warning text-dark">Menunggu</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="text-center py-4">Tidak ada aktivitas terbaru.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div></div>
                    </div>
                </div>
                 {{-- Recent Attendances --}}
                <div class="col-md-6">
                    <div class="card card-round">
                        <div class="card-header"><div class="card-head-row card-tools-still-right"><div class="card-title">Kehadiran Terbaru</div></div></div>
                        <div class="card-body p-0"><div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead><tr><th>Tanggal</th><th>Siswa</th><th>Status</th><th>Waktu Masuk</th></tr></thead>
                                <tbody>
                                    @forelse ($recentAttendances as $attendance)
                                        <tr>
                                            <td>{{ $attendance->date->format('d M Y') }}</td>
                                            <td>{{ $attendance->internship->student->user->nama }}</td>
                                            <td>
                                                @if ($attendance->status === 'hadir')<span class="badge bg-success text-white">Hadir</span>
                                                @elseif($attendance->status === 'alpa')<span class="badge bg-danger text-white">Alpa</span>
                                                @elseif($attendance->status === 'sakit')<span class="badge bg-warning text-dark">Sakit</span>
                                                @else<span class="badge bg-info text-white">Izin</span>
                                                @endif
                                            </td>
                                            <td>{{ $attendance->check_in_time ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="text-center py-4">Tidak ada data kehadiran terbaru.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div></div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Data untuk Chart Kehadiran
                const attendanceLabels = @json($attendanceLabels);
                const attendanceData = @json($attendanceData);

                if (document.getElementById('attendanceChart')) {
                    const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
                    new Chart(attendanceCtx, {
                        type: 'doughnut',
                        data: {
                            labels: attendanceLabels,
                            datasets: [{
                                label: 'Jumlah',
                                data: attendanceData,
                                backgroundColor: ['#28a745', '#ffc107', '#17a2b8', '#dc3545'],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { position: 'top' } }
                        }
                    });
                }

                // Data untuk Chart Distribusi Jurusan
                const departmentLabels = @json($departmentLabels);
                const departmentData = @json($departmentData);

                if (document.getElementById('departmentChart')) {
                    const departmentCtx = document.getElementById('departmentChart').getContext('2d');
                    new Chart(departmentCtx, {
                        type: 'bar',
                        data: {
                            labels: departmentLabels,
                            datasets: [{
                                label: 'Jumlah Siswa',
                                data: departmentData,
                                backgroundColor: 'rgba(23, 162, 184, 0.8)',
                                borderColor: 'rgba(23, 162, 184, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: { precision: 0 }
                                }
                            }
                        }
                    });
                }
            });
        </script>
    @endpush
@endsection
