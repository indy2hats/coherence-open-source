@extends('layout.main')

@section('content')
<div class="row">
    <div class="col-md-7 pull-left">
        <strong>
            <h2 class="page-title">Salary Component</h3>
        </strong>
    </div>
    <div class="col-md-5 text-right ml-auto m-b-30">
        <a href="#" class="btn btn-w-m btn-success" data-toggle="modal" data-target="#add-salary-component"><i class="ri-add-line"></i> Add Component</a>
    </div>
</div>
<div class="content-div animated fadeInUp">
	<div class="ibox-content salary-component-list">
        @include('payroll.salary-component.list')
    </div>
</div>
<div id="edit-component" class="modal custom-modal fade" role="dialog">
</div>
@include('payroll.salary-component.create')
@include('payroll.salary-component.delete')
@endsection
@section('after_scripts')
<script src="{{ asset('js/resources/payroll/salary-component/script.min.js') }}"></script>
@endsection