@extends('layout.main')

@section('content')

<div class="alert alert-danger alert-start">Please Select a Project</div>
<div class="list animated fadeInUp">
@include('timesheets.project-daterange.view')
</div>
<div class="text-center">
	<img src="{{asset('images/loading.gif')}}" width="200" class="loading hidden "/>
</div>
@endsection
@section('after_scripts')
<script src="{{ asset('js/resources/timesheets/project-daterange/script-min.js') }}"></script>
@endsection