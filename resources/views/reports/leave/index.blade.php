@extends('layout.main')
@section('content')
<div class="row">
    <div class="col-md-12">
        <strong><h2 class="page-title">Leave Report Of Employees</h3></strong>
     </div>
</div>
<div class="row">
<div class="col-md-12">
    <div class="ibox">
        <div class="ibox-content m-b pull-left">

            <form id="leave-report-filter-form" action="" method="get">
            @csrf {{ csrf_field() }}
                
                        @include('reports.leave.filters.year')
                        @include('reports.leave.filters.month')
                        @include('reports.leave.filters.user-dropdown')
                
            </form>
            </div>
    </div>
</div>
</div>
<div class="row">       
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content animated fadeInUp">
                <div class="table-responsive">

                    <table class="table table-striped table-bordered table-hover userLeaveReportTable">
                        <thead>
                            <tr>
                                <th>Employee Name</th>
                                <th>Paid Leaves</th>
                                <th>LOP</th>
                                <th>Total Leaves</th>
                            </tr>
                        </thead>
                     
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('after_scripts')
<script src="{{ asset('js/resources/reports/users/script-min.js') }}"></script>
@endsection
