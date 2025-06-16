<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDepartmentRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:100',
            'kode' => 'required|string|max:10|unique:departments,kode,' . $this->route('department'),
            'deskripsi' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'nama.required' => 'Nama departemen harus diisi.',
            'nama.string' => 'Nama departemen harus berupa teks.',
            'nama.max' => 'Nama departemen tidak boleh lebih dari 100 karakter.',
            'kode.required' => 'Kode departemen harus diisi.',
            'kode.string' => 'Kode departemen harus berupa teks.',
            'kode.unique' => 'Kode departemen sudah terdaftar.',
            'kode.max' => 'Kode departemen tidak boleh lebih dari 10 karakter.',
            'deskripsi.string' => 'Deskripsi harus berupa teks.',
        ];
    }
}
