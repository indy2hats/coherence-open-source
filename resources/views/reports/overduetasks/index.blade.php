@extends('layout.main')
@section('content')
<div class="row">
    <div class="col-md-8">
            <strong>
                <h2 class="page-title">Overdue Tasks</h3>
            </strong>
    </div>

    <div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-content index-top animated fadeInUp">
 
    @include('reports.overduetasks.search')
</div>
                <div id="overdue_content" class="index-bottom">
                    
        </div>
        </div>
        
    </div>

</div>

@endsection
@section('after_scripts')
<script src="{{ asset('js/resources/reports/overduetasks/script-min.js') }}"></script>
@endsection
