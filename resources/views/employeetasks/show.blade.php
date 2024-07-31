<div class="row">
    <div class="col-lg-8 col-xl-9">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <div @if(($task->estimated_time<$task->time_spent || $task->end_date < date('Y-m-d')) && in_array($task->status,config('overdue-status'))) class="row " @else class="row" @endif>
                            <h3>

                                @if($task->priority == 'Low')
                                <label class="label label-success">High</label>
                                @endif
                                @if($task->priority == 'Medium')
                                <label class="label label-primary">High</label>
                                @endif
                                @if($task->priority == 'High')
                                <label class="label label-danger">High</label>
                                @endif
                                @if($task->priority == 'Critical')
                                <label class="label label-critical">High</label>
                                @endif

                                <label class="label label-primary">Estimate: {{$task->estimated_time}}hr</label>
                                <label class="label label-default">Time Taken: {{$task->time_spent}}hr</label>

                            </h3>
                            <div class="col-md-12">
                                <div>
                                    <h2 data-id="{{$task->id}}" id="task-id"><strong>{{$task->title}}</strong>

                                    </h2>
                                    <table>
                                        <tr>
                                            <td>
                                                <h3>Project : <strong>{{$task->project->project_name}}</strong></h3>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Company : <strong>{{$task->project->client->company_name}}</strong></td>
                                        </tr>

                                        <tr>
                                            <td>Heads : <strong>
                                                    @foreach($task->project->projectUsers as $project_heads)
                                                    {{$project_heads->full_name}}
                                                    @if(!$loop->last)
                                                    {{', '}}
                                                    @endif
                                                    @endforeach
                                                </strong></td>
                                        </tr>
                                        <tr>
                                            <td>Employees : <strong>
                                                    @foreach($task->users as $user)
                                                    {{$user->full_name}}
                                                    @if(!$loop->last)
                                                    {{', '}}
                                                    @endif
                                                    @endforeach
                                                </strong></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                </div>


                @if($task->task_url != null)
                <div class="row" style="margin: 5px">
                    <div class="row" style="padding:5px">
                        Task URL : <a href="{{$task->task_url}}" target="blank"> {{$task->task_url}} <i class="fa fa-external-link"></i></a>
                    </div>
                </div>

                @endif

                @if($task->description !=null)
                <div class="row" style="margin: 5px">
                    <div class="row" style="padding:5px">
                        Description :
                    </div>
                    <div class="row" style="border: 1px solid #ccc;border-radius: 5px;padding: 5px">{!!$task->description!!}
                    </div>
                </div>
                @endif
            </div>
        </div>
        <div class="sessions">
            @include('employeetasks.listSession')
        </div>
    </div>
    <div class="col-lg-4 col-xl-3">
        <input type="hidden" id="percent_complete" value="{{$task->percent_complete}}">
        <div class="panel panel-primary">
            <div class="row timer-div">
                <div class="col-sm-12" style="color: green;vertical-align: middle;font-size: 30px;">
                    <img src="{{asset('images/timer.png')}}" width="20%" style="margin-left:20px">
                    <label id="hours">00</label>hr <label id="minutes">00</label>m <label id="seconds">00</label>s
                </div>
            </div>
            <div class="panel-body text-center">
                <input type="hidden" id="task-id-timer" value="{{ $task->id }}">
                <div class="col-md-8">
                    <button class="btn btn-info" id="start" style="height: 100%;width: 100%">
                        <label id="timer-button">START</label>
                    </button>
                </div>
                <div class="col-md-4">
                    <button id="development_complete" class="btn btn-primary w-100" style="height: 100%;width: 100%" @if($task->status == 'Done' || $task->percent_complete == 100) disabled @endif>
                        <label>Finish</label>
                    </button>
                </div>
            </div>
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <i class="fa fa-percent"></i> Overall Status
            </div>
            <div class="panel-body">
                <h2>
                    {{$task->percent_complete}}% Completed</h2>
                <div class="progress progress-mini">
                    <div style="width: {{$task->percent_complete}}%;" class="progress-bar progress-bar-danger"></div>
                </div>
                <div class="row" style="margin-top: 10px">
                    @if($task->status != 'Done')
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="">Status</label>
                            <select class="chosen-select" id="task_status" name="task_status">
                                <option {{$task->status == 'Backlog' ? 'selected' : ''}}>Backlog</option>
                                <option {{$task->status == 'In Progress' ? 'selected' : ''}}>In Progress</option>
                                <option {{$task->status == 'Development Completed' ? 'selected' : ''}}>Development Completed</option>
                                <option {{$task->status == 'Under QA' ? 'selected' : ''}}>Under QA</option>
                            </select>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <i class="ri-information-line"></i> Project : <strong>{{$task->project->project_name}}</strong>
            </div>
            <div class="panel-body">
                <table class="table table-striped table-border">
                    <tbody>
                        <tr>
                            <td><strong>Type :</strong></td>
                            <td class="text-right"><strong>{{$task->project->project_type}}</strong></td>
                        </tr>
                        <tr>
                            <td><strong>Cost :</strong></td>
                            <td class="text-right"><strong>{{$task->project->rate}} {{$task->project->client->currency}}</strong></td>
                        </tr>
                        <tr>
                            <td><strong>Total Hours :</strong></td>
                            <td class="text-right"><strong>{{$task->project->total_hours}} Hours</strong></td>
                        </tr>
                        <tr>
                            <td><strong>Created :</strong></td>
                            <td class="text-right"><strong>{{$task->project->created_at_format}}</strong></td>
                        </tr>
                        <tr>
                            <td><strong>Start Date :</strong></td>
                            <td class="text-right"><strong>{{$task->project->start_date_format}}</strong></td>
                        </tr>
                        <tr>
                            <td><strong>Deadline :</strong></td>
                            <td class="text-right"><strong>{{$task->project->end_date_format}}</strong></td>
                        </tr>
                        <tr>
                            <td><strong>Priority :</strong></td>
                            <td class="text-right"><strong>
                                    @if($task->project->priority=="High")
                                    <label class="label label-danger">High</label>
                                    @endif
                                    @if($task->project->priority=="Medium")
                                    <label class="label label-warning">Medium</label>
                                    @endif
                                    @if($task->project->priority=="Low")
                                    <label class="label label-primary">Low</label>
                                    @endif
                                    @if($task->project->priority=="Critical")
                                    <label class="label label-critical">Critical</label>
                                    @endif
                                </strong>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#show-project-credentials"><i class="ri-eye-off-line"></i> Credentials</button>
                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#show-project-files"><i class="ri-attachment-2"></i> Files</button>
            </div>
        </div>
    </div>
</div>