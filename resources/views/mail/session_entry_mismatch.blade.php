@extends('mail.mail-layout')
@section('content')
<body>
    <p>There are {{ $sessions }} entries reported that have mismatch in start time and created at time.</p>
</body>
@endsection