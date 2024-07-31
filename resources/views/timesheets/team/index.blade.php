<div class='teamList'>
@extends('layout.main')

@section('content')
<div class="row">
	<div class="col-md-12">
		<strong><h3 class="page-title" style="font-size: 25px">Team Timesheet</h3></strong>
	</div>
</div>
<div class="list animated fadeInUp ">
	@include('timesheets.team.managesheet')
</div>
<div class="text-center">
	<img src="{{asset('images/loading.gif')}}" width="200" class="loading hidden "/>
</div>
@include('teams.create')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

<script src="{{ asset('js/resources/timesheets/team/script.js')}}"></script>
@endsection
</div>