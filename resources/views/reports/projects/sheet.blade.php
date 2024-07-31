<div class="ibox-content">
	<div class="row">
		<table class="table table-striped listData">
			<thead>
				<tr>

					<th>Project Name</th>

					<th>Project Code</th>

					<th>Client/Account</th>

					<th>Billable Hours</th>

					<th>Non-Billable Hours</th>

					<th>Total Hours</th>

				</tr>

			</thead>
			<tbody>
				@foreach($projects as $project)
				<td><a href="/task-report/{{$project->id}}">{{$project->project_name}}
				</td>
				</td>
				<td>{{$project->project_id}}</td>
				<td>{{$project->company_name}}</td>
				<td>{{floor($project->billed_today/60).'h '.($project->billed_today%60).'m'}}</td>
				<td>{{floor(($project->total - $project->billed_today)/60).'h '.(( $project->total -
					$project->billed_today)%60).'m'}}</td>
				<td>{{floor($project->total/60).'h '.($project->total%60).'m'}}</td>

				</tr>
				@endforeach

			</tbody>
		</table>
	</div>
</div>