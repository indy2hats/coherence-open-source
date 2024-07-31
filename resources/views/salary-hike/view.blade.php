@extends('layout.main')

@section('content')
@include('salary-hike.show')
@endsection
@section('after_scripts')
<link href="{{ asset('css/plugins/c3/c3.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/plugins/iCheck/custom.css') }}" rel="stylesheet">
<script src="{{ asset('js/plugins/c3/c3.min.js') }}"></script>
<script src="{{ asset('js/plugins/iCheck/icheck.min.js') }}"></script>
<script src="{{ asset('js/plugins/typehead/bootstrap3-typeahead.min.js') }}"></script>
<script src="{{ asset('js/jquery.mask.min.js') }}"></script>
<script src="{{ asset('js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('js/plugins/d3/d3.min.js') }}"></script>
@endsection

<style>
        .hike-table{
            margin-top: 15px;
        }
        </style>