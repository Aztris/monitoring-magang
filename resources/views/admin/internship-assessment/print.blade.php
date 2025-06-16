@extends('layouts.print')

@section('title', 'Laporan Penilaian - ' . ($internship->student?->nama ?? 'Siswa Tidak Ditemukan'))

@section('header')
    <h1>LAPORAN PENILAIAN PRAKTIK KERJA LAPANGAN</h1>
    <div class="subtitle">Sistem Monitoring Magang</div>
@endsection

@section('info-block')
    <h2 style="margin-top: 0; margin-bottom: 15px; font-size: 18px; color: #2c3e50;">INFORMASI PESERTA</h2>
    <div class="info-grid">
        <div class="info-item">
            <strong>Nama Siswa:</strong> {{ $internship->student?->nama ?? 'Siswa Tidak Ditemukan' }}
        </div>
        <div class="info-item">
            <strong>Nama DUDIKA:</strong> {{ $internship->internshipGroup?->company?->nama ?? 'N/A' }}
        </div>
        <div class="info-item">
            <strong>NIS:</strong> {{ $internship->student?->nis ?? 'N/A' }}
        </div>
         <div class="info-item">
            <strong>Posisi Magang:</strong> {{ $internship->posisi ?? 'N/A' }}
        </div>
        <div class="info-item">
            <strong>Guru Pembimbing:</strong> {{ $internship->internshipGroup?->teacher?->nama ?? 'N/A' }}
        </div>
        <div class="info-item">
            <strong>Periode:</strong> {{ $internship->internshipGroup?->start_date?->format('d M Y') }} - {{ $internship->internshipGroup?->end_date?->format('d M Y') }}
        </div>
    </div>
@endsection

@section('section-title', 'RINCIAN NILAI')

@section('main-content')
    <table>
        <thead class="text-center">
            <tr>
                <th width="5%">No</th>
                <th width="75%" style="text-align: left;">Kriteria Penilaian</th>
                <th width="20%">Nilai</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalScore = 0;
                $criteriaCount = 0;
            @endphp
            @forelse ($assessmentCriteria as $criteria)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $criteria->nama }}</td>
                    <td class="text-center">
                        @php
                            $assessment = $assessments->get($criteria->id);
                            $nilai = $assessment ? $assessment->nilai : 0;
                            if(is_numeric($nilai)) {
                                $totalScore += $nilai;
                                $criteriaCount++;
                            }
                        @endphp
                        {{ $nilai }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">Kriteria penilaian belum tersedia.</td>
                </tr>
            @endforelse
        </tbody>
        @if($criteriaCount > 0)
            <tfoot class="table-group-divider" style="font-weight: bold;">
                @php
                    $averageScore = $totalScore / $criteriaCount;
                @endphp
                <tr>
                    <td colspan="2" class="text-end">Total Nilai</td>
                    <td class="text-center">{{ $totalScore }}</td>
                </tr>
                <tr>
                    <td colspan="2" class="text-end">Nilai Rata-rata</td>
                    <td class="text-center">{{ number_format($averageScore, 2) }}</td>
                </tr>
            </tfoot>
        @endif
    </table>
@endsection

@section('signature-block')
    {{-- <div class="row mt-5" style="font-size: 12pt;">
        <div class="col-6 text-center">
            <p>Guru Pembimbing,</p>
            <br><br><br>
            <p><strong><u>{{ $internship->internshipGroup?->teacher?->nama ?? '(____________________)' }}</u></strong></p>
            <p>NIP. {{ $internship->internshipGroup?->teacher?->nip ?? '-' }}</p>
        </div>
        <div class="col-6 text-center">
            <p>Pembimbing Industri,</p>
            <br><br><br>
            <p><strong><u>{{ $internship->internshipGroup?->company?->pic_nama ?? '(____________________)' }}</u></strong></p>
            <p>Jabatan</p>
        </div>
    </div> --}}
@endsection
