@extends('admin.layout.dashboard')

@section('content')
<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Academic Details</h4>
    </div>
    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item active">Academic Details</li>
        </ol>
    </div>
</div>


<!-- Add Button -->
<div class="row mb-3">
    <div class="col-12">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAcademicDetail">Add Academic Detail</button>
    </div>
</div>

<!-- Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered dt-responsive nowrap">
                    <thead>
                        <tr>
                            <th>Academic Year</th>
                            <th>Semester</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($academicDetails as $detail)
                        <tr>
                            <td>{{ $detail->academic_year }}</td>
                            <td>{{ $detail->semester }}</td>
                            <td>
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editAcademicDetail{{ $detail->id }}">Edit</button>
                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAcademicDetail{{ $detail->id }}">Delete</button>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editAcademicDetail{{ $detail->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <form action="{{ url('/admin/updateAcademicDetail') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="academic_detail_id" value="{{ $detail->id }}">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Academic Detail</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Academic Year</label>
                                                <input type="text" name="academic_year" class="form-control" value="{{ $detail->academic_year }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Semester</label>
                                                <select name="semester" class="form-control" required>
                                                    <option value="Harmattan" {{ $detail->semester == 'Harmattan' ? 'selected' : '' }}>Harmattan</option>
                                                    <option value="Rain" {{ $detail->semester == 'Rain' ? 'selected' : '' }}>Rain</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Update</button>
                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Delete Modal -->
                        <div class="modal fade" id="deleteAcademicDetail{{ $detail->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <form action="{{ url('/admin/deleteAcademicDetail') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="academic_detail_id" value="{{ $detail->id }}">
                                    <div class="modal-content">
                                        <div class="modal-body text-center p-4">
                                            <div class="text-end">
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <h5 class="mb-3">Are you sure you want to delete this academic detail?</h5>
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                            </div>
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

<!-- Add Academic Detail Modal -->
<div class="modal fade" id="addAcademicDetail" tabindex="-1">
    <div class="modal-dialog modal-centered modal-md">
        <form action="{{ url('/admin/newAcademicDetail') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Academic Detail</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Academic Year</label>
                        <input type="text" name="academic_year" class="form-control" placeholder="e.g. 2024/2025" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Semester</label>
                        <select name="semester" class="form-control" required>
                            <option value="">-- Select Semester --</option>
                            <option value="Harmattan">Harmattan</option>
                            <option value="Rain">Rain</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Add</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
