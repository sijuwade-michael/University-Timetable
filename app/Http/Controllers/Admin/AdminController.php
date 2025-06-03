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

use App\Models\Faculty;
use App\Models\Lecturer;
use App\Models\AcademicDetail;
use App\Models\Course;
use App\Models\Period;
use App\Models\Timetable;
use App\Models\Venue;

class AdminController extends Controller
{
    public function index(){
        return view('admin.home');
    }


    //FACULTIES MANAGEMENT
    public function faculties(){
        $faculties = Faculty::all();
        return view('admin.faculties',[
            'faculties' => $faculties,
        ]);
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



    //LECTURERS MANAGEMENT
    public function lecturers(){
        $lecturers = Lecturer::all();
        return view('admin.lecturers',[
            'lecturers' => $lecturers,
        ]);
    }

    public function newLecturer(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:10',
            'lastname' => 'required|string|max:255',
            'othernames' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back()->withInput();
        }

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->lastname . ' ' . $request->othernames)));

        $lecturer = new Lecturer([
            'title' => $request->title,
            'lastname' => $request->lastname,
            'othernames' => $request->othernames,
            'slug' => $slug,
        ]);

        if ($lecturer->save()) {
            alert()->success('Success', 'Lecturer added successfully')->persistent('Close');
        } else {
            alert()->error('Error', 'Failed to add lecturer')->persistent('Close');
        }

        return redirect()->back();
    }

    public function updateLecturer(Request $request){
        $validator = Validator::make($request->all(), [
            'lecturer_id' => 'required|exists:lecturers,id',
            'title' => 'required|string|max:10',
            'lastname' => 'required|string|max:255',
            'othernames' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back()->withInput();
        }

        $lecturer = Lecturer::findOrFail($request->lecturer_id);
        $newSlug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->lastname . ' ' . $request->othernames)));

        $lecturer->title = $request->title;
        $lecturer->lastname = $request->lastname;
        $lecturer->othernames = $request->othernames;
        $lecturer->slug = $newSlug;

        if ($lecturer->isDirty()) {
            if ($lecturer->save()) {
                alert()->success('Success', 'Lecturer updated successfully')->persistent('Close');
            } else {
                alert()->error('Error', 'Failed to update lecturer')->persistent('Close');
            }
        } else {
            alert()->info('No Changes', 'No updates were made')->persistent('Close');
        }

        return redirect()->back();
    }

    public function deleteLecturer(Request $request){
        $validator = Validator::make($request->all(), [
            'lecturer_id' => 'required|exists:lecturers,id',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back();
        }

        $lecturer = Lecturer::find($request->lecturer_id);

        if (!$lecturer) {
            alert()->error('Error', 'Lecturer not found')->persistent('Close');
            return redirect()->back();
        }

        if ($lecturer->delete()) {
            alert()->success('Deleted', 'Lecturer deleted successfully')->persistent('Close');
        } else {
            alert()->error('Oops!', 'Failed to delete lecturer')->persistent('Close');
        }

        return redirect()->back();
    }


    //VENUES MANAGEMENT
    public function venues(){
        $venues = Venue::all();
        return view('admin.venues',[
            'venues' => $venues,
        ]);
    }

    public function newVenue(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:venues,name',
            'code' => 'required|string|max:50|unique:venues,code',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back()->withInput();
        }

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->name)));

        $venue = new Venue([
            'name' => $request->name,
            'code' => $request->code,
            'slug' => $slug,
        ]);

        if ($venue->save()) {
            alert()->success('Success', 'Venue created successfully')->persistent('Close');
        } else {
            alert()->error('Error', 'Failed to create venue')->persistent('Close');
        }

        return redirect()->back();
    }

    public function updateVenue(Request $request){
        $validator = Validator::make($request->all(), [
            'venue_id' => 'required|exists:venues,id',
            'name' => 'required|string|max:255|unique:venues,name,' . $request->venue_id,
            'code' => 'required|string|max:50|unique:venues,code,' . $request->venue_id,
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back()->withInput();
        }

        $venue = Venue::findOrFail($request->venue_id);
        $newSlug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->name)));

        $venue->name = $request->name;
        $venue->code = $request->code;
        $venue->slug = $newSlug;

        if ($venue->isDirty()) {
            if ($venue->save()) {
                alert()->success('Success', 'Venue updated successfully')->persistent('Close');
            } else {
                alert()->error('Error', 'Failed to update venue')->persistent('Close');
            }
        } else {
            alert()->info('No Changes', 'No updates were made')->persistent('Close');
        }

        return redirect()->back();
    }

    public function deleteVenue(Request $request){
        $validator = Validator::make($request->all(), [
            'venue_id' => 'required|exists:venues,id',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back();
        }

        $venue = Venue::find($request->venue_id);

        if (!$venue) {
            alert()->error('Error', 'Invalid Venue')->persistent('Close');
            return redirect()->back();
        }

        if ($venue->delete()) {
            alert()->success('Deleted', 'Venue deleted successfully')->persistent('Close');
        } else {
            alert()->error('Oops!', 'Failed to delete venue')->persistent('Close');
        }

        return redirect()->back();
    }


    //PERIODS MANAGEMENT

    public function periods(){
        $periods = Period::all();
        return view('admin.periods',[
            'periods' => $periods,
        ]);
    }


    public function newPeriod(Request $request){
        $validator = Validator::make($request->all(), [
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'label' => 'required|in:Morning,Afternoon,Evening',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back()->withInput();
        }

        $period = new Period([
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'label' => $request->label,
        ]);

        if ($period->save()) {
            alert()->success('Success', 'Period created successfully')->persistent('Close');
        } else {
            alert()->error('Error', 'Failed to create period')->persistent('Close');
        }

        return redirect()->back();
    }


    public function updatePeriod(Request $request){
        $validator = Validator::make($request->all(), [
            'period_id' => 'required|exists:periods,id',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'label' => 'required|in:Morning,Afternoon,Evening',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back()->withInput();
        }

        $period = Period::findOrFail($request->period_id);
        $period->start_time = $request->start_time;
        $period->end_time = $request->end_time;
        $period->label = $request->label;

        if ($period->isDirty()) {
            if ($period->save()) {
                alert()->success('Success', 'Period updated successfully')->persistent('Close');
            } else {
                alert()->error('Error', 'Failed to update period')->persistent('Close');
            }
        } else {
            alert()->info('No Changes', 'No updates were made')->persistent('Close');
        }

        return redirect()->back();
    }


    public function deletePeriod(Request $request){
        $validator = Validator::make($request->all(), [
            'period_id' => 'required|exists:periods,id',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back();
        }

        $period = Period::find($request->period_id);

        if (!$period) {
            alert()->error('Error', 'Invalid Period')->persistent('Close');
            return redirect()->back();
        }

        if ($period->delete()) {
            alert()->success('Deleted', 'Period deleted successfully')->persistent('Close');
        } else {
            alert()->error('Oops!', 'Failed to delete period')->persistent('Close');
        }

        return redirect()->back();
    }


    //Academic Details Management

    public function academicDetails(){
        $academicDetails = AcademicDetail::all();
        return view('admin.academicDetails',[
            'academicDetails' => $academicDetails,
        ]);
    }

    public function newAcademicDetail(Request $request){
        $validator = Validator::make($request->all(), [
            'academic_year' => 'required|string|max:20',
            'semester' => 'required|in:Harmattan,Rain',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back()->withInput();
        }

        $academicDetail = new AcademicDetail([
            'academic_year' => $request->academic_year,
            'semester' => $request->semester,
        ]);

        if ($academicDetail->save()) {
            alert()->success('Success', 'Academic Detail added successfully')->persistent('Close');
        } else {
            alert()->error('Error', 'Failed to add Academic Detail')->persistent('Close');
        }

        return redirect()->back();
    }

    public function updateAcademicDetail(Request $request){
        $validator = Validator::make($request->all(), [
            'academic_detail_id' => 'required|exists:academic_details,id',
            'academic_year' => 'required|string|max:20',
            'semester' => 'required|in:Harmattan,Rain',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back()->withInput();
        }

        $academicDetail = AcademicDetail::findOrFail($request->academic_detail_id);
        $academicDetail->academic_year = $request->academic_year;
        $academicDetail->semester = $request->semester;

        if ($academicDetail->isDirty()) {
            if ($academicDetail->save()) {
                alert()->success('Updated', 'Academic Detail updated successfully')->persistent('Close');
            } else {
                alert()->error('Error', 'Failed to update Academic Detail')->persistent('Close');
            }
        } else {
            alert()->info('No Changes', 'No updates were made')->persistent('Close');
        }

        return redirect()->back();
    }

    public function deleteAcademicDetail(Request $request){
        $validator = Validator::make($request->all(), [
            'academic_detail_id' => 'required|exists:academic_details,id',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back();
        }

        $academicDetail = AcademicDetail::find($request->academic_detail_id);

        if (!$academicDetail) {
            alert()->error('Error', 'Academic Detail not found')->persistent('Close');
            return redirect()->back();
        }

        if ($academicDetail->delete()) {
            alert()->success('Deleted', 'Academic Detail deleted successfully')->persistent('Close');
        } else {
            alert()->error('Oops!', 'Failed to delete Academic Detail')->persistent('Close');
        }

        return redirect()->back();
    }




    //COURSES MANAGEMENT

    public function courses(){
        $courses = Course::all();
        $venues = Venue::all();
        $lecturers = Lecturer::all();
        $academicDetails = AcademicDetail::all();
        $faculties = Faculty::all();
        $periods = Period::all();
        return view('admin.courses',[
            'courses' => $courses,
            'venues' => $venues,
            'lecturers' => $lecturers,
            'academicDetails' => $academicDetails,
            'faculties' => $faculties,
            'periods' => $periods,
        ]);
    }

    public function newCourse(Request $request){
        $validator = Validator::make($request->all(), [
            'faculty_id' => 'required|exists:faculties,id',
            'course_code' => 'required|string|max:10|unique:courses,course_code',
            'course_title' => 'required|string|max:100',
            'course_unit' => 'required|numeric|min:1',
            'academic_details_id' => 'required|exists:academic_details,id',
            'lecturer_id' => 'required|exists:lecturers,id',
            'venue_id' => 'required|exists:venues,id',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back()->withInput();
        }

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->course_title)));

        $course = new Course([
            'faculty_id' => $request->faculty_id,
            'course_code' => strtoupper($request->course_code),
            'course_title' => $request->course_title,
            'course_unit' => $request->course_unit,
            'academic_details_id' => $request->academic_details_id,
            'lecturer_id' => $request->lecturer_id,
            'venue_id' => $request->venue_id,
            'slug' => $slug,
        ]);

        if ($course->save()) {
            alert()->success('Success', 'Course added successfully')->persistent('Close');
        } else {
            alert()->error('Error', 'Failed to add course')->persistent('Close');
        }

        return redirect()->back();
    }

    public function updateCourse(Request $request){
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'faculty_id' => 'required|exists:faculties,id',
            'course_code' => 'required|string|max:10|unique:courses,course_code,' . $request->course_id,
            'course_title' => 'required|string|max:100',
            'course_unit' => 'required|numeric|min:1',
            'academic_details_id' => 'required|exists:academic_details,id',
            'lecturer_id' => 'required|exists:lecturers,id',
            'venue_id' => 'required|exists:venues,id',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back()->withInput();
        }

        $newSlug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->course_title)));

        $course = Course::findOrFail($request->course_id);

        $course->faculty_id = $request->faculty_id;
        $course->course_code = strtoupper($request->course_code);
        $course->course_title = $request->course_title;
        $course->course_unit = $request->course_unit;
        $course->academic_details_id = $request->academic_details_id;
        $course->lecturer_id = $request->lecturer_id;
        $course->venue_id = $request->venue_id;
        $course->slug = $newSlug;

        if ($course->isDirty()) {
            if ($course->save()) {
                alert()->success('Updated', 'Course updated successfully')->persistent('Close');
            } else {
                alert()->error('Error', 'Failed to update course')->persistent('Close');
            }
        } else {
            alert()->info('No Changes', 'No updates were made')->persistent('Close');
        }

        return redirect()->back();
    }

    public function deleteCourse(Request $request){
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back();
        }

        $course = Course::find($request->course_id);

        if (!$course) {
            alert()->error('Error', 'Course not found')->persistent('Close');
            return redirect()->back();
        }

        if ($course->delete()) {
            alert()->success('Deleted', 'Course deleted successfully')->persistent('Close');
        } else {
            alert()->error('Oops!', 'Failed to delete course')->persistent('Close');
        }

        return redirect()->back();
    }


    //TIMETABLE MANAGEMENT

    public function timetables(Request $request){
        $faculties = Faculty::with('courses')->get();
        $selectedFaculty = $request->input('faculty_id');
        $selectedCourseId = $request->input('course_id');

        $courses = $selectedFaculty
            ? Course::where('faculty_id', $selectedFaculty)->get()
            : [];

        $selectedCourse = $selectedCourseId
            ? Course::with(['venue', 'lecturer', 'academicDetails'])->find($selectedCourseId)
            : null;

        $academicDetails = $selectedCourse?->academicDetails ? [$selectedCourse->academicDetails] : [];
        $venues = $selectedCourse?->venue ? [$selectedCourse->venue] : [];

        $timetables = Timetable::with('course', 'faculty', 'venue', 'academicDetail')->get();

        return view('admin.timetables', [
            'faculties' => $faculties,
            'selectedFaculty' => $selectedFaculty,
            'courses' => $courses,
            'selectedCourse' => $selectedCourse,
            'academicDetails' => $academicDetails,
            'venues' => $venues,
            'timetables' => $timetables,
        ]);
    }

    public function allTimetables(){
        $timetables = Timetable::with('course', 'faculty', 'venue', 'academicDetail')->get();
        return view('admin.allTimetables', [
            'timetables' => $timetables,
        ]);
    }



}
