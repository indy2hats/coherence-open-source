<table class="table table-hover payroll-table">
    <thead>
        <tr>
            <th>Month/Year</th>
            <th>Total Earnings</th>
            <th>Total Incentives</th>
            <th>Status</th>
            <th class="text-center">Action</th>
        </tr>
    </thead>
    <tbody>              
        @foreach($payrolls as $payroll)        
        <tr>
            <td>
                <a href="/payroll/{{ $payroll->id }}">{{ $payroll->month }}</a>
            </td>
            <td>{{$payroll->total_amount}}</td>
            <td>{{$payroll->incentives}}</td>      
            <td class="">
                @php
                $status= config('payroll.payrolls.status');
                $pendingStatus=$status[0];                
                @endphp
                <small>
                    {{$payroll->percent_status}}% {{ ucwords($payroll->status==$pendingStatus? 'processed':'completed')}}
                </small>
                <div class="progress progress-mini">
                    <div style="width: {{$payroll->percent_status}}%;" class="progress-bar progress-bar-mini"></div>
                </div>
                <span ></span></td>
            <td class="text-center">              
               
                @if ( $payroll->status==$pendingStatus )                  
                <a class="label label-{{$payroll->status==$pendingStatus ? 'success':'default' }} btn" href="#" id="update-payroll-status" data-toggle="modal" data-target="#change_status" 
                data-id="{{ $payroll->id }}" data-label="{{ $payroll->status==$pendingStatus ? $status[1] : ''}}" data-tooltip="tooltip" data-placement="top" title="Change Status">
                {{ 'Mark as'.$status[1] }}
                </a>               
                @endif
                          
            </td>        
        </tr>
        @endforeach
    </tbody>
</table>