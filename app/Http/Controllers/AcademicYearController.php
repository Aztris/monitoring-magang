<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use Illuminate\Http\Request;
use App\Http\Requests\StoreAcademicYearRequest;
use App\Http\Requests\UpdateAcademicYearRequest;

class AcademicYearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $selectedYear = $request->attributes->get('selected_academic_year');

        // Jika tidak ada tahun akademik yang dipilih
        if (!$selectedYear) {
            return redirect()->route('academic-years.index')
                ->with('error', 'Silakan pilih tahun akademik terlebih dahulu');
        }

        $academic_years = AcademicYear::all()->sortByDesc('end_date');
        $title = 'Daftar Tahun Ajaran';

        return view('admin.academic-year.list', compact('academic_years', 'title'));
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
    public function store(StoreAcademicYearRequest $request)
    {
        $data = $request->validated();

        // Handle active year logic
        if ($data['is_active'] ?? false) {
            AcademicYear::query()->update(['is_active' => false]);
        }

        AcademicYear::create($data);

        return redirect()->route('academic-years.index')
            ->with('toast', [
                'type' => 'success',
                'message' => 'Academic year created successfully'
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
    public function update(UpdateAcademicYearRequest $request, AcademicYear $academicYear)
    {
        $data = $request->validated();

        // Jika diaktifkan, nonaktifkan yang lain
        if ($data['is_active'] ?? false) {
            AcademicYear::where('id', '!=', $academicYear->id)
                ->update(['is_active' => false]);
        }

        $academicYear->update($data);

        return redirect()->route('academic-years.index')
            ->with('toast', [
                'type' => 'success',
                'message' => 'Academic year updated successfully'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcademicYear $academicYear)
    {
        try {
            // Cek apakah tahun akademik aktif
            if ($academicYear->is_active) {
                return redirect()->route('academic-years.index')
                    ->with('toast', [
                        'type' => 'error',
                        'message' => 'Tidak dapat menghapus tahun akademik aktif'
                    ]);
            }

            $academicYear->delete();

            return redirect()->route('academic-years.index')
                ->with('toast', [
                    'type' => 'success',
                    'message' => 'Tahun akademik berhasil dihapus'
                ]);
        } catch (\Exception $e) {
            return redirect()->route('academic-years.index')
                ->with('toast', [
                    'type' => 'error',
                    'message' => 'Gagal menghapus tahun akademik: ' . $e->getMessage()
                ]);
        }
    }

    public function select(Request $request, AcademicYear $academicYear)
    {
        // Simpan pilihan tahun akademik di session
        $request->session()->put('selected_academic_year_id', $academicYear->id);

        return back()->with('success', 'Tahun akademik berhasil dipilih');
    }

    public function setActive(AcademicYear $academicYear)
    {
        // Hanya admin yang bisa mengubah tahun akademik aktif
        $this->authorize('admin');

        AcademicYear::setActiveYear($academicYear->id);

        return back()->with('success', 'Tahun akademik aktif berhasil diubah');
    }
}
