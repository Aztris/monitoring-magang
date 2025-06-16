<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Aktivitas Magang - {{ $student->nama }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e0e0e0;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            color: #2c3e50;
        }
        .header .subtitle {
            font-size: 14px;
            color: #7f8c8d;
        }
        .student-info {
            margin-bottom: 25px;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
        .info-item {
            margin-bottom: 8px;
        }
        .info-item strong {
            display: inline-block;
            width: 150px;
            color: #34495e;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 13px;
        }
        th {
            background-color: #f8f9fa;
            color: #2c3e50;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
        }
        th, td {
            border: 1px solid #e0e0e0;
            padding: 10px 12px;
            text-align: left;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .photo-cell {
            text-align: center;
        }
        .photo-cell img {
            max-width: 100px;
            max-height: 80px;
            border: 1px solid #ddd;
            border-radius: 3px;
            object-fit: cover;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #e0e0e0;
            font-size: 12px;
            text-align: right;
            color: #7f8c8d;
        }
        .activity-description {
            max-height: 100px;
            overflow-y: auto;
            padding-right: 5px;
        }
        @media print {
            @page {
                margin: 15mm;
            }
            body {
                padding: 0;
            }
            .activity-description {
                max-height: none;
                overflow-y: visible;
            }
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>LAPORAN AKTIVITAS MAGANG</h1>
        <div class="subtitle">Sistem Monitoring Magang</div>
    </div>

    <div class="student-info">
        <h2 style="margin-top: 0; margin-bottom: 15px; font-size: 18px; color: #2c3e50;">DATA SISWA</h2>

        <div class="info-grid">
            <div class="info-item">
                <strong>Nama Siswa:</strong> {{ $student->nama }}
            </div>
            <div class="info-item">
                <strong>NIS:</strong> {{ $student->nis }}
            </div>
            <div class="info-item">
                <strong>Jenis Kelamin:</strong> {{ $student->jenkel == 'L' ? 'Laki-laki' : 'Perempuan' }}
            </div>
            <div class="info-item">
                <strong>Grup Magang:</strong>
                @foreach ($student->internships as $internship)
                    {{ $internship->internshipGroup->nama }}@if (!$loop->last), @endif
                @endforeach
            </div>
            <div class="info-item">
                <strong>DUDIKA:</strong>
                @foreach ($student->internships as $internship)
                    {{ $internship->internshipGroup->company->nama }}@if (!$loop->last), @endif
                @endforeach
            </div>
            <div class="info-item">
                <strong>Guru Pembimbing:</strong>
                @foreach ($student->internships as $internship)
                    {{ $internship->internshipGroup->teacher->nama }}@if (!$loop->last), @endif
                @endforeach
            </div>
            <div class="info-item">
                <strong>Periode:</strong>
                @if($activities->count() > 0)
                    {{ $activities->first()->date->format('d M Y') }} - {{ $activities->last()->date->format('d M Y') }}
                @else
                    -
                @endif
            </div>
            <div class="info-item">
                <strong>Total Aktivitas:</strong>
                {{ $activities->count() }} kegiatan
            </div>
        </div>
    </div>

    <h3 style="margin: 25px 0 15px 0; font-size: 16px; color: #2c3e50; border-bottom: 1px solid #e0e0e0; padding-bottom: 5px;">
        DETAIL AKTIVITAS
    </h3>

    @if($activities->count() > 0)
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Tanggal</th>
                <th width="20%">Judul Aktivitas</th>
                <th width="40%">Deskripsi</th>
                <th width="20%">Dokumentasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($activities as $activity)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $activity->date->format('d M Y') }}</td>
                    <td>{{ $activity->title }}</td>
                    <td>
                        <div class="activity-description">
                            {{ $activity->description }}
                        </div>
                    </td>
                    <td class="photo-cell">
                        @if($activity->activity_photo)
                            <img src="{{ Storage::url($activity->activity_photo) }}" alt="Dokumentasi Aktivitas">
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div style="padding: 15px; background: #f8f9fa; border-radius: 5px; text-align: center;">
        Tidak ada data aktivitas yang ditemukan
    </div>
    @endif

    <div class="footer">
        Dicetak pada: {{ now()->format('d M Y H:i') }} oleh {{ auth()->user()->nama ?? 'System' }}
    </div>

    <script>
        window.onload = function() {
            window.print();
            window.onafterprint = function() {
                window.close();
            };
        };
    </script>
</body>
</html>
