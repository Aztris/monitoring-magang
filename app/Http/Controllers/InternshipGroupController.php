<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Student;
use App\Models\Teacher;
use Barryvdh\DomPDF\PDF;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use App\Models\InternshipGroup;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreInternshipGroupRequest;
use App\Http\Requests\UpdateInternshipGroupRequest;

class InternshipGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $user = Auth::user();

        switch ($user->role) {
            case 'admin':
                return $this->adminIndex($request);
            case 'student':
                return redirect('/dashboard')->with(
                    'toast',
                    [
                        'type' => 'info',
                        'message' => 'User tidak memiliki akses ke halaman ini'
                    ]
                );
            case 'teacher':
                return $this->teacherIndex($request);
            case 'company':
                return $this->companyIndex($request);
            default:
                return redirect('/');
        }
    }

    private function adminIndex(Request $request)
    {
        $selectedYear = $request->attributes->get('selected_academic_year');

        if (!$selectedYear) {
            return redirect()->route('academic-years.index')
                ->with('toast', [
                    'type' => 'error',
                    'message' => 'Silakan pilih tahun akademik terlebih dahulu'
                ]);
        }

        $internshipGroups = InternshipGroup::with(['company', 'teacher', 'academicYear'])->where('academic_year_id', $selectedYear->id)->get();

        $companies = Company::all();
        $teachers = Teacher::all();
        $academicYears = AcademicYear::all();

        return view('admin.internship-group.list', [
            'internshipGroups' => $internshipGroups,
            'companies' => $companies,
            'teachers' => $teachers,
            'academicYears' => $academicYears,
            'title' => 'Internship Groups'
        ]);
    }

    private function teacherIndex(Request $request)
    {
        $selectedYear = $request->attributes->get('selected_academic_year');

        if (!$selectedYear) {
            return redirect()->route('academic-years.index')
                ->with('toast', [
                    'type' => 'error',
                    'message' => 'Silakan pilih tahun akademik terlebih dahulu'
                ]);
        }

        $teacherId = auth()->user()->teacher->id;

        $internshipGroups = InternshipGroup::with(['company', 'teacher', 'academicYear'])
            ->where('academic_year_id', $selectedYear->id)
            ->where('teacher_id', $teacherId)
            ->get();

        $companies = Company::all();
        $academicYears = AcademicYear::all();

        return view('teacher.internship-group.list', [
            'internshipGroups' => $internshipGroups,
            'companies' => $companies,
            'academicYears' => $academicYears,
            'title' => 'Internship Groups'
        ]);
    }

    private function companyIndex(Request $request)
    {
        $selectedYear = $request->attributes->get('selected_academic_year');

        if (!$selectedYear) {
            return redirect()->route('academic-years.index')
                ->with('toast', [
                    'type' => 'error',
                    'message' => 'Silakan pilih tahun akademik terlebih dahulu'
                ]);
        }

        $companyId = auth()->user()->company->id;

        $internshipGroups = InternshipGroup::with(['company', 'teacher', 'academicYear'])
            ->where('academic_year_id', $selectedYear->id)
            ->where('company_id', $companyId)
            ->get();

        $companies = Company::all();
        $academicYears = AcademicYear::all();

        return view('company.internship-group.list', [
            'internshipGroups' => $internshipGroups,
            'companies' => $companies,
            'academicYears' => $academicYears,
            'title' => 'Internship Groups'
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
    public function store(StoreInternshipGroupRequest $request)
    {
        DB::transaction(function () use ($request) {
            $validated = $request->validated();

            $newInternshipGroup = InternshipGroup::create($validated);
        });

        return redirect()->route('internship-groups.index')
            ->with('toast', [
                'type' => 'success',
                'message' => 'Kelompok Magang Baru berhasil dibuat!'
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id, Request $request)
    {
        $selectedYear = $request->attributes->get('selected_academic_year');

        $internshipGroup = InternshipGroup::with(['company', 'teacher', 'academicYear', 'internships.student'])->findOrFail($id);

        $allStudents = Student::whereDoesntHave('internships', function ($query) use ($selectedYear) {
            $query->whereHas('internshipGroup', function ($groupQuery) use ($selectedYear) {
                $groupQuery->where('academic_year_id', $selectedYear->id);
            });
        })
            ->orderBy('nama', 'asc')
            ->get();


        return view('admin.internship-group.show', compact('internshipGroup', 'allStudents'));
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
    public function update(UpdateInternshipGroupRequest $request, InternshipGroup $internshipGroup)
    {
        DB::transaction(function () use ($request, $internshipGroup) {
            $validated = $request->validated();
            // Memperbarui grup magang
            $internshipGroup->update($validated);
        });
        return redirect()->route('internship-groups.index')
            ->with('toast', [
                'type' => 'success',
                'message' => 'kelompok magang berhasil diperbarui!'
            ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InternshipGroup $internshipGroup)
    {
        DB::beginTransaction();
        try {
            $internshipGroup->delete();
            DB::commit();
            return redirect()->route('internship-groups.index')->with('toast', [
                'type' => 'success',
                'message' => 'Kelompok Magang berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('toast', [
                'type' => 'error',
                'message' => 'Gagal menghapus Kelompok Magang'
            ]);
        }
    }

    public function print(InternshipGroup $internshipGroup)
    {
        // Eager load semua relasi yang dibutuhkan untuk menghindari query N+1
        $internshipGroup->load([
            'company',
            'teacher',
            'academicYear',
            'internships.student.department'
        ]);

        // Kirim data ke view khusus untuk cetak
        return view('admin.internship-group.print', compact('internshipGroup'));
    }
}
