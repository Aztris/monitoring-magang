<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Department;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreClassRoomRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ClassRoomImport;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ClassRoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil semua ruang kelas beserta relasi departemen dan tahun akademik
        $classRooms = ClassRoom::with(['department'])->get()->sortByDesc('id');

        // Mengambil semua departemen dan tahun akademik untuk dropdown
        $departments = Department::all();
        $academicYears = AcademicYear::all();

        // Debugging: Cek data yang diambil
        // dd($departments, $academicYears);

        // Mengembalikan view dengan data yang diperlukan
        return view('admin.class-room.list', compact('classRooms', 'departments', 'academicYears'));
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
    public function store(StoreClassRoomRequest $request)
    {
        DB::transaction(function () use ($request) {
            $validated = $request->validated();

            $newClassRoom = ClassRoom::create($validated);
        });
        return redirect()->route('class-rooms.index')
            ->with('toast', [
                'type' => 'success',
                'message' => 'Kelas berhasil dibuat!'
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
    public function update(StoreClassRoomRequest $request, ClassRoom $classRoom)
    {
        DB::transaction(function () use ($request, $classRoom) {
            $validated = $request->validated();

            $classRoom->update($validated);
        });
        return redirect()->route('class-rooms.index')
            ->with('toast', [
                'type' => 'success',
                'message' => 'Kelas berhasil diperbarui!'
            ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClassRoom $classRoom)
    {
        DB::transaction(function () use ($classRoom) {
            // Menghapus ruang kelas
            $classRoom->delete();
        });
        // Menggunakan session untuk menampilkan toast
        return redirect()->route('class-rooms.index')
            ->with('toast', [
                'type' => 'success',
                'message' => 'Kelas berhasil dihapus!'
            ]);
    }

    /**
     * Menangani proses import data kelas dari file Excel.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv'
        ]);

        try {
            $importer = new ClassRoomImport;
            Excel::import($importer, $request->file('file'));
            $rowCount = $importer->getRowCount();
            $message = $rowCount > 0 ? $rowCount . ' baris data kelas berhasil diimport!' : 'Tidak ada data baru yang diimport.';

            return redirect()->route('class-rooms.index')->with('toast', [
                'type' => 'success',
                'message' => $message
            ]);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = "Baris " . $failure->row() . ": " . implode(', ', $failure->errors());
            }
            return redirect()->route('class-rooms.index')->with('toast', [
                'type' => 'error',
                'message' => 'Gagal mengimpor data. ' . implode(' | ', $errorMessages)
            ]);
        } catch (\Exception $e) {
            return redirect()->route('class-rooms.index')->with('toast', [
                'type' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Mengunduh file template Excel untuk import data kelas.
     */
    public function downloadTemplate(): BinaryFileResponse
    {
        $filePath = public_path('templates/template_kelas.xlsx');

        if (!file_exists($filePath)) {
            abort(404, 'File template tidak ditemukan.');
        }

        return response()->download($filePath);
    }
}
