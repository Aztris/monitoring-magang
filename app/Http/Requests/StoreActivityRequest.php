<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreActivityRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Izinkan semua pengguna untuk mengakses request ini
    }

    public function rules()
{
    return [
        'internship_id' => 'required|exists:internships,id', // <-- TAMBAHKAN INI
        'date' => 'required|date',
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'activity_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ];
}

    public function messages()
    {
        return [
            'date.required' => 'Tanggal kegiatan harus diisi.',
            'date.date' => 'Format tanggal tidak valid.',
            'title.required' => 'Judul kegiatan harus diisi.',
            'title.string' => 'Judul kegiatan harus berupa teks.',
            'title.max' => 'Judul kegiatan tidak boleh lebih dari 255 karakter.',
            'activity_photo.image' => 'File yang diunggah harus berupa gambar.',
            'activity_photo.mimes' => 'Gambar harus dalam format jpeg, png, jpg, atau gif.',
            'activity_photo.max' => 'Ukuran gambar tidak boleh lebih dari 2MB.',
        ];
    }
}
