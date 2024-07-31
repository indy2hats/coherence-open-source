<div class="task-session">

<div class="table-wrapper">

    <div class="table-responsive">


        <table class="table table-hover sessionTable">

            <thead>

                <tr>
                    <th>Employee</th>
                    <th>Date</th>
                    <th>Type of Complaint</th>
                    <th>Comments</th>
                    <th>Status</th>
                    <th>Resolving Status</th>
                    @can('view-assets')<th>Action</th>@endcan
                </tr>

            </thead>

            <tbody>
                @forelse($asset->assetTicket as $list)
                <tr>
                    <td>{{$list->user->full_name ?? 'Deleted User' }}</td>
                    <td>{{\Carbon\Carbon::parse($list->created_at)->format('M d, Y')}}</td>
                    <td>{{$list->type ?? '' }}</td>
                    <td>{!! nl2br(trim($list->issue)) !!}</td>
                    <td>@if($list->status == 'open')
                    <label class="label label-danger">{{ ucwords(str_replace('_', ' ', $list->status)) }}</label>
                    @endif
                    @if($list->status == 'closed')
                    <label class="label label-success">{{ ucwords(str_replace('_', ' ', $list->status)) }}</label>
                    @endif
                    </td>
                    <td>{{$list->ticketStatus->title ?? '' }}</td>
                    @can('manage-assets')
						<td>
							<a class="dropdown-item ticket-status-edit {{ $list->status == 'closed' ? 'hide-link': ''}}" href="#" data-toggle="modal"
								data-target="#ticket_status_edit" data-id="{{ $list->id }}" data-tooltip="tooltip"
								data-placement="top" title="Edit Status"><i class="ri-add-line m-r-5"></i>
							</i></a>
                            @can('ticket-assets')
							<a class="dropdown-item ticket-raise-edit-asset {{ $list->status_id != NULL ? 'hide-link': ''}}" href="#" data-id="{{ $list->id }}"
								data-tooltip="tooltip" data-placement="top" title="Edit Ticket"> <i
									class="ri-pencil-line m-r-5"></i></a>
							@endcan
						</td>
                        
						@endcan

                        

                </tr>
                @empty
                <tr>
                    <td colspan="6" align="center">
                        No Tickets Raised
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>



</div>
</div>
