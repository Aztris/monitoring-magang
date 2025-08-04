<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\ClassRoom;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\Internship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentImport;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::with(['user', 'classRoom', 'department'])->get()->sortByDesc('id');
        $classRooms = ClassRoom::all()->sortByDesc('grade_level');
        $departments = Department::all()->sortByDesc('kode');
        $title = 'Daftar Siswa';
        return view('admin.student.list', compact('students', 'title', 'classRooms', 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nama' => 'required|string|max:100',
                'email' => 'required|email|unique:users,email',
                'nis' => 'required|string|max:20|unique:students,nis',
                'jenkel' => 'required|in:L,P',
                'tanggal_lahir' => 'nullable|date',
                'no_hp' => 'nullable|string|max:15',
                'alamat' => 'nullable|string',
                'tempat_lahir' => 'nullable|string|max:100',
                'nama_ayah' => 'nullable|string|max:100',
                'nama_ibu' => 'nullable|string|max:100',
                'no_hp_ortu' => 'nullable|string|max:15',
                'class_room_id' => 'required|exists:class_rooms,id',
                'department_id' => 'required|exists:departments,id',
                'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            DB::transaction(function () use ($request, $validatedData) {
                $user = User::create([
                    'nama' => $validatedData['nama'],
                    'email' => $validatedData['email'],
                    'password' => Hash::make($validatedData['nis']),
                    'role' => 'student',
                    'foto_profil' => $request->file('foto_profil') ? $request->file('foto_profil')->store('profile_photos/students', 'public') : null,
                ]);
                $validatedData['user_id'] = $user->id;
                Student::create($validatedData);
            });

            return redirect()->route('students.index')->with('toast', ['type' => 'success', 'message' => 'Siswa berhasil ditambahkan']);
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput()->with('toast', ['type' => 'error', 'message' => 'Data tidak valid. Silakan periksa kembali.']);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $validatedData = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $student->user_id,
            'nis' => 'required|string|max:20|unique:students,nis,' . $id,
            'jenkel' => 'required|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'no_hp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
            'tempat_lahir' => 'nullable|string|max:100',
            'nama_ayah' => 'nullable|string|max:100',
            'nama_ibu' => 'nullable|string|max:100',
            'no_hp_ortu' => 'nullable|string|max:15',
            'class_room_id' => 'required|exists:class_rooms,id',
            'department_id' => 'required|exists:departments,id',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        DB::transaction(function () use ($request, $student, $validatedData) {
            $student->user->update([
                'nama' => $validatedData['nama'],
                'email' => $validatedData['email'],
                'foto_profil' => $request->file('foto_profil') ? $request->file('foto_profil')->store('profile_photos/students', 'public') : $student->user->foto_profil,
            ]);
            $student->update($validatedData);
        });

        return redirect()->route('students.index')->with('toast', ['type' => 'success', 'message' => 'Siswa berhasil diperbarui']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        DB::transaction(function () use ($student) {
            $student->user()->delete();
            $student->delete();
        });
        return redirect()->route('students.index')->with('toast', ['type' => 'success', 'message' => 'Siswa berhasil dihapus']);
    }

    public function showAttendances(Student $student)
    {
        $selectedYearId = session('selected_academic_year_id');

        $internship = Internship::where('student_id', $student->id)
            ->whereHas('internshipGroup', function ($query) use ($selectedYearId) {
                $query->where('academic_year_id', $selectedYearId);
            })
            ->first();

        $attendances = collect();
        if ($internship) {
            $attendances = Attendance::where('internship_id', $internship->id)
                ->orderBy('date', 'desc')
                ->get();
        }

        return view('admin.attendance.show', compact('student', 'attendances', 'internship'));
    }

    /**
     * Menangani proses import data siswa dari file Excel.
     */
    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,csv']);
        $importer = new StudentImport;
        try {
            DB::transaction(function () use ($importer, $request) {
                Excel::import($importer, $request->file('file'));
            });

            $rowCount = $importer->getRowCount();

            // Logika baru untuk notifikasi
            if ($rowCount > 0) {
                $message = $rowCount . ' baris data siswa berhasil diimport!';
                $type = 'success';
            } else {
                $message = 'Tidak ada data baru yang diimport. Pastikan tidak ada data duplikat di dalam file.';
                $type = 'info'; // Menggunakan 'info' untuk notifikasi netral
            }

            return redirect()->route('students.index')->with('toast', ['type' => $type, 'message' => $message]);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = "Baris " . $failure->row() . ": " . implode(', ', $failure->errors());
            }
            return redirect()->route('students.index')->with('toast', ['type' => 'error', 'message' => 'Gagal mengimpor data. ' . implode(' | ', $errorMessages)]);
        } catch (\Exception $e) {
            return redirect()->route('students.index')->with('toast', ['type' => 'error', 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Mengunduh file template Excel untuk import data siswa.
     */
    public function downloadTemplate(): BinaryFileResponse
    {
        $filePath = public_path('templates/template_siswa.xlsx');
        if (!file_exists($filePath)) {
            abort(404, 'File template tidak ditemukan.');
        }
        return response()->download($filePath);
    }
}
