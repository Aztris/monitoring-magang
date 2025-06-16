<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class StoreAcademicYearRequest extends FormRequest
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
            'name' => 'required|string|max:255|unique:academic_years',
            'start_date' => 'required|date|before:end_date',
            'end_date' => 'required|date|after:start_date',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ];
    }

    public function messages()
    {
        return [
            'start_date.before' => 'Start date must be before end date',
            'end_date.after' => 'End date must be after start date'
        ];
    }

}
