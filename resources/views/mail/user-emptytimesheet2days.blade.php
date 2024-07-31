
@extends('mail.mail-layout')
@section('content')
<body>
<p>You have not filled timesheet since two days.</p>
<a href="{{ url('/my-timesheet')}}">{{ url('/my-timesheet')}}</a>
</body>
@endsection