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
                                    <td>{{ $timetable->faculty->name ?? 'N/A' }}</td>
                                    <td>{{ $timetable->academic_period }}</td>
                                    <td>{{ $timetable->course->title ?? 'N/A' }}</td>
                                    <td>{{ $timetable->course_unit }}</td>
                                    <td>{{ $timetable->venue }}</td>
                                    <td>
                                        <a href="{{ route('timetables.edit', $timetable->id) }}" class="btn btn-sm btn-info">Edit</a>
                                        <form action="{{ route('timetables.destroy', $timetable->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
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
        <form method="POST" action="{{ url('') }}">
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
                        @error('faculty_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Row for Course, Venue, and Course Unit -->
                    <div class="row mb-4">
                        <!-- Course -->
                        <div class="col-md-4">
                            <label for="course_id" class="form-label">Course</label>
                            <select id="course_id" name="course_id" class="form-select" required>
                                <option value="">-- Select Course --</option>
                            </select>
                            @error('course_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Venue -->
                        <div class="col-md-4">
                            <label for="venue" class="form-label">Venue</label>
                            <select id="venue" name="venue" class="form-select" required>
                                <option value="">-- Select Venue --</option>
                            </select>
                            @error('venue')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Course Unit -->
                        <div class="col-md-4">
                            <label for="course_unit" class="form-label">Course Unit</label>
                            <select id="course_unit" name="course_unit" class="form-select" required>
                                <option value="">-- Select Course Unit --</option>
                            </select>
                            @error('course_unit')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <!-- Academic Period -->
                    <div class="mb-3">
                        <label class="form-label">Academic Period</label>
                        <input type="text" name="academic_period" class="form-control" required>
                        @error('academic_period')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
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
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const facultySelect = document.getElementById('faculty_id');
    const courseSelect = document.getElementById('course_id');
    const venueSelect = document.getElementById('venue');
    const courseUnitSelect = document.getElementById('course_unit');

    facultySelect.addEventListener('change', function () {
        const facultyId = this.value;
        courseSelect.innerHTML = '<option value="">Loading...</option>';
        axios.get("{{ url('admin/getCourses') }}", {
            params: { faculty_id: facultyId }
        }).then(response => {
            const courses = response.data.courses;
            courseSelect.innerHTML = '<option value="">-- Select Course --</option>';
            courses.forEach(course => {
                const option = document.createElement('option');
                option.value = course.id;
                option.textContent = course.title;
                courseSelect.appendChild(option);
            });
            // Reset related fields
            venueSelect.innerHTML = '<option value="">-- Select Venue --</option>';
            courseUnitSelect.innerHTML = '<option value="">-- Select Course Unit --</option>';
        }).catch(error => {
            courseSelect.innerHTML = '<option value="">Failed to load courses</option>';
            console.error(error);
        });
    });

    courseSelect.addEventListener('change', function () {
        const courseId = this.value;
        venueSelect.innerHTML = '<option value="">Loading...</option>';
        courseUnitSelect.innerHTML = '<option value="">Loading...</option>';
        axios.get("{{ url('admin/getCourseDetails') }}", {
            params: { course_id: courseId }
        }).then(response => {
            const course = response.data.course;
            venueSelect.innerHTML = `<option value="${course.venue}">${course.venue}</option>`;
            courseUnitSelect.innerHTML = `<option value="${course.course_unit}">${course.course_unit}</option>`;
        }).catch(error => {
            venueSelect.innerHTML = '<option value="">Failed to load</option>';
            courseUnitSelect.innerHTML = '<option value="">Failed to load</option>';
            console.error(error);
        });
    });
});
</script>
@endpush
