@extends('admin.layout.dashboard')

@section('content')

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Courses</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item active">Courses</li>
        </ol>
    </div>
</div>

<!-- Courses Table -->
<div class="row">
    <div class="col-12">
        <div class="flex-shrink-0 mb-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCourse">Add Course</button>
        </div>
        <div class="card">
            <div class="card-body">
                <table id="key-table" class="table table-bordered dt-responsive nowrap">
                    <thead>
                        <tr>
                            <th>Faculty</th>
                            <th>Course code</th>
                            <th>Course title</th>
                            <th>Course Unit</th>
                            <th>Lecturer</th>
                            <th>Venue</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($courses as $course)
                        <tr>
                            <td>{{ $course->faculty?->name }}</td>
                            <td>{{ $course->course_code }}</td>
                            <td>{{ $course->course_title }}</td>
                            <td>{{ $course->course_unit }}</td>
                            <td>{{ $course->lecturer->name }}</td>
                            <td>{{ $course->venue}}</td>
                            <td>
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editCourse{{ $course->id }}">Edit</button>
                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteCourse{{ $course->id }}">Delete</button>
                            </td>
                        </tr>

                        <!-- Edit Faculty Modal -->
                        <div class="modal fade" id="editCourse{{ $course->id }}" tabindex="-1" aria-hidden="true">
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
                                                <label>Course_unit</label>
                                                <input type="text" name="course_unit" class="form-control" value="{{ $course-> course_unit }}" required>
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
                        </div>

                        <!-- Delete Faculty Modal -->
                        <div class="modal fade" id="deleteCourse{{ $course->id }}" tabindex="-1" aria-hidden="true">
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
                        </div>

                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Course Modal -->
<div class="modal fade" id="addCourse" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ url('/admin/newCourse') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Course</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <select name="faculty_id" class="form-control" required>
                        <option value="">-- Select Faculty --</option>
                    @foreach($faculties as $faculty)
                        <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                    @endforeach
                    </select>
                </div>

                {{-- <div class="modal-body">
                <div class="mb">
                    <label>Faculty_id</label>
                    <input type="text" name="faculty_id" class="form-control" required>
                </div>
            </div> --}}
            <div class="modal-body">
                <div class="mb">
                    <label>Course_code</label>
                    <input type="text" name="course_code" class="form-control" required>
                </div>
            </div>
            <div class="modal-body">
                <div class="mb">
                    <label>Course_title</label>
                    <input type="text" name="course_title" class="form-control" required>
                </div>
            </div>
             <div class="modal-body">
                <div class="mb">
                    <label>Course_unit</label>
                    <input type="text" name="course_unit" class="form-control" required>
                </div>
            </div>

            <div class="modal-body"> 
                <select name="lecturer_id" class="form-control" required>
                    <option value="">-- Select Lecturer --</option>
                    @foreach($lecturers as $lecturer)
                        <option value="{{ $lecturer->id }}">{{ $lecturer->name }}</option>
                    @endforeach
                </select>
            </div>
            {{-- <div class="modal-body">
                <div class="mb">
                    <label>Lecturer_id</label>
                    <input type="text" name="lecturer_id" class="form-control" required>
                </div>
            </div> --}}

            <div class="modal-body"> 
                <select name="venue" class="form-control" required>
                    <option value="">-- Select Venue --</option>
                    @foreach($venues as $venue)
                        <option value="{{ $venue->name }}">{{ $venue->name }}</option>
                    @endforeach
                </select>
            </div>
            {{-- <div class="modal-body">
                <div class="mb">
                    <label>Venue</label>
                    <input type="text" name="venue" class="form-control" required>
                </div>
            </div> --}}
            <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Add Course</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
        </form>
    </div>
</div>

@endsection
