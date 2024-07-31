<div class="row">
        <div class="col-md-4 text-center">
            <div id="employee_expense" style="height: 250px" class="pie"></div>

                        <label> <h2><strong id="employee_expense_id"> </strong></h2></label>
        </div>
        <div class="col-md-8">
        	 <table class="table table-striped">
        <thead>
            <tr>
                <th>Username</th>
                <th>Role</th>
                <th>Designation</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalSalary=0;
            @endphp
            @foreach($users as $payrollUser)
            <tr>
                <td>{{$payrollUser->user->full_name}}</td>
                <td>{{$payrollUser->user->role->display_name}}</td>
                <td>{{$payrollUser->user->designation->name}}</td>
                @php
                    $userSalary = $payrollUser->employee_expense ==0 ? $payrollUser->user->monthly_salary :$payrollUser->employee_expense;
                    $totalSalary+= $userSalary 
                @endphp
                <td><span class="float-right"><strong>{{ number_format($userSalary,2,'.','') }}</strong></span></td>
            </tr>
            @endforeach
           
        </tbody>
        <tfoot>
            <td><strong>Total</strong></td>
            <td></td>
            <td></td>
            <td><strong>{{ number_format($totalSalary,2,'.','') }}</strong></td>
            <td></td>
        </tfoot>        
    </table>
    </div></div>