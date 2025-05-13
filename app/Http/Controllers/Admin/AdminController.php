<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests;
use App\Models\Faculty;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function index(){
        return view('admin.home');
    }

    public function faculty(){
        return view('admin.faculty');
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

   // Faculty Management Logic
    public function facultyadmin() {
    $faculties = faculty::all();  
    return view('admin.faculty', [
        'faculties' => $faculties,
    ]);
}

public function newFaculty(Request $request){
    $validator = Validator::make($request->all(), [
        'email' => 'required|email|unique:faculties,email',
        'password' => 'required|confirmed|min:6',
        'title' => 'nullable|string|max:10',
        'othernames' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'dob' => 'required|date',
        'phone' => 'required|string|max:15',
        'address' => 'nullable|string',
        'city' => 'nullable|string|max:100',
        'state' => 'nullable|string|max:100',
        'gender' => 'required|in:Male,Female,Other,other,male,female',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'cv' => 'nullable|mimes:pdf,doc,docx|max:2048',
        'cover_letter' => 'nullable|mimes:pdf,doc,docx|max:2048',
    ]);

    if ($validator->fails()) {
        alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
        return redirect()->back()->withInput();
    }

    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->last_name . '-' . $request->othernames)));
    $hashedFolder = md5(uniqid() . time());
    $folderPath = public_path("uploads/faculties/{$hashedFolder}");

    if (!file_exists($folderPath)) {
        mkdir($folderPath, 0777, true);
    }

    $imagePath = null;
    if ($request->hasFile('image')) {
        $imageName = 'profile.' . $request->file('image')->getClientOriginalExtension();
        $request->file('image')->move($folderPath, $imageName);
        $imagePath = "uploads/faculties/{$hashedFolder}/{$imageName}";
    }

    $cvPath = null;
    if ($request->hasFile('cv')) {
        $cvName = 'cv.' . $request->file('cv')->getClientOriginalExtension();
        $request->file('cv')->move($folderPath, $cvName);
        $cvPath = "uploads/faculties/{$hashedFolder}/{$cvName}";
    }

    $coverLetterPath = null;
    if ($request->hasFile('cover_letter')) {
        $coverLetterName = 'cover_letter.' . $request->file('cover_letter')->getClientOriginalExtension();
        $request->file('cover_letter')->move($folderPath, $coverLetterName);
        $coverLetterPath = "uploads/faculties/{$hashedFolder}/{$coverLetterName}";
    }

    $faculty = new Faculty([
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'title' => $request->title,
        'othernames' => $request->othernames,
        'last_name' => $request->last_name,
        'dob' => $request->dob,
        'phone' => $request->phone,
        'address' => $request->address,
        'city' => $request->city,
        'state' => $request->state,
        'gender' => strtolower($request->gender),
        'slug' => $slug,
        'upload_folder' => $hashedFolder,
        'image' => $imagePath,
        'cv' => $cvPath,
        'cover_letter' => $coverLetterPath,
    ]);

    if ($faculty->save()) {
        alert()->success('Success', 'Faculty created successfully')->persistent('Close');
    } else {
        alert()->error('Error', 'Failed to create faculty')->persistent('Close');
    }

    return redirect()->back();
}

public function updateFaculty(Request $request){
    $request->validate([
        'faculty_id' => 'required|exists:faculties,id',
        'title' => 'nullable|string|max:10',
        'othernames' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'dob' => 'required|date',
        'phone' => 'required|string|max:15',
        'address' => 'nullable|string',
        'city' => 'nullable|string|max:100',
        'state' => 'nullable|string|max:100',
        'gender' => 'required|in:Male,Female,Other,other,male,female',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'cv' => 'nullable|mimes:pdf,doc,docx|max:2048',
        'cover_letter' => 'nullable|mimes:pdf,doc,docx|max:2048',
    ]);

    $faculty = Faculty::findOrFail($request->faculty_id);

    // Generate slug
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->last_name . '-' . $request->othernames)));

    // Create or reuse upload folder
    if (!$faculty->upload_folder) {
        $hashedFolder = md5($faculty->id . uniqid());
        $faculty->upload_folder = $hashedFolder;
        $faculty->save();
    } else {
        $hashedFolder = $faculty->upload_folder;
    }

    $folderPath = public_path("uploads/faculties/{$hashedFolder}");
    if (!file_exists($folderPath)) {
        mkdir($folderPath, 0777, true);
    }

    // File handling
    $imageUrl = $faculty->image;
    if ($request->hasFile('image')) {
        $imageName = 'profile.' . $request->file('image')->getClientOriginalExtension();
        $request->file('image')->move($folderPath, $imageName);
        $imageUrl = "uploads/faculties/{$hashedFolder}/{$imageName}";
    }

    $cvUrl = $faculty->cv;
    if ($request->hasFile('cv')) {
        $cvName = 'cv.' . $request->file('cv')->getClientOriginalExtension();
        $request->file('cv')->move($folderPath, $cvName);
        $cvUrl = "uploads/faculties/{$hashedFolder}/{$cvName}";
    }

    $coverLetterUrl = $faculty->cover_letter;
    if ($request->hasFile('cover_letter')) {
        $coverLetterName = 'cover_letter.' . $request->file('cover_letter')->getClientOriginalExtension();
        $request->file('cover_letter')->move($folderPath, $coverLetterName);
        $coverLetterUrl = "uploads/faculties/{$hashedFolder}/{$coverLetterName}";
    }

    // Update and check for actual changes
    $faculty->fill([
        'title' => $request->title,
        'othernames' => $request->othernames,
        'last_name' => $request->last_name,
        'dob' => $request->dob,
        'phone' => $request->phone,
        'address' => $request->address,
        'city' => $request->city,
        'state' => $request->state,
        'gender' => strtolower($request->gender),
        'image' => $imageUrl,
        'cv' => $cvUrl,
        'cover_letter' => $coverLetterUrl,
        'slug' => $slug,
    ]);

    if ($faculty->isDirty()) {
        if ($faculty->save()) {
            alert()->success('Success', 'Faculty updated successfully')->persistent('Close');
        } else {
            alert()->error('Oops!', 'Something went wrong while saving the changes')->persistent('Close');
        }
    } else {
        alert()->info('No Changes', 'No updates were made')->persistent('Close');
    }

    return redirect()->back();
}

public function viewFaculty($slug){
    $faculty = Faculty::where('slug', $slug)->firstOrFail();

    return view('admin.faculty', [
        'faculty' => $faculty,
    ]);
}

public function deleteFaculty(Request $request){
    $validator = Validator::make($request->all(), [
        'faculty_id' => 'required',
    ]);

    if ($validator->fails()) {
        alert()->error('Error', $validator->messages()->all()[0])->persistent('Close');
        return redirect()->back();
    }

    if(!$faculty = Faculty::find($request->faculty_id)){
        alert()->error('Oops', 'Invalid Faculty')->persistent('Close');
        return redirect()->back();
    }

    if($faculty->delete()) {
        alert()->success('Deleted', 'Faculty successfully deleted');
        return redirect()->back();
    }

    alert()->error('Oops!', 'Something went wrong')->persistent('Close');
    return redirect()->back();
}

}
