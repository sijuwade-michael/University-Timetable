<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faculty_id')->constrained()->onDelete('cascade')->nullable();
            $table->string('course_code')->nullable();
            $table->string('course_title')->nullable();
            $table->integer('course_unit')->nullable();
            $table->foreignId('academic_details_id')->constrained()->onDelete('cascade')->nullable();
            $table->foreignId('lecturer_id')->constrained('lecturers')->onDelete('cascade')->nullable();
            $table->foreignId('venue_id')->constrained()->onDelete('cascade')->nullable();
            $table->string('slug')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
    }
};
