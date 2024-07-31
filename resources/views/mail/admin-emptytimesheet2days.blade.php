@extends('mail.mail-layout')
@section('content')

<body>
<p>The following employees have not entered their timesheet. </p>
<table style="width:100%;">
        
        <thead>
            <tr>
                <th style="text-align:left;">Employee List</th>

            </tr>

        </thead>
       
        <tbody>
        @foreach($datas as $users)
            <tr>                
                <td style="text-align:left;">{{$users->first_name}}</td>
            </tr>
            @endforeach
        </tbody>

    </table>
</body>
@endsection