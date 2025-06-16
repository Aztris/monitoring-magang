@extends('layouts.app')

@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">{{ $title ?? 'Daftar Siswa Magang' }}</h4>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="basic-datatables" class="display table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th>Posisi</th>
                                <th>Grup Magang</th>
                                <th>Pembimbing</th>
                                <th style="width: 10%">Lihat Kehadiran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($students as $student)
                                @php
                                    $internship = $student->internships->first();
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $student->nis }}</td>
                                    <td>{{ $student->nama }}</td>
                                    <td>{{ $internship?->posisi ?? 'N/A' }}</td>
                                    <td>{{ $internship?->internshipGroup?->nama ?? 'N/A' }}</td>
                                    <td>{{ $internship?->internshipGroup?->teacher?->nama ?? 'N/A' }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('students.attendances.show', $student) }}"
                                           class="btn btn-info btn-sm" title="Lihat Riwayat Kehadiran">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        Tidak ada siswa yang magang pada tahun akademik ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
