@extends('mail.mail-layout')
@section('content')
<style>
    td,th{
        padding: 5px 10px;
        text-align: left ;    
   }
</style>
<body>
   <p> The following employees will be considered as leave on {{ $reminderDate}}.</p>
   <table style="width:100%;text-align:left;">
       <thead>
           <th>Employee</th>
       </thead>
       <tbody>
           @foreach ($employees as $employee)		
           <tr>
               <td>{{ ucwords($employee) }}</td>
           </tr>
           @endforeach
       </tbody>
   </table>
</body>
@endsection