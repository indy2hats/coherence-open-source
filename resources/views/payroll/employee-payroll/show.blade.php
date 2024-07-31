@extends('layout.main')

@section('content')
<div class="row">
    <div class="col-md-5">
        <strong>
            <h2 class="page-title">Employee Payroll</h3>
        </strong>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-sm-9 payslip-view">
            <div class="row ibox-content  mb10">
                <div class="col-sm-3 col-md-3 col-offset-md-2 text-right ">
                    <div class="form-group text-right">
                        <div class="input-group date">
                            <span class="input-group-addon"><i class="ri-calendar-2-line"></i></span><input type="text" class="form-control payroll-month-datepicker dateInput m-auto" id="payroll-month-datepicker" value="{{ $monthYear }}" data-id="{{ $id }}" />
                        </div>                    
                    </div>
                </div>       
                @if (!empty($employeePayroll))
                    <div class="col-sm-6">
                        <a class="dropdown-item export-employee-payroll  btn btn-success mb-2" href="{{ route('payroll-user.export',[$employeePayroll->user_id,$employeePayroll->payroll->filterMonth])}}" data-toggle="modal"
                            data-tooltip="tooltip" data-placement="top" title="Download Payslip">
                            <i class="ri-download-line m-r-5 m-b-5"></i> Download</a>
                            @if($errors->has('export_error_message'))
                            <span class="text-danger text-left field-error" id="label_export_error_message">{{ $errors->first('export_error_message') }}</span>                        
                            @endif
                    </div> 
                @endif
            </div>
            <div class="row ibox-content ">    
                @if (empty($employeePayroll))
                <div class="list animated fadeInUp ibox-content">  
                    <p class="text-center">No data available for selected month</p>
                </div>
                 @else                     
                    <div class="col-sm-12 payslip-pdf-view  ">
                        @include('payroll.employee-payroll.pdf-view')
                    </div>             
                @endif
            </div>            
        </div>
    </div>
</div>

@endsection
@section('after_scripts')
<script src="{{ asset('js/resources/payroll/employee-payroll/script.min.js') }}"></script>
@endsection