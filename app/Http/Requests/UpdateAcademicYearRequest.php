<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAcademicYearRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
{
    return [
        'name' => [
            'required',
            'string',
            'max:255',
            Rule::unique('academic_years')->ignore($this->academic_year),
            'regex:/^\d{4}\/\d{4}$/'
        ],
        'start_date' => 'required|date|before:end_date',
        'end_date' => 'required|date|after:start_date',
        'deskripsi' => 'nullable|string',
        'is_active' => 'boolean'
    ];
}
}
