@extends('layout.main')

@section('content')
<div class="row">
    <div class="col-md-6">
        <strong>
            <h2 class="page-title">Payroll</h3>
        </strong>
    </div>
    <div class="col-sm-2 col-md-2">
        @php
            $year=(int)date('Y');
        @endphp
        <div class="form-group">
            <select class="chosen-select" id="payroll_year_filter" name="payroll_year_filter">
                <option value="">Select Year</option>              
                @for($i=$year-7;$i<=$year;$i++)
                <option value="{{$i}}" {{ $filterYear==$i ? "selected":'' }} >{{ $i }} - {{ $i+1 }}</option>
                @endfor          
            </select>    
        </div> 
    </div>
    <div class="col-md-4 text-right float-right ml-auto m-b-30">        
        <a href="#" class="btn btn-w-m btn-success" data-toggle="modal" data-target="#upload-payroll-file"><i class="fa fa-upload"></i> Upload Payroll</a>
        <a href="#" class="btn btn-w-m btn-info" data-toggle="modal" data-target="#download-payroll-file"><i class="ri-download-line"></i> Download Template </a>
    </div>
</div>
<div class="content-div animated fadeInUp">
	<div class="ibox-content payroll-list">
        @include('payroll.monthly-payroll.list')
    </div>
</div>
@include('payroll.monthly-payroll.import')
@include('payroll.monthly-payroll.export')
@include('payroll.monthly-payroll.update')
@endsection

@section('after_scripts')
<script src="{{ asset('js/resources/payroll/manage-payroll/script.min.js') }}"></script>
@endsection