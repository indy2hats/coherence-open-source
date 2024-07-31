	<div class="row">
		<table class="table table-striped listData">
			<thead>
				<tr>

					<th>Project Name</th>

					<th>Project Code</th>

					<th>Client/Account</th>

					<th class="time-spent">Time Spent</th>

					<th class="billed-hours">Billed Hours</th>

					<th class="non-billed-hours">Non Billed Hours</th>

					<th>Percentage</th>

					<th></th>

				</tr>

			</thead>
			<tbody>
				@foreach($projects as $project)
					@php 
					$percentage = $project->time_spent > 0 ? number_format(($project->billed_time / $project->time_spent) * 100, 2) : '0.00';
					$style = '';
					$nonBilledTime = max(0, $project->time_spent - $project->billed_time);
					@endphp
					@if((float)$percentage < (float)config('general.project_billability_percentage_cutoff'))
						@php $style = "style='color:red'"; @endphp
					@endif
					<td><a href="/projects/{{$project->id}}" {!!$style!!}>{{$project->project_name}}</td>
					<td {!!$style!!}>{{$project->project_id}}</td>
					<td {!!$style!!}>{{$project->company_name}}</td>
					<td {!!$style!!}>{{floor($project->time_spent/60).'h '.($project->time_spent%60).'m'}}</td>
					<td {!!$style!!}>{{floor($project->billed_time/60).'h '.($project->billed_time%60).'m'}}</td>
					<td {!!$style!!}>{{floor(($nonBilledTime)/60).'h '.(( $nonBilledTime)%60).'m'}}</td>
					<td {!!$style!!}>{{$percentage}} %</td>		

					<td {!!$style!!}><a data-projectId="{{$project->id}}" class="progress-graph"><i class="fa fa-line-chart"></i></a></td>

					</tr>
				@endforeach

			</tbody>
			<tfoot>
				<th></th>
				<th></th>
				<th><strong>Total</strong></th>
				<th class="total-time-spent"></th>
				<th class="total-billed-hours"></th>
				<th></th>
				<th class="percentage"></th>
                <th></th>
			</tfoot>
		</table>
	</div>
<form id="project-progress-graph-form" class="hide d-none" action="{{route('projectBillabilityHoursGraph')}}" method="post">
    @csrf
    <input type="hidden" name="projectId" id="progressGraphProjectId" value="">
    <input type="hidden" name="dateRange" id="progressGraphDateRange" value="">
</form>
