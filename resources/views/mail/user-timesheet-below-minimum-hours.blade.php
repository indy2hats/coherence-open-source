@extends('mail.mail-layout')
@section('content')
<body>
	<p>You have entered less than {{$minSessionHour}} hours on {{$reminderDate}}. <br/>Please enter it as soon as possible by clicking here <a href="{{ url('/my-timesheet')}}">{{ url('/my-timesheet')}}.</a></p>
</body>
@endsection