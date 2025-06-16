<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Internship;
use Illuminate\Http\Request;
use App\Models\InternshipGroup;
use App\Models\AssessmentCriteria;
use App\Models\InternshipAssessment;
use Illuminate\Support\Facades\Auth;

class InternshipAssessmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role === 'student' && $user->student?->internships->isNotEmpty()) {
            $activeInternship = $user->student->internships()
                ->whereHas('internshipGroup', fn($q) => $q->where('academic_year_id', session('selected_academic_year_id')))
                ->first();

            return $activeInternship
                ? redirect()->route('internship-assessments.show', $activeInternship)
                : redirect()->route('dashboard')->with('toast', ['type' => 'info', 'message' => 'Anda tidak memiliki data magang aktif.']);
        }

        $selectedYear = $request->attributes->get('selected_academic_year');
        if (!$selectedYear) {
            return redirect()->route('academic-years.index')->with('toast', ['type' => 'error', 'message' => 'Pilih tahun akademik.']);
        }

        $query = Internship::with(['student', 'internshipGroup.teacher', 'assessments'])
            ->whereHas('internshipGroup', fn($q) => $q->where('academic_year_id', $selectedYear->id));

        if ($user->role === 'teacher') {
            $query->whereHas('internshipGroup', fn($q) => $q->where('teacher_id', $user->teacher->id));
        } elseif ($user->role === 'company') {
            $query->whereHas('internshipGroup', fn($q) => $q->where('company_id', $user->company->id));
        }

        $internships = $query->get();
        $assessmentCriteria = AssessmentCriteria::all();

        return view('admin.internship-assessment.list', compact('internships', 'selectedYear', 'assessmentCriteria'));
    }

    public function studentIndex($user)
    {
        $student = Student::with(['internships.internshipGroup.teacher', 'internships.internshipGroup.company'])
            ->where('user_id', $user->id)
            ->firstOrFail();
        $internship = $student->internships->first();
        $assessmentCriteria = AssessmentCriteria::all();
        $assessments = $internship->assessments->keyBy('assessment_criteria_id');
        return view('admin.internship-assessment.show', compact('internship', 'assessmentCriteria', 'assessments'));
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
    public function store(Request $request)
    {
        $request->validate([
            'internship_id' => 'required|exists:internships,id',
            'assessment_criteria_id' => 'required|exists:assessment_criterias,id',
            'assessor_id' => 'required|exists:users,id',
            'nilai' => 'required|integer|min:0|max:100',
        ]);
        InternshipAssessment::create([
            'internship_id' => $request->internship_id,
            'assessment_criteria_id' => $request->assessment_criteria_id,
            'assessor_id' => Auth::id(),
            'nilai' => $request->nilai,
        ]);
        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'Nilai berhasil disimpan.'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($internshipId)
    {
        $user = Auth::user();

        $internship = Internship::with(['student', 'internshipGroup.teacher'])->findOrFail($internshipId);

        $assessmentCriteria = AssessmentCriteria::all();

        $assessments = $internship->assessments->keyBy('assessment_criteria_id');

        return view('admin.internship-assessment.show', compact('internship', 'assessmentCriteria', 'assessments'));
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
    public function update(Request $request, $id)
    {
        $request->validate([
            'nilai' => 'required|integer|min:0|max:100',
        ]);
        $assessment = InternshipAssessment::findOrFail($id);
        $assessment->update([
            'nilai' => $request->nilai,
        ]);
        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'Nilai berhasil diperbarui.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function print($internshipId)
    {
        $internship = Internship::with([
            'student',
            'internshipGroup.teacher',
            'internshipGroup.company'
        ])->findOrFail($internshipId);

        $assessmentCriteria = AssessmentCriteria::all();

        $internshipGroup = $internship->internshipGroup;
        $assessments = $internship->assessments->keyBy('assessment_criteria_id');

        return view('admin.internship-assessment.print', compact(
            'internship',
            'assessmentCriteria',
            'assessments',
            'internshipGroup'
        ));
    }


}
