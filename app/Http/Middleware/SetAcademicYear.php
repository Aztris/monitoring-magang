<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AcademicYear;
use Symfony\Component\HttpFoundation\Response;

class SetAcademicYear
{
    public function handle(Request $request, Closure $next): Response
    {
        // Jika ada tahun akademik yang dipilih di session, gunakan itu
        if ($request->session()->has('selected_academic_year_id')) {
            $selectedYear = AcademicYear::find($request->session()->get('selected_academic_year_id'));

            if ($selectedYear) {
                $request->attributes->add(['selected_academic_year' => $selectedYear]);
                return $next($request);
            }
        }

        // Jika tidak, gunakan tahun akademik aktif dari cache/database
        $activeYear = AcademicYear::getActiveYear();

        if ($activeYear) {
            $request->session()->put('selected_academic_year_id', $activeYear->id);
            $request->attributes->add(['selected_academic_year' => $activeYear]);
        }

        return $next($request);
    }
}
