@extends('layout.main')

@section('content')
<div class="row">
    <div class="col-md-7 pull-left">
        <strong>
            <h2 class="page-title"> Overhead & Expense</h3>
        </strong>
    </div>
    <div class="col-md-5 text-right ml-auto m-b-30">
        <button class="btn btn-w-m btn-success" type="button" data-toggle="modal" data-target="#add_expense"><i class="ri-add-line"></i>
            Add Expense
       </button>
        <button class="btn btn-w-m btn-info" type="button" data-toggle="modal" data-target="#add_overhead"><i class="ri-add-line"></i>
             Add Overhead
        </button>
    </div>
</div>
<div class="row main animated fadeInUp">
    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="row" style="padding: 5px 0px;">
                       <div class="col-lg-2 col-lg-offset-10 text-right">
                            <div class="form-group" id="data_3">

                                <div class="input-group date">

                                    <span class="input-group-addon"><i class="ri-calendar-2-line"></i></span><input type="text" class="form-control" id="date-chart">

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="year-chart">
                        <div class="row">
                            <div class="col-lg-4 hidden">
                                <div>
                                    <canvas id="lineChart" height="100"></canvas>
                                </div>
                            </div>

                            <div class="col-lg-4 text-center">
                                <div id="morris-donut-chart" style="height: 250px" class="pie"></div>
                                <p>Yearly Overheads</p>
                                <label> <h2><strong id="total_id"> </strong></h2></label>                                
                            </div>
                                
                            <div class="col-lg-4 text-center">
                                <div id="morris-donut-expense-chart" style="height: 250px" class="pie"></div>
                                <p>Yearly Expenses</p>
                                <label> <h2><strong id="total_expense_id"> </strong></h2></label>                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row list" style="margin-top: 10px;">
            @include('general.overheads.list')
        </div>
        <div class="row listExpense" style="margin-top: 10px;">
            @include('general.overheads.expenses.list')
        </div>
    </div>
</div>
@include('general.overheads.create')
@include('general.overheads.delete')
@include('general.overheads.expenses.create')
@include('general.overheads.expenses.delete')
<div id="edit_overhead" class="modal custom-modal fade" role="dialog">
    {{-- @include('general.overheads.edit') --}}
</div>
<div id="edit_expense" class="modal custom-modal fade" role="dialog">
</div>
@stop
@section('after_scripts')
<script src="{{ asset('js/resources/general/overheads/script-min.js') }}"></script>
<script src="{{ asset('js/plugins/morris/morris.js') }}"></script>
<script src="{{ asset('js/plugins/morris/raphael-2.1.0.min.js') }}"></script>
<script src="{{ asset('js/plugins/chartJs/Chart.min.js') }}"></script>
<script src="{{ asset('js/plugins/typehead/bootstrap3-typeahead.min.js') }}"></script>
@stop