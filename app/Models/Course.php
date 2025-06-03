<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'faculty_id',
        'course_code',
        'course_title',
        'course_unit',
        'academic_details_id',
        'lecturer_id',
        'venue_id',
        'slug',
    ];

    // Relationships

    /**
     * A course belongs to a faculty.
     */
    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    /**
     * A course belongs to academic details (like a session or semester).
     */
    public function academicDetails()
    {
        return $this->belongsTo(AcademicDetail::class);
    }

    /**
     * A course is taught by a lecturer.
     * Assuming lecturers are stored in the users table.
     */
    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
        // If lecturers are in a separate model/table, change User::class to Lecturer::class
    }

    /**
     * A course takes place in a venue.
     */
    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    /**
     * A course can have many timetables.
     */
    public function timetables(){
        return $this->hasMany(Timetable::class);
    }

}
