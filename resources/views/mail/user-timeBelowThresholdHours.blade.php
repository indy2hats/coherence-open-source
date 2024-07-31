@extends('mail.mail-layout')
@section('content')
<body>
    <p> You have entered less than {{ $workHoursForTheWeek }} hours last week. Please make sure you haven't missed anything on your timesheet.</p>
    <a href="{{ url('/my-timesheet')}}">{{ url('/my-timesheet')}}</a>
</body>
@endsection