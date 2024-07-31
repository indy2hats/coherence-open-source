@extends('layout.main')

@section('content')
<div>
        <strong>
            <h2 class="page-title">Task Bounce Report</h2>
        </strong>
    </div>
<div class="list animated fadeInUp">
    <div class="ibox-content">
        <div class="row">
        <div class="col-md-8">
        </div>
        <form id="task-search-form">
            <div class="col-md-3">
                <input class="form-control active" type="text" id="daterange" name="daterange"  value="{{$date}}">
            </div>
            <div class="col-md-1">
                <div class="form-group">
                    <button class="btn btn-primary btn search">Search</button>
                </div>
            </div>
        </form>
    </div>
    </div>
	<div class="ibox-content" id="report_content">

    </div>
</div>

@endsection
@section('after_scripts')
@include('task-bounds.reports-script')
@endsection