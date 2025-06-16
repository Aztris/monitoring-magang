<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreTeacherRequest;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teachers = Teacher::all()->sortByDesc('id');
        $title = 'Daftar Guru';

        return view('admin.teacher.list', compact('teachers', 'title'));
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
        // Validasi data yang diterima
        $validatedData = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'nip' => 'required|string|max:20|unique:teachers,nip',
            'jenkel' => 'required|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'no_hp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
            'mata_pelajaran' => 'nullable|string',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Maksimal 2MB
        ]);

        // Buat user baru
        $user = User::create([
            'nama' => $validatedData['nama'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['nip']), // NIP sebagai password
            'role' => 'teacher',
            'foto_profil' => $request->file('foto_profil') ? $request->file('foto_profil')->store('profile_photos/teachers', 'public') : null,
        ]);

        // Simpan data guru
        $teacherData = [
            'user_id' => $user->id,
            'nama' => $validatedData['nama'],
            'nip' => $validatedData['nip'],
            'jenkel' => $validatedData['jenkel'],
            'tanggal_lahir' => $validatedData['tanggal_lahir'],
            'no_hp' => $validatedData['no_hp'],
            'alamat' => $validatedData['alamat'],
            'mata_pelajaran' => $validatedData['mata_pelajaran'],
        ];

        Teacher::create($teacherData);

        return redirect()->route('teachers.index')
            ->with('toast', [
                'type' => 'success',
                'message' => 'Teacher created successfully'
            ]);
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
        // Validasi data yang diterima
        $validatedData = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $request->user_id, // Pastikan email unik, kecuali untuk user yang sama
            'nip' => 'required|string|max:20|unique:teachers,nip,' . $id, // Pastikan NIP unik, kecuali untuk guru yang sama
            'jenkel' => 'required|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'no_hp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
            'mata_pelajaran' => 'nullable|string',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Maksimal 2MB
        ]);

        // Temukan guru berdasarkan ID
        $teacher = Teacher::findOrFail($id);
        if ($teacher) {
            $user = $teacher->user; // Ambil data user terkait

            // Update data user
            $user->update([
                'nama' => $validatedData['nama'],
                'email' => $validatedData['email'],
                'foto_profil' => $request->file('foto_profil') ? $request->file('foto_profil')->store('profile_photos/teachers', 'public') : $user->foto_profil,
                // Username bisa tetap sama atau diubah sesuai kebutuhan
            ]);

            // Update data guru
            $teacherData = [
                'nip' => $validatedData['nip'],
                'jenkel' => $validatedData['jenkel'],
                'tanggal_lahir' => $validatedData['tanggal_lahir'],
                'no_hp' => $validatedData['no_hp'],
                'alamat' => $validatedData['alamat'],
                'mata_pelajaran' => $validatedData['mata_pelajaran'],
            ];

            $teacher->update($teacherData);

            return redirect()->route('teachers.index')
                ->with('toast', [
                    'type' => 'success',
                    'message' => 'Teacher updated successfully'
                ]);
        } else {
            return redirect()->route('teachers.index')
                ->with('toast', [
                    'type' => 'error',
                    'message' => 'Teacher not found'
                ]);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher)
    {
        DB::beginTransaction();
        try {
            $teacher->delete();
            DB::commit();
            return redirect()->route('teachers.index')->with('toast', [
                'type' => 'success',
                'message' => 'Guru berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('toast', [
                'type' => 'error',
                'message' => 'Gagal menghapus Guru'
            ]);
        }
    }
}
