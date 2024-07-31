<div class="ibox-content">    
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>#</th>
                <th>Employee</th>
                @foreach($dates as $date)
                    <th>{{ \Carbon\Carbon::parse($date)->format('d')}}</br>{{ \Carbon\Carbon::parse($date)->format('l')[0]}}</th>
                @endforeach
            </tr>
            </thead>
            <tbody>
                @forelse($applications as $application)
                    <tr>
                        <td>{{$application['employee_id']}}</td>
                        <td>{{$application['employee']}}</td>
                        @php 
                            $leaveDates = $application['dates'];
                            $dateKeys = array_keys($application['dates']);
                        @endphp
                        @foreach($dates as $date)
                            <td @if(in_array($date, $holidays)) class="holiday" @endif @if(\Carbon\Carbon::parse($date)->isSunday() || \Carbon\Carbon::parse($date)->isSaturday()) class="off-day" @endif @if(in_array($date, $dateKeys)) class="
                                    {{trim(str_replace(' ', '-', strtolower($leaveDates[$date])))}}
                                " title="{{$leaveDates[$date]}}" @endif >
                            </td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td align="center" colspan="{{count($dates)+2}}">No Leaves Found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="row">
        <div class="col-md-8">
            <strong>
                <h3 class="page-title" style="font-size: 25px; margin-top: 25px;">Manage Applications</h3>
            </strong>
        </div>
    </div>
<div class="ibox-content">
    <div class="table-responsive">
        <table class="table table-striped listTable2">
                        <thead>
                            <tr>
                                <th>Application Date</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>From</th>
                                <th>To</th>
                                <th>No: of Days</th>
                                <th>Session</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th>Remarks</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $applicationLists as $list )
                            <tr>
                                <td data-sort="{{$list->created_at}}">{{date_format(new DateTime($list->created_at),'d/m/Y')}}</td>
                                <td>{{$list->users->full_name}}</td>
                                <td>{{$list->type}}</td>
                                <td>{{$list->from_date_format}}</td>
                                <td>{{$list->to_date_format}}</td>
                                <td>{{ $list->total_leave_days}}</td>
                                <td>{{$list->session}}</td>
                                <td>{!! strip_tags($list->reason) !!}</td>
                                <td>{{$list->status}}</td>
                                <td>{!! strip_tags($list->reason_for_rejection) !!}</td>
                                <td>
                                <a class="edit-data" data-id="{{$list->id}}"><i class="ri-pencil-line"></i></a>
                                <a style="margin-left:10px;" class="delete_leave" data-toggle="modal" data-target="#delete_leave" data-id="{{$list->id}}"><i class="ri-delete-bin-line"></i></a>
                            </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
    </div>
</div>