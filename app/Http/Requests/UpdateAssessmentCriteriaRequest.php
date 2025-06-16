<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAssessmentCriteriaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->role == 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ];
    }
    public function messages()
    {
        return [
            'nama.required' => 'Nama kriteria penilaian harus diisi.',
            'nama.string' => 'Nama kriteria penilaian harus berupa teks.',
            'nama.max' => 'Nama kriteria penilaian tidak boleh lebih dari 255 karakter.',
            'deskripsi.string' => 'Deskripsi harus berupa teks.',
        ];
    }
}
