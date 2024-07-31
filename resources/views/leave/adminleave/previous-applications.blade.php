@extends('layout.main')
@section('content')

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-md-8 pl0">
            <strong>
                <h2 class="page-title">Leave Applications</h2>
            </strong>
        </div>
    </div>
            <div class="row">
                <div class="col-lg-12" style="padding: 0px;">
                <div class="ibox ">
                    <div class="ibox-title">
                        <div class="row">
                            <div class="col-md-3">
                                <button type="button" class="btn btn-outline btn-link arrow-back" data-date=""><i class="ri-arrow-left-double-line ri-2x"></i></button>


                                <button type="button" class="btn btn-outline btn-link todayBtn"><strong><i class="ri-calendar-2-line"></i> This Month</strong></button>


                                <button type="button" class="btn btn-outline btn-link arrow-front" data-date=""><i class="ri-arrow-right-double-line ri-2x"></i></button>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select class="chosen-select" id="year" name="year">
                                          @for ($year = date('Y'); $year > date('Y') - 10; $year--)
                                          <option value="{{$year}}">
                                                  {{$year}}
                                          </option>
                                          @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <select class="chosen-select" id="month" name="month">
                                          @foreach(range(1,12) as $month)
                                                <option @if($month == date("m")) selected @endif value="{{$month}}">
                                                    {{date("M", strtotime('2016-'.$month))}}
                                                </option>
                                          @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    @php             
                                        $userTypes =config('general.user-type.labels');
                                    @endphp
                                    <select class="chosen-select" data-placeholder="Select User Type" name="userType" id="userType">                   
                                        <option value=""></option> 
                                        @foreach ($userTypes as $key => $type)
                                        <option value="{{$key}}" {{ (string)$key== $userType ? 'selected': '' }}>{{$type}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
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
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select class="chosen-select" id="leaveType" name="leaveType">
                                        <option value="">Select Leave Type</option>
                                        @foreach($leaveTypes as $leaveType)
                                            <option value="{{ $leaveType->slug }}">
                                                {{ $leaveType->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div  id="list_leave">
                        
                    </div>
                </div>
            </div>
            
            </div>
            
        </div>
        <div id="edit_leave" class="modal custom-modal animated fadeInUp" role="dialog">
        </div>
        @include('leave.delete')
@endsection
@section('after_scripts')
@include('leave.adminleave.previous-list-script')
@endsection