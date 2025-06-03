@extends('admin.layout.dashboard')

@section('content')

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Periods</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item active">Periods</li>
        </ol>
    </div>
</div>

<!-- Periods Table -->
<div class="row">
    <div class="col-12">
        <div class="flex-shrink-0 mb-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPeriod">Add Period</button>
        </div>
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered dt-responsive nowrap">
                    <thead>
                        <tr>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Label</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($periods as $period)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($period->start_time)->format('h:i A') }}</td>
                            <td>{{ \Carbon\Carbon::parse($period->end_time)->format('h:i A') }}</td>
                            <td>{{ $period->label }}</td>
                            <td>
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editPeriod{{ $period->id }}">Edit</button>
                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deletePeriod{{ $period->id }}">Delete</button>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editPeriod{{ $period->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <form action="{{ url('/admin/updatePeriod') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="period_id" value="{{ $period->id }}">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Period</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Start Time</label>
                                                <input type="time" name="start_time" class="form-control" value="{{ $period->start_time }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">End Time</label>
                                                <input type="time" name="end_time" class="form-control" value="{{ $period->end_time }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Label</label>
                                                <select name="label" class="form-control" required>
                                                    <option value="Morning" {{ $period->label == 'Morning' ? 'selected' : '' }}>Morning</option>
                                                    <option value="Afternoon" {{ $period->label == 'Afternoon' ? 'selected' : '' }}>Afternoon</option>
                                                    <option value="Evening" {{ $period->label == 'Evening' ? 'selected' : '' }}>Evening</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Update Period</button>
                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Delete Modal -->
                        <div class="modal fade" id="deletePeriod{{ $period->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-body text-center p-4">
                                        <div class="text-end">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <h5 class="my-4">Are you sure you want to delete this period?</h5>
                                        <form action="{{ url('/admin/deletePeriod') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="period_id" value="{{ $period->id }}">
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

<!-- Add Period Modal -->
<div class="modal fade" id="addPeriod" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-centered modal-md">
        <form action="{{ url('/admin/newPeriod') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Period</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Start Time</label>
                        <input type="time" name="start_time" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">End Time</label>
                        <input type="time" name="end_time" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Label</label>
                        <select name="label" class="form-control" required>
                            <option value="">-- Select Label --</option>
                            <option value="Morning">Morning</option>
                            <option value="Afternoon">Afternoon</option>
                            <option value="Evening">Evening</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Add Period</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
