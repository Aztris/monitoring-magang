<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama',
        'kode',
        'deskripsi'
    ];

    public function classRooms()
    {
        return $this->hasMany(ClassRoom::class);
    }

    public function students()
    {
        return $this->hasManyThrough(Student::class, ClassRoom::class);
    }
}
