<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::group(['prefix' => 'admin'], function () {
    Route::get('/', [App\Http\Controllers\Admin\Auth\LoginController::class, 'showLoginForm'])->name('admin.login');
    Route::get('/login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'login']);
    Route::post('/logout', [App\Http\Controllers\Admin\Auth\LoginController::class, 'logout'])->name('logout');
  
    // Route::get('/register', [App\Http\Controllers\Admin\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
    // Route::post('/register', [App\Http\Controllers\Admin\Auth\RegisterController::class, 'register']);
  
    Route::post('/password/email', [App\Http\Controllers\Admin\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.request');
    Route::post('/password/reset', [App\Http\Controllers\Admin\Auth\ResetPasswordController::class, 'reset'])->name('password.email');
    Route::get('/password/reset', [App\Http\Controllers\Admin\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.reset');
    Route::get('/password/reset/{token}', [App\Http\Controllers\Admin\Auth\ResetPasswordController::class, 'showResetForm']);

    Route::get('/home', [App\Http\Controllers\Admin\AdminController::class, 'index'])->name('home')->middleware(['auth:admin']);

    Route::get('/faculties', [App\Http\Controllers\Admin\AdminController::class, 'faculties'])->name('faculties')->middleware(['auth:admin']);
    Route::post('/newFaculty',[App\Http\Controllers\Admin\AdminController::class, 'newFaculty'])->name('newFaculty')->middleware(['auth:admin']);
    Route::post('/updateFaculty',[App\Http\Controllers\Admin\AdminController::class, 'updateFaculty'])->name('updateFaculty')->middleware(['auth:admin']);
    Route::post('/deleteFaculty', [App\Http\Controllers\Admin\AdminController::class, 'deleteFaculty'])->name('deleteFaculty')->middleware(['auth:admin']);

    Route::get('/lecturers', [App\Http\Controllers\Admin\AdminController::class, 'lecturers'])->name('lecturers')->middleware(['auth:admin']);
    Route::post('/newLecturer',[App\Http\Controllers\Admin\AdminController::class, 'newLecturer'])->name('newLecturer')->middleware(['auth:admin']);
    Route::post('/updateLecturer',[App\Http\Controllers\Admin\AdminController::class, 'updateLecturer'])->name('updateLecturer')->middleware(['auth:admin']);
    Route::post('/deleteLecturer', [App\Http\Controllers\Admin\AdminController::class, 'deleteLecturer'])->name('deleteLecturer')->middleware(['auth:admin']);

    Route::get('/venues', [App\Http\Controllers\Admin\AdminController::class, 'venues'])->name('venues')->middleware(['auth:admin']);
    Route::post('/newVenue',[App\Http\Controllers\Admin\AdminController::class, 'newVenue'])->name('newVenue')->middleware(['auth:admin']);
    Route::post('/updateVenue',[App\Http\Controllers\Admin\AdminController::class, 'updateVenue'])->name('updateVenue')->middleware(['auth:admin']);
    Route::post('/deleteVenue', [App\Http\Controllers\Admin\AdminController::class, 'deleteVenue'])->name('deleteVenue')->middleware(['auth:admin']);

    Route::get('/periods', [App\Http\Controllers\Admin\AdminController::class, 'periods'])->name('periods')->middleware(['auth:admin']);
    Route::post('/newPeriod',[App\Http\Controllers\Admin\AdminController::class, 'newPeriod'])->name('newPeriod')->middleware(['auth:admin']);
    Route::post('/updatePeriod',[App\Http\Controllers\Admin\AdminController::class, 'updatePeriod'])->name('updatePeriod')->middleware(['auth:admin']);
    Route::post('/deletePeriod', [App\Http\Controllers\Admin\AdminController::class, 'deletePeriod'])->name('deletePeriod')->middleware(['auth:admin']);

    Route::get('/academicDetails', [App\Http\Controllers\Admin\AdminController::class, 'academicDetails'])->name('academicDetails')->middleware(['auth:admin']);
    Route::post('/newAcademicDetail',[App\Http\Controllers\Admin\AdminController::class, 'newAcademicDetail'])->name('newAcademicDetail')->middleware(['auth:admin']);
    Route::post('/updateAcademicDetail',[App\Http\Controllers\Admin\AdminController::class, 'updateAcademicDetail'])->name('updateAcademicDetail')->middleware(['auth:admin']);
    Route::post('/deleteAcademicDetail', [App\Http\Controllers\Admin\AdminController::class, 'deleteAcademicDetail'])->name('deleteAcademicDetail')->middleware(['auth:admin']);

    Route::get('/courses', [App\Http\Controllers\Admin\AdminController::class, 'courses'])->name('courses')->middleware(['auth:admin']);
    Route::post('/newCourse',[App\Http\Controllers\Admin\AdminController::class, 'newCourse'])->name('newCourse')->middleware(['auth:admin']);
    Route::post('/updateCourse',[App\Http\Controllers\Admin\AdminController::class, 'updateCourse'])->name('updateCourse')->middleware(['auth:admin']);
    Route::post('/deleteCourse', [App\Http\Controllers\Admin\AdminController::class, 'deleteCourse'])->name('deleteCourse')->middleware(['auth:admin']);

    Route::get('/timetables', [App\Http\Controllers\Admin\AdminController::class, 'timetables'])->name('timetables')->middleware(['auth:admin']);
    Route::get('/allTimetables', [App\Http\Controllers\Admin\AdminController::class, 'allTimetables'])->name('allTimetables')->middleware(['auth:admin']);

    Route::post('/newTimetable',[App\Http\Controllers\Admin\AdminController::class, 'newTimetable'])->name('newTimetable')->middleware(['auth:admin']);
    Route::post('/updateTimetable',[App\Http\Controllers\Admin\AdminController::class, 'updateTimetable'])->name('updateTimetable')->middleware(['auth:admin']);
    Route::post('/deleteTimetable', [App\Http\Controllers\Admin\AdminController::class, 'deleteTimetable'])->name('deleteTimetable')->middleware(['auth:admin']);
});

