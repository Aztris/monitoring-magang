@extends('layouts.app')

@section('content')
    @php
        $role = Auth::user()->role;
        $canAccess = $role == 'admin' || $role == 'company';
    @endphp
    <div class="container">
        <div class="page-inner">
            <div class="d-flex align-items-center mb-4">
                <h2 class="page-title mb-0">Formulir Penilaian Magang</h2>
                <div class="ms-auto">
                    @if (Auth::user()->role != 'student')
                        <a href="{{ route('internship-assessments.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    @endif
                    <a href="{{ route('internship-assessments.print', $internship->id) }}" class="btn btn-danger btn-sm"
                        target="_blank">
                        <i class="fas fa-print"></i> Cetak
                    </a>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 text-center">
                            @php
                                $photoPath = $internship->student?->user?->foto_profil ?? 'profile_photos/default_avatar.png';
                            @endphp
                            <img src="{{ asset('storage/' . $photoPath) }}"
                                 alt="Foto Profil" class="img-fluid rounded-circle" style="object-fit: cover;">
                       </div>
                        <div class="col-md-5">
                            <h5 class="card-title mb-3">Data Siswa</h5>
                            <p class="mb-2"><strong>Nama:</strong> {{ $internship->student->nama }}</p>
                            <p class="mb-2"><strong>NIS:</strong> {{ $internship->student->nis }}</p>
                            <p class="mb-0"><strong>Posisi:</strong> {{ $internship->posisi ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-5">
                            <h5 class="card-title mb-3">Data Magang</h5>
                            <p class="mb-2"><strong>DUDIKA:</strong> {{ $internship->internshipGroup->company->nama }}</p>
                            <p class="mb-2"><strong>Kelompok:</strong> {{ $internship->internshipGroup->nama }}</p>
                            <p class="mb-0"><strong>Pembimbing:</strong> {{ $internship->internshipGroup->teacher->nama }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Rincian Penilaian</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="55%">Kriteria Penilaian</th>
                                    <th width="20%" class="text-center">Nilai</th>
                                    @if ($canAccess)
                                        <th width="25%" class="text-center">Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalScore = 0;
                                    $criteriaCount = 0;
                                @endphp
                                @forelse ($assessmentCriteria as $criteria)
                                    <tr>
                                        <td>{{ $criteria->nama }}</td>
                                        <td class="text-center">
                                            @php
                                                $assessment = $assessments->get($criteria->id);
                                                $nilai = $assessment ? $assessment->nilai : null;
                                                if (is_numeric($nilai)) {
                                                    $totalScore += $nilai;
                                                    $criteriaCount++;
                                                }
                                            @endphp

                                            @if (is_numeric($nilai))
                                                <span
                                                    class="badge fs-6
                                                    @if ($nilai >= 85) bg-success
                                                    @elseif($nilai >= 70) bg-warning text-dark
                                                    @else bg-danger @endif">
                                                    {{ $nilai }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">Belum dinilai</span>
                                            @endif
                                        </td>
                                        @if ($canAccess)
                                            <td class="text-center">
                                                @if ($assessment)
                                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                        data-bs-target="#editModal{{ $criteria->id }}">
                                                        <i class="fas fa-edit"></i> Edit Nilai
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-success" data-bs-toggle="modal"
                                                        data-bs-target="#addModal{{ $criteria->id }}">
                                                        <i class="fas fa-plus"></i> Beri Nilai
                                                    </button>
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ $canAccess ? '3' : '2' }}" class="text-center py-4">Kriteria
                                            penilaian belum ditambahkan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($criteriaCount > 0)
                    <div class="card-footer">
                        <div class="d-flex justify-content-end align-items-center">
                            <h5 class="me-3 mb-0">Nilai Akhir Rata-Rata:</h5>
                            @php
                                $averageScore = $totalScore / $criteriaCount;
                            @endphp
                            <span
                                class="badge fs-4
                                @if ($averageScore >= 85) bg-success
                                @elseif($averageScore >= 70) bg-warning text-dark
                                @else bg-danger @endif">
                                {{ number_format($averageScore, 2) }}
                            </span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if ($canAccess)
        @foreach ($assessmentCriteria as $criteria)
            @php $assessment = $assessments->get($criteria->id); @endphp
            @if ($assessment)
                <div class="modal fade" id="editModal{{ $criteria->id }}" tabindex="-1"
                    aria-labelledby="editModalLabel{{ $criteria->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="editModalLabel{{ $criteria->id }}">Edit Nilai:
                                    {{ $criteria->nama }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="{{ route('internship-assessments.update', $assessment->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <input type="hidden" name="internship_id" value="{{ $internship->id }}">
                                    <input type="hidden" name="assessment_criteria_id" value="{{ $criteria->id }}">
                                    <input type="hidden" name="assessor_id" value="{{ Auth::id() }}">
                                    <div class="mb-3">
                                        <label class="form-label">Nilai (0-100)</label>
                                        <input type="number" name="nilai" value="{{ $assessment->nilai }}"
                                            min="0" max="100" required class="form-control">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                                            class="fas fa-times"></i> Tutup</button>
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan
                                        Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <div class="modal fade" id="addModal{{ $criteria->id }}" tabindex="-1"
                    aria-labelledby="addModalLabel{{ $criteria->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="addModalLabel{{ $criteria->id }}">Tambah Nilai:
                                    {{ $criteria->nama }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="{{ route('internship-assessments.store') }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <input type="hidden" name="internship_id" value="{{ $internship->id }}">
                                    <input type="hidden" name="assessment_criteria_id" value="{{ $criteria->id }}">
                                    <input type="hidden" name="assessor_id" value="{{ Auth::id() }}">
                                    <div class="mb-3">
                                        <label class="form-label">Nilai (0-100)</label>
                                        <input type="number" name="nilai" min="0" max="100" required
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                                            class="fas fa-times"></i> Tutup</button>
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah
                                        Nilai</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    @endif
@endsection
