@extends('layouts.app')

@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">{{ $title ?? 'Daftar Siswa yang Magang' }}</h4>
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
                                <th>Jumlah Aktivitas</th>
                                <th style="width: 10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $student)
                                @foreach ($student->internships as $internship)
                                    <tr>
                                        <td>{{ $loop->parent->iteration }}</td>
                                        <td>{{ $student->nis }}</td>
                                        <td>{{ $student->nama }}</td>
                                        <td>{{ $internship->posisi }}</td>
                                        <td>{{ $internship->internshipGroup->nama }}</td>
                                        <td>{{ $student->internships->first()->activities->count() }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('activities.show', $student) }}" class="btn btn-sm btn-info">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
