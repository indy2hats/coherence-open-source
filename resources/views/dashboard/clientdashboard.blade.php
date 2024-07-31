@extends('layout.main')
@section('content')
<div class="row">
    <div class="col-md-12 text-right">
            @can('manage-tasks')
                <a href="#" class="btn btn-w-m btn-success create-modal" data-toggle="modal" data-target="#add_task"><i class="ri-add-line"></i> Add Task</a>
            @endcan
    </div>
</div>
<div class="row" style="margin-top: 20px" ;>
    <div class="col-md-12">
        <div class="panel-group skilled-panel">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <a data-toggle="collapse" href="#collapse1" style="color: white">
                        <h4 class="panel-title">
                            <i class="fa fa-tasks"></i> Tasks in Progress<span class="pull-right"><i class="ri-arrow-up-s-line"></i></span></h4>
                    </a>
                </div>
                <div id="collapse1" class="panel-collapse collapse in">
                    <div class="panel-body">
                        <div class="col-lg-12">
                            <div class="table-responsive">

                                <table class="table table-striped table-bordered table-hover dataTableProductive dataTable">

                                    <thead>
                                        <tr>
                                            <th>Task</th>
                                            <th>Project</th>
                                            <th>Deadline</th>
                                            <th>Estimated Hours</th>
                                            <th>Time Spent</th>
                                        </tr>

                                    </thead>

                                    <tbody>
                                        @foreach($inProgressTasks as $task)
                                        <tr class="gradeX">
                                            <?php $userTotalTimeSpent = $task->tasks_session()->sum('total'); ?>
                                            <td><a href="/tasks/{{ $task->id }}">{{ $task->title}}</a></td>

                                            <td>{{ $task->project->project_name}}</td>

                                            <td>{{ \Carbon\Carbon::parse($task->end_date)->format('d/m/Y')}}</td>

                                            <td>{{ floor(($task->estimated_time*60)/60).'h '.(($task->estimated_time*60)%60).'m'}}</td>

                                            <td>{{ floor(($userTotalTimeSpent)/60).'h '.floor(($userTotalTimeSpent)%60).'m'}}</td>
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
    </div>
</div>

@include('tasks.stop-session')
@include('tasks.add')
@stop
@section('after_scripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="{{ asset('js/plugins/iCheck/icheck.min.js') }}"></script>
<link href="{{ asset('css/plugins/iCheck/custom.css') }}" rel="stylesheet">
<script src="{{ asset('js/jquery.mask.min.js') }}"></script>
<script src="{{ asset('js/plugins/typehead/bootstrap3-typeahead.min.js') }}"></script>
<script src="{{ asset('js/jquery.mask.min.js') }}"></script>
<script src="{{ asset('js/resources/dashboard/client-script-min.js') }}"></script>
@include('partials.push_notification')
@stop