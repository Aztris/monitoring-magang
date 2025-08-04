<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CompanyImport;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::all()->sortByDesc('id');
        $title = 'Daftar Perusahaan';
        return view('admin.company.list', compact('companies', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'no_hp' => 'nullable|string|max:15',
            'nama_pimpinan' => 'nullable|string|max:100',
            'bidang_usaha' => 'nullable|string|max:100',
            'alamat' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'pic_nama' => 'nullable|string|max:100',
            'pic_phone' => 'nullable|string|max:15',
            'pic_email' => 'nullable|email',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        DB::transaction(function () use ($request, $validatedData) {
            $user = User::create([
                'nama' => $validatedData['nama'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['email']),
                'role' => 'company',
                'foto_profil' => $request->file('foto_profil') ? $request->file('foto_profil')->store('profile_photos/companies', 'public') : null,
            ]);

            $validatedData['user_id'] = $user->id;
            Company::create($validatedData);
        });

        return redirect()->route('companies.index')
            ->with('toast', [
                'type' => 'success',
                'message' => 'DUDIKA berhasil ditambahkan!'
            ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $company = Company::findOrFail($id);
        $validatedData = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $company->user_id,
            'no_hp' => 'nullable|string|max:15',
            'nama_pimpinan' => 'nullable|string|max:100',
            'bidang_usaha' => 'nullable|string|max:100',
            'alamat' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'pic_nama' => 'nullable|string|max:100',
            'pic_phone' => 'nullable|string|max:15',
            'pic_email' => 'nullable|email',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        DB::transaction(function () use ($request, $company, $validatedData) {
            $user = $company->user;
            $user->update([
                'nama' => $validatedData['nama'],
                'email' => $validatedData['email'],
                'foto_profil' => $request->file('foto_profil') ? $request->file('foto_profil')->store('profile_photos/companies', 'public') : $user->foto_profil,
            ]);
            $company->update($validatedData);
        });

        return redirect()->route('companies.index')
            ->with('toast', [
                'type' => 'success',
                'message' => 'DUDIKA berhasil diperbarui'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        DB::transaction(function () use ($company) {
            $company->user()->delete();
            $company->delete();
        });

        return redirect()->route('companies.index')->with('toast', [
            'type' => 'success',
            'message' => 'Perusahaan berhasil dihapus'
        ]);
    }

    /**
     * Menangani proses import data perusahaan dari file Excel.
     */
    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,csv']);

        $importer = new CompanyImport;
        try {
            DB::transaction(function () use ($importer, $request) {
                Excel::import($importer, $request->file('file'));
            });

            $rowCount = $importer->getRowCount();
            $message = $rowCount > 0 ? $rowCount . ' baris data perusahaan berhasil diimport!' : 'Tidak ada data baru yang diimport.';

            return redirect()->route('companies.index')->with('toast', ['type' => 'success', 'message' => $message]);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = "Baris " . $failure->row() . ": " . implode(', ', $failure->errors());
            }
            return redirect()->route('companies.index')->with('toast', ['type' => 'error', 'message' => 'Gagal mengimpor data. ' . implode(' | ', $errorMessages)]);
        } catch (\Exception $e) {
            return redirect()->route('companies.index')->with('toast', ['type' => 'error', 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Mengunduh file template Excel untuk import data perusahaan.
     */
    public function downloadTemplate(): BinaryFileResponse
    {
        $filePath = public_path('templates/template_perusahaan.xlsx');
        if (!file_exists($filePath)) {
            abort(404, 'File template tidak ditemukan.');
        }
        return response()->download($filePath);
    }
}
