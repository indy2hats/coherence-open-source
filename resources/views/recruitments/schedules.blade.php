@extends('layout.main')
@section('content')
    <div class="row">
        <div class="col-md-10">
            <strong>
                <h2 class="page-title">Recruitment Schedules</h3>
            </strong>
        </div>
        <div class="col-md-2 text-right float-right ml-auto m-b-30 recruitment-date-filter">
            <input class="form-control datetimepicker" type="text" name="schedule_search" id="schedule_search" value="{{$date}}">
        </div>
    </div>

    <div class="content-div animated fadeInUp schedule" id="list">
        @include('recruitments.schedule')
    </div>

@endsection
@section('after_scripts')
<link href="{{ asset('css/plugins/footable/footable.core.css')}}" rel="stylesheet">
<script src="{{ asset('js/plugins/footable/footable.all.min.js')}}"></script>
<link href="{{ asset('css/plugins/clockpicker/clockpicker.css')}}" rel="stylesheet">
<script src="{{ asset('js/plugins/clockpicker/clockpicker.js')}}"></script>
<script src="{{ asset('js/resources/recruitments/script-min.js') }}"></script>
@endsection
