<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Activity;
use App\Models\Internship;
use Illuminate\Http\Request;
use App\Models\InternshipGroup;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreActivityRequest;
use App\Http\Requests\UpdateActivityRequest;

class ActivityController extends Controller
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
                return $this->studentIndex($user);
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

        $internshipGroups = InternshipGroup::with(['academicYear'])
            ->where('academic_year_id', $selectedYear->id)
            ->get();

        $internships = Internship::with(['student'])
            ->whereIn('internship_group_id', $internshipGroups->pluck('id'))
            ->get();

        $students = $internships->pluck('student')->unique('id');

        return view('admin.activity.list', compact('students'));
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

        $internshipGroups = InternshipGroup::with(['academicYear'])
            ->where('academic_year_id', $selectedYear->id)
            ->where('teacher_id', $teacherId)
            ->get();

        $internships = Internship::with(['student'])
            ->whereIn('internship_group_id', $internshipGroups->pluck('id'))
            ->get();

        $students = $internships->pluck('student')->unique('id');

        return view('teacher.activity.list', compact('students', 'selectedYear'));
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

        $internshipGroups = InternshipGroup::with(['academicYear'])
            ->where('academic_year_id', $selectedYear->id)
            ->where('company_id', $companyId)
            ->get();

        $internships = Internship::with(['student'])
            ->whereIn('internship_group_id', $internshipGroups->pluck('id'))
            ->get();

        $students = $internships->pluck('student')->unique('id');

        return view('company.activity.list', compact('students', 'selectedYear'));
    }

    private function studentIndex($user)
    {
        $selectedYearId = session('selected_academic_year_id');

        $internship = Internship::where('student_id', $user->student->id)
            ->whereHas('internshipGroup', function ($query) use ($selectedYearId) {
                $query->where('academic_year_id', $selectedYearId);
            })
            ->first();

        if ($internship) {
            $activities = Activity::where('internship_id', $internship->id)
                ->orderBy('date', 'desc')
                ->get();
        }
        return view('student.activity.list', [
            'title' => 'Aktivitas Harian',
            'activities' => $activities,
            'internship' => $internship, // <-- Kirim data internship ke view
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
    // app/Http/Controllers/ActivityController.php

    public function store(StoreActivityRequest $request)
    {
        $user = Auth::user();
        $selectedYearId = session('selected_academic_year_id');

        if (!$user->student || !$selectedYearId) {
            return back()->with('toast', ['type' => 'error', 'message' => 'Data siswa atau tahun akademik tidak ditemukan.']);
        }

        $internship = Internship::where('student_id', $user->student->id)
            ->whereHas('internshipGroup', function ($query) use ($selectedYearId) {
                $query->where('academic_year_id', $selectedYearId);
            })
            ->first();

        if (!$internship) {
            return back()->with('toast', ['type' => 'error', 'message' => 'Anda tidak memiliki data magang aktif.']);
        }

        $data = $request->validated();

        $data['internship_id'] = $internship->id;

        if ($request->hasFile('activity_photo')) {
            $path = $request->file('activity_photo')->store('activities', 'public');
            $data['activity_photo'] = $path;
        }

        Activity::create($data);

        return redirect()->route('activities.index')->with('toast', [
            'type' => 'success',
            'message' => 'Aktivitas berhasil ditambahkan.'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($studentId)
    {
        $student = Student::findOrFail($studentId);
        $internships = $student->internships;
        $activities = Activity::whereIn('internship_id', $internships->pluck('id'))->get();

        return view('admin.activity.show', compact('student', 'activities'));
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
    public function update(UpdateActivityRequest $request, Activity $activity)
    {
        // Simpan foto jika ada
        if ($request->hasFile('activity_photo')) {
            // Hapus foto lama jika ada
            if ($activity->activity_photo) {
                Storage::disk('public')->delete($activity->activity_photo);
            }
            $activityPhotoPath = $request->file('activity_photo')->store('activities', 'public');
            $activity->activity_photo = $activityPhotoPath;
        }
        // Update aktivitas
        $activity->update([
            'date' => $request->date,
            'title' => $request->title,
            'description' => $request->description,
            // 'activity_photo' sudah diupdate di atas jika ada
        ]);
        return redirect()->route('activities.index')->with('toast', [
            'type' => 'success',
            'message' => 'Aktivitas berhasil diperbarui!'
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function print($studentId)
    {
        // Ambil data student berdasarkan user yang login
        $student = Student::findOrFail($studentId); // Asumsi relasi sudah didefinisikan di model User
        // Ambil data internship berdasarkan student
        $internships = $student->internships; // Asumsi relasi sudah didefinisikan di model Student
        // Ambil data activities berdasarkan internship
        $activities = Activity::whereIn('internship_id', $internships->pluck('id'))->get();

        return view('admin.activity.print', compact('student', 'activities'));
    }
}
