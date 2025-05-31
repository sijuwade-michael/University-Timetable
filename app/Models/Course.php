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
        'course_unit',
        'lecturer_id',
        'venue',
        'slug'
    ];

    public function faculty() {
    return $this->belongsTo(Faculty::class);
    }   

    public function lecturer() {
    return $this->belongsTo(Lecturer::class);
    }

    public function venue() {
    return $this->belongsTo(Venue::class);
    }
    

}
