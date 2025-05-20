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
            $table->string('faculty_id')->nullable();
            $table->string('course_code')->nullable();
            $table->string('course_title')->nullable();
            $table->integer('course_unit')->nullable();
            $table->string('lecturer_id')->nullable();
            $table->string('venue')->nullable();
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
