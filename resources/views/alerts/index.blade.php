@extends('layout.main')

@section('content')

<div class="row">
    <div class="col-md-7">
        <strong>
            <h2 class="page-title">Manage Popup Alerts</h2>
        </strong>
    </div>

    <div class="col-md-5 text-right ml-auto m-b-30">
        <a href="#" class="btn btn-w-m btn-success" data-toggle="modal" data-target="#add_alert"><i class="ri-add-line"></i> Add Alert</a>
    </div>
</div>
<div class="ibox-content">
<div class="list animated fadeInUp">
    @include('alerts.list')
</div>
</div>
@include('alerts.create')
@include('alerts.delete')
@endsection
@section('after_scripts')
<script src="{{ asset('js/resources/alerts/script-min.js') }}"></script>
@endsection