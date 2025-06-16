<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use App\Models\Company;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Activity;
use App\Models\ClassRoom;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\Internship;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use App\Models\InternshipGroup;
use App\Models\AssessmentCriteria;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $selectedYear = $request->attributes->get('selected_academic_year');

        if (!$selectedYear) {
            return redirect()->route('academic-years.index')
                ->with('toast', [
                    'type' => 'error',
                    'message' => 'Silakan pilih tahun akademik terlebih dahulu'
                ]);
        }

        $user = Auth::user();

        switch ($user->role) {
            case 'admin':
                return $this->Admin($selectedYear);
                case 'student':
                    return $this->Student($selectedYear);
            case 'teacher':
                return $this->Teacher($selectedYear);
            case 'company':
                return $this->Company($selectedYear);
            default:
                return redirect('/');
        }
    }

    private function Admin($selectedYear)
    {
        $adminCount = Admin::count();
        $studentCount = Student::count();
        $teacherCount = Teacher::count();
        $companyCount = Company::count();
        $departmentCount = Department::count();
        $classRoomCount = ClassRoom::count();
        $assessmentCriteriaCount = AssessmentCriteria::count();

        $internshipGroupCount = InternshipGroup::where('academic_year_id', $selectedYear->id)->count();
        $activityCount = Activity::whereHas('internship.internshipGroup', function ($query) use ($selectedYear) {
            $query->where('academic_year_id', $selectedYear->id);
        })->count();


        $departments = Department::withCount(['students' => function ($query) use ($selectedYear) {
            $query->whereHas('internships', function ($subQuery) use ($selectedYear) {
                $subQuery->whereHas('internshipGroup', function ($groupQuery) use ($selectedYear) {
                    $groupQuery->where('academic_year_id', $selectedYear->id);
                });
            });
        }])->get();

        $departmentLabels = $departments->pluck('nama');
        $departmentData = $departments->pluck('students_count');


        $attendanceStatistics = Attendance::whereHas('internship.internshipGroup', function ($query) use ($selectedYear) {
            $query->where('academic_year_id', $selectedYear->id);
        })
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $attendanceLabels = ['Hadir', 'Sakit', 'Izin', 'Alpa'];
        $attendanceData = [
            $attendanceStatistics->get('hadir', 0),
            $attendanceStatistics->get('sakit', 0),
            $attendanceStatistics->get('izin', 0),
            $attendanceStatistics->get('alpa', 0)
        ];


        $recentActivities = Activity::with(['internship.student.user', 'internship.internshipGroup'])
            ->whereHas('internship.internshipGroup', function ($query) use ($selectedYear) {
                $query->where('academic_year_id', $selectedYear->id);
            })
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $recentAttendances = Attendance::with(['internship.student.user', 'internship.internshipGroup'])
            ->whereHas('internship.internshipGroup', function ($query) use ($selectedYear) {
                $query->where('academic_year_id', $selectedYear->id);
            })
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();


        return view('dashboard.admin', compact(
            'adminCount',
            'studentCount',
            'teacherCount',
            'companyCount',
            'activityCount',
            'internshipGroupCount',
            'departmentCount',
            'classRoomCount',
            'assessmentCriteriaCount',
            'recentActivities',
            'recentAttendances',
            'departmentLabels',
            'departmentData',
            'attendanceLabels',
            'attendanceData'
        ));
    }

    private function Teacher($selectedYear)
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            return redirect('/')->with('toast', ['type' => 'error', 'message' => 'Profil guru tidak ditemukan.']);
        }

        $teacherInternshipGroupIds = InternshipGroup::where('teacher_id', $teacher->id) //
            ->where('academic_year_id', $selectedYear->id) //
            ->pluck('id');

        $internshipGroupCount = $teacherInternshipGroupIds->count();
        $studentCount = Internship::whereIn('internship_group_id', $teacherInternshipGroupIds)->count(); //

        $attendanceStatistics = Attendance::whereHas('internship', function ($query) use ($teacherInternshipGroupIds) {
            $query->whereIn('internship_group_id', $teacherInternshipGroupIds);
        })
            ->select('status', DB::raw('count(*) as total')) //
            ->groupBy('status')
            ->pluck('total', 'status');

        $attendanceLabels = ['Hadir', 'Sakit', 'Izin', 'Alpa'];
        $attendanceData = [
            $attendanceStatistics->get('hadir', 0), //
            $attendanceStatistics->get('sakit', 0), //
            $attendanceStatistics->get('izin', 0), //
            $attendanceStatistics->get('alpa', 0) //
        ];

        $recentActivities = Activity::with(['internship.student.user'])
            ->whereHas('internship', function ($query) use ($teacherInternshipGroupIds) {
                $query->whereIn('internship_group_id', $teacherInternshipGroupIds);
            })
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.teacher', compact(
            'internshipGroupCount',
            'studentCount',
            'attendanceLabels',
            'attendanceData',
            'recentActivities'
        ));
    }

    private function Company($selectedYear)
    {
        $company = Auth::user()->company;

        if (!$company) {
            return redirect('/')->with('toast', ['type' => 'error', 'message' => 'Profil perusahaan tidak ditemukan.']);
        }

        $companyGroupIds = InternshipGroup::where('company_id', $company->id)
            ->where('academic_year_id', $selectedYear->id)
            ->pluck('id');

        $groupCount = $companyGroupIds->count();
        $studentCount = Internship::whereIn('internship_group_id', $companyGroupIds)->count();

        $pendingAttendanceCount = Attendance::whereHas('internship', function ($query) use ($companyGroupIds) {
            $query->whereIn('internship_group_id', $companyGroupIds);
        })->where('verification_status', 'pending')->count(); //

        $pendingActivityCount = Activity::whereHas('internship', function ($query) use ($companyGroupIds) {
            $query->whereIn('internship_group_id', $companyGroupIds);
        })->where('verification_status', 'pending')->count();

        $recentPendingAttendances = Attendance::with('internship.student.user')
            ->whereHas('internship', function ($query) use ($companyGroupIds) {
                $query->whereIn('internship_group_id', $companyGroupIds);
            })
            ->where('verification_status', 'pending') //
            ->latest('date')
            ->take(5)
            ->get();

        $recentPendingActivities = Activity::with('internship.student.user')
            ->whereHas('internship', function ($query) use ($companyGroupIds) {
                $query->whereIn('internship_group_id', $companyGroupIds);
            })
            ->latest('date')
            ->take(5)
            ->get();

        return view('dashboard.company', compact(
            'groupCount',
            'studentCount',
            'pendingAttendanceCount',
            'pendingActivityCount',
            'recentPendingAttendances',
            'recentPendingActivities'
        ));
    }

    private function Student($selectedYear)
    {
        $user = Auth::user();
        $student = $user->student;

        $internship = Internship::with([
            'internshipGroup.company',
            'internshipGroup.teacher'
        ])
            ->where('student_id', $student->id)
            ->whereHas('internshipGroup', function ($query) use ($selectedYear) {
                $query->where('academic_year_id', $selectedYear->id);
            })
            ->first();

        $todaysAttendance = null;
        $recentActivities = collect();
        
        if ($internship) {
            $todaysAttendance = Attendance::where('internship_id', $internship->id)
                ->where('date', now()->toDateString()) // Cek untuk tanggal hari ini
                ->first();

            $recentActivities = Activity::where('internship_id', $internship->id)
                ->latest()
                ->take(3)
                ->get();
        }

        return view('dashboard.student', compact(
            'user',
            'internship',
            'todaysAttendance',
            'recentActivities'
        ));
    }
}
