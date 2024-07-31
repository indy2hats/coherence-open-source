@extends('layout.main')

@section('content')
<div class="row">
    <div class="col-md-12 col-lg-8">
        <div class="heading-inline">
            <h3 class="no-margins" style="text-align:left;float:left;">Employee Name :
                <strong>{{$employee->full_name}}</strong>
            </h3>
            <hr style="clear:both;" />
        </div>

        <div class="m-b-md pt-15 tabs-container">
    
            <h4>Hike History</h4>

            <div class="table-responsive hike-table">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Hike ({{$salaryCurrency}})</th>
                            <th>Previous Salary ({{$salaryCurrency}})</th>
                            <th>Updated Salary ({{$salaryCurrency}})</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($hikeHistory as $history)
                            <tr>
                                <td>{{ $history->date }}</td>
                                <td>{{ $history->hike }}</td>
                                <td>{{ $history->previous_salary }}</td>
                                <td>{{ $history->updated_salary }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
    
        </div>
    </div>
</div>
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