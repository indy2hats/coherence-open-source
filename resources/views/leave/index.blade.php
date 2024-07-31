@extends('layout.main')
@section('content')

<div class="row">
    <div class="col-md-7 pull-left">
        <strong>
            <h2 class="page-title">Leave Application</h2>
        </strong>
    </div>
    <div class="col-md-5 text-right ml-auto m-b-30">
        <a href="#" class="btn btn-w-m btn-success" data-toggle="modal" data-target="#apply_leave"><i class="ri-add-line"></i> Apply Leave</a>
      
    </div>
</div>
<div class="content-div animated fadeInUp">
    <div class="row">
        <div class="col-md-12">
            @include('leave.search')
        </div>
    </div>
    <div class="ibox-content panel">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-lg-3 pull-left">
                        <div class="form-group" id="data_3">
                            <div class="input-group date">
                                <span class="input-group-addon"><i class="ri-calendar-2-line"></i></span><input type="text" value="{{$date}}" class="form-control" id="date-chart">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @include('leave.list')
                </div>
            </div>
        </div>
    </div>
</div>
@include('leave.cancel')
@include('leave.delete')
@endsection
@section('after_scripts')
<script src="{{ asset('js/resources/leave/script-min.js') }}"></script>
@endsection