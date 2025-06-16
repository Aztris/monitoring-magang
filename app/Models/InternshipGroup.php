<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InternshipGroup extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'internship_groups';

    protected $fillable = [
        'nama',
        'company_id',
        'teacher_id',
        'academic_year_id',
        'start_date',
        'end_date',
        'deskripsi',
        'enum',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function internships()
    {
        return $this->hasMany(Internship::class);
    }
}
