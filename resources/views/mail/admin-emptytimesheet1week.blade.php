@extends('mail.mail-layout')
@section('content')
<body>
<p>The following employees have not filled their timesheets since one week.</p>
<table style="width:100%;">
       
        <thead>
            <tr>
                <th style="text-align:left;">Employee List</th>

            </tr>

        </thead>
       
        <tbody>
        @foreach($data as $user)
            <tr>
             <td style="text-align:left;"><a href="{{ url('weekly-report/users/'.$user->id)}}">{{$user->first_name." ".$user->last_name}}</td> 
            </tr>
            @endforeach
        </tbody>

    </table>
</body>
@endsection