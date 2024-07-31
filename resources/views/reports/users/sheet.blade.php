<div class="ibox-content">
	<div class="row">
		<table class="table table-striped listData">
			<thead>
				<tr>

					<th>Employee Name</th>

					<th>Billable Hours</th>

					<th>Non-Billable Hours</th>

					<th>Total Hours</th>

				</tr>

			</thead>
			<tbody>
				@foreach($users as $user)

				<td>{{$user->first_name.' '.$user->last_name}}</td>
				<td>{{floor($user->billed_today/60).'h '.($user->billed_today%60).'m'}}</td>
				<td>{{floor(($user->total-$user->billed_today)/60).'h '.(($user->total-$user->billed_today)%60).'m'}}
				</td>
				<td>{{floor($user->total/60).'h '.($user->total%60).'m'}}</td>

				</tr>
				@endforeach

			</tbody>
		</table>
	</div>
</div>