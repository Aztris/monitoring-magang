<?php

namespace App\Imports;

use App\Models\Department;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class DepartmentImport implements ToModel, WithHeadingRow, WithValidation
{
    private $importedRowCount = 0;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Setiap kali model berhasil dibuat, tambahkan hitungan
        $this->importedRowCount++;

        return new Department([
            'nama'      => $row['nama'],
            'kode'      => $row['kode'],
            'deskripsi' => $row['deskripsi'],
        ]);
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
            // 'unique:departments,kode' memastikan kode jurusan belum ada di database
            'kode' => 'required|string|max:255|unique:departments,kode',
            'deskripsi' => 'nullable|string',
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
