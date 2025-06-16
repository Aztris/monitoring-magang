<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternshipAssessment extends Model
{
    protected $fillable = ['internship_id', 'assessment_criteria_id', 'assessor_id', 'nilai'];

    public function internship()
    {
        return $this->belongsTo(Internship::class);
    }

    public function criteria()
    {
        return $this->belongsTo(AssessmentCriteria::class, 'assessment_criteria_id');
    }

    public function assessor()
    {
        return $this->belongsTo(User::class, 'assessor_id');
    }
}
