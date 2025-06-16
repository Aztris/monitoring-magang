@extends('layouts.app')

@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">{{ $title ?? 'Daftar Kehadiran' }}</h4>
                    <button type="button" class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal"
                        data-bs-target="#checkInModal">
                        <i class="fas fa-camera me-1"></i> Absen Masuk / Check-in
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
                                <th>Foto Masuk</th>
                                <th>Waktu Masuk</th>
                                <th>Foto Pulang</th>
                                <th>Waktu Pulang</th>
                                <th>Status</th>
                                <th>Verifikasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attendances as $attendance)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ \Carbon\Carbon::parse($attendance->date)->translatedFormat('d F Y') }}</td>
                                    <td>
                                        @if ($attendance->check_in_photo)
                                            <img src="{{ Storage::url($attendance->check_in_photo) }}" width="100"
                                                alt="Foto Absen Masuk">
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $attendance->check_in_time ?? '-' }}</td>
                                    <td>
                                        @if ($attendance->check_in_time && !$attendance->check_out_time)
                                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#checkOutModal" data-attendance-id="{{ $attendance->id }}">
                                                <i class="fas fa-sign-out-alt me-1"></i> Check Out
                                            </button>
                                        @else
                                            <img src="{{ Storage::url($attendance->check_out_photo) }}" width="100"
                                                alt="Foto Absen Masuk">
                                        @endif
                                    </td>
                                    <td>{{ $attendance->check_out_time ?? '-' }}</td>
                                    <td><span class="badge bg-info">{{ ucfirst($attendance->status) }}</span></td>
                                    <td>
                                        @if ($attendance->verification_status == 'verified')
                                            <span class="badge bg-success">Terverifikasi</span>
                                        @elseif($attendance->verification_status == 'rejected')
                                            <span class="badge bg-danger">Ditolak</span>
                                        @else<span class="badge bg-warning text-dark">Menunggu</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="checkInModal" tabindex="-1" aria-labelledby="checkInModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="checkInModalLabel">Formulir Absen Masuk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('attendances.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="date" class="form-label">Tanggal & Waktu</label>
                            <input type="text" class="form-control" id="date"
                                value="{{ \Carbon\Carbon::now()->translatedFormat('l, j F Y, H:i') }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status Kehadiran</label>
                            <select class="form-select" name="status" id="status" required>
                                <option value="hadir" selected>Hadir</option>
                                <option value="sakit">Sakit</option>
                                <option value="izin">Izin</option>
                                <option value="alpa">Alpa</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Catatan (Opsional)</label>
                            <textarea class="form-control" name="notes" id="notes" rows="2"
                                placeholder="Contoh: Ada keperluan keluarga"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Foto Absensi</label>

                            <div id="camera-container" class="text-center">
                                <video id="camera-feed" width="100%" autoplay playsinline class="rounded border"></video>
                                <button type="button" id="take-photo-btn" class="btn btn-secondary w-100 mt-2">
                                    <i class="fas fa-camera"></i> Ambil Gambar
                                </button>
                            </div>

                            <div id="preview-container" class="text-center d-none">
                                <img id="photo-preview" src="" alt="Preview Foto" class="img-thumbnail" />
                                <button type="button" id="retake-photo-btn" class="btn btn-outline-danger w-100 mt-2">
                                    <i class="fas fa-sync-alt"></i> Ambil Ulang Gambar
                                </button>
                            </div>

                            <canvas id="photo-canvas" class="d-none"></canvas>
                        </div>

                        <input type="hidden" name="check_in_photo" id="photo-data" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Kirim Absensi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="checkOutModal" tabindex="-1" aria-labelledby="checkOutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="checkOutModalLabel">Formulir Absen Pulang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="checkOutForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <p class="text-center">Anda akan melakukan check-out untuk tanggal
                            <br><strong>{{ \Carbon\Carbon::now()->translatedFormat('l, j F Y') }}</strong></p>
                        <hr>
                        <div class="mb-3">
                            <label class="form-label">Ambil Foto Absensi Pulang</label>
                            <div id="camera-container-checkout" class="text-center">
                                <video id="camera-feed-checkout" width="100%" autoplay playsinline
                                    class="rounded border"></video>
                                <button type="button" id="take-photo-btn-checkout" class="btn btn-secondary w-100 mt-2">
                                    <i class="fas fa-camera"></i> Ambil Gambar
                                </button>
                            </div>
                            <div id="preview-container-checkout" class="text-center d-none">
                                <img id="photo-preview-checkout" src="" alt="Preview Foto"
                                    class="img-thumbnail" />
                                <button type="button" id="retake-photo-btn-checkout"
                                    class="btn btn-outline-danger w-100 mt-2">
                                    <i class="fas fa-sync-alt"></i> Ambil Ulang Gambar
                                </button>
                            </div>
                            <canvas id="photo-canvas-checkout" class="d-none"></canvas>
                        </div>
                        <input type="hidden" name="check_out_photo" id="photo-data-checkout" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Kirim Check-out</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const checkInModal = document.getElementById('checkInModal');
                const cameraContainer = document.getElementById('camera-container');
                const previewContainer = document.getElementById('preview-container');
                const video = document.getElementById('camera-feed');
                const canvas = document.getElementById('photo-canvas');
                const takePictureBtn = document.getElementById('take-photo-btn');
                const retakePictureBtn = document.getElementById('retake-photo-btn');
                const photoDataInput = document.getElementById('photo-data');
                const photoPreview = document.getElementById('photo-preview');

                let stream = null;

                // Fungsi untuk menampilkan live camera dan menyembunyikan preview
                function showCameraView() {
                    cameraContainer.classList.remove('d-none');
                    previewContainer.classList.add('d-none');
                    photoDataInput.value = ''; // Kosongkan data foto
                }

                // Fungsi untuk menampilkan preview dan menyembunyikan live camera
                function showPreviewView() {
                    cameraContainer.classList.add('d-none');
                    previewContainer.classList.remove('d-none');
                }

                async function startCamera() {
                    showCameraView(); // Tampilkan view kamera saat dimulai
                    try {
                        if (stream) { // Hentikan stream lama jika ada
                            stream.getTracks().forEach(track => track.stop());
                        }
                        stream = await navigator.mediaDevices.getUserMedia({
                            video: {
                                facingMode: 'user'
                            }
                        });
                        video.srcObject = stream;
                    } catch (err) {
                        console.error("Error accessing camera: ", err);
                        alert('Tidak bisa mengakses kamera. Pastikan Anda memberikan izin.');
                    }
                }

                function stopCamera() {
                    if (stream) {
                        stream.getTracks().forEach(track => track.stop());
                        stream = null;
                    }
                }

                // Saat modal ditampilkan, nyalakan kamera
                checkInModal.addEventListener('shown.bs.modal', startCamera);

                // Saat modal ditutup, selalu matikan kamera dan reset view
                checkInModal.addEventListener('hidden.bs.modal', function() {
                    stopCamera();
                    showCameraView(); // Reset ke tampilan kamera untuk pembukaan selanjutnya
                });

                // Saat tombol "Ambil Gambar" diklik
                takePictureBtn.addEventListener('click', function() {
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

                    const dataUrl = canvas.toDataURL('image/jpeg');
                    photoPreview.src = dataUrl;
                    photoDataInput.value = dataUrl;

                    // Matikan stream kamera dan ganti tampilan
                    stopCamera();
                    showPreviewView();
                });

                // Saat tombol "Ambil Ulang Gambar" diklik
                retakePictureBtn.addEventListener('click', function() {
                    // Nyalakan kembali kamera dan ganti tampilan
                    startCamera();
                });
            });
        </script>
    @endpush

    @push('scripts')
{{-- ... Skrip untuk modal check-in dari jawaban sebelumnya tetap di sini ... --}}

<script>
// ===================================================================
// SKRIP UNTUK CHECK-OUT MODAL
// ===================================================================
document.addEventListener('DOMContentLoaded', function () {
    const checkOutModal = document.getElementById('checkOutModal');
    if(checkOutModal) {
        const checkOutForm = document.getElementById('checkOutForm');
        const cameraContainer = document.getElementById('camera-container-checkout');
        const previewContainer = document.getElementById('preview-container-checkout');
        const video = document.getElementById('camera-feed-checkout');
        const canvas = document.getElementById('photo-canvas-checkout');
        const takePictureBtn = document.getElementById('take-photo-btn-checkout');
        const retakePictureBtn = document.getElementById('retake-photo-btn-checkout');
        const photoDataInput = document.getElementById('photo-data-checkout');
        const photoPreview = document.getElementById('photo-preview-checkout');
        let stream_checkout = null;

        function showCameraViewCheckout() {
            cameraContainer.classList.remove('d-none');
            previewContainer.classList.add('d-none');
            photoDataInput.value = '';
        }

        function showPreviewViewCheckout() {
            cameraContainer.classList.add('d-none');
            previewContainer.classList.remove('d-none');
        }

        async function startCameraCheckout() {
            showCameraViewCheckout();
            try {
                if (stream_checkout) {
                    stream_checkout.getTracks().forEach(track => track.stop());
                }
                stream_checkout = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } });
                video.srcObject = stream_checkout;
            } catch (err) {
                alert('Tidak bisa mengakses kamera.');
            }
        }

        function stopCameraCheckout() {
            if (stream_checkout) {
                stream_checkout.getTracks().forEach(track => track.stop());
                stream_checkout = null;
            }
        }

        checkOutModal.addEventListener('shown.bs.modal', function (event) {
            // Ambil attendance ID dari tombol yang diklik
            const button = event.relatedTarget;
            const attendanceId = button.getAttribute('data-attendance-id');

            // Set action form secara dinamis
            checkOutForm.action = `/attendances/${attendanceId}`;

            startCameraCheckout();
        });

        checkOutModal.addEventListener('hidden.bs.modal', function() {
            stopCameraCheckout();
            showCameraViewCheckout();
        });

        takePictureBtn.addEventListener('click', function () {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
            const dataUrl = canvas.toDataURL('image/jpeg');
            photoPreview.src = dataUrl;
            photoDataInput.value = dataUrl;
            stopCameraCheckout();
            showPreviewViewCheckout();
        });

        retakePictureBtn.addEventListener('click', startCameraCheckout);
    }
});
</script>
@endpush
@endsection
