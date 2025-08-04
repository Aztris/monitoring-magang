<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CompanyImport implements ToModel, WithHeadingRow, WithValidation
{
    private $importedRowCount = 0;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Proses ini akan dibungkus dalam transaction di dalam controller
        // untuk memastikan konsistensi data antara tabel User dan Company.
        $user = User::create([
            'nama'      => $row['nama'],
            'email'     => $row['email'],
            // Menggunakan email sebagai password default untuk kemudahan.
            // Pengguna perusahaan bisa menggunakan fitur "Forgot Password" nanti.
            'password'  => Hash::make($row['email']),
            'role'      => 'company',
        ]);

        $company = new Company([
            'user_id'       => $user->id,
            'nama'          => $row['nama'],
            'email'         => $row['email'],
            'no_hp'         => $row['no_hp'],
            'nama_pimpinan' => $row['nama_pimpinan'],
            'bidang_usaha'  => $row['bidang_usaha'],
            'alamat'        => $row['alamat'],
            'deskripsi'     => $row['deskripsi'],
            'pic_nama'      => $row['pic_nama'],
            'pic_phone'     => $row['pic_phone'],
            'pic_email'     => $row['pic_email'],
        ]);

        $this->importedRowCount++;

        return $company;
    }

    /**
     * Menambahkan validasi untuk setiap baris.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:255',
            // 'unique:users,email' memastikan email belum terdaftar untuk user manapun.
            'email' => 'required|email|unique:users,email',
            'no_hp' => 'nullable|string|max:15',
            'nama_pimpinan' => 'nullable|string|max:100',
            'bidang_usaha' => 'nullable|string|max:100',
            'alamat' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'pic_nama' => 'nullable|string|max:100',
            'pic_phone' => 'nullable|string|max:15',
            'pic_email' => 'nullable|email',
        ];
    }

    /**
     * Method untuk mendapatkan jumlah baris yang berhasil diimpor.
     *
     * @return int
     */
    public function getRowCount(): int
    {
        return $this->importedRowCount;
    }
}
