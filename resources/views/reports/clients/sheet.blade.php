<div class="ibox-content">
	<div class="row">
		<table class="table table-striped listData">
			<thead>
				<tr>
					<th>Client / Account</th>

					<th>Billable Hours</th>

					<th>Non-Billable Hours</th>

					<th>Total Hours</th>
				</tr>

			</thead>
			<tbody>
				@foreach($clients as $client)

				<td>{{$client->company_name}}</td>
				<td>{{floor($client->billed_today/60).'h '.($client->billed_today%60).'m'}}</td>
				<td>{{floor(($client->total-$client->billed_today)/60).'h'.(($client->total-$client->billed_today)%60).'m'}}
				</td>
				<td>{{floor($client->total/60).'h '.($client->total%60).'m'}}</td>

				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>