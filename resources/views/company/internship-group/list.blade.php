@extends('layouts.app')
@section('content')
    @php
        $selectedYear = request()->attributes->get('selected_academic_year');
    @endphp

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">{{ $title ?? 'List Internship Groups' }}</h4>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="basic-datatables" class="display table table-striped table-hover overflow-auto">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Perusahaan</th>
                                <th>Pengajar</th>
                                <th>Tahun Akademik</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th>Status</th>
                                <th style="width: 10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($internshipGroups as $group)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $group->nama }}</td>
                                    <td>{{ $group->company->nama ?? '-' }}</td>
                                    <td>{{ $group->teacher->nama ?? '-' }}</td>
                                    <td>{{ $group->academicYear->name ?? '-' }}</td>
                                    <td>{{ $group->start_date->format('d M Y') }}</td>
                                    <td>{{ $group->end_date->format('d M Y') }}</td>

                                    <td>{{ ucfirst($group->enum) }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('internship-groups.show', $group->id) }}"
                                                class="btn btn-sm btn-info" title="Show Details">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </div>
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
