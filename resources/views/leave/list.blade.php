<div class="col-lg-12" id="leave_applications">
    <table class="table table-striped leaveTable">
        <thead>
            <tr>
                <th>Application Date</th>
                <th>Type</th>
                <th>From</th>
                <th>To</th>
                <th>No: of Days</th>
                {{-- <th>LOP</th> --}}
                <th>Session</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Action Taken By</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($allLeaves as $list)
                <tr>
                    <td>{{ $list->created_at_format}}</td>
                    <td>{{ $list->type}}</td>
                    <td>{{ $list->from_date_format}}</td>
                    <td>{{ $list->to_date_format}}</td>
                    <td>{{ $list->total_leave_days}}</td>
                    {{-- <td>{{ $list->lop }}</td> --}}
                    <td>{{ $list->session }}</td>
                    <td>{!! $list->reason !!}</td>
                    <td>{{ $list->status}}</td>
                    <td>{{ ($list->user_approved) ? $list->user_approved->full_name : '' }}</td>
                    @if($list->status == "Approved" && $list->to_date > date('Y-m-d'))
                    <td><a class="cancel_leave" data-toggle="modal" data-target="#cancel_leave" data-id="{{$list->id}}"><i class="fa fa-undo" aria-hidden="true"></i> Cancel</a></td>
                    @elseif($list->status == "Waiting")
                        <td><a class="delete_leave" data-toggle="modal" data-target="#delete_leave" data-id="{{$list->id}}" data-tooltip="tooltip" data-placement="top" title="Delete"><i class="ri-delete-bin-line"></i></a></td>
                    @else
                    <td>{!!$list->reason_for_rejection!!}</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
    @include('leave.apply')
</div>