<div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    @can('manage-assets')<th>Action</th>@endcan
                </tr>
            </thead>
            <tbody>
                @if(count($assetTicketStatus) > 0)
                @foreach ($assetTicketStatus as $status)
                <tr>
                    <td>
                        {{ $status->title }}
                    </td>
                    <td>
                        {{ $status->description }}
                    </td>
                    @can('manage-assets')
                    <td>
                        <a class="dropdown-item edit-ticket-status" href="#" data-id="{{ $status->id }}"
                            data-tooltip="tooltip" data-placement="top" title="Edit"> <i
                                class="ri-pencil-line m-r-5"></i></a>
                        <a class="dropdown-item delete-ticket-status" href="#" data-toggle="modal"
                            data-target="#delete_ticket_status" data-id="{{ $status->id }}" data-tooltip="tooltip"
                            data-placement="top" title="Delete"><i class="ri-delete-bin-line m-r-5"></i></a>
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
            {{$assetTicketStatus->links()}}
        </div>
</div>