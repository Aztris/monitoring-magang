@extends('layouts.app')

@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">{{ $title ?? 'Daftar Data' }}</h4>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="basic-datatables" class="display table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>NIS</th>
                                <th>Nama</th>
                                <th>Posisi</th>
                                <th>Grup Magang</th>
                                <th>Pembimbing</th>
                                <th>Nilai Rata<sup>2</sup></th>
                                <th style="width: 10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($internships as $internship)
                                @php
                                    $student = $internship->student;
                                    $totalScore = $internship->assessments->sum('nilai');
                                    $criteriaCount = $assessmentCriteria->count();
                                    $averageScore = $criteriaCount > 0 ? $totalScore / $criteriaCount : null;
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $student->nis }}</td>
                                    <td>{{ $student->nama }}</td>
                                    <td>{{ $internship ? $internship->posisi : 'N/A' }}</td>
                                    <td>{{ $internship ? $internship->internshipGroup->nama : 'N/A' }}</td>
                                    <td>{{ $internship ? $internship->internshipGroup->teacher->nama : 'N/A' }}</td>
                                    <td>{{ $internship->assessments ? number_format($averageScore, 2) : 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('internship-assessments.show', $internship->id) }}"
                                            class="btn btn-info btn-sm">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
