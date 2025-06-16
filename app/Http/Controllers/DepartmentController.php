<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;

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

}
