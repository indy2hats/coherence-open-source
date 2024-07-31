@extends('layout.main')
@section('content')

<div class="row">
    <div class="col-md-8">
        <strong>
            <h2 class="page-title">Assign Leave</h3>
        </strong>
    </div>
</div>
<div class="content-div animated fadeInUp">
    <div class="ibox-content">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-lg-4 pull-left">
                        <div class="form-group" >
                            <select class="chosen-select" id="user" name="user">
                                <option value="">Select User</option>
                                @foreach($users as $user)
                                    <option value="{{$user->id}}">
                                        {{$user->full_name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-8 text-right float-right ml-auto m-b-30">
        <a href="#" class="btn btn-w-m btn-success" id="apply_leave_link" data-toggle="modal" data-target="#apply_leave" style="display: none;"><i class="ri-add-line"></i> Assign Leave</a>
      
    </div>
                </div>
                
            </div>
        </div>
    </div>
    <div id="leave_list" class="ibox-content" style="display: none;">
        <div class="row">
            @include('leave.assignLeave.list')
        </div>
    </div>
</div>
@endsection
@section('after_scripts')
<script src="{{ asset('js/resources/leave/assignLeave/script-min.js') }}"></script>
@endsection