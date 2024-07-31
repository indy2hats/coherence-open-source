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
	<div class="ibox-content payroll-list">
        @include('payroll.employee-payroll.show')
    </div>
</div>
@endsection
