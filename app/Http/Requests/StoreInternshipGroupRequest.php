<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class StoreInternshipGroupRequest extends FormRequest
{
    public function authorize()
    {
        return Auth::user()->role === 'admin';
    }

    public function rules()
    {
        return [
            'nama' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'teacher_id' => 'required|exists:teachers,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'deskripsi' => 'nullable|string',
            'enum' => 'required|in:active,inactive',
        ];
    }

    public function messages()
    {
        return [
            'nama.required' => 'Nama grup magang harus diisi.',
            'company_id.required' => 'Perusahaan harus dipilih.',
            'teacher_id.required' => 'Pengajar harus dipilih.',
            'academic_year_id.required' => 'Tahun akademik harus dipilih.',
            'start_date.required' => 'Tanggal mulai harus diisi.',
            'end_date.required' => 'Tanggal selesai harus diisi.',
            'end_date.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
            'enum.required' => 'Status harus dipilih.',
        ];
    }
}
