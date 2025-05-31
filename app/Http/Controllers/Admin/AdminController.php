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
use DB;

use App\Models\Course;
use App\Models\Lecturer;
use App\Models\Period;
use App\Models\Timetable;
use App\Models\Venue;
use App\Models\Table;
use App\Models\Faculty;
use App\Models\Admin;

use SweetAlert;
use Mail;
use Alert;
use Log;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        $admin = Auth::guard('admin')->user();
        return view('admin.home', ['admin' => $admin]);
    }

    // public function getCoursesByFaculty($facultyId)
    // {
    //     $faculty = Faculty::with('courses')->find($facultyId);

    //     if (!$faculty) {
    //         return response()->json(['message' => 'Faculty not found'], 404);
    //     }

    //     return response()->json([
    //         'faculty' => $faculty->name,
    //         'courses' => $faculty->courses,
    //     ]);
    // }



    public function faculty()
    {
        $faculties = Faculty::all();
        return view('admin.faculty', ['faculties' => $faculties]);
    }

    public function course()
    {
        $courses = Course::all();
        $faculties = Faculty::all();
        $lecturers = Lecturer::all();
        $venues = Venue::all();

        return view('admin.course', compact('courses', 'faculties', 'lecturers', 'venues'));
    }

    public function lecturer()
    {
        $lecturers = Lecturer::all();
        return view('admin.lecturer', ['lecturers' => $lecturers]);
    }

    // public function table()
    // {
    //     $tables = Table::all();
    //     return view('admin.table', ['tables' => $tables]);
    // }
   public function table(){
    $tables = Table::all();

    $timetable = [];

    foreach ($tables as $row) {
        $day = $row->day;
        $timeslot = $row->timeslot;
        $course = $row->course;

        if (!isset($timetable[$day])) {
            $timetable[$day] = [];
        }
        if (!isset($timetable[$day][$timeslot])) {
            $timetable[$day][$timeslot] = [];
        }

        $timetable[$day][$timeslot][] = $course;
    }

    return view('admin.table', ['tables' => $timetable]);
}





    public function period()
    {
        $periods = Period::all();
        return view('admin.period', ['periods' => $periods]);
    }

    
    
    // public function timetable(){
    //     $faculties = Faculty::with('courses')->get();

        
    //     $timetables = Timetable::all();
    //     $courses = Course::all();

    
    //     return view('admin.timetable', [
    //         'courses' => $courses,
    //         'faculties' => $faculties,
    //         'timetables' => $timetables,
    //     ]);
    // }

    public function timetable(Request $request){
        $faculties = Faculty::with('courses')->get();
        
        // Get selected faculty ID from request
        $selectedFacultyId = $request->input('faculty_id');

        $courses = collect();
        if ($selectedFacultyId) {
            $faculty = Faculty::with('courses')->find($selectedFacultyId);
            if ($faculty) {
                $courses = $faculty->courses;
            }
        }

        $timetables = Timetable::all();

        return view('admin.timetable', [
            'faculties' => $faculties,
            'courses' => $courses,
            'selectedFacultyId' => $selectedFacultyId,
            'timetables' => $timetables,
        ]);
    }



    // public function Timetable(){
    //     $faculty_list = DB::table('faculties')
    //                     ->groupBy('name')
    //                     ->get();

    //     return view('timetable')->with('faculty_list', $faculty_list);
    // }

    // public function fetch(Request $request)
    // {
    //     $select = $request->get('select'); // e.g., 'faculty_id'
    //     $value = $request->get('value');   // selected faculty id
    //     $dependent = $request->get('dependent'); // 'course'

    //     $data = DB::table('courses')
    //             ->where($select, $value)
    //             ->groupBy($dependent)
    //             ->get();

    //     $output = '<option value="">Select ' . ucfirst($dependent) . '</option>';
    //     foreach ($data as $row) {
    //         $output .= '<option value="' . $row->$dependent . '">' . $row->$dependent . '</option>';
    //     }

    //     echo $output;
    // }




    // public function timetable(Request $request){
    //     $faculties = Faculty::with('courses')->get();
    //     $selectedFacultyId = $request->input('faculty_id');

    //     $courses = collect();
    //     if ($selectedFacultyId) {
    //         $faculty = Faculty::with('courses')->find($selectedFacultyId);
    //         if ($faculty) {
    //             $courses = $faculty->courses;
    //         }
    //     }

    //     $timetables = Timetable::all(); 

    //     return view('admin.timetable', compact('faculties', 'courses', 'selectedFacultyId', 'timetables'));
    // }



    public function venue()
    {
        $venues = Venue::all();
        return view('admin.venue', ['venues' => $venues]);
    }

    public function newFaculty(Request $request)
    {
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

    public function updateFaculty(Request $request)
    {
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

    public function deleteFaculty(Request $request)
    {
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

    public function newLecturer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back()->withInput();
        }

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->name)));

        $lecturer = new Lecturer([
            'name' => $request->name,
            'slug' => $slug,
        ]);

        if ($lecturer->save()) {
            alert()->success('Success', 'Lecturer created successfully')->persistent('Close');
        } else {
            alert()->error('Error', 'Failed to create lecturer')->persistent('Close');
        }

        return redirect()->back();
    }

    public function updateLecturer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lecturer_id' => 'required|exists:lecturers,id',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back()->withInput();
        }

        $lecturer = Lecturer::findOrFail($request->lecturer_id);
        $newSlug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->name)));

        $lecturer->name = $request->name;
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

    public function deleteLecturer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lecturer_id' => 'required|exists:lecturers,id',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back();
        }

        $lecturer = Lecturer::find($request->lecturer_id);

        if (!$lecturer) {
            alert()->error('Error', 'Invalid Lecturer')->persistent('Close');
            return redirect()->back();
        }

        if ($lecturer->delete()) {
            alert()->success('Deleted', 'Lecturer deleted successfully')->persistent('Close');
        } else {
            alert()->error('Oops!', 'Failed to delete lecturer')->persistent('Close');
        }

        return redirect()->back();
    }

    public function newVenue(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:venues,name',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back()->withInput();
        }

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->name)));

        $venue = new Venue([
            'name' => $request->name,
            'slug' => $slug,
        ]);

        if ($venue->save()) {
            alert()->success('Success', 'Faculty created successfully')->persistent('Close');
        } else {
            alert()->error('Error', 'Failed to create faculty')->persistent('Close');
        }

        return redirect()->back();
    }

    public function updateVenue(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'venue_id' => 'required|exists:venues,id',
            'name' => 'required|string|max:255|unique:venues,name,' . $request->venue_id,
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back()->withInput();
        }

        $venue = Venue::findOrFail($request->venue_id);
        $newSlug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->name)));

        $venue->name = $request->name;
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

    public function deleteVenue(Request $request)
    {
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

    public function newCourse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_code' => 'required|string|unique:courses,course_code',
            'course_title' => 'required|string|unique:courses,course_title',
            'course_unit' => 'required|string|unique:courses,course_unit',
            'faculty_id' => 'required|exists:faculties,name',
            'lecturer_id' => 'required|exists:lecturers,name',
            'venue' => 'required|exists:venues,name',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back()->withInput();
        }

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->faculty_id . '-' . $request->course_code)));

        $course = new Course([
            'faculty_id' => $request->faculty_id,
            'course_code' => $request->course_code,
            'course_title' => $request->course_title,
            'course_unit' => $request->course_unit,
            'lecturer_id' => $request->lecturer_id,
            'venue' => $request->venue,
            'slug' => $slug,
        ]);

        if ($course->save()) {
            alert()->success('Success', 'Course created successfully')->persistent('Close');
        } else {
            alert()->error('Error', 'Failed to create course')->persistent('Close');
        }

        return redirect()->back();
    }

    public function updateCourse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back()->withInput();
        }

        $course = Course::findOrFail($request->course_id);
        $newSlug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->name)));

        $course->faculty_id = $request->faculty_id;
        $course->course_title = $request->course_title;
        $course->course_code = $request->course_code;
        $course->course_unit = $request->course_unit;
        $course->lecturer_id = $request->lecturer_id;
        $course->venue = $request->venue;
        $course->slug = $newSlug;

        if ($course->isDirty()) {
            if ($course->save()) {
                alert()->success('Success', 'Course updated successfully')->persistent('Close');
            } else {
                alert()->error('Error', 'Failed to update course')->persistent('Close');
            }
        } else {
            alert()->info('No Changes', 'No updates were made')->persistent('Close');
        }

        return redirect()->back();
    }

    public function deleteCourse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back();
        }

        $course = Course::find($request->course_id);

        if (!$course) {
            alert()->error('Error', 'Invalid Course')->persistent('Close');
            return redirect()->back();
        }

        if ($course->delete()) {
            alert()->success('Deleted', 'Course deleted successfully')->persistent('Close');
        } else {
            alert()->error('Oops!', 'Failed to delete course')->persistent('Close');
        }

        return redirect()->back();
    }

    public function newPeriod(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'label' => 'nullable|string|max:255',
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




    public function getCourses(Request $request){
        $data['courses'] = Course::where('faculty_id', $request->faculty_id)
                                ->get(['name', 'id']);
        return response()->json($data);
    }


    

}
