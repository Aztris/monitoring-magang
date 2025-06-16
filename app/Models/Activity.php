<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Activity extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'internship_id',
        'date',
        'title',
        'description',
        'activity_photo',
        'verification_status',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'date' => 'date',
        'verification_status' => 'string',
        'verified_at' => 'datetime', 
    ];

    public function internship()
    {
        return $this->belongsTo(Internship::class);
    }
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
