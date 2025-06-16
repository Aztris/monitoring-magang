<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'nis',
        'nama',
        'jenkel',
        'agama',
        'class_room_id',
        'department_id',
        'no_hp',
        'alamat',
        'tempat_lahir',
        'tanggal_lahir',
        'nama_ayah',
        'nama_ibu',
        'no_hp_ortu'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function internships()
    {
        return $this->hasMany(Internship::class);
    }
}
