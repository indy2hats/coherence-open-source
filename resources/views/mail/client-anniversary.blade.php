@extends('mail.mail-layout')
@section('content')
<style>
    td,th{
        padding: 5px 10px;
        text-align: left ;    
   }
</style>
<body>
   <p>Let's celebrate the work anniversaries of our esteemed clients!</p>
   <table style="width:100%;text-align:left;">
       <thead>
           <th>Clients with anniversary on {{ $anniversaryDate}}:</th>
       </thead>
       <tbody>
           @foreach ($clients as $client)	
           <tr>
            <td>{{ ucwords($client->company_name) }} ({{$client->acquisition_years}} {{ $client->acquisition_years ==1 ? 'year' : 'years' }}) </td>
           </tr>
           @endforeach
       </tbody>
   </table>
</body>
@endsection