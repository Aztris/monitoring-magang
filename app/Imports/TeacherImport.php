<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Teacher;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class TeacherImport implements ToModel, WithHeadingRow, WithValidation
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
        // untuk memastikan konsistensi data antara tabel User dan Teacher.
        $user = User::create([
            'nama'      => $row['nama'],
            'email'     => $row['email'],
            // Menggunakan NIP sebagai password default untuk kemudahan.
            'password'  => Hash::make($row['nip']),
            'role'      => 'teacher',
        ]);

        $teacher = new Teacher([
            'user_id'       => $user->id,
            'nama'          => $row['nama'],
            'nip'           => $row['nip'],
            'jenkel'        => $row['jenkel'],
            'tanggal_lahir' => $row['tanggal_lahir'],
            'no_hp'         => $row['no_hp'],
            'alamat'        => $row['alamat'],
            'mata_pelajaran'=> $row['mata_pelajaran'],
        ]);

        $this->importedRowCount++;

        return $teacher;
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
            // 'unique:teachers,nip' memastikan NIP unik.
            'nip' => 'required|string|max:20|unique:teachers,nip',
            'jenkel' => 'required|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'no_hp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
            'mata_pelajaran' => 'nullable|string',
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
