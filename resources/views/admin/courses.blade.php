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

<!-- Course Table -->
<div class="row">
    <div class="col-12">
        <div class="flex-shrink-0 mb-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCourse">Add Course</button>
        </div>
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered dt-responsive nowrap">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Title</th>
                            <th>Unit</th>
                            <th>Faculty</th>
                            <th>Session</th>
                            <th>Lecturer</th>
                            <th>Venue</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($courses as $course)
                        <tr>
                            <td>{{ $course->course_code }}</td>
                            <td>{{ $course->course_title }}</td>
                            <td>{{ $course->course_unit }}</td>
                            <td>{{ $course->faculty->name ?? 'N/A' }}</td>
                            <td>{{ $course->academicDetails->academic_year ?? '' }} - {{ $course->academicDetails->semester ?? '' }}</td>
                            <td>{{ $course->lecturer->title . ' ' . $course->lecturer->lastname }}</td>
                            <td>{{ $course->venue->name ?? 'N/A' }}</td>
                            <td>
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editCourse{{ $course->id }}">Edit</button>
                                <form action="{{ url('/admin/deleteCourse') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="course_id" value="{{ $course->id }}">
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Course Modal -->
                        <div class="modal fade" id="editCourse{{ $course->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <form action="{{ url('/admin/updateCourse') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="course_id" value="{{ $course->id }}">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Course</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Faculty</label>
                                                <select name="faculty_id" class="form-control" required>
                                                    @foreach ($faculties as $faculty)
                                                        <option value="{{ $faculty->id }}" {{ $course->faculty_id == $faculty->id ? 'selected' : '' }}>
                                                            {{ $faculty->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Course Code</label>
                                                <input type="text" name="course_code" class="form-control" value="{{ $course->course_code }}" required>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Course Title</label>
                                                <input type="text" name="course_title" class="form-control" value="{{ $course->course_title }}" required>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Course Unit</label>
                                                <input type="number" name="course_unit" class="form-control" value="{{ $course->course_unit }}" required>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Academic Session</label>
                                                <select name="academic_details_id" class="form-control" required>
                                                    @foreach ($academicDetails as $detail)
                                                        <option value="{{ $detail->id }}" {{ $course->academic_details_id == $detail->id ? 'selected' : '' }}>
                                                            {{ $detail->academic_year }} - {{ $detail->semester }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Lecturer</label>
                                                <select name="lecturer_id" class="form-control" required>
                                                    @foreach ($lecturers as $lecturer)
                                                        <option value="{{ $lecturer->id }}" {{ $course->lecturer_id == $lecturer->id ? 'selected' : '' }}>
                                                            {{ $lecturer->title . ' ' . $lecturer->lastname . ' ' . $lecturer->othernames }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-12">
                                                <label class="form-label">Venue</label>
                                                <select name="venue_id" class="form-control" required>
                                                    @foreach ($venues as $venue)
                                                        <option value="{{ $venue->id }}" {{ $course->venue_id == $venue->id ? 'selected' : '' }}>
                                                            {{ $venue->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Update Course</button>
                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                                        </div>
                                    </div>
                                </form>
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
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form action="{{ url('/admin/newCourse') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Course</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Faculty</label>
                        <select name="faculty_id" class="form-control" required>
                            <option value="">-- Select Faculty --</option>
                            @foreach ($faculties as $faculty)
                                <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Course Code</label>
                        <input type="text" name="course_code" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Course Title</label>
                        <input type="text" name="course_title" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Course Unit</label>
                        <input type="number" name="course_unit" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Academic Session</label>
                        <select name="academic_details_id" class="form-control" required>
                            <option value="">-- Select Session --</option>
                            @foreach ($academicDetails as $detail)
                                <option value="{{ $detail->id }}">{{ $detail->academic_year }} - {{ $detail->semester }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Lecturer</label>
                        <select name="lecturer_id" class="form-control" required>
                            <option value="">-- Select Lecturer --</option>
                            @foreach ($lecturers as $lecturer)
                                <option value="{{ $lecturer->id }}">{{ $lecturer->title . ' ' . $lecturer->lastname . ' ' . $lecturer->othernames }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Venue</label>
                        <select name="venue_id" class="form-control" required>
                            <option value="">-- Select Venue --</option>
                            @foreach ($venues as $venue)
                                <option value="{{ $venue->id }}">{{ $venue->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Add Course</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
