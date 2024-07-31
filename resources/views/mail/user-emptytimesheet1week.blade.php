@extends('mail.mail-layout')
@section('content')
<body>
    <p> You have not entered on your timesheet for the last week.</p>
<a href="{{ url('/my-timesheet')}}">{{ url('/my-timesheet')}}</a>
</body>
@endsection