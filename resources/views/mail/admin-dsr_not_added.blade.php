@extends('mail.mail-layout')
@section('content')

<body>
    <p>The following employees have not entered their daily status report.</p>
    <table style="width:100%;">
        <tr>
            <th style="text-align:left;">Employee</th>
        </tr>
        <tbody>
       
        @foreach($usersList as $id => $user)
            <tr>
                <td style="text-align:left;">{{$user['name']}}</td>
            </tr>
        @endforeach
        </tbody> 
    </table>
</body>
@endsection