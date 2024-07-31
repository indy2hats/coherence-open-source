<div class="ibox-content">
	<div class="row">
		<table class="table table-striped listData">
			<thead>
				<tr>
					<th>Employee</th>
					<th>Task</th>
					<th>Project</th>
					<th>Rejected Date</th>
					<th>Rejected By</th>
					<th>Reason</th>
					<th>Severity</th>
				</tr>
				
			</thead>
			<tbody>
				@forelse($tasks as $task)
					<tr>
						<td>{{$task->users->full_name}}</td>
						<td><a href="{{url('/tasks/'.$task->task->id)}}">{{$task->task->title}}</a></td>
						<td><a href="{{url('/projects/'.$task->task->project->id)}}">{{$task->task->project->project_name}}</a></td>
						<td>{{$task->updated_at->format('d/m/Y')}}</td>
						<td>{{$task->rejectedBy->full_name ?? ''}}</td>
						<td>@foreach($task->issue as $reason)
							<span class="label-plain block" style="margin: 2px;">
							{{$reason}} </span>
							@endforeach
                        </td>
						<td>{{$task->severity}}</td>
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