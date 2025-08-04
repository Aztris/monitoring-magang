<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DepartmentImport;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = Department::all()->sortByDesc('id');
        $title = 'Daftar Jurusan';

        return view('admin.department.list', compact('departments', 'title'));
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
    public function store(StoreDepartmentRequest $request)
    {
        $data = $request->validated();
        // Buat departemen baru
        Department::create($data);
        return redirect()->route('departments.index')
            ->with('toast', [
                'type' => 'success',
                'message' => 'Jurusan berhasil ditambahkan'
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
    public function update(UpdateDepartmentRequest $request, $id)
    {
        $data = $request->validated();
        // Temukan departemen berdasarkan ID
        $department = Department::findOrFail($id);
        // Update data departemen
        $department->update($data);
        return redirect()->route('departments.index')
            ->with('toast', [
                'type' => 'success',
                'message' => 'Jurusan berhasil diperbarui'
            ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        DB::beginTransaction();
        try {
            $department->delete();
            DB::commit();
            return redirect()->route('departments.index')->with('toast', [
                'type' => 'success',
                'message' => 'Jurusan berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('toast', [
                'type' => 'error',
                'message' => 'Gagal menghapus jurusan'
            ]);
        }
    }

    /**
     * Menangani proses import data jurusan dari file Excel.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv'
        ]);

        try {
            // 1. Buat instance dari importer
            $importer = new DepartmentImport;

            // 2. Lakukan import
            Excel::import($importer, $request->file('file'));

            // 3. Ambil jumlah baris yang berhasil diimpor
            $rowCount = $importer->getRowCount();

            // 4. Buat pesan sukses yang dinamis
            $message = $rowCount > 0 ? $rowCount . ' baris data jurusan berhasil diimport!' : 'Tidak ada data baru yang diimport.';

            return redirect()->route('departments.index')->with('toast', [
                'type' => 'success',
                'message' => $message
            ]);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = "Baris " . $failure->row() . ": " . implode(', ', $failure->errors());
            }
            return redirect()->route('departments.index')->with('toast', [
                'type' => 'error',
                'message' => 'Gagal mengimpor data. ' . implode(' | ', $errorMessages)
            ]);
        } catch (\Exception $e) {
            return redirect()->route('departments.index')->with('toast', [
                'type' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }


    /**
     * Mengunduh file template Excel untuk import data jurusan. (VERSI PERBAIKAN)
     */
    public function downloadTemplate(): BinaryFileResponse
    {
        // dd('Route Testing: Berhasil mencapai metode downloadTemplate.');

        $relativePath = 'templates' . DIRECTORY_SEPARATOR . 'template_jurusan.xlsx';
        $filePath = public_path($relativePath);

        // Langkah 1: Logging untuk Debugging
        // Ini akan mencatat path lengkap yang coba diakses oleh Laravel.
        Log::info('Mencoba mengunduh template dari path: ' . $filePath);

        // Langkah 2: Pengecekan file yang lebih andal
        if (!file_exists($filePath)) {
            // Jika file tidak ada, catat sebagai error.
            Log::error('File template TIDAK DITEMUKAN di path: ' . $filePath);
            abort(404, 'File template tidak ditemukan. Silakan periksa log aplikasi untuk detail path yang salah.');
        }

        // Langkah 3: Menentukan header secara eksplisit
        // Ini memberitahu browser secara paksa bahwa file ini adalah file Excel.
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];

        return response()->download($filePath, 'template_jurusan.xlsx', $headers);
    }
}
