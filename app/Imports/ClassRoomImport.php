<?php

namespace App\Imports;

use App\Models\ClassRoom;
use App\Models\Department;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ClassRoomImport implements ToModel, WithHeadingRow, WithValidation
{
    private $importedRowCount = 0;
    private $departmentMap;

    /**
     * Constructor untuk mengambil dan memetakan data jurusan.
     * Ini lebih efisien daripada query berulang di dalam loop.
     */
    public function __construct()
    {
        // Ambil semua jurusan dan petakan 'kode' => 'id'
        $this->departmentMap = Department::pluck('id', 'kode');
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Ambil kode jurusan dari baris Excel
        $departmentCode = $row['kode_jurusan'];

        // Cari ID jurusan dari peta yang sudah kita buat
        $departmentId = $this->departmentMap->get($departmentCode);

        // Jika ID jurusan tidak ditemukan, lewati baris ini
        if (!$departmentId) {
            return null;
        }

        // Setiap kali model berhasil dibuat, tambahkan hitungan
        $this->importedRowCount++;

        return new ClassRoom([
            'name'          => $row['nama_kelas'],
            'grade_level'   => $row['tingkat_kelas'],
            'department_id' => $departmentId, // Gunakan ID yang sudah ditemukan
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
            'nama_kelas' => 'required|string|max:255',
            'tingkat_kelas' => 'required|string|max:255',
            // 'exists:departments,kode' memastikan kode jurusan yang diinput ada di database
            'kode_jurusan' => 'required|string|exists:departments,kode',
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
