<table class="table table-hover employee-payroll-table">
    <thead>
        <tr>
            <th>Employee</th>
            <th>Total Earnings</th>
            <th>Net Salary</th>            
            <th>Monthly CTC</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>              
        @foreach($employeePayroll as $userPayroll)        
        <tr>
            <td>{{ $userPayroll->user->full_name }}</td>
            <td>{{ $userPayroll->total_earnings }}</td>           
            <td>{{ $userPayroll->net_salary }}</td>                  
            <td>{{ $userPayroll->monthly_ctc }}</td>  
            @php
                $status= config('payroll.payrolls.status');
                $pendingStatus=$status[0];
            @endphp
            <td class="text-left">
                <a class="dropdown-item view-employee-payroll  m-r-5" href="{{ route('payroll-user.view',[$userPayroll->user_id,$userPayroll->payroll->filterMonth] )}}"  data-toggle="modal"
                    data-id="{{ $userPayroll->id }}"  data-tooltip="tooltip"
                    data-placement="top" title="Show"><i class="ri-eye-line m-r-5"></i></a> |
                    @if ($currentPayroll->status== $pendingStatus)
                    <a class="dropdown-item edit-employee-payroll-button " href="#" data-id="{{ $userPayroll->id }}"
                    data-tooltip="tooltip" data-placement="top" title="Edit"> <i
                        class="ri-pencil-line m-r-5"></i></a>                |
                    @endif
                <a class="dropdown-item export-employee-payroll" href="{{ route('payroll-user.export',[$userPayroll->user_id,$userPayroll->payroll->filterMonth])}}" data-toggle="modal"
                data-tooltip="tooltip" data-placement="top" title="Download Payslip"><i class="ri-download-line m-r-5"></i></a>                
            </td>    
        </tr>
        @endforeach
    </tbody>
</table>