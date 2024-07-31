@extends('layout.main')

@section('content')
<div class="row">
    <div class="col-md-8">
        <strong>
            <h2 class="page-title">Fixed Overhead Expenses </h2>
        </strong>
    </div>
    <div class="col-md-4 text-right">
        {{-- <a href="#" class="btn btn-w-m btn-primary add_to_current"><i class="ri-add-line"></i> Add To Expense</a> --}}
        <a class="btn btn-w-m btn-info" data-toggle="modal" data-target="#add_overhead_type"><i class="ri-add-line"></i> Add Overhead
        </a>
    </div>
</div>
<div class="list animated fadeInUp ibox-content panel" style="margin-top: 10px">
    @include('settings.manage-overhead.list')
</div>

@include('settings.manage-overhead.create')
@include('settings.manage-overhead.delete')
<div id="edit_overhead_type" class="modal custom-modal fade" role="dialog">
    {{-- @include('settings.manage-overhead.edit') --}}
</div>

<div class="row">
    <div class="col-md-12">
        <strong>
            <h2 class="page-title">Managerial Overhead Expenses </h2>
        </strong>
    </div>
</div>
<div class="animated fadeInUp panel ibox-content" style="margin-top: 10px">
    @include('settings.manage-overhead.employee-expense')
   
</div>
@endsection
@section('after_scripts')
<script src="{{ asset('js/plugins/morris/raphael-2.1.0.min.js') }}"></script>
<script src="{{ asset('js/plugins/morris/morris.js') }}"></script>
<script src="{{ asset('js/plugins/typehead/bootstrap3-typeahead.min.js') }}"></script>
<script src="{{ asset('js/plugins/chartJs/Chart.min.js') }}"></script>
<script src="{{ asset('js/resources/settings/manage-overhead/script-min.js') }}"></script>
@endsection