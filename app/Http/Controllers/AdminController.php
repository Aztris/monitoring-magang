<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua pengguna dengan role 'admin'
        $admins = Admin::all()->sortByDesc('id');
        return view('admin.admin.list', [
            'title' => 'List Admins',
            'admins' => $admins,
        ]);
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
            'password' => 'required|string|min:6',
            'jenkel' => 'required|in:L,P',
            'no_hp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Gunakan DB Transaction
        DB::beginTransaction();
        try {
            // 1. Buat user baru
            $user = User::create([
                'nama' => $validatedData['nama'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'role' => 'admin',
                'foto_profil' => $request->file('foto_profil') ? $request->file('foto_profil')->store('profile_photos/admins', 'public') : null,
            ]);

            // 2. Simpan data admin
            Admin::create([
                'user_id' => $user->id,
                'jenkel' => $validatedData['jenkel'],
                'no_hp' => $validatedData['no_hp'],
                'alamat' => $validatedData['alamat'],
            ]);

            // Jika semua berhasil, commit transaksinya
            DB::commit();

            return redirect()->route('admins.index')->with('toast', [
                'type' => 'success',
                'message' => 'Admin berhasil dibuat!'
            ]);
        } catch (\Exception $e) {
            // Jika ada error, batalkan semua query
            DB::rollBack();

            // Opsional: catat error untuk debugging
            Log::error('Gagal membuat admin baru: ' . $e->getMessage());

            return redirect()->back()->withInput()->with('toast', [
                'type' => 'error',
                'message' => 'Terjadi kesalahan, admin gagal dibuat.'
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
        // Validasi data yang diterima
        $validatedData = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $request->user_id,
            'jenkel' => 'required|in:L,P',
            'no_hp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Maksimal 2MB
        ]);
        // Temukan admin berdasarkan ID
        $admin = Admin::findOrFail($id);
        // Update data pengguna
        $user = User::findOrFail($admin->user_id);
        $user->update([
            'nama' => $validatedData['nama'],
            'email' => $validatedData['email'],
            'foto_profil' => $request->file('foto_profil') ? $request->file('foto_profil')->store('profile_photos/admins', 'public') : $user->foto_profil,
            // Password tidak diupdate, jika ingin mengupdate password, tambahkan logika di sini
        ]);

        // Update data admin
        $admin->update([
            'jenkel' => $validatedData['jenkel'],
            'no_hp' => $validatedData['no_hp'],
            'alamat' => $validatedData['alamat'],
        ]);
        return redirect()->route('admins.index')
            ->with('toast', [
                'type' => 'success',
                'message' => 'Admin Berhasil diperbarui'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin)
    {
        $user = $admin->user;
        DB::beginTransaction();
        try {
            $user->delete();
            DB::commit();
            return redirect()->route('admins.index')->with('toast', [
                'type' => 'success',
                'message' => 'Admin berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('toast', [
                'type' => 'error',
                'message' => 'Gagal menghapus Admin'
            ]);
        }
    }
}
