@extends('layout.main')
@section('content')
@can('view-admin-dashboard')
<div class="row" style="margin-top: 20px" ;>
    <div class="col-md-12">
        <div class="panel-group skilled-panel animated fadeInUp">
            <div class="panel panel-primary">
                <!-- <div class="panel-heading">
                    <a data-toggle="collapse" href="#collapse1" style="color: white">
                        <h4 class="panel-title">
                            <i class="ri-time-line"></i> Employee Status<span class="pull-right"><i class="epms-icon--1x ri-arrow-up-s-line"></i></span></h4>
                    </a>
                </div> -->
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-10">
                            <a data-toggle="collapse" href="#collapse1" style="color: white">
                                <h4 class="panel-title">
                                    <i class="ri-time-line"></i> Employee Status<span class="pull-right"><i class="epms-icon--1x ri-arrow-up-s-line"></i></span>
                                </h4>
                            </a>
                        </div>
                        <div class="col-md-2 text-right">
                            <select class="form-control chosen-select" id="list-type">
                                <option value="all" selected>All</option>
                                <option value="team">My Team</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div id="collapse1" class="panel-collapse collapse in">
                    <div class="tabs-container no-padding no-margins">
                    <div class="panel-body">
                        <div class="col-lg-12">

                           

                                <ul class="nav nav-tabs">

                                    <li class="active"><a data-toggle="tab" href="#tab-3">Productive</a></li>

                                    <li class=""><a data-toggle="tab" href="#tab-4">Upskilling</i></a></li>

                                    <li class=""><a data-toggle="tab" href="#tab-5">Idle</i></a></li>

                                    <li class=""><a data-toggle="tab" href="#tab-6">On Leave</i></a></li>

                                </ul>

                                <div class="tab-content">

                                    <div id="tab-3" class="tab-pane active">
                                        @include('dashboard.productivelist')
                                    </div>
                                    <div id="tab-4" class="tab-pane">
                                        @include('dashboard.upskillinglist')
                                    </div>
                                    <div id="tab-5" class="tab-pane">
                                        @include('dashboard.idlelist')
                                    </div>

                                    <div id="tab-6" class="tab-pane">
                                        @include('dashboard.onLeaveList')
                                    </div>
                                </div>

                          

                        </div>

                        @can('view-profit-graph')
                        <div class="col-lg-8" style="color: black">
                                <div class="ibox float-e-margins">
                                    <div class="ibox-title" style="padding: 5px 0px;">
                                        <div class="col-lg-3">
                                            <h3 class="text-right">Year</h3>
                                        </div>
                                        <div class="col-lg-4 text-right">
                                            <div class="form-group" id="data_3">

                                                <div class="input-group date">

                                                    <span class="input-group-addon"><i class="ri-calendar-2-line"></i></span><input type="text" class="form-control" id="date-chart" autocomplete="off">

                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                            <button type="button" class="btn btn-w-m btn-info cache-clear">Clear Cache</button>
                                        </div>
                                    </div>
                                    <div class="ibox-content income-chart">
                                    </div>
                                </div>
                        </div>
                        @endcan
                        @if(Gate::check('view-status-graph') && Gate::check('view-profit-graph'))

                        <div class="col-lg-4 text-center" style="color: black;">
                            @else
                            <div class="col-lg-12 text-center" style="color: black;">
                                @endif
                                <div class="ibox float-e-margins">
                                    <div class="ibox-content" style="padding-top: 70px">
                                        <div id="pie"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
    @endhasrole
    <div class="row animated fadeInUp" style="margin-top: 10px; margin-bottom: 30px;">
        <div class="col-md-6">
            <div class="ibox-title">
                <h3>Overdue Projects</a></h3>
            </div>
            <div class="ibox-content">
                <div class="table-responsive dashboard-table">

                    <table class="table table-striped dataTableOverdueProjectList">

                        <thead>

                            <tr>
                                <th>Project</th>

                                <th>Client / Account</th>

                                <th>Project Lead</th>

                                <th>Deadline</th>

                            </tr>

                        </thead>
                      

                    </table>

                </div>

            </div>
        </div>
        <div class="col-md-6">
            <div class="ibox-title">
                <h3><a href="{{route('overdueTasks')}}">Overdue Tasks</a></h3>
            </div>
            <div class="ibox-content">
                <div class="table-responsive dashboard-table">

                    <table class="table table-striped dataTableOverdueTaskList">

                        <thead>

                            <tr>
                                <th>Task</th>

                                <th>Project</th>

                                <th>Assigned To</th>

                                <th>Deadline</th>

                            </tr>

                        </thead>


                    </table>

                </div>

            </div>
        </div>
    </div>




    @stop
    @section('after_scripts')
    <script src="{{ asset('js/resources/dashboard/script-min.js') }}"></script>
    <script src="{{ asset('js/plugins/chartJs/Chart.min.js') }}"></script>
    <script src="{{ asset('js/plugins/d3/d3.min.js') }}"></script>
    <script src="{{ asset('js/plugins/c3/c3.min.js') }}"></script>
    @include('partials.push_notification')
    <script type="text/javascript">
        var currentUser = "{{Auth::user()->id }}";
        
    </script>
    @stop