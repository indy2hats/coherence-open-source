@extends('layout.main')

@section('content')
<div class="row">
    <div class="col-md-8">
        <strong>
            <h2 class="page-title">Employee Payroll</h3>
        </strong>
    </div>
</div>
<div class="list animated fadeInUp">    
    <div class="row">
        <div class="col-sm-12 text-center">            
            <h3>
                @if ($currentPayroll)
                {{ date('F Y',strtotime($currentPayroll->month)) }} Payroll 
                @endif
            </h3>                                 
        </div>
    </div>
    @if($errors->has('export_error_message'))
      <span class="text-danger text-left field-error" id="label_export_error_message">{{ $errors->first('export_error_message') }}</span>                        
    @endif
	<div class="ibox-content employee-payroll-list">
        @include('payroll.employee-payroll.list')
    </div>
</div>
<div id="edit-employee-payroll" class="modal custom-modal fade" role="dialog">
</div>
@endsection

@section('after_scripts')
<script src="{{ asset('js/resources/payroll/employee-payroll/script.min.js') }}"></script>
@endsection