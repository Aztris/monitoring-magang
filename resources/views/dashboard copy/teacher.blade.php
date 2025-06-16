@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <!-- Main content -->
        <main role="main" class="col-md-9 ms-sm-auto col-lg-10 px-4">

            <!-- Metrics Section -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Total Siswa</h5>
                            <p class="card-text">{{ $totalStudents ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Total Kegiatan</h5>
                            <p class="card-text">{{ $totalActivities ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Total Kehadiran</h5>
                            <p class="card-text">{{ $totalAttendances ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Total Penilaian</h5>
                            <p class="card-text">{{ $totalAssessments ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activities Section -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Aktivitas Terbaru</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach ($recentActivities as $activity)
                            <li class="list-group-item">
                                <strong>{{ $activity->internship->student->user->nama }}</strong> {{ $activity->description }} <small
                                    class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </main>
    </div>
@endsection
