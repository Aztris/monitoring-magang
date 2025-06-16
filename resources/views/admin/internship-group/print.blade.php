@extends('layouts.print')

@section('title', 'Laporan Kelompok Magang - ' . $internshipGroup->nama)

@section('header')
    <h1>LAPORAN KELOMPOK MAGANG</h1>
    <div class="subtitle">Sistem Monitoring Magang</div>
@endsection

@section('info-block')
    <h2 style="margin-top: 0; margin-bottom: 15px; font-size: 18px; color: #2c3e50;">INFORMASI KELOMPOK</h2>
    <div class="info-grid">
        <div class="info-item"><strong>Nama Kelompok:</strong> {{ $internshipGroup->nama }}</div>
        <div class="info-item"><strong>Tahun Akademik:</strong> {{ $internshipGroup->academicYear->name }}</div>
        <div class="info-item"><strong>DUDIKA:</strong> {{ $internshipGroup->company->nama }}</div>
        <div class="info-item"><strong>Guru Pembimbing:</strong> {{ $internshipGroup->teacher->nama }}</div>
        <div class="info-item"><strong>Periode Magang:</strong> {{ $internshipGroup->start_date->format('d M Y') }} - {{ $internshipGroup->end_date->format('d M Y') }}</div>
        <div class="info-item"><strong>Total Anggota:</strong> {{ $internshipGroup->internships->count() }} siswa</div>
    </div>
@endsection

@section('section-title', 'DAFTAR ANGGOTA KELOMPOK')

@section('main-content')
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">NIS</th>
                <th width="35%">Nama Siswa</th>
                <th width="20%">Jurusan</th>
                <th width="25%">Posisi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($internshipGroup->internships as $internship)
                <tr>
                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                    <td>{{ $internship->student?->nis ?? 'N/A' }}</td>
                    <td>{{ $internship->student?->nama ?? 'Siswa Dihapus' }}</td>
                    <td>{{ $internship->student?->department?->nama ?? 'N/A' }}</td>
                    <td>{{ $internship->posisi ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
