@extends('layout.main')

@section('content')
<!-- page title -->
<div class="row">
    <div class="col-md-8">
        <strong>
            <h2 class="page-title">Clients</h3>
        </strong>
    </div>
    <div class="col-md-2 text-right float-right ml-auto m-b-30">
       
    </div>
    <div class="col-md-2 text-right float-right ml-auto m-b-30">
        <div class="form-group" id="data_3">

            <div class="input-group date">

                <span class="input-group-addon"><i class="ri-calendar-2-line"></i></span><input type="text" class="form-control" id="date-chart">

            </div>

        </div>
    </div>
</div>
<!-- /page title -->
 <div class="ibox-content">

                    <div id="calendar"></div>

                </div>
@endsection
@section('after_scripts')
<script src="{{ asset('js/resources/timesheets/client-monthly/script-min.js') }}"></script>
@endsection