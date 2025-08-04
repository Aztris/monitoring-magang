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
use Illuminate\Validation\ValidationException;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::all()->sortByDesc('id');
        $classRooms = ClassRoom::all()->sortByDesc('grade_level');
        $departments = Department::all()->sortByDesc('kode');
        $title = 'Daftar Siswa';

        return view('admin.student.list', compact('students', 'title', 'classRooms', 'departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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

            $user = User::create([
                'nama' => $validatedData['nama'],
                'email' => $validatedData['email'],
                'password' => bcrypt($validatedData['nis']),
                'role' => 'student',
                'foto_profil' => $request->file('foto_profil') ? $request->file('foto_profil')->store('profile_photos/students', 'public') : null,
            ]);

            $studentData = [
                'user_id' => $user->id,
                'nama' => $validatedData['nama'],
                'nis' => $validatedData['nis'],
                'jenkel' => $validatedData['jenkel'],
                'tanggal_lahir' => $validatedData['tanggal_lahir'],
                'no_hp' => $validatedData['no_hp'],
                'alamat' => $validatedData['alamat'],
                'tempat_lahir' => $validatedData['tempat_lahir'],
                'nama_ayah' => $validatedData['nama_ayah'],
                'nama_ibu' => $validatedData['nama_ibu'],
                'no_hp_ortu' => $validatedData['no_hp_ortu'],
                'class_room_id' => $validatedData['class_room_id'],
                'department_id' => $validatedData['department_id'],
            ];

            Student::create($studentData);

            return redirect()->route('students.index')
                ->with('toast', [
                    'type' => 'success',
                    'message' => 'siswa berhasil ditambahkan'
                ]);
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('toast', [
                    'type' => 'error',
                    'message' => 'Data tidak valid. Silakan periksa kembali.'
                ]);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $request->user_id,
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

        $student = Student::findOrFail($id);
        $user = $student->user;

        $user->update([
            'nama' => $validatedData['nama'],
            'email' => $validatedData['email'],
            'foto_profil' => $request->file('foto_profil') ? $request->file('foto_profil')->store('profile_photos/students', 'public') : $user->foto_profil,
        ]);

        $student->update([
            'nis' => $validatedData['nis'],
            'nama' => $validatedData['nama'],
            'jenkel' => $validatedData['jenkel'],
            'tanggal_lahir' => $validatedData['tanggal_lahir'],
            'no_hp' => $validatedData['no_hp'],
            'alamat' => $validatedData['alamat'],
            'tempat_lahir' => $validatedData['tempat_lahir'],
            'nama_ayah' => $validatedData['nama_ayah'],
            'nama_ibu' => $validatedData['nama_ibu'],
            'no_hp_ortu' => $validatedData['no_hp_ortu'],
            'class_room_id' => $validatedData['class_room_id'],
            'department_id' => $validatedData['department_id'],
        ]);

        return redirect()->route('students.index')->with('toast', [
            'type' => 'success',
            'message' => 'Siswa berhasil diperbarui'
        ]);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $user = $student->user;

        DB::beginTransaction();
        try {
            $user->delete();
            DB::commit();
            return redirect()->route('students.index')->with('toast', [
                'type' => 'success',
                'message' => 'Siswa berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('toast', [
                'type' => 'error',
                'message' => 'Gagal menghapus Siswa'
            ]);
        }
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

}
