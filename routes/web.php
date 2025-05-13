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
    Route::get('/faculty', [App\Http\Controllers\Admin\AdminController::class, 'faculty'])->name('faculty')->middleware(['auth:admin']);
    Route::get('/course', [App\Http\Controllers\Admin\AdminController::class, 'course'])->name('course')->middleware(['auth:admin']);
    Route::get('/lecturer', [App\Http\Controllers\Admin\AdminController::class, 'lecturer'])->name('lecturer')->middleware(['auth:admin']);
    Route::get('/period', [App\Http\Controllers\Admin\AdminController::class, 'period'])->name('period')->middleware(['auth:admin']);
    Route::get('/timetable', [App\Http\Controllers\Admin\AdminController::class, 'timetable'])->name('timetable')->middleware(['auth:admin']);
    Route::get('/venue', [App\Http\Controllers\Admin\AdminController::class, 'venue'])->name('venue')->middleware(['auth:admin']);


    Route::get('/faculties', [App\Http\Controllers\Admin\AdminController::class, 'faculties'])->name('faculties')->middleware(['auth:admin']);
    Route::post('/newFaculty',[App\Http\Controllers\Admin\AdminController::class, 'newFaculty'])->name('newFaculty')->middleware(['auth:admin']);
    Route::post('/updateFaculty',[App\Http\Controllers\Admin\AdminController::class, 'updateFaculty'])->name('updateFaculty')->middleware(['auth:admin']);
    Route::post('/deleteFaculty', [App\Http\Controllers\Admin\AdminController::class, 'deleteFaculty'])->name('deleteFaculty')->middleware(['auth:admin']);

    
});

