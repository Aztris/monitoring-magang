<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssessmentCriteria;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreAssessmentCriteriaRequest;
use App\Http\Requests\UpdateAssessmentCriteriaRequest;

class AssessmentCriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $criterias = AssessmentCriteria::all()->sortByDesc('id');
        $title = 'Daftar Kriteria Penilaian Magang';

        return view('admin.assessment-criteria.list', compact('criterias', 'title'));
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
    public function store(StoreAssessmentCriteriaRequest $request)
    {
        DB::transaction(function () use ($request) {
            $validated = $request->validated();
            AssessmentCriteria::create($validated);
        });
        return redirect()->route('assessment-criteria.index')
            ->with('toast', [
                'type' => 'success',
                'message' => 'Kriteria penilaian berhasil ditambahkan.'
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

    public function update(Request $request, AssessmentCriteria $assessmentCriteria)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        try {
            // Ambil ID dari request
            $id = $request->input('id');

            // Temukan kriteria berdasarkan ID
            $criteria = AssessmentCriteria::findOrFail($id);

            // Update data
            $criteria->update([
                'nama' => $request->input('nama'),
                'deskripsi' => $request->input('deskripsi'),
            ]);

            return redirect()->route('assessment-criteria.index')
                ->with('toast', [
                    'type' => 'success',
                    'message' => 'Kriteria penilaian berhasil diperbarui.'
                ]);
        } catch (\Exception $e) {
            return redirect()->route('assessment-criteria.index')
                ->with('toast', [
                    'type' => 'error',
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ]);
        }
    }

    // public function update(UpdateAssessmentCriteriaRequest $request, AssessmentCriteria $assessmentCriteria)
    // {
    //     $data = $request->validated();

    //     $assessmentCriteria->update($data);

    //     return redirect()->route('assessment-criteria.index')
    //         ->with('toast', [
    //             'type' => 'success',
    //             'message' => 'Kriteria penilaian berhasil diperbarui.'
    //         ]);
    // }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // Validasi input
        $request->validate([
            'id' => 'required|exists:assessment_criterias,id',
        ]);

        try {
            // Ambil ID dari request
            $id = $request->input('id');

            // Temukan kriteria berdasarkan ID
            $criteria = AssessmentCriteria::findOrFail($id);

            // Hapus data
            $criteria->delete();

            return redirect()->route('assessment-criteria.index')
                ->with('toast', [
                    'type' => 'success',
                    'message' => 'Kriteria penilaian berhasil dihapus.'
                ]);
        } catch (\Exception $e) {
            return redirect()->route('assessment-criteria.index')
                ->with('toast', [
                    'type' => 'error',
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ]);
        }
    }
}
