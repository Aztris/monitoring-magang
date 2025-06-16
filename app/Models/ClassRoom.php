<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'grade_level',
        'department_id',
        // 'academic_year_id'
    ];

    // Relationships
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // public function academicYear()
    // {
    //     return $this->belongsTo(AcademicYear::class);
    // }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
