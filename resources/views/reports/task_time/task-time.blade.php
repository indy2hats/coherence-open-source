@extends('layout.main')

@section('content')
<div class="row">
    <div class="col-md-7 pull-left">
        <strong>
            <h2 class="page-title">Task Time Sheet</h3>
        </strong>
    </div>
    <div class="col-md-5 text-right ml-auto m-b-30">
    </div>
</div>
<div class="list animated fadeInUp">
	@include('reports.task_time.search')
    <div id="task_content">

    </div>
</div>

@endsection
@section('after_scripts')
<script src="{{ asset('js/resources/reports/task_time/script-min.js') }}"></script>
@endsection