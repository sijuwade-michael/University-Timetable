<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Timetable extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=[
        'faculty_id',
        'course_id',
        'course_unit',
        'course_unit',
        'academic_period',
        'lecture_arrangement',
        'slug'
    ];



}
