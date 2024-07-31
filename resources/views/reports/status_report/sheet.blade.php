<div>
	<div class="row">
		<table class="table table-striped listData">
			<thead>
				<tr>
					<th>Employee</th>
					<th>Today's Update</th>
					<th>Issues Faced</th>
					<th>Tomorrow's Plan</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>
				@forelse($reports as $report)
					<tr>
						<td>{{$report->full_name}}</td>
						<td>{!! nl2br($report->dailyReports[0]->todays_task ?? '') !!}</td>
						<td>{!! nl2br($report->dailyReports[0]->impediments ?? '') !!}</td>
						<td>{!! nl2br($report->dailyReports[0]->tommorows_task ?? '') !!}</td>
						<td>
							@if($holiday) 
									<span class="badge badge-warning">Holiday</span>
							@elseif($weekend)
							<span class="badge badge-success">Weekend</span>
							@else
								@if($report->getLeaveCountAttributeByDate($date) >= 1)
									<span class="badge badge-warning">Leave</span>
								@else
									@if(!$report->checkIfDsrEntered($date))
										<span class="badge badge-danger">Not Added</span>
									@else
										<span class="badge badge-primary">Added</span>
									@endif
								@endif
							@endif
						</td>
					</tr>
				@empty
					<tr>
						<td colspan="4" align="center">No Data Found</td>
					</tr>
				@endforelse
			</tbody>
		</table>
	</div>
</div>