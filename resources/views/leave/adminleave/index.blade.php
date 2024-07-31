@extends('layout.main')
@section('content')

<div class="row">
    <div class="col-md-7 pull-left">
        <strong>
            <h2 class="page-title">Manage Leave Applications</h2>
        </strong>
    </div>
    <div class="col-md-5 text-right ml-auto m-b-30">
        <!-- <a href="#" class="btn btn-w-m btn-success" data-toggle="modal" data-target="#add_client"><i class="ri-add-line"></i> Add Leave</a> -->
    </div>
</div>
<div class="row ibox-content panel animated fadeInUp" id="leave_list">
    
</div>
@include('leave.adminleave.accept')
@include('leave.adminleave.reject')
<div class="modal custom-modal fade" id="view_remaining_leave" role="dialog">
</div>
@endsection
@section('after_scripts')
<script src="{{ asset('js/resources/leave/adminleave/script-min.js') }}"></script>
@endsection