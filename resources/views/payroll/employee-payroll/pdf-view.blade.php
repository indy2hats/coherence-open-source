<style>
.float-right{
    float: right;
    }
@page { 
    margin-right:0px; 
    margin-left:0px; 
    margin-top:100px; 
    }
 
</style>

<table class="table m-auto text-center payslip-table" border="1" cellspacing="0" cellpadding="0">
    <thead>
        @if(Helper::getCompanyLogo())
        <tr >
            <th colspan="4" class="text-center">
                <img src="{{asset(Helper::getCompanyLogo())}}" class="img-logo" style="width: 150px; height: 150px"></th>
        </tr>
        @endif
        <tr>
            <td colspan="4"> {{ $companyDetails['company_name']?? ''  }} </td>
        </tr>
        <tr>
            <td colspan="4">{{ $companyDetails['company_address_line1'] ?? ''}} {{ $companyDetails['company_address_line2'] ?? '' }}, {{ $companyDetails['company_city']?? '' }}, {{  $companyDetails['company_state'] ?? ''}}, {{  $companyDetails['company_country']?? ''  }} - {{  $companyDetails['company_zip'] ?? '' }}</td>
            </tr>
        <tr>
            <td colspan="4">Phone: {{ $companyDetails['company_phone'] ?? '' }}, Email: {{ $companyDetails['company_email'] ?? ''}}, CIN: {{ $companyDetails['company_cin'] ?? ''}}</td>
        </tr>
        <tr>
            <th class="text-center" colspan="4">Payslip for the month of {{ $employeePayroll->payroll->full_month }} </th>                                          
        </tr>
    </thead>
    <tbody class="text-left">
        <tr>
            <th>Employee Name</th>
            <td>{{ $employeePayroll->user->full_name }}</td>
            <th>Employee Code</th>
            <td>{{ $employeePayroll->user->employee_id }}</td>
        </tr>
        <tr>
            <th>Designation</th>
            <td>{{ $employeePayroll->user->designation->name }}</td>
            <th>Joining Date</th>
            <td>{{ $employeePayroll->user->joining_date_format }}</td>
        </tr>
        <tr>
            <th>Department</th>
            <td>{{ $employeePayroll->user->department->name}}</td>
            <th>Bank Name</th>
            <td>{{ $employeePayroll->user->bank_name }}</td>
        </tr>
        <tr>
            <th>Branch</th>
            <td>{{ $employeePayroll->user->branch}}</td>
            <th>Bank Account No</th>
            <td>{{ $employeePayroll->user->account_no }}</td>
        </tr>                   
        <tr>
            <th>PAN Number</th>
            <td>{{ $employeePayroll->user->pan}}</td>
            <th>UAN Number</th>
            <td>{{ $employeePayroll->user->uan }}</td>
        </tr>
        <tr>
            <th>Loss of Pay</th>
            <td>{{ $employeePayroll->user_loss_of_pay ?? 0}}</td>
            <th>Leave Taken</th>
            <td>{{ $employeePayroll->user_leaves ?? 0 }}</td>
        </tr>
        <tr>
            <th class="text-center" colspan="4">PAYMENT DETAILS</th>                                          
        </tr>
        <tr>
            <th class="text-center" colspan="2">Regular Monthly CTC</th>    
            <th class="text-center" colspan="2" > {{ $employeePayroll->employee_ctc }}</th>                                          
        </tr>
        <tr>
            <th class="text-center" colspan="2">Gross Salary</th>    
            <th class="text-center" colspan="2" > {{ $employeePayroll->gross_amount }}</th>                                          
        </tr>
        <tr>
            <th colspan="2">Earnings <span class="float-right">Amount</span></th>
            <th colspan="2">Deductions <span class="float-right">Amount</span></th>                     
        </tr>
        <tr>  
            <td colspan="2">
            @foreach ($earingComponents as $key => $component)                     
                {{ $key }}  <span class="float-right">{{ $component}}</span> <br/>                
            @endforeach    
            Incentives <span class="float-right">{{ $employeePayroll->incentives}}</span>
                @for($i=count($earingComponents);$i<count($deductionComponents);$i++)
                <br/>
                @endfor
            </td>                  
            <td colspan="2">   
                @foreach ($deductionComponents as $key => $component)                     
                {{ $key }}  <span class="float-right">{{ $component}}</span> <br/>                
            @endforeach                              
            </td>
        </tr>     
        <tr>
            <th>Total Earnings</th>
            <td><span class="float-right">{{ $employeePayroll->employee_total_earnings }}</span></td>
            <th>Total Deductions</th>
            <td><span class="float-right">{{ $employeePayroll->employee_total_deductions }}</span></td>
        </tr>                                                          
    </tbody>
    <tfoot>
        <tr>  
            <th colspan="4">Net Salary <span class="float-right"> {{  $employeePayroll->employee_net_salary }}</span>
            </th>  
        </tr>  <tr>                  
            <th colspan="4">In Words <span class="float-right"> {{  $employeePayroll->net_salary_in_words }}</span>
            </th> 
        </tr>                          
    </tfoot>
</table>
<br>
<p class="text-center">Disclaimer: This is a system generated payslip, hence does not require a seal or signature.</p>
