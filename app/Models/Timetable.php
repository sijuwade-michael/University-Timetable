<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Timetable extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'faculty_id',
        'course_id',
        'academic_detail_id',
        'slug',
        'venue_id',
        'period_id',
    ];


    public function faculty(){
        return $this->belongsTo(Faculty::class);
    }

    public function course(){
        return $this->belongsTo(Course::class);
    }

    public function academicDetail(){
        return $this->belongsTo(AcademicDetail::class);
    }

    public function period(){
        return $this->belongsTo(Period::class);
    }

    public function venue(){
        return $this->belongsTo(Venue::class);
    }


}
