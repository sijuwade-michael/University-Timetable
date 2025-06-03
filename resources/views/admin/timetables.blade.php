@extends('admin.layout.dashboard')

@section('content')

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Add New Entry</h4>
    </div>
    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item active">New Entry</li>
        </ol>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="panel-body">
                    <div class="card-header">
                <h5 class="mb-0">Create New Timetable Entry</h5>
            </div>
            <div class="card-body">
                {{-- Step 1: Select Faculty --}}
                <form method="GET" action="{{ url('/admin/timetables') }}">
                    <div class="row g-3 mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Faculty</label>
                            <select name="faculty_id" class="form-control" onchange="this.form.submit()">
                                <option value="">-- Select Faculty --</option>
                                @foreach ($faculties as $faculty)
                                    <option value="{{ $faculty->id }}" {{ ($selectedFaculty == $faculty->id) ? 'selected' : '' }}>
                                        {{ $faculty->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>

                {{-- Step 2: Select Course --}}
                @if($selectedFaculty && count($courses))
                <form method="GET" action="{{ url('/admin/timetables') }}">
                    <input type="hidden" name="faculty_id" value="{{ $selectedFaculty }}">
                    <div class="row g-3 mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Course</label>
                            <select name="course_id" class="form-control" onchange="this.form.submit()" required>
                                <option value="">-- Select Course --</option>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}" {{ (request('course_id') == $course->id) ? 'selected' : '' }}>
                                        {{ $course->course_code }} - {{ $course->course_title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
                @endif

                {{-- Step 3: Submit Timetable Entry --}}
                @if($selectedCourse)
                <form action="{{ url('/admin/newTimetable') }}" method="POST" class="mt-3">
                    @csrf
                    <input type="hidden" name="faculty_id" value="{{ $selectedFaculty }}">
                    <input type="hidden" name="course_id" value="{{ $selectedCourse->id }}">

                    {{-- Display Lecturer --}}
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Lecturer</label>
                        <input type="text" class="form-control" value="{{ $selectedCourse->lecturer->title . ' ' . $selectedCourse->lecturer->lastname . ' ' . $selectedCourse->lecturer->othernames ?? 'N/A' }}" readonly>
                    </div>

                    <div class="row g-3">
                        {{-- Academic Session --}}
                        <div class="col-md-6">
                            <label class="form-label">Academic Session</label>
                            <select name="academic_detail_id" class="form-control" required>
                                @foreach ($academicDetails as $detail)
                                    <option value="{{ $detail->id }}">{{ $detail->academic_year }} - {{ $detail->semester }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Venue --}}
                        <div class="col-md-6">
                            <label class="form-label">Venue</label>
                            <select name="venue_id" class="form-control" required>
                                @foreach ($venues as $venue)
                                    <option value="{{ $venue->id }}">{{ $venue->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-primary">Save Timetable</button>
                    </div>
                </form>
                @endif

            </div>
        </div>
    </div>
</div>




@endsection
