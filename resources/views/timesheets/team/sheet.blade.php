<div class="ibox-content float-e-margins">
	<div class="row">
		<table class="table table-striped table-bordered table-hover teamTimesheetTable">
			<thead>
				<tr>
					<th>Employee</th>
					<th>No of Tasks</th>
					<th>Total Hours</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@foreach($teamTimesheet as $team=>$userTimeSheet)
				<tr>
					@php
					$dateVal = isset($filter['date']) ? DateTime::createFromFormat('d/m/Y', $filter['date'])->format('d-m-Y') : date('d-m-Y');
					@endphp
					<td><a target="_blank" href="{{ route("viewSheetUser", [ "id" =>  $userTimeSheet->reportee_user->id, "date" => $dateVal ]) }}">{{ $userTimeSheet->reportee_user->first_name.' '.$userTimeSheet->reportee_user->last_name  }}
					</a></td>
					<td>{{ $userTimeSheet->reportee_user->users_task_session->isEmpty() ? 0 : $userTimeSheet->reportee_user->users_task_session->first()->task_count }}</td>
					<td> 
						@php
							$time =  $userTimeSheet->reportee_user->users_task_session->first()->total_time ?? 0;
						@endphp 
						{{ floor($time/60) }}h {{ $time%60 }}m
					</td>
					<td><a style="margin-left:10px;" class="delete-team-employee" data-toggle="modal" data-target="#delete_team_employee" data-id="{{$userTimeSheet->id}}" data-tooltip="tooltip" data-placement="top" title="Delete this employee from the team"><i class="fa fa-trash"></i></a></td>
				</tr>
				@endforeach			 
			</tbody>
		</table>
	</div>
</div>
