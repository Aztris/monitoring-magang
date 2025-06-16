<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'nama',
        'email',
        'logo',
        'alamat',
        'no_hp',
        'nama_pimpinan',
        'bidang_usaha',
        'deskripsi',
        'pic_nama',
        'pic_phone',
        'pic_email'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
