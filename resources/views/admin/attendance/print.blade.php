@extends('layouts.print')

@section('title', 'Laporan Kehadiran - ' . $student->nama)

@section('header')
    <h1>LAPORAN RIWAYAT KEHADIRAN SISWA</h1>
    <div class="subtitle">Praktik Kerja Lapangan (PKL)</div>
@endsection

@section('info-block')
    <h2 style="margin-top: 0; margin-bottom: 15px; font-size: 18px; color: #2c3e50;">INFORMASI PESERTA</h2>
    <div class="info-grid">
        <div class="info-item"><strong>Nama Siswa:</strong> {{ $student->nama }}</div>
        <div class="info-item"><strong>NIS:</strong> {{ $student->nis }}</div>
        <div class="info-item"><strong>DUDIKA:</strong> {{ $internship?->internshipGroup?->company?->nama ?? 'N/A' }}</div>
        <div class="info-item"><strong>Guru Pembimbing:</strong> {{ $internship?->internshipGroup?->teacher?->nama ?? 'N/A' }}</div>
    </div>
@endsection

@section('main-content')
    <h3 class="section-title">DETAIL KEHADIRAN</h3>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Tanggal</th>
                <th width="15%">Waktu Masuk</th>
                <th width="15%">Waktu Pulang</th>
                <th width="15%">Status</th>
                <th width="30%">Verifikasi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($attendances as $attendance)
                <tr>
                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                    <td>{{ $attendance->date->translatedFormat('D, d M Y') }}</td>
                    <td style="text-align: center;">{{ $attendance->check_in_time ?? '-' }}</td>
                    <td style="text-align: center;">{{ $attendance->check_out_time ?? '-' }}</td>
                    <td style="text-align: center;">{{ ucfirst($attendance->status) }}</td>
                    <td style="text-align: center;">{{ ucfirst($attendance->verification_status) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data kehadiran untuk dilaporkan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($attendances->isNotEmpty())
        <h3 class="section-title" style="margin-top: 30px;">REKAPITULASI KEHADIRAN</h3>
        @php
            $hadir = $attendances->where('status', 'hadir')->count();
            $sakit = $attendances->where('status', 'sakit')->count();
            $izin = $attendances->where('status', 'izin')->count();
            $alpa = $attendances->where('status', 'alpa')->count();
            $total = $attendances->count();
        @endphp
        <table class="table-sm" style="width: 50%;">
            <tr>
                <td width="50%">Jumlah Hadir</td>
                <td width="5%">:</td>
                <td><strong>{{ $hadir }}</strong> hari</td>
            </tr>
            <tr>
                <td>Jumlah Sakit</td>
                <td>:</td>
                <td><strong>{{ $sakit }}</strong> hari</td>
            </tr>
            <tr>
                <td>Jumlah Izin</td>
                <td>:</td>
                <td><strong>{{ $izin }}</strong> hari</td>
            </tr>
             <tr>
                <td>Jumlah Tanpa Keterangan</td>
                <td>:</td>
                <td><strong>{{ $alpa }}</strong> hari</td>
            </tr>
            <tr style="border-top: 1px solid #e0e0e0; font-weight: bold;">
                <td>Total Hari Tercatat</td>
                <td>:</td>
                <td><strong>{{ $total }}</strong> hari</td>
            </tr>
        </table>
    @endif
@endsection

@section('signature-block')
    {{-- <div style="width: 100%; margin-top: 50px; font-size: 12pt; display: flex; justify-content: flex-end;">
        <div style="width: 40%; text-align: center;">
            <p>Batang, {{ now()->translatedFormat('d F Y') }}</p>
            <p>Guru Pembimbing,</p>
            <br><br><br>
            <p><strong><u>{{ $internship?->internshipGroup?->teacher?->nama ?? '(____________________)' }}</u></strong></p>
            <p>NIP. {{ $internship?->internshipGroup?->teacher?->nip ?? '-' }}</p>
        </div>
    </div> --}}
@endsection
