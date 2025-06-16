<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AcademicYear extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'is_active',
        'deskripsi'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean'
    ];

    public function classRooms()
    {
        return $this->hasMany(ClassRoom::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public static function getActiveYear()
    {
        return Cache::remember('active_academic_year', now()->addDay(), function () {
            return self::where('is_active', true)->first();
        });
    }

    public static function setActiveYear($id)
    {
        self::query()->update(['is_active' => false]);

        $year = self::findOrFail($id);
        $year->update(['is_active' => true]);

        Cache::put('active_academic_year', $year, now()->addDay());

        return $year;
    }
}
