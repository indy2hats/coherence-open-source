@extends('layout.main')

@section('content')

<div class="row">
    <div class="col-md-12">
        <strong>
            <h2 class="page-title">Employee Checklist</h2>
        </strong>
    </div>
</div>
<div class="ibox-title">    
    <div class="row">
        <div class="col-md-9">
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <select class="chosen-select" name="user_id" id="user_id">
                    <option value="">Select Employee</option>
                    @foreach ($users as $user)
                    <option value="{{$user->id}}" {{ request()->user_id == $user->id ? 'selected': '' }}>{{$user->full_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>

<div class="employee-list animated fadeInUp">
	<h2 class="text-center empty-list">Please Select An Employee</h2>
	@include('checklists.manage-checklist.employee-list')
</div>
@endsection
@section('after_scripts')

<script src="{{ asset('js/plugins/iCheck/icheck.min.js') }}"></script>
<link href="{{ asset('css/plugins/iCheck/custom.css') }}" rel="stylesheet">

<script src="{{ asset('js/resources/checklists/manage-checklist/script-min.js') }}"></script>
@endsection
