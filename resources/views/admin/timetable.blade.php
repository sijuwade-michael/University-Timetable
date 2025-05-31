@extends('admin.layout.dashboard')

@section('content')
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
                            <th>Faculty</th>
                            <th>Academic Period</th>
                            <th>Course</th>
                            <th>Course Unit</th>
                            <th>Venue</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @isset($timetables)
                            @foreach ($timetables as $timetable)
                                <tr>
                                    <td>{{ $timetable->faculty_id }}</td>
                                    <td>{{ $timetable->academic_period }}</td>
                                    <td>{{ $timetable->course_id }}</td>
                                    <td>{{ $timetable->course_unit }}</td>
                                    <td>{{ $timetable->venue }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editTimetable{{ $timetable->id }}">Edit</button>
                                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteTimetable{{ $timetable->id }}">Delete</button>
                                    </td>
                                </tr>
                            @endforeach
                        @endisset
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Timetable Modal -->
<div class="modal fade" id="addTimetable" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="GET" action="{{ route('admin.timetable') }}">
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

                <!-- Row for Courses, Venue, and Course Unit -->
                <div class="row mb-5">
                    <!-- Courses -->
                    <div class="col-md-4">
                        <label class="form-label">Courses</label>
                        <select id="course_id" name="course_id" class="form-select form-select-sm" required>
                            <option value="">-- Select Courses --</option>
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

                <!-- Academic Period with extra space above -->
                <div class="mb-3">
                    <label class="form-label">Academic Period</label>
                    <input type="text" name="academic_period" class="form-control" required>
                </div>
            </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Add Timetable</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
$('#faculty').change(function() {
    var facultyID = $(this).val();
    if (facultyID) {
        $.ajax({
            url: '/get-courses?faculty_id=' + facultyID,
            type: "GET",
            dataType: "json",
            success: function(data) {
                $('#course').empty();
                $.each(data.courses, function(key, value) {
                    $('#course').append('<option value="' + value.id + '">' + value.name + '</option>');
                });
            }
        });
    }
});
</script>


@endsection


