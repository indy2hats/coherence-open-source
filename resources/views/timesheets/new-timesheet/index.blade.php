@extends('layout.main')

@section('content')
<div class="list animated fadeInUp">
    @include('timesheets.new-timesheet.view')
</div>
<div class="text-center">
    <img src="{{asset('images/loading.gif')}}" width="200" class="loading hidden " />
</div>
@endsection
@section('after_scripts')
<script src="{{ asset('js/resources/timesheets/new-timesheet/script-min.js') }}"></script>
@endsection