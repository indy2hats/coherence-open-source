@extends('layout.main')

@section('content')
<div class="alert alert-danger alert-start">Please Select a User</div>
@include('timesheets.user-monthly.view')
@endsection
@section('after_scripts')
<link href='https://unpkg.com/@fullcalendar/core@4.4.0/main.min.css' rel='stylesheet' />
<link href='https://unpkg.com/@fullcalendar/daygrid@4.4.0/main.min.css' rel='stylesheet' />
<link href='https://unpkg.com/@fullcalendar/timegrid@4.4.0/main.min.css' rel='stylesheet' />
<script src='https://unpkg.com/@fullcalendar/core@4.4.0/main.min.js'></script>
<script src='https://unpkg.com/@fullcalendar/interaction@4.4.0/main.min.js'></script>
<script src='https://unpkg.com/@fullcalendar/daygrid@4.4.0/main.min.js'></script>
<script src='https://unpkg.com/@fullcalendar/timegrid@4.4.0/main.min.js'></script>
<script src="{{ asset('js/resources/timesheets/user-monthly/script-min.js') }}"></script>
<script>
var data = {!! json_encode($data)!!};
</script>
@endsection