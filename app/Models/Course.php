<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable=[
        'faculty_id',
        'course_code',
        'course_title',
        'lecturer_id'
    ];

}
