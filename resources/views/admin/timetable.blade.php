@extends('admin.layout.dashboard') @section('content')

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Timetables</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item active">Timetables</li>
        </ol>
    </div>
</div>
<!-- KeyTable Datatable -->
<div class="row">
    <div class="col-12">
        <div class="flex-shrink-0 mb-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTimetable">Add Timetable</button>
        </div>
        <div class="card">
            <div class="card-body">
                <table id="key-table" class="table table-bordered dt-responsive nowrap">
                    <thead>
                        <tr>
                            <th>faculty_id</th>
                            <th>academic_period</th>
                            <th>course_id</th>
                            <th>course_unit</th>
                            <th>venue</th>
                            {{-- <th>lecture_arrangement</th> --}}
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                         @foreach ($timetables as $timetable)
                        <tr>
                            <td>{{ $timetable->faculty_id }}</td>
                            <td>{{ $timetable->course_id }}</td>
                            <td>{{ $timetable->course_unit }}</td>
                            <td>{{ $timetable->venue }}</td>
                            <td>{{ $timetable->academic_period }}</td>
                            {{-- <td>{{ $timetable->lecture_arrangement}}</td> --}}
                            <td>
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editTimetable{{ $timetable->id }}">Edit</button>
                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteTimetable{{ $Timetable->id }}">Delete</button>
                            </td>
                        </tr>


                         <!-- Edit Faculty Modal -->
                        {{-- <div class="modal fade" id="editCourse{{ $course->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="{{ url('/admin/updateCourse') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="course_id" value="{{ $course->id }}"/>
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Course</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">
                                            <select name="faculty_id" class="form-control" required>
                                                    <option value="">-- Select Faculty --</option>
                                            @foreach($faculties as $faculty)
                                                    <option value="{{ $faculty->name }}">{{ $faculty->name }}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                        {{-- <div class="modal-body">
                                            <div class="mb-3">
                                                <label>Faculty_id</label>
                                                <input type="text" name="faculty_id" class="form-control" value="{{ $course-> faculty_id }}" required>
                                            </div>
                                        </div> --}}
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label>Course_code</label>
                                                <input type="text" name="course_code" class="form-control" value="{{ $course-> course_code }}" required>
                                            </div>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label>Course_title</label>
                                                <input type="text" name="course_title" class="form-control" value="{{ $course-> course_title }}" required>
                                            </div>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label>Lecturer_id</label>
                                                <input type="text" name="lecturer_id" class="form-control" value="{{ $course-> lecturer_id }}" required>
                                            </div>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label>Venue</label>
                                                <input type="text" name="venue" class="form-control" value="{{ $course-> venue }}" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Update Course</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div> --}}

                        <!-- Delete Faculty Modal -->
                        {{-- <div class="modal fade" id="deleteCourse{{ $course->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-body text-center p-4">
                                        <div class="text-end">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <h5 class="my-4">Are you sure you want to delete <br><strong>{{ $course->name }}</strong>?</h5>
                                        <form action="{{ url('/admin/deleteCourse') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="course_id" value="{{ $course->id }}">
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div> --}}

                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

    <!-- Add Course Modal -->
    <div class="modal fade" id="addTimetable" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ url('/admin/newTimetable') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Timetable</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <!-- Faculty Dropdown -->
                    <div class="mb-3">
                        <label for="faculty_id" class="form-label">Faculty</label>
                        <select id="faculty_id" name="faculty_id" class="form-select" required>
                            <option value="">-- Select Faculty --</option>
                            @foreach($faculties as $faculty)
                                <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Course Codes, Venue, Course Unit in a row -->
                    {{-- <div class="row">
                        <!-- Course Codes -->
                        <div class="col-md-6">
                            <label class="form-label">Course Codes</label>

                            <!-- Search Input -->
                            <input type="text" id="courseSearch" class="form-control form-control-sm mb-2" placeholder="Search course code...">

                            <!-- Scrollable List -->
                            <div class="border p-2 rounded" style="max-height: 150px; overflow-y: auto;">
                                <!-- Small Select All button inside -->
                                <div class="d-flex justify-content-end mb-1">
                                    <button type="button" class="btn btn-sm btn-outline-success py-0 px-2" id="selectAllCourses" title="Select All">
                                        ✓ All
                                    </button>
                                </div>

                                <!-- Course List -->
                                <div id="courseSearch" class="list-group list-group-flush">
                                    @foreach($courses as $course)
                                        <label class="list-group-item d-flex justify-content-between align-items-center p-1">
                                            <span class="course-code-text">{{ $course->course_code }}</span>
                                            <input type="checkbox" name="course_id[]" value="{{ $course->id }}" class="course-checkbox form-check-input" />
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>  --}} 
                         <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Courses</label>
                                <select id="course_id" name="course_id" class="form-select form-select-sm" required>
                                    @foreach ($faculties as $faculty )
                                        <option value="{{ $course->course_id }}">{{ $course->course_code . ' ' . $course->course_title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Venue -->
                            <div class="col-md-4">
                                <label class="form-label">Venue</label>
                                <select id="venue" name="venue" class="form-select form-select-sm" required>
                                    <option value="">-- Select Venue --</option>
                                </select>
                            </div>

                            <!-- Course Unit -->
                            <div class="col-md-4">
                                <label class="form-label">Course Unit</label>
                                <select id="course_unit" name="course_unit" class="form-select form-select-sm" required>
                                    <option value="">-- Select Course Unit --</option>
                                </select>
                            </div>
                        </div>

                        <!-- Academic Period -->
                        <div class="mb-3">
                            <label class="form-label">Academic Period</label>
                            <input type="text" name="academic_period" class="form-control" required>
                        </div>
                    
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Add Timetable</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>




@endsection
