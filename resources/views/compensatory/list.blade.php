<div class="table-responsive">
    <div class="col-md-12">
        <div>
            <table class="table dataTable">
                <thead>
                    <tr>
                        <th>Date</th>
                        @unlessrole('employee')
                            <th>Employee</th>
                        @endunlessrole
                        <th>Session</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Action Taken By</th>
                        <th>Remarks</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($list as $item)

                        <tr>

                        <td>{{$item->date}}</td>
                        @unlessrole('employee')
                            <td>{{$item->users->full_name}}</td>
                        @endunlessrole
                        <td>{{$item->session}}</td>
                        <td>{!! strip_tags($item->reason) !!}</td>
                        <td>
                            @if ($item->status=='Approved')
                                <span class="badge badge-success">Approved</span>
                            @elseif($item->status == 'Rejected')
                                <span class="badge badge-danger">Rejected</span>
                            @else
                                <span class="badge badge-info">Pending</span>
                            @endif
                        </td>
                        <td>{{ ($item->user_approved) ? $item->user_approved->full_name : '' }}</td>
                        <td>{!! strip_tags($item->reason_for_rejection) !!}</td>
                        <td>

                            <span class="dlt-i">
                                <a href="#" class="delete-item" data-id="{{ $item->id }}" data-tooltip="tooltip" data-placement="top"title="Delete">
                                    <i data-toggle="modal" data-target="#delete_item" class="ri-delete-bin-line" aria-hidden="true"></i>
                                </a>
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
