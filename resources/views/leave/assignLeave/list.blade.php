<div class="col-lg-12" id="leave_applications">
   <div class="table-responsive">
    <table class="table table-striped leaveTable">
        <thead>
            <tr>
                <th>Employee</th>
                <th>Application Date</th>
                <th>Type</th>
                <th>From</th>
                <th>To</th>
                <th>No: of Days</th>
                {{-- <th>LOP</th> --}}
                <th>Session</th>
                <th>Reason</th>
                <th>Status</th>
                <th class="text-center">Action Taken By</th>
                <th class="text-center">Notes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($allLeaves as $list)
                <tr>
                    <td>{{ $list->users->full_name}}</td>
                    <td>{{ $list->created_at_format}}</td>
                    <td>{{ $list->type}}</td>
                    <td>{{ $list->from_date_format}}</td>
                    <td>{{ $list->to_date_format}}</td>
                    <td>{{ $list->total_leave_days}}</td>
                    {{-- <td>{{ $list->lop }}</td> --}}
                    <td>{{ $list->session }}</td>
                    <td>{!! strip_tags($list->reason) !!}</td>
                    <td>{{ $list->status}}</td>
                    <td>{{ ($list->user_approved) ? $list->user_approved->full_name : '' }}</td>
                    <td>{!! strip_tags($list->reason_for_rejection) !!}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @include('leave.assignLeave.apply')
   </div>
</div>