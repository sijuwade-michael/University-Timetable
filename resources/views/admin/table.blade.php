@extends('admin.layout.dashboard')

@section('content')
<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Timetable</h4>
    </div>
    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item active">Timetable</li>
        </ol>
    </div>
</div>

<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Table
                </div>

                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Time</th>
                                @foreach(array_keys($tables) as $day)
                                    <th>{{ $day }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                // Collect all unique timeslots across days
                                $allTimeslots = [];
                                foreach ($tables as $day => $timeslots) {
                                    foreach ($timeslots as $timeslot => $courses) {
                                        if (!in_array($timeslot, $allTimeslots)) {
                                            $allTimeslots[] = $timeslot;
                                        }
                                    }
                                }
                                sort($allTimeslots); // Sort timeslots ascending
                            @endphp

                            @foreach($allTimeslots as $timeslot)
                                <tr>
                                    <td>{{ $timeslot }}</td>
                                    @foreach(array_keys($tables) as $day)
                                        <td>
                                            @if(isset($tables[$day][$timeslot]))
                                                @foreach($tables[$day][$timeslot] as $course)
                                                    <div>{{ $course }}</div>
                                                @endforeach
                                            @else
                                                &nbsp;
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
@endsection
