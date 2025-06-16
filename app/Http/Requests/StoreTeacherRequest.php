<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class StoreTeacherRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Pastikan hanya admin yang dapat membuat guru
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
            'user_id' => 'required|exists:users,id', // Pastikan user_id valid
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi foto profil
            'nip' => 'required|string|max:20|unique:teachers,nip', // Validasi NIP
            'nama' => 'required|string|max:100',
            'jenkel' => 'required|in:L,P', // Validasi jenis kelamin
            'no_hp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
            'mata_pelajaran' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'User  ID harus diisi.',
            'user_id.exists' => 'User  ID tidak valid.',
            'nip.required' => 'NIP harus diisi.',
            'nip.unique' => 'NIP sudah terdaftar.',
            'jenkel.required' => 'Jenis kelamin harus dipilih.',
            'jenkel.in' => 'Jenis kelamin harus L (Laki-laki) atau P (Perempuan).',
            'foto_profil.image' => 'File yang diunggah harus berupa gambar.',
            'foto_profil.mimes' => 'Gambar harus berformat jpeg, png, jpg, atau gif.',
            'foto_profil.max' => 'Ukuran gambar tidak boleh lebih dari 2MB.',
        ];
    }
}
