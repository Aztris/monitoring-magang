<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Internship extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'internships';

    protected $fillable = [
        'student_id',
        'internship_group_id',
        'posisi',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function internshipGroup()
    {
        return $this->belongsTo(InternshipGroup::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function assessments()
    {
        return $this->hasMany(InternshipAssessment::class);
    }
}
