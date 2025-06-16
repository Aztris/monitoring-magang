@extends('layouts.app')

@section('content')
<div class="container">
    <div class="page-inner">

        <div class="row">
            <div class="col-sm-6 col-md-6">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-primary bubble-shadow-small">
                                    <i class="fas fa-users-cog"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Kelompok Bimbingan</p>
                                    <h4 class="card-title">{{ $internshipGroupCount }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-success bubble-shadow-small">
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Jumlah Siswa Bimbingan</p>
                                    <h4 class="card-title">{{ $studentCount }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card card-round">
                    <div class="card-header">
                        <div class="card-head-row">
                            <div class="card-title">Statistik Kehadiran Siswa Bimbingan</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="attendanceChart" height="300"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-round">
                    <div class="card-header">
                        <div class="card-head-row">
                            <div class="card-title">Aktivitas Terbaru Siswa</div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Siswa</th>
                                        <th>Judul Aktivitas</th>
                                        <th>Deskripsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentActivities as $activity)
                                    <tr>
                                        <td>{{ $activity->date->format('d M Y') }}</td>
                                        <td>{{ $activity->internship->student->user->nama }}</td>
                                        <td>{{ Str::limit($activity->title, 30) }}</td>
                                        <td>
                                            {{$activity->description ?? '-'}}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">Tidak ada aktivitas terbaru dari siswa bimbingan.</td>
                                    </tr>
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
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
@endsection
