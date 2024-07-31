@extends('layout.main')
@section('content')
<div class="row">
    <div class="col-md-12">
        <strong>
            <h2 class="page-title">Base Currency</h3>
        </strong>
    </div>

</div>

<div class="row content-div animated fadeInUp">

    <div class="col-md-12">
        
    <div class="col-md-12 m-b-lg">

        <h4>You are using {{$currency}}[{{$base->title}}] as Base Currency.</h4>
         <a href="#" class="btn btn-w-m btn-success change-modal" data-toggle="modal" data-target="#change_currency"><i class="ri-edit-fill"></i> Change</a>
</div>

         <div class="schedule mt20">

        <div class="ibox">
            <div class="ibox-title schedule-toggle">
                <h5>Exchange Rates [{{$base->title}}]</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="epms-icon--1x ri-arrow-up-s-line"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content" style="">    
        <ul class="four-col-listing">
                            @foreach($api_data['rates'] as $key=>$value)
                            <li>{{$key}} - <strong>{{$value}}</strong></li>
                            @endforeach
                        </ul>    
                    </div>
                </div>
                        
        </div>
        </div>


</div>
<div id="change_currency" class="modal custom-modal fade" role="dialog"></div>
@endsection
@section('after_scripts')
<script src="{{ asset('js/resources/settings/base-currency/script-min.js') }}"></script>
@endsection