<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureStudentHasActiveInternship
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Middleware ini hanya berlaku untuk role 'student'
        // Role lain (admin, guru, dll) akan dilewatkan saja.
        if (!$user || $user->role !== 'student') {
            return $next($request);
        }

        // Ambil data siswa dan tahun akademik yang dipilih dari request
        // Pastikan middleware SetAcademicYear berjalan sebelum ini.
        $student = $user->student;
        $selectedYear = $request->attributes->get('selected_academic_year');

        // Jika siswa tidak punya profil atau tidak ada tahun akademik terpilih, blok akses.
        if (!$student || !$selectedYear) {
            return $this->redirectToDashboard();
        }

        // Cek apakah siswa punya data magang di tahun akademik yang DIPILIH
        $hasInternshipThisYear = $student->internships()
            ->whereHas('internshipGroup', function ($query) use ($selectedYear) {
                $query->where('academic_year_id', $selectedYear->id);
            })
            ->exists();

        // Jika TIDAK punya data magang, lempar ke dashboard
        if (!$hasInternshipThisYear) {
            return $this->redirectToDashboard();
        }

        // Jika punya, izinkan akses ke halaman yang dituju
        return $next($request);
    }

    /**
     * Helper function untuk redirect dengan pesan.
     */
    private function redirectToDashboard()
    {
        return redirect()->route('dashboard')->with('toast', [
            'type' => 'error',
            'message' => 'Anda tidak memiliki data magang aktif pada tahun akademik ini.'
        ]);
    }
}
