@extends('layout.main')

@section('content')

@if(!$userId)
<div class="alert alert-danger alert-start">Please Select a User</div>
@endif
<div class="list animated fadeInUp">
	@include('timesheets.usersheet.managesheet')
</div>
<div class="text-center">
	<img src="{{asset('images/loading.gif')}}" width="200" class="loading hidden "/>
</div>
@endsection
@section('after_scripts')
<script src="{{ asset('js/resources/timesheets/usersheet/script-min.js') }}"></script>
@endsection