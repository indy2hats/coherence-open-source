@extends('mail.mail-layout')
@section('content')

<body>
    <p>The following employees have not entered their quota of weekly hours on their timesheets.</p>
    <table style="width:100%;text-align:left;">
        <tr>
        <th>Employee</th>
        <th>Days Worked</th>
        <th>Time Spent</th>
        </tr>
        <tbody>
        
        @foreach($userWithoutNeededHours as $id => $user)
        <tr>
            <td><a href="{{ url('weekly-report/users/'.$id)}}">{{$user['name']}}</a></td>
            <td>{{$user['days_worked']}}</td>
            <td>{{floor($user['total']/60).'h '.($user['total']%60).'m'}}</td>
        </tr>

        @endforeach
        </tbody>
    </table>
</body>
@endsection

