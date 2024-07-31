<div class="ibox-content">
	<div class="row">
		<table class="table table-striped taskDataTable">
			<thead>
				<tr>
					<th>Employee</th>
					<th>Task</th>
					<th>Time Spent</th>
				</tr>				
			</thead>
			<tbody>
				@forelse($taskTimeData as $data)
					<tr>
						<td>{{$data->user->first_name. ' ' . $data->user->last_name}}</td>
						<td><a href="{{url('/tasks/'.$data->task->id)}}">{{$data->task->title}}</a></td>
						<td>{{ floor(($data->total_hours)/60).'h '.floor(($data->total_hours)%60).'m'}}</td>
					</tr>
				@empty
					<tr>
						<td colspan="7" align="center">No Data Found</td>
					</tr>
				@endforelse
				
			</tbody>
			@if(count($taskTimeData) > 0)
			<tfooter>
				<tr>
					<td colspan="2" align="center"><strong>Total Hours</strong></td>
					<td colspan="1" align="left"><strong>{{ floor(($taskTimeData->sum('total_hours'))/60).'h '.floor(($taskTimeData->sum('total_hours'))%60).'m' }}</strong></td>
				</tr>	
			</tfooter>
			@endif
		</table>
	</div>
</div>