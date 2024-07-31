@extends('mail.mail-layout')
@section('content')
    <p class="paragraph-text-style" style="color:#000">Here is your one-time password </p>
    <p class="paragraph-text-style" style="color:#000">To login, please enter the below code in the web page.</p>
    <h2 style="color:#000;margin:0"><strong>{{ $user->email_token }}</strong></h2>
    <p class="paragraph-text-style" style="color:#000;margin-top:0px">This code will expire in 10 minutes</p>
    <p class="paragraph-text-style" style="color:#000">This message was automatically generated. Please do not reply to it</p>
@endsection

