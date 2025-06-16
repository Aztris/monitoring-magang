<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'internship_id',
        'date',
        'check_in_time',
        'check_in_photo',
        'check_out_time',
        'check_out_photo',
        'status',
        'notes',
        'verification_status',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'date' => 'date',
        'check_in_time' => 'string',
        'check_out_time' => 'string',
        'status' => 'string',
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

    public function modifiedBy()
    {
        return $this->belongsTo(User::class, 'modified_by');
    }
}
