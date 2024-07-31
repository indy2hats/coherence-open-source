@extends('layout.main')
@section('content')
<div class="row">
    <div class="col-md-8">
            <strong>
                <h2 class="page-title">Employee Performance Report</h3>
            </strong>
    </div>

    <div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-content animated fadeInUp">
                @include('reports.performance.search')
                <div id="performance_content">
                    
        </div>
            </div>
        </div>
        
    </div>

</div>

@endsection
@section('after_scripts')
<script src="{{ asset('js/resources/reports/performance/script-min.js') }}"></script>
@endsection
