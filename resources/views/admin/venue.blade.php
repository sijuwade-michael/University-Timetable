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
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addApplicant">Add Timetable</button>
    </div>
                                <div class="card">
                                    <div class="card-body">
                                        <table id="key-table" class="table table-bordered dt-responsive nowrap">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Position</th>
                                                    <th>Office</th>
                                                    <th>Age</th>
                                                    <th>Start date</th>
                                                    <th>Salary</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>John Smith</td>
                                                    <td>Project Manager</td>
                                                    <td>Los Angeles</td>
                                                    <td>35</td>
                                                    <td>2023-08-10</td>
                                                    <td>$110,000</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>

                        </div>

@endsection
