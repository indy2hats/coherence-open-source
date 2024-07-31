<div class="ibox-content">
	<div class="row">
		<table class="table table-striped listData">
			<thead>
				<tr>
					<th>Employee</th>
					<th>Worked Hours</th>
					<th>Billable Hours</th>
					<th>Tasks Rejection Rate</th>
					<th>Working Days</th>
					<th>Paid Leaves</th>
					<th>Score</th>
				</tr>
				
			</thead>
			<tbody>
				@forelse($users as $user)
					<tr>
						<td>{{$user->full_name}}</td>
						<td>{{round(($user->users_task_session->sum('total')/60), 2)}}</td>
						<td>{{round(($user->users_task_session->sum('billed_today')/60), 2)}}</td>
						<td>@if($user->users_task_rejection->count() != 0 && $user->users_task->count() != 0)
							{{round(($user->users_task_rejection->count()/($user->users_task->count()))*100, 2)}}% @else
							0% @endif</td>
						<td>{{$workingDays}}</td>
						<td>{{$user->getLeaveCountAttribute($fromDate, $toDate)}}</td>
						@php 
							$leaveCount = $workingDays - $user->getLeaveCountAttribute($fromDate, $toDate);
							if ($leaveCount == 0) {
								$score = 0;
							} else {
								$score = ($user->users_task_session->sum('billed_today')/60)/($leaveCount*8);
							}
						@endphp
						<td>{{round($score*100,2)}}%</td>
					</tr>
				@empty
					<tr>
						<td colspan="7" align="center">No Data Found</td>
					</tr>
				@endforelse
			</tbody>
		</table>
	</div>
</div>