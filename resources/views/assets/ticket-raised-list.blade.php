
		<div>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Name</th>
						<th>Configuration</th>
						<th>Serial Number</th>
						<th>Raised By</th>
						<th>Type of Complaint</th>
						<th>Reason</th>
						<th>Status</th>
						<th>Resolving Status</th>
						@can('view-assets')<th>Action</th>@endcan
					</tr>
				</thead>
				<tbody>
					@if(count($assets) > 0)
					@foreach ($assets as $viewAsset)
					<tr>
						<td>
							{{ $viewAsset->asset->name ?? ''}}
						</td>
						<td>{{ $viewAsset->asset->configuration  ?? ''}}</td>
						<td>{{ $viewAsset->asset->serial_number ?? ''}}</td>
						<td>{{ $viewAsset->user->full_name }}</td>
						<td>{{ $viewAsset->type ?? ''}}</td>
						<td>{!! nl2br(trim($viewAsset->issue)) !!}</td>
						<td>@if($viewAsset->status == 'open')
							<label class="label label-danger">{{ ucwords(str_replace('_', ' ', $viewAsset->status)) }}</label>
							@endif
							@if($viewAsset->status == 'closed')
							<label class="label label-success">{{ ucwords(str_replace('_', ' ', $viewAsset->status)) }}</label>
							@endif
							</td>
							<td> {{ $viewAsset->ticketStatus->title ?? '' }}
								</td>
						@can('manage-assets')
						<td>
							<a class="dropdown-item ticket-status-edit {{ $viewAsset->status == 'closed' ? 'hide-link': ''}}" href="#" data-toggle="modal"
								data-target="#ticket_status_edit" data-id="{{ $viewAsset->id }}" data-tooltip="tooltip"
								data-placement="top" title="Edit Status"><i class="ri-add-line m-r-5"></i>
							</i></a>
							@can('ticket-assets')
							<a class="dropdown-item ticket-raise-edit-asset {{ $viewAsset->status_id != NULL ? 'hide-link': ''}}" href="#" data-id="{{ $viewAsset->id }}"
								data-tooltip="tooltip" data-placement="top" title="Edit Ticket"> <i
									class="ri-pencil-line m-r-5"></i></a>
							@endcan
							<a class="dropdown-item" href="assets/{{ $viewAsset->asset->id }}"  data-tooltip="tooltip"
								data-placement="top" title="View"><i class="ri-eye-line"></i></a>
						</td>
						@endcan
					</tr>
					@endforeach
					@else
					<tr>
						 <td colspan="7" align="center">No Data Found</td>
					</tr>
					@endif
				</tbody>
			</table>
			<div class="pagination-div">
				{{$assets->links()}}
			</div>
	</div>