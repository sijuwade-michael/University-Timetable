<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'academic_year',
        'semester',
    ];



    /**
     * Get the course that owns the academic detail.
     */
    public function courses(){
        return $this->hasMany(Course::class);
    }

}
