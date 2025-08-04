<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'no_hp' => 'nullable|string|max:15',
            'nama_pimpinan' => 'nullable|string|max:100',
            'bidang_usaha' => 'nullable|string|max:100',
            'alamat' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'pic_nama' => 'nullable|string|max:100',
            'pic_phone' => 'nullable|string|max:15',
            'pic_email' => 'nullable|email',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Maksimal 2MB
        ]);

        // Buat user baru
        $user = User::create([
            'nama' => $validatedData['nama'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['email']), // Menggunakan password acak
            'role' => 'company',
            'foto_profil' => $request->file('foto_profil') ? $request->file('foto_profil')->store('profile_photos/companies', 'public') : null,
        ]);

        // Simpan data perusahaan
        $companyData = [
            'user_id' => $user->id,
            'nama' => $validatedData['nama'],
            'email' => $validatedData['email'],
            'no_hp' => $validatedData['no_hp'],
            'nama_pimpinan' => $validatedData['nama_pimpinan'],
            'bidang_usaha' => $validatedData['bidang_usaha'],
            'alamat' => $validatedData['alamat'],
            'deskripsi' => $validatedData['deskripsi'],
            'pic_nama' => $validatedData['pic_nama'],
            'pic_phone' => $validatedData['pic_phone'],
            'pic_email' => $validatedData['pic_email'],
        ];

        Company::create($companyData);

        return redirect()->route('companies.index')
            ->with('toast', [
                'type' => 'success',
                'message' => 'DUDIKA berhasil ditambahkan!'
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
            'email' => 'required|email|unique:users,email,' . $request->user_id,
            'no_hp' => 'nullable|string|max:15',
            'nama_pimpinan' => 'nullable|string|max:100',
            'bidang_usaha' => 'nullable|string|max:100',
            'alamat' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'pic_nama' => 'nullable|string|max:100',
            'pic_phone' => 'nullable|string|max:15',
            'pic_email' => 'nullable|email',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Maksimal 2MB
        ]);

        // Temukan perusahaan berdasarkan ID
        $company = Company::findOrFail($id);

        // Update data pengguna
        $user = User::findOrFail($company->user_id);
        $user->update([
            'nama' => $validatedData['nama'],
            'email' => $validatedData['email'],
            'foto_profil' => $request->file('foto_profil') ? $request->file('foto_profil')->store('profile_photos/companies', 'public') : $user->foto_profil,
            // Password tidak diupdate, jika ingin mengupdate password, tambahkan logika di sini
        ]);

        // Update data perusahaan
        $company->update([
            'nama' => $validatedData['nama'],
            'email' => $validatedData['email'],
            'no_hp' => $validatedData['no_hp'],
            'nama_pimpinan' => $validatedData['nama_pimpinan'],
            'bidang_usaha' => $validatedData['bidang_usaha'],
            'alamat' => $validatedData['alamat'],
            'deskripsi' => $validatedData['deskripsi'],
            'pic_nama' => $validatedData['pic_nama'],
            'pic_phone' => $validatedData['pic_phone'],
            'pic_email' => $validatedData['pic_email'],
        ]);

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
        $user = $company->user;
        DB::beginTransaction();
        try {
            $user->delete();
            DB::commit();
            return redirect()->route('companies.index')->with('toast', [
                'type' => 'success',
                'message' => 'Perusahaan berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('toast', [
                'type' => 'error',
                'message' => 'Gagal menghapus Perusahaan'
            ]);
        }
    }
}
