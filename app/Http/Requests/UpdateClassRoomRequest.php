<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateClassRoomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->role === 'admin';

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'grade_level' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'academic_year_id' => 'required|exists:academic_years,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama ruang kelas harus diisi.',
            'name.string' => 'Nama ruang kelas harus berupa teks.',
            'name.max' => 'Nama ruang kelas tidak boleh lebih dari 255 karakter.',
            'grade_level.required' => 'Tingkat kelas harus diisi.',
            'grade_level.string' => 'Tingkat kelas harus berupa teks.',
            'grade_level.max' => 'Tingkat kelas tidak boleh lebih dari 255 karakter.',
            'department_id.required' => 'Departemen harus dipilih.',
            'department_id.exists' => 'Departemen yang dipilih tidak valid.',
            'academic_year_id.required' => 'Tahun akademik harus dipilih.',
            'academic_year_id.exists' => 'Tahun akademik yang dipilih tidak valid.',
        ];
    }
}
