<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests;
use Illuminate\Support\Facades\Validator;


use App\Models\Course;
use App\Models\Lecturer;
use App\Models\Period;
use App\Models\Timetable;   
use App\Models\Venue;
use App\Models\Faculty;
use App\Models\Admin;



use SweetAlert;
use Mail;
use Alert;
use Log;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index(){
        $admin = Auth::guard('admin')->user();
        return view('admin.home', [
            'admin' => $admin,
        ]);
    }

    public function faculty(){
        $faculties = Faculty::all();
        return view('admin.faculty', [
            'faculties' => $faculties,
        ]);
    }

     public function course(){
        return view('admin.course');
    }

    public function lecturer(){
        return view('admin.lecturer');
    }
    
    public function period(){
        return view('admin.period');
    }

    public function timetable(){
        return view('admin.timetable');
    }
    
    public function venue(){
        return view('admin.venue');
    }

    public function newFaculty(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:faculties,name',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back()->withInput();
        }

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->name)));

        $faculty = new Faculty([
            'name' => $request->name,
            'slug' => $slug,
        ]);

        if ($faculty->save()) {
            alert()->success('Success', 'Faculty created successfully')->persistent('Close');
        } else {
            alert()->error('Error', 'Failed to create faculty')->persistent('Close');
        }

        return redirect()->back();
    }

    public function updateFaculty(Request $request){
        $validator = Validator::make($request->all(), [
            'faculty_id' => 'required|exists:faculties,id',
            'name' => 'required|string|max:255|unique:faculties,name,' . $request->faculty_id,
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back()->withInput();
        }

        $faculty = Faculty::findOrFail($request->faculty_id);

        $newSlug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->name)));

        $faculty->name = $request->name;
        $faculty->slug = $newSlug;

        if ($faculty->isDirty()) {
            if ($faculty->save()) {
                alert()->success('Success', 'Faculty updated successfully')->persistent('Close');
            } else {
                alert()->error('Error', 'Failed to update faculty')->persistent('Close');
            }
        } else {
            alert()->info('No Changes', 'No updates were made')->persistent('Close');
        }

        return redirect()->back();
    }

    public function deleteFaculty(Request $request){
        $validator = Validator::make($request->all(), [
            'faculty_id' => 'required|exists:faculties,id',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back();
        }

        $faculty = Faculty::find($request->faculty_id);

        if (!$faculty) {
            alert()->error('Error', 'Invalid Faculty')->persistent('Close');
            return redirect()->back();
        }

        if ($faculty->delete()) {
            alert()->success('Deleted', 'Faculty deleted successfully')->persistent('Close');
        } else {
            alert()->error('Oops!', 'Failed to delete faculty')->persistent('Close');
        }

        return redirect()->back();
    }




}

