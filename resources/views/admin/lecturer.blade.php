@extends('admin.layout.dashboard')

@section('content')

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Faculties</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item active">Faculties</li>
        </ol>
    </div>
</div>

<!-- Faculties Table -->
<div class="row">
    <div class="col-12">
        <div class="flex-shrink-0 mb-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLecturer">Add Lecturer</button>
        </div>
        <div class="card">
            <div class="card-body">
                <table id="key-table" class="table table-bordered dt-responsive nowrap">
                    <thead>
                        <tr>
                            <th>Lastname</th>
                            <th>Othernames</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lecturers as $lecturer)
                        <tr>
                            <td>{{ $lecturer->last_name }}</td>
                            <td>{{ $lecturer->other_names }}</td>
                            <td>
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editLecturer{{ $lecturer->id }}">Edit</button>
                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteLecturer{{ $lecturer->id }}">Delete</button>
                            </td>
                        </tr>

                        <!-- Edit Faculty Modal -->
                        <div class="modal fade" id="editLecturer{{ $lecturer->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="{{ url('/admin/updateLecturer') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="lecturer_id" value="{{ $lecturer->id }}"/>
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Lecturer</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label>>Title *</label>
                                                <input type="text" name="title" class="form-control" value="{{ $lecturer->title }}" required>
                                            </div>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label>>Lastname *</label>
                                                <input type="text" name="last_name" class="form-control" value="{{ $lecturer->last_name }}" required>
                                            </div>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label>>Othernames *</label>
                                                <input type="text" name="other_names" class="form-control" value="{{ $lecturer->other_names }}" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Update Lecturer</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- <!-- Delete Faculty Modal -->
                        <div class="modal fade" id="deleteFaculty{{ $faculty->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-body text-center p-4">
                                        <div class="text-end">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <h5 class="my-4">Are you sure you want to delete <br><strong>{{ $faculty->name }}</strong>?</h5>
                                        <form action="{{ url('/admin/deleteFaculty') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="faculty_id" value="{{ $faculty->id }}">
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

<!-- Add Faculty Modal -->
<div class="modal fade" id="addLecturer" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ url('/admin/newLecturer') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Lecturer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3">
                        <label>Title *</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Lastname *</label>
                        <input type="text" name="last_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Othernames *</label>
                        <input type="text" name="other_names" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Add Lecturer</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
