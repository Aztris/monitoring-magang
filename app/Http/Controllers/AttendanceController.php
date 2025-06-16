<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Attendance;
use App\Models\Internship;
use Illuminate\Http\Request;
use App\Models\InternshipGroup;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index(Request $request)
    {
        $user = Auth::user();

        switch ($user->role) {
            case 'admin':
                // Admin melihat daftar semua siswa yang magang di tahun aktif
                return $this->managementIndex($request);
            case 'teacher':
                // Guru melihat daftar siswa bimbingannya di tahun aktif
                return $this->managementIndex($request);
            case 'company':
                // Perusahaan melihat daftar siswa yang magang di tempatnya di tahun aktif
                return $this->managementIndex($request);
            case 'student':
                // Siswa melihat daftar kehadirannya sendiri
                return $this->studentIndex($request);
            default:
                return redirect('/');
        }
    }

    /**
     * Menyiapkan daftar SISWA untuk peran manajemen (Admin, Guru, Perusahaan).
     */
    private function managementIndex(Request $request)
    {
        $user = Auth::user();
        $selectedYear = $request->attributes->get('selected_academic_year');

        if (!$selectedYear) {
            return redirect()->route('academic-years.index')->with('toast', ['type' => 'error', 'message' => 'Silakan pilih tahun akademik.']);
        }
        $selectedYearId = $selectedYear->id;

        // Query dasar: Ambil siswa yang punya data magang di tahun akademik terpilih
        $query = Student::whereHas('internships.internshipGroup', function ($q) use ($selectedYearId) {
            $q->where('academic_year_id', $selectedYearId);
        });

        // Terapkan filter tambahan berdasarkan peran
        if ($user->role === 'teacher') {
            $query->whereHas('internships.internshipGroup', function ($q) use ($user) {
                $q->where('teacher_id', $user->teacher->id);
            });
        } elseif ($user->role === 'company') {
             $query->whereHas('internships.internshipGroup', function ($q) use ($user) {
                $q->where('company_id', $user->company->id);
            });
        }

        $query->with(['internships' => function ($q) use ($selectedYearId) {
            $q->whereHas('internshipGroup', fn($groupQuery) => $groupQuery->where('academic_year_id', $selectedYearId));
            $q->with('internshipGroup.teacher');
        }]);

        $students = $query->orderBy('nama', 'asc')->get();

        return view('admin.attendance.list', [
            'title' => 'Daftar Siswa Magang',
            'students' => $students
        ]);
    }

    /**
     * Menyiapkan daftar KEHADIRAN untuk peran siswa.
     */
    private function studentIndex(Request $request)
    {
        $user = Auth::user();
        $selectedYear = $request->attributes->get('selected_academic_year');

        $internship = Internship::where('student_id', $user->student->id)
            ->whereHas('internshipGroup', fn($q) => $q->where('academic_year_id', $selectedYear->id))
            ->first();

        $attendances = $internship ? $internship->attendances()->latest('date')->get() : collect();

        return view('student.attendance.index2', compact('attendances'));
    }


    private function adminIndex(Request $request)
    {
        $selectedYear = $request->attributes->get('selected_academic_year');

        if (!$selectedYear) {
            return redirect()->route('academic-years.index')
                ->with('toast', [
                    'type' => 'error',
                    'message' => 'Silakan pilih tahun akademik terlebih dahulu'
                ]);
        }

        $internshipGroups = InternshipGroup::with(['academicYear'])
            ->where('academic_year_id', $selectedYear->id)
            ->get();

        $internships = Internship::with(['student'])
            ->whereIn('internship_group_id', $internshipGroups->pluck('id'))
            ->get();

        $students = $internships->pluck('student')->unique('id');

        return view('admin.attendance.list', compact('students', 'selectedYear'));
    }

    private function teacherIndex(Request $request)
    {
        $selectedYear = $request->attributes->get('selected_academic_year');

        if (!$selectedYear) {
            return redirect()->route('academic-years.index')
                ->with('toast', [
                    'type' => 'error',
                    'message' => 'Silakan pilih tahun akademik terlebih dahulu'
                ]);
        }

        $teacherId = auth()->user()->teacher->id;

        $internshipGroups = InternshipGroup::with(['academicYear'])
            ->where('academic_year_id', $selectedYear->id)
            ->where('teacher_id', $teacherId)
            ->get();

        $internships = Internship::with(['student'])
            ->whereIn('internship_group_id', $internshipGroups->pluck('id'))
            ->get();

        $students = $internships->pluck('student')->unique('id');

        return view('admin.attendance.list', compact('students', 'selectedYear'));
    }

    private function companyIndex(Request $request)
    {
        $companyId = auth()->user()->company->id;

        $internshipGroups = InternshipGroup::with(['academicYear'])
            ->where('company_id', $companyId)
            ->get();

        $internships = Internship::with(['student'])
            ->whereIn('internship_group_id', $internshipGroups->pluck('id'))
            ->get();

        $attendances = Attendance::with(['internship.student'])
            ->whereIn('internship_id', $internships->pluck('id'))
            ->orderBy('date', 'desc') // Urutkan berdasarkan tanggal terbaru
            ->get();

        return view('admin.attendance.list', compact('attendances'));
    }


    // private function studentIndex($user)
    // {
    //     $student = $user->student;
    //     $internships = $student->internships;
    //     $attendances = Attendance::whereIn('internship_id', $internships->pluck('id'))
    //         ->orderBy('date', 'desc')
    //         ->get();

    //     return view('student.attendance.index2', compact('attendances'));
    // }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'status' => 'required|in:hadir,sakit,izin', //
            'check_in_photo' => 'required', //
            'notes' => 'nullable|string',
        ]);

        $user = Auth::user();
        $student = $user->student;
        $selectedYearId = session('selected_academic_year_id');

        // Dapatkan data magang siswa
        $internship = Internship::where('student_id', $student->id)
            ->whereHas('internshipGroup', function ($query) use ($selectedYearId) {
                $query->where('academic_year_id', $selectedYearId);
            })
            ->firstOrFail();

        // Proses penyimpanan foto dari base64
        $imageData = $request->input('check_in_photo'); //
        list($type, $imageData) = explode(';', $imageData);
        list(, $imageData)      = explode(',', $imageData);
        $decodedImage = base64_decode($imageData);

        $filename = 'check_in_' . uniqid() . '.jpg';
        $path = 'attendances/check_in_photos/' . $filename;

        // Simpan file ke storage
        Storage::disk('public')->put($path, $decodedImage); //

        // Simpan data absensi ke database
        Attendance::updateOrCreate(
            [
                'internship_id' => $internship->id,
                'date' => now()->toDateString(), //
            ],
            [
                'check_in_time' => now()->toTimeString(), //
                'check_in_photo' => $path, //
                'status' => $request->input('status'),
                'notes' => $request->input('notes'),
            ]
        );

        return redirect()->route('attendances.index')->with('toast', ['type' => 'success', 'message' => 'Absensi berhasil direkam!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Internship $internship) // <-- Perubahan utama: Menerima objek Internship
    {
        $internship->load('student');

        $student = $internship->student;

        if (!$student) {
            abort(404, 'Data siswa untuk magang ini tidak ditemukan.');
        }

        $attendances = $internship->attendances()
            ->orderBy('date', 'desc')
            ->get();

        return view('admin.attendance.show', compact('student', 'attendances'));
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'check_out_photo' => 'required', //
        ]);

        $imageData = $request->input('check_out_photo');
        list($type, $imageData) = explode(';', $imageData);
        list(, $imageData)      = explode(',', $imageData);
        $decodedImage = base64_decode($imageData);

        $filename = 'check_out_' . uniqid() . '.jpg';
        $path = 'attendances/check_out_photos/' . $filename;

        Storage::disk('public')->put($path, $decodedImage);

        $attendance->update([
            'check_out_time' => now()->toTimeString(), //
            'check_out_photo' => $path, //
        ]);

        return redirect()->route('attendances.index')->with('toast', ['type' => 'success', 'message' => 'Berhasil Check-out! Selamat beristirahat.']);
    }

    public function verify(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'verification_status' => 'required|in:pending,verified,rejected',
        ]);
        // Temukan kehadiran berdasarkan ID
        $attendance = Attendance::findOrFail($id);
        // Update status verifikasi
        $attendance->verification_status = $request->input('verification_status');
        $attendance->verified_by = auth()->user()->id; // Menyimpan ID pengguna yang melakukan verifikasi
        $attendance->verified_at = now(); // Menyimpan waktu verifikasi
        $attendance->save();
        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'Status verifikasi berhasil diperbarui.'
        ]);
    }

    public function print(Internship $internship) 
    {
        $internship->load('student', 'internshipGroup.teacher', 'internshipGroup.company');

        $student = $internship->student;

        if (!$student) {
            abort(404, 'Data siswa untuk magang ini tidak ditemukan.');
        }

        $attendances = $internship->attendances()
                                  ->orderBy('date', 'asc')
                                  ->get();

        return view('admin.attendance.print', compact(
            'student',
            'internship',
            'attendances'
        ));
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
