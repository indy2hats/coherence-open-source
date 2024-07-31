@extends('layout.main')
@section('content')
<div class="row">
    <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-success pull-right">Overall</span>
                    <h5>Completed Tasks</h5>
                </div>
                <div class="ibox-content">
                    <h2 class="no-margins">{{ $counts['completed'] }}</h2>
                    <div class="stat-percent font-bold text-success"><small>Total Tasks: </small>{{ $counts['total'] }} </div>
                    <small> Tasks</small>
                </div>
            </div>
    </div>
    <div class="col-lg-3">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <span class="label label-warning pull-right"><i class="ri-time-line"></i></span>
                <h5>Tasks</h5>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-md-6 text-left">
                        <h2 class="no-margins">{{$counts['upcoming']}}</h2>
                        <small>Upcoming Tasks</small>
                    </div>
                    <div class="col-md-6 text-right">
                        <h2 class="no-margins">{{$counts['ongoing']}}</h2>
                        <small>Ongoing Tasks</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <span class="label label-info pull-right">Overall</span>
                <h5>Total Hours Worked</h5>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-md-6 text-left">
                        <h2 class="no-margins">{{ floor($totalHours->total/60).'h '.($totalHours->total%60).'m'}}</h2>
                        <small>Total</small>
                    </div>
                    <div class="col-md-6 text-right">
                        <h2 class="no-margins">{{ floor($totalHours->billed/60).'h '.($totalHours->billed%60).'m'}}</h2>
                        <small>Billed</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <span class="label label-info pull-right">This Week</span>
                <h5>{{ floor($total['total']/60).'h '.($total['total']%60).'m'}}</h5>
            </div>
            <div class="ibox-content">
                <div id="sparkline1"></div>
            </div>
        </div>
    </div>
</div>
@if($rejected->count() != 0)
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                    <div class="ibox-title red-bg">
                        <h5 style="color:#fff;"><i class="fa fa-warning"></i> Rejected Tasks</h5>
                    </div>
                    <div class="ibox-content">

                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Task</th>
                                <th>Project</th>
                                <th>Rejected On</th>
                                <th>Rejected By</th>
                                <th>Reason</th>
                                <th>Severity</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($rejected as $task)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{$task->task->title}}</td>
                                <td>{{$task->task->project->project_name}}</td>
                                <td>{{$task->updated_at->format('d/m/Y')}}</td>
                                <td>{{$task->rejectedBy->full_name ?? ''}}</td>
                                <td>@foreach($task->issue as $reason)
                                    <span class="label-plain block" style="margin: 2px;">
                                    {{$reason}} </span>
                                    @endforeach
                                </td>
                                <td>{{$task->severity}}</td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
        </div>
    </div>
@endif
<div class="row" style="margin-top: 20px" ;>
    <div class="col-md-12">
        <div class="panel-group skilled-panel">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <a data-toggle="collapse" href="#collapse1" style="color: white">
                        <h4 class="panel-title">
                            <i class="fa fa-tasks"></i> Tasks in Progress<span class="pull-right"><i class="epms-icon--1x ri-arrow-up-s-line"></i></span></h4>
                    </a>
                </div>
                <div id="collapse1" class="panel-collapse collapse in">
                    <div class="panel-body">
                        <div class="col-lg-12">
                            <div class="table-responsive">

                                <table class="table table-striped table-bordered table-hover dataTableProductive">

                                    <thead>
                                        <tr>

                                            <th>Task</th>

                                            <th>Project</th>

                                            <th>Deadline</th>

                                            <th>Estimated Hours</th>

                                            <th>Time Spent</th>
                                            @unlessrole('client')
                                            <th>Action</th>
                                            @endunlessrole

                                        </tr>

                                    </thead>

                                    <tbody>
                                    @unlessrole('client')
                                        <?php 
                                        $startStatus = 'In Progress';
                                        $finishStatus = "Development Completed";
                                        if((auth()->user()->designation->name == 'Quality Analyst' && auth()->user()->cannot('manage-tasks')) 
                                            || (auth()->user()->designation->name == 'Quality Analyst' && auth()->user()->can('manage-tasks') && in_array($task->status,['Development Completed','Under QA']))) {
                                            $startStatus = 'Under QA';
                                            $finishStatus = "Done";
                                        }
                                        ?>
                                    @endunlessrole
                                    <input type="hidden" id="start_task_status" value="{{ $startStatus }}"> 
                                    <input type="hidden" id="finish_task_status" value="{{ $finishStatus }}">   
                                    @foreach($inProgressTasks as $task)
                                        <tr class="gradeX">
                                            <?php $userTotalTimeSpent = $task->user_tasks_session()->sum('total'); ?> 
                                            <td><a href="/tasks/{{ $task->id }}">{{ $task->title}}</a></td>

                                            <td>{{ $task->project->project_name}}</td>

                                            <td>{{ $task->end_date}}</td>

                                            <td>{{ floor(($task->estimated_time*60)/60).'h '.(($task->estimated_time*60)%60).'m'}}</td>

                                            <td >{{ floor(($userTotalTimeSpent)/60).'h '.floor(($userTotalTimeSpent)%60).'m'}}</td>
                                            @unlessrole('client')
                                                <td data-task-id="{{$task->id}}" id="td_{{$task->id}}">
                                                    @if (($task->status == 'In Progress' && (auth()->user()->designation->name != 'Quality Analyst' || auth()->user()->can('manage-tasks'))) ||
                                                        (($task->status == 'Under QA' || $task->status == 'Development Completed' ) && auth()->user()->designation->name == 'Quality Analyst'))
                                                        
                                                    <label id="hours">00</label>hr <label id="minutes">00</label>m <label id="seconds">00</label>s
                                                    <button class="btn btn-info" type="button" id="start-button"><i class="fa fa-play" id="icon_{{$task->id}}"></i>
                                                        </button>
                                                    @endif
                                                </td>
                                            @endunlessrole
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('tasks.stop-session')
        @stop
        @section('after_scripts')
        <script src="{{ asset('js/resources/dashboard/employee-script-min.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
        <script src="{{ asset('js/plugins/sparkline/jquery.sparkline.min.js') }}"></script>
        <script type="text/javascript">
            var currentUser = "<?php echo Auth::user()->id ?>";
                var sparklineCharts = function(){

         $("#sparkline1").sparkline([{{$total['Mon']/60}},{{$total['Tue']/60}},{{$total['Wed']/60}},{{$total['Thu']/60}},{{$total['Fri']/60}},{{$total['Sat']/60}},{{$total['Sun']/60}}], {

             type: 'line',

             width: '100%',

             height: '60',

             lineColor: '#1ab394',

             fillColor: "#ffffff"

         });


    };
        </script>
        @include('partials.push_notification')
        @stop