@extends('layout.main')

@section('content')
<div>
    <strong>
        <h2 class="page-title">Task Bounce Graph</h2>
    </strong>
</div>
<div class="col-lg-8">
    <div class="ibox-content">
        <div class="row" >
            <div class="col-md-12">
            </div>
            <form id="task-search-form">
                <div class="col-md-4">
                    <select class="chosen-select" id="userId" name="userId">
                        <option value="">Select User</option>
                        @foreach ($users as $userId => $userName)
                        <option value="{{$userId}}" {{ request()->userId == $userId ? 'selected': '' }}>{{$userName}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5">
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
    <div class="ibox-content bounce-chart">
    </div>
</div>

@endsection
@section('after_scripts')
<script src="{{ asset('js/resources/task-bounds/script-min.js') }}"></script>
<script src="{{ asset('js/plugins/chartJs/Chart.min.js') }}"></script>
<script src="{{ asset('js/plugins/d3/d3.min.js') }}"></script>
<script src="{{ asset('js/plugins/c3/c3.min.js') }}"></script>
@endsection