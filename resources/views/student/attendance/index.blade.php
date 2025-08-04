@extends('layouts.app')
@section('content')
    <style>
        .camera-preview {
            width: 100%;
            height: 300px;
            background-color: #f8f9fa;
            border-radius: 0.5rem;
            overflow: hidden;
            position: relative;
        }

        .captured-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 0.5rem;
        }

        .btn-camera {
            position: absolute;
            bottom: 1rem;
            left: 50%;
            transform: translateX(-50%);
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            border: none;
        }

        .btn-camera:hover {
            background-color: rgba(0, 0, 0, 0.7);
        }

        .status-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-present {
            background-color: #d1e7dd;
            color: #0f5132;
        }

        .status-absent {
            background-color: #f8d7da;
            color: #842029;
        }

        .status-excused {
            background-color: #cfe2ff;
            color: #084298;
        }

        .status-late {
            background-color: #fff3cd;
            color: #664d03;
        }

        .verification-pending {
            background-color: #f8f9fa;
            color: #6c757d;
        }

        .verification-verified {
            background-color: #d1e7dd;
            color: #0f5132;
        }

        .verification-rejected {
            background-color: #f8d7da;
            color: #842029;
        }

        .sidebar {
            background-color: #f8f9fa;
            border-right: 1px solid #dee2e6;
            height: calc(100vh - 56px);
        }

        .nav-link.active {
            background-color: #e9ecef;
            color: #0d6efd !important;
        }

        .nav-link:hover:not(.active) {
            background-color: #e9ecef;
        }
    </style>

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">{{ $title ?? 'Daftar Kehadiran' }}</h4>
                    <button type="button" class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal"
                        data-bs-target="#checkInModal">
                        <i class="fas fa-sign-in-alt me-1"></i> Check In
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="basic-datatables" class="display table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>Absen Masuk</th>
                                <th>Absen Pulang</th>
                                <th>Status</th>
                                <th>Verifikasi</th>
                                {{-- <th>Catatan</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $data = false;
                            @endphp
                            @foreach ($attendances as $attendance)
                                @php
                                    $data = true;
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $attendance->date->format('D,d-m-Y') }}</td>
                                    <td>
                                        <img src="{{ Storage::url($attendance->check_in_photo) }}" width="100"
                                            alt="FotoAbsen Masuk">
                                    </td>
                                    <td>
                                        @if (!empty($attendance->check_out_photo))
                                            <img src="{{ Storage::url($attendance->check_out_photo) }}" width="100"
                                                alt="Foto Absen Masuk">
                                        @else
                                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                                data-bs-target="#checkOutModal" data-attendance-id="{{ $attendance->id }}">
                                                <i class="fas fa-sign-out-alt me-1"></i> Check Out
                                            </button>
                                        @endif
                                    </td>
                                    <td>{{ ucfirst($attendance->status) }}</td>
                                    <td>{{ ucfirst($attendance->verification_status) }}</td>
                                    {{-- <td>{{ $attendance->notes }}</td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Check In Modal -->
    <div class="modal fade" id="checkInModal" tabindex="-1" aria-labelledby="checkInModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="checkInModalLabel">Absen Datang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="checkInForm" action="{{ route('attendances.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="internship_id"
                            value="{{ Auth::user()->student->internships->first()->id }}">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="check_in_date" class="form-label">Tanggal</label>
                                <input type="date" class="form-control" id="check_in_date" name="date" required>
                            </div>
                            <div class="col-md-6">
                                <label for="check_in_time" class="form-label">Jam</label>
                                <input type="time" class="form-control" id="check_in_time" name="check_in_time" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="hadir">Hadir</option>
                                <option value="izin">Izin</option>
                                <option value="sakit">Sakit</option>
                                <option value="alpa">Alpa</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Foto Absen</label>
                            <div class="camera-preview">
                                <video id="video" autoplay playsinline class="w-100 h-100"></video>
                                <div id="capturedImageContainer" style="display: none;">
                                    <img id="capturedImage" class="captured-image" src="" alt="Captured photo">
                                </div>
                                <button type="button" id="captureBtn" class="btn-camera">
                                    <i class="fas fa-camera"></i>
                                </button>
                            </div>
                            <input type="hidden" id="check_in_photo" name="check_in_photo">
                        </div>
                        <div class="mb-3">
                            <label for="check_in_notes" class="form-label">Catatan</label>
                            <textarea class="form-control" id="check_in_notes" name="notes" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="submitCheckIn" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    @if ($data)
        <div class="modal fade" id="checkOutModal" tabindex="-1" aria-labelledby="checkOutModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="checkOutModalLabel">Absen Pulang</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="checkOutForm"
                            action="{{ route('attendances.update', ['attendance' => $attendance->id]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" id="attendance_id" name="attendance_id">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="check_out_time" class="form-label">Jam</label>
                                    <input type="time" class="form-control" id="check_out_time" name="check_out_time"
                                        required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Foto Absen</label>
                                <div class="camera-preview">
                                    <video id="videoCheckOut" autoplay playsinline class="w-100 h-100"></video>
                                    <div id="capturedImageContainerCheckOut" style="display: none;">
                                        <img id="capturedImageCheckOut" class="captured-image" src=""
                                            alt="Captured photo">
                                    </div>
                                    <button type="button" id="captureBtnCheckOut" class="btn-camera">
                                        <i class="fas fa-camera"></i>
                                    </button>
                                </div>
                                <input type="hidden" id="check_out_photo" name="check_out_photo">
                            </div>
                            <div class="mb-3">
                                <label for="check_out_notes" class="form-label">Catatan</label>
                                <textarea class="form-control" id="check_out_notes" name="notes" rows="3"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" id="submitCheckOut" class="btn btn-success">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    @endif


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('check_in_date').value = today;

            const now = new Date();
            const hours = now.getHours().toString().padStart(2, '0');
            const minutes = now.getMinutes().toString().padStart(2, '0');
            document.getElementById('check_in_time').value = `${hours}:${minutes}`;
            document.getElementById('check_out_time').value = `${hours}:${minutes}`;
        });

        const checkInModal = document.getElementById('checkInModal');
        const video = document.getElementById('video');
        const captureBtn = document.getElementById('captureBtn');
        const capturedImage = document.getElementById('capturedImage');
        const capturedImageContainer = document.getElementById('capturedImageContainer');
        const checkInPhoto = document.getElementById('check_in_photo');
        let stream = null;

        checkInModal.addEventListener('shown.bs.modal', async function() {
            try {
                stream = await navigator.mediaDevices.getUserMedia({
                    video: true
                });
                video.srcObject = stream;
            } catch (err) {
                console.error("Error accessing camera: ", err);
                alert("Could not access the camera. Please make sure you have granted camera permissions.");
            }
        });

        checkInModal.addEventListener('hidden.bs.modal', function() {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }
            capturedImageContainer.style.display = 'none';
            video.style.display = 'block';
            checkInPhoto.value = '';
        });

        captureBtn.addEventListener('click', function() {
            const canvas = document.createElement('canvas');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

            const imageDataUrl = canvas.toDataURL('image/jpeg');
            capturedImage.src = imageDataUrl;
            checkInPhoto.value = imageDataUrl;

            capturedImageContainer.style.display = 'block';
            video.style.display = 'none';
        });

        document.getElementById('submitCheckIn').addEventListener('click', function() {
            if (!checkInPhoto.value) {
                alert('Please capture your photo before submitting');
                return;
            }

            document.getElementById('checkInForm').submit();
        });

        const checkOutButtons = document.querySelectorAll('[data-bs-target="#checkOutModal"]');
        checkOutButtons.forEach(button => {
            button.addEventListener('click', function() {
                const attendanceId = this.getAttribute('data-attendance-id');

                document.getElementById('attendance_id').value = attendanceId;
            });
        });

        const checkOutModal = document.getElementById('checkOutModal');
        const videoCheckOut = document.getElementById('videoCheckOut');
        const captureBtnCheckOut = document.getElementById('captureBtnCheckOut');
        const capturedImageCheckOut = document.getElementById('capturedImageCheckOut');
        const capturedImageContainerCheckOut = document.getElementById('capturedImageContainerCheckOut');
        const checkOutPhoto = document.getElementById('check_out_photo');
        let streamCheckOut = null;

        checkOutModal.addEventListener('shown.bs.modal', async function() {
            try {
                streamCheckOut = await navigator.mediaDevices.getUserMedia({
                    video: true
                });
                videoCheckOut.srcObject = streamCheckOut;
            } catch (err) {
                console.error("Error accessing camera: ", err);
                alert("Could not access the camera. Please make sure you have granted camera permissions.");
            }
        });

        checkOutModal.addEventListener('hidden.bs.modal', function() {
            if (streamCheckOut) {
                streamCheckOut.getTracks().forEach(track => track.stop());
            }
            capturedImageContainerCheckOut.style.display = 'none';
            videoCheckOut.style.display = 'block';
            checkOutPhoto.value = '';
        });

        captureBtnCheckOut.addEventListener('click', function() {
            const canvas = document.createElement('canvas');
            canvas.width = videoCheckOut.videoWidth;
            canvas.height = videoCheckOut.videoHeight;
            canvas.getContext('2d').drawImage(videoCheckOut, 0, 0, canvas.width, canvas.height);

            const imageDataUrl = canvas.toDataURL('image/jpeg');
            capturedImageCheckOut.src = imageDataUrl;
            checkOutPhoto.value = imageDataUrl;

            capturedImageContainerCheckOut.style.display = 'block';
            videoCheckOut.style.display = 'none';
        });

        document.getElementById('submitCheckOut').addEventListener('click', function() {
            if (!checkOutPhoto.value) {
                alert('Please capture your photo before submitting');
                return;
            }

            document.getElementById('checkOutForm').submit();
        });
    </script>
@endsection
