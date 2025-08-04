<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TeacherImport;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teachers = Teacher::with('user')->get()->sortByDesc('id');
        $title = 'Daftar Guru';
        return view('admin.teacher.list', compact('teachers', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'nip' => 'required|string|max:20|unique:teachers,nip',
            'jenkel' => 'required|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'no_hp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
            'mata_pelajaran' => 'nullable|string',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        DB::transaction(function () use ($request, $validatedData) {
            $user = User::create([
                'nama' => $validatedData['nama'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['nip']),
                'role' => 'teacher',
                'foto_profil' => $request->file('foto_profil') ? $request->file('foto_profil')->store('profile_photos/teachers', 'public') : null,
            ]);

            $validatedData['user_id'] = $user->id;
            Teacher::create($validatedData);
        });

        return redirect()->route('teachers.index')
            ->with('toast', [
                'type' => 'success',
                'message' => 'Guru berhasil ditambahkan!'
            ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);
        $validatedData = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $teacher->user_id,
            'nip' => 'required|string|max:20|unique:teachers,nip,' . $id,
            'jenkel' => 'required|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'no_hp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
            'mata_pelajaran' => 'nullable|string',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        DB::transaction(function () use ($request, $teacher, $validatedData) {
            $teacher->user->update([
                'nama' => $validatedData['nama'],
                'email' => $validatedData['email'],
                'foto_profil' => $request->file('foto_profil') ? $request->file('foto_profil')->store('profile_photos/teachers', 'public') : $teacher->user->foto_profil,
            ]);
            $teacher->update($validatedData);
        });

        return redirect()->route('teachers.index')
            ->with('toast', [
                'type' => 'success',
                'message' => 'Guru berhasil diperbarui'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher)
    {
        DB::transaction(function () use ($teacher) {
            $teacher->user()->delete();
            $teacher->delete();
        });

        return redirect()->route('teachers.index')->with('toast', [
            'type' => 'success',
            'message' => 'Guru berhasil dihapus'
        ]);
    }

    /**
     * Menangani proses import data guru dari file Excel.
     */
    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,csv']);

        $importer = new TeacherImport;
        try {
            DB::transaction(function () use ($importer, $request) {
                Excel::import($importer, $request->file('file'));
            });

            $rowCount = $importer->getRowCount();
            $message = $rowCount > 0 ? $rowCount . ' baris data guru berhasil diimport!' : 'Tidak ada data baru yang diimport.';

            return redirect()->route('teachers.index')->with('toast', ['type' => 'success', 'message' => $message]);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = "Baris " . $failure->row() . ": " . implode(', ', $failure->errors());
            }
            return redirect()->route('teachers.index')->with('toast', ['type' => 'error', 'message' => 'Gagal mengimpor data. ' . implode(' | ', $errorMessages)]);
        } catch (\Exception $e) {
            return redirect()->route('teachers.index')->with('toast', ['type' => 'error', 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Mengunduh file template Excel untuk import data guru.
     */
    public function downloadTemplate(): BinaryFileResponse
    {
        $filePath = public_path('templates/template_guru.xlsx');
        if (!file_exists($filePath)) {
            abort(404, 'File template tidak ditemukan.');
        }
        return response()->download($filePath);
    }
}
