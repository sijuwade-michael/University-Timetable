@extends('admin.layout.dashboard') @section('content')

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
<!-- KeyTable Datatable -->
<div class="row">
    <div class="col-12">
        <div class="flex-shrink-0 mb-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPeriod">Add Period</button>
        </div>
        <div class="card">
            <div class="card-body">
                <table id="key-table" class="table table-bordered dt-responsive nowrap">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                     @foreach ($periods as $period)
                        
                     <tr>
                    <td>{{ substr($period->start_time, 0, 5) }} - {{ substr($period->end_time, 0, 5) }}</td>
                            <td>
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editPeriod{{ $period->id }}">Edit</button>
                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deletePeriod{{ $period->id }}">Delete</button>
                            </td>
                        </td>
                     </tr>
                     

                     <!-- Edit Faculty Modal -->
                        <div class="modal fade" id="editPeriod{{ $period->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="{{ url('/admin/updatePeriod') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="period" value="{{ $period->id }}" />
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Period</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="start_time">Start Time</label>
                                            <select name="start_time" class="form-control" required>
                                                <option value="">-- Select Start Time --</option>
                                                @for ($hour = 8; $hour <= 17; $hour++)
                                                <option value="{{ sprintf('%02d:00', $hour) }}" {{ old('end_time') == sprintf('%02d:00', $hour) ? 'selected' : '' }}>
                                                            {{ $hour }}:00
                                                        </option>
                                                        <option value="{{ sprintf('%02d:30', $hour) }}" {{ old('end_time') == sprintf('%02d:30', $hour) ? 'selected' : '' }}>
                                                            {{ $hour }}:30
                                                        </option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="end_time">End Time</label>
                                                <select name="end_time" class="form-control" required>
                                                    <option value="">-- Select End Time --</option>
                                                    @for ($hour = 8; $hour <= 18; $hour++)
                                                        <option value="{{ sprintf('%02d:00', $hour) }}" {{ old('end_time') == sprintf('%02d:00', $hour) ? 'selected' : '' }}>
                                                            {{ $hour }}:00
                                                        </option>
                                                        <option value="{{ sprintf('%02d:30', $hour) }}" {{ old('end_time') == sprintf('%02d:30', $hour) ? 'selected' : '' }}>
                                                            {{ $hour }}:30
                                                        </option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Update Period</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Delete Faculty Modal -->
                        <div class="modal fade" id="deletePeriod{{ $period->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-body text-center p-4">
                                        <div class="text-end">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <h5 class="my-4">
                                            Are you sure you want to delete <br />
                                            <strong>{{ $period->name }}</strong>?
                                        </h5>
                                        <form action="{{ url('/admin/deletePeriod') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="period" value="{{ $period->id }}" />
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
    <!-- Add Faculty Modal -->
<div class="modal fade" id="addPeriod" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ url('/admin/newPeriod') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Period</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                <div class="mb-3">
                    <label for="start_time">Start Time</label>
                    <select name="start_time" class="form-control" required>
                        <option value="">-- Select Start Time --</option>
                        @for ($hour = 8; $hour <= 17; $hour++)
                           <option value="{{ sprintf('%02d:00', $hour) }}" {{ old('end_time') == sprintf('%02d:00', $hour) ? 'selected' : '' }}>
                                    {{ $hour }}:00
                                </option>
                                <option value="{{ sprintf('%02d:30', $hour) }}" {{ old('end_time') == sprintf('%02d:30', $hour) ? 'selected' : '' }}>
                                    {{ $hour }}:30
                                </option>
                        @endfor
                    </select>
                </div>
                <div class="mb-3">
                    <label for="end_time">End Time</label>
                        <select name="end_time" class="form-control" required>
                            <option value="">-- Select End Time --</option>
                            @for ($hour = 8; $hour <= 18; $hour++)
                                <option value="{{ sprintf('%02d:00', $hour) }}" {{ old('end_time') == sprintf('%02d:00', $hour) ? 'selected' : '' }}>
                                    {{ $hour }}:00
                                </option>
                                <option value="{{ sprintf('%02d:30', $hour) }}" {{ old('end_time') == sprintf('%02d:30', $hour) ? 'selected' : '' }}>
                                    {{ $hour }}:30
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save Period</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
