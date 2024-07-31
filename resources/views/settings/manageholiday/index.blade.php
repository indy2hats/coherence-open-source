@extends('layout.main')
@section('content')
<div class="row" style="padding-bottom: 20px">
    <div class="col-md-4">
        <strong>
            <h2 class="page-title">Yearly Holidays</h3>
        </strong>
    </div>
    <div class="col-md-8">
        @include('settings.manageholiday.actions')
    </div>
</div>
<div class="row animated fadeInUp">
    <div class="col-md-12">
        <div class="row ibox-content">
            <div class="col-lg-3 pull-left">
                <div class="form-group" id="data_3">
                    <div class="input-group date">
                        <span class="input-group-addon"><i class="ri-calendar-2-line"></i></span><input type="text" value="{{$date}}" class="form-control" id="date-chart">
                    </div>
                </div>
            </div>
        </div>
        <div class="row ibox-content "  id="holiday_container">
            @include('settings.manageholiday.list')
        </div>
    </div>

</div>
@include('settings.manageholiday.create')
@include('settings.manageholiday.weekly_holidays')
<div id="edit_holidays" class="modal custom-modal fade" role="dialog">
    {{-- @include('settings.manage-holdiay.edit') --}}
</div>
@include('settings.manageholiday.delete')
@endsection
@section('after_scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="{{ asset('js/resources/settings/manageholiday/script-min.js') }}"></script>
@endsection