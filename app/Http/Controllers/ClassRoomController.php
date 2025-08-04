<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Department;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreClassRoomRequest;

class ClassRoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil semua ruang kelas beserta relasi departemen dan tahun akademik
        $classRooms = ClassRoom::with(['department'])->get();

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
}
