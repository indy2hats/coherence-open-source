@extends('mail.mail-layout')
@section('content')

<body>
    <p> The following employees have entered less than {{ $minSessionHour }} hours on {{ $reminderDate}}.</p>
	<table style="width:100%;text-align:left;">
		<thead>
			<th>Employee</th>
			<th>Hours</th>
		</thead>
		<tbody>
			@foreach ($mailContent as $mailItem)		
			<tr>
				<td>{{ ucwords($mailItem['name']) }}</td>
				<td>{{ floor($mailItem['total']/60) }} {{ floor($mailItem['total']/60) < 2 ? ' hour' : ' hours '}}
					{{ ($mailItem['total']%60)==0? '':($mailItem['total']%60).' minutes'}}  {{ $mailItem['leave']?? '' }}</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</body>
@endsection