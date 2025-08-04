<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Student;
use App\Models\ClassRoom;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Str;

class StudentImport implements ToModel, WithHeadingRow, WithValidation
{
    private $importedRowCount = 0;
    private $departmentMap;
    private $classRoomMap;

    /**
     * Constructor untuk mengambil dan memetakan data jurusan dan kelas.
     * Pencocokan sekarang dibuat case-insensitive.
     */
    public function __construct()
    {
        // Ambil semua jurusan dan petakan 'kode' (dalam huruf kecil) => 'id'
        $this->departmentMap = Department::all()->keyBy(function ($item) {
            return Str::lower($item->kode);
        })->map(function ($item) {
            return $item->id;
        });

        // Ambil semua kelas dan petakan 'nama' (dalam huruf kecil) => 'id'
        $this->classRoomMap = ClassRoom::all()->keyBy(function ($item) {
            return Str::lower($item->name);
        })->map(function ($item) {
            return $item->id;
        });
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Ubah input dari Excel menjadi huruf kecil sebelum dicocokkan
        $departmentCode = Str::lower($row['kode_jurusan']);
        $classRoomName = Str::lower($row['nama_kelas']);

        // Cari ID Jurusan dan Kelas dari peta yang sudah kita buat
        $departmentId = $this->departmentMap->get($departmentCode);
        $classRoomId = $this->classRoomMap->get($classRoomName);

        // Jika salah satu tidak ditemukan, lewati baris ini
        if (!$departmentId || !$classRoomId) {
            return null;
        }

        // Buat User baru
        $user = User::create([
            'nama'      => $row['nama'],
            'email'     => $row['email'],
            'password'  => Hash::make($row['nis']),
            'role'      => 'student',
        ]);

        // Buat data Student baru
        $student = new Student([
            'user_id'       => $user->id,
            'nis'           => $row['nis'],
            'nama'          => $row['nama'],
            'jenkel'        => $row['jenkel'],
            'agama'         => $row['agama'],
            'class_room_id' => $classRoomId,
            'department_id' => $departmentId,
            'no_hp'         => $row['no_hp'],
            'alamat'        => $row['alamat'],
            'tempat_lahir'  => $row['tempat_lahir'],
            'tanggal_lahir' => $row['tanggal_lahir'],
            'nama_ayah'     => $row['nama_ayah'],
            'nama_ibu'      => $row['nama_ibu'],
            'no_hp_ortu'    => $row['no_hp_ortu'],
        ]);

        $this->importedRowCount++;

        return $student;
    }

    /**
     * Menambahkan validasi untuk setiap baris.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'nis' => 'required|string|max:20|unique:students,nis',
            'jenkel' => 'required|in:L,P',
            // Validasi sekarang case-insensitive
            'nama_kelas' => ['required', 'string', function ($attribute, $value, $fail) {
                if (!$this->classRoomMap->has(Str::lower($value))) {
                    $fail('Nama kelas ' . $value . ' tidak ditemukan.');
                }
            }],
            'kode_jurusan' => ['required', 'string', function ($attribute, $value, $fail) {
                if (!$this->departmentMap->has(Str::lower($value))) {
                    $fail('Kode jurusan ' . $value . ' tidak ditemukan.');
                }
            }],
            'agama' => 'nullable|string',
            'no_hp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
            'tempat_lahir' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'nama_ayah' => 'nullable|string',
            'nama_ibu' => 'nullable|string',
            'no_hp_ortu' => 'nullable|string|max:15',
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
