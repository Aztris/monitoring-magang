<?php

namespace App\Http\Controllers;

use App\Models\Internship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InternshipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        // Validate the incoming request
        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
            'internship_group_id' => 'required|exists:internship_groups,id',
        ]);

        // Loop through each selected student ID and create an internship record
        foreach ($request->student_ids as $studentId) {
            Internship::create([
                'student_id' => $studentId,
                'internship_group_id' => $request->internship_group_id,
                // You can add other fields here if necessary
            ]);
        }

        // Redirect back with a success message
        return redirect()->route('internship-groups.show', $request->internship_group_id)
            ->with('toast', [
                'type' => 'success',
                'message' => 'Siswa berhasil ditambahkan ke kelompok magang.'
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
        // Validate the incoming request
        $request->validate([
            'position' => 'required|string|max:255', // Validate the position input
        ]);

        // Find the internship record
        $internship = Internship::findOrFail($id);

        // Update the position
        $internship->update([
            'posisi' => $request->position,
        ]);

        // Redirect back with a success message
        return redirect()->route('internship-groups.show', $internship->internship_group_id)
            ->with('toast', [
                'type' => 'success',
                'message' => 'posisi siswa berhasil diperbarui.'
            ]);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Internship $internship)
    {
        // Simpan ID grup magang sebelum menghapus internship
        // Asumsi: Model Internship memiliki kolom 'internship_group_id'
        // atau relasi 'internshipGroup' yang bisa diakses.
        $internshipGroupId = $internship->internship_group_id;

        // Jika Anda menggunakan relasi, contohnya:
        // $internshipGroupId = $internship->internshipGroup->id;
        // Pastikan relasi 'internshipGroup' sudah didefinisikan di model Internship

        DB::beginTransaction();
        try {
            $internship->delete();
            DB::commit();

            // Redirect ke halaman show dari internship-groups dengan ID yang sesuai
            return redirect()->route('internship-groups.show', $internshipGroupId)->with('toast', [
                'type' => 'success',
                'message' => 'Data magang berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menghapus data magang: ' . $e->getMessage()); // Log error untuk debugging
            return redirect()->back()->with('toast', [
                'type' => 'error',
                'message' => 'Gagal menghapus data magang. Silakan coba lagi.'
            ]);
        }
    }
}
