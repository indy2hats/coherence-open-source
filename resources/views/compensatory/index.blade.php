@extends('layout.main')
@section('content')
<div class="row">
    <div class="col-md-8">
        <strong>
            <h2 class="page-title">Compensatory Works</h3>
        </strong>
    </div>
    <div class="col-md-4     text-right float-right ml-auto m-b-30">
        <a href="#" class="btn btn-w-m btn-success create-modal" data-toggle="modal" data-target="#create_new"><i
                class="ri-add-line"></i> Add New</a>
    </div>

</div>
<div class="row">
    <div class="col-lg-3">
        <div class="form-group" id="data_3">

            <div class="input-group date">

                <span class="input-group-addon"><i class="ri-calendar-2-line"></i></span><input type="text"
                    class="form-control year-search" id="date-chart" value="{{$year}}">

            </div>

        </div>
    </div>

    <div class="col-lg-9"></div>
</div>

<div class="content-div animated fadeInUp">
    <div class="main ibox-content">
        @include('compensatory.list')
    </div>
</div>

@include('compensatory.create')
@include('compensatory.delete')
@endsection
@section('after_scripts')
<script src="{{ asset('js/resources/compensatory/script-min.js') }}"></script>
@endsection