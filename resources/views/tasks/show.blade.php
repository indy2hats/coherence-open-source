<style>
    /* Todo :style fix */
   .section-comments .media-body img:not(.img-circle),
   .section-comments .note-editor img{
            max-width: 800px!important;
            max-height: 800px!important;
    }

</style>
<div class="row">   
<div class="col-md-12 col-lg-8">
    <div class="heading-inline">
        <h3 class="no-margins" style="text-align:left;float:left;">Project Name :
            @if($task->project->id)
            <a href="{{ Helper::getProjectView().$task->project->id }}"><strong>{{$task->project->project_name}}</strong></a>
            @else
            {{$task->project->project_name}}
            @endif

            @if($task->is_archived == 1)<i class="ri-archive-line"></i>@endif
        </h3>
        @if($parent) <h3 style="text-align:right;float:right;" data-id="{{$parent->id}}" id="task_id">Parent Task
            &nbsp;&nbsp;&nbsp;: <a href="../tasks/{{$parent->id}}"><strong>{{$parent->title}}</strong></a></h3>

        @endif
        <hr style="clear:both;" />
    </div>
    <h2 data-id="{{$task->id}}" class="task-title" id="task-id">
        <strong>{{$task->title}}</strong>
        @can('manage-tasks')
        <span><a><i class="ri-pencil-line epms-icon--1x edit-task pl-15" aria-hidden="true" data-parent="true" data-id="{{$task->id}}"></i></a></span>
        @if($task->is_archived == 1)
            <span><a><i class="fa fa-trash destroy-task pl-15 pr-15" data-target="#destroy_task" data-type="parent" data-id="{{$task->id}}" aria-hidden="true" data-toggle="modal" title="Delete"></i></a></span>
        @else
            <span><a><i class="ri-archive-line delete-task pl-15 pr-15 epms-icon--1x" data-target="#delete_task" data-type="parent" data-id="{{$task->id}}" aria-hidden="true" data-toggle="modal" title="Archive"></i></a></span>
        @endif
        @endcan
        @if ($task->tag)
        <span class="label label-primary">{{$task->taskTag->title}}</span>
        @endif
    </h2>
    <div class="m-b-md pt-15 tabs-container">

        <ul class="nav nav-tabs pt-10">

            <li class="active"><a data-toggle="tab" href="#tab-1"> Task Details</a></li>

            @unlessrole('client')
            <li class=""><a data-toggle="tab" href="#sessions">Sessions</a></li>
            @endunlessrole

            @if($task->parent_id == '')
            <li class=""><a data-toggle="tab" href="#sub-task">Sub Task ({{ $totalSubTasks }})</a></li>
            @endif

            @if($task->checklists->count())
            <li class=""><a data-toggle="tab" href="#checklist">Checklist</a></li>
            @endif

            @unlessrole('client')
                <li class=""><a data-toggle="tab" href="#rejection-list">Task Rejections ({{ $taskRejections->count()}})</a></li>
            @endunlessrole

            <li class=""><a data-toggle="tab" href="#documents">Documents ({{ $task->documents->count() }})</a></li>

        </ul>

        <div class="tab-content">

            <div id="tab-1" class="tab-pane active">
           
                <div class="panel-body">
                    <div class="row">
                        @unlessrole('client')
                            <div class="col-lg-4 pt-10">

                                <div class="widget style1 widget--with-icon aqua-bg">

                                        @php
                                        $activeSessions = $task->tasks_session()->whereDay('start_time', '=',
                                        date('d'))->where('current_status', 'started')->get();

                                        $activeChildSessions = collect();

                                        foreach($task->children as $children) {
                                        $activeChildSessions =
                                        $activeChildSessions->merge($children->tasks_session()->whereDay('start_time',
                                        '=', date('d'))->where('current_status', 'started')->get());
                                        }

                                        if ($activeChildSessions->count() > 0) {
                                        $activeSessions = $activeSessions->merge($activeChildSessions);
                                        }
                                        $activeStartTimes = $activeSessions->pluck('start_time')->toArray();
                                        $timeTaken = $task->time_spent + $task->children->sum('time_spent');
                                        @endphp
                                        <div>

                                            <span> Time Taken </span>
                                            <h3 class="font-bold">{{number_format($timeTaken,2)}} <span class="small" style="color: white;">
                                                    Hours </span></h3>
                                        </div> 
                                        <i class="ri-time-line ri-4x"></i>


                                </div>

                            </div>
                            <div class="col-lg-4 pt-10">

                                <div class="widget style1  widget--with-icon beer-bg pl-15">

                                        <div>

                                            <span> Estimate Hours </span>

                                            <h3 class="font-bold">
                                                @if(in_array(auth()->user()->role->name, config('general.task_actual_estimate.view_roles')))
                                                    {{number_format($task->actual_estimated_time + $task->children->sum('actual_estimated_time'),2)}}
                                                @else
                                                    {{number_format($task->estimated_time + $task->children->sum('estimated_time'),2)}}
                                                @endif
                                                <span class="small" style="color: white;"> Hours </span>
                                            </h3>
                                        </div>

                                        <i class="ri-time-line ri-4x"></i>

                                    </div>

                                </div>
                            <div class="col-lg-4 pt-10">

                                <div class="widget style1 widget--with-icon mandy-bg">

                                        <div class="col-xs-8">

                                            <span> Deadline </span>

                                            <h3 class="font-bold">{{$task->end_date_format}}</h3>

                                        </div>

                                        <i class="ri-calendar-2-line ri-4x"></i>

                                </div>
                            </div>
                            @php
                            $show_section = false;
                            foreach($exceed_reasons as $reason){
                            if($reason->exceed_reason != null)
                            $show_section = true;
                            }
                            @endphp
                            @if($show_section)
                            <div class="ibox-title">
                                <h5>Reasons why the estimate was exceeded </h5>
                            </div>
                            <div class="ibox-content">
                                @foreach($exceed_reasons as $reason)
                                @if($reason->exceed_reason != null)
                                <blockquote>

                                    <p>{!! $reason->exceed_reason !!}</p>

                                    <small><strong>{{$reason->user->full_name}}</strong> </small>

                                </blockquote>
                                @endif
                                @endforeach


                            </div>
                            @endif
                        @endunlessrole
                        <div class="col-md-12" style="padding-top: 20px;">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <a data-toggle="collapse" href="#collapse1" style="color: white">
                                        <h4 class="panel-title">
                                            <i class="fa fa-"></i> Comments<span class="pull-right"><i class="epms-icon--1x ri-arrow-up-s-line"></i></span>
                                        </h4>
                                    </a>
                                </div>
                                <div id="collapse1" class="panel-collapse collapse in">
                                    <div class="panel-body social-footer" id="commentsWrapper">
                                        <div class="section-comments">
                                            @comments(['model' => $task])
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
               
            </div>

            @unlessrole('client')
            <div id="sessions" class="tab-pane">
                <div class="panel-body">
                    @include('tasks.main-task-session')
                </div>
            </div>
            @endunlessrole

            <div id="sub-task" class="tab-pane">

                <div class="panel-body">

                    @if($task->parent_id == '')
                    <div class="row">
                        <div class="col-md-12">
                            <div class="ibox">
                                <h2 class="session-title">Sub Tasks </h2>
                                @can('manage-tasks')
                                <div class="text-right pull-right">
                                    @if($task->parent_id == '')
                                    <button data-toggle="modal" data-id="{{$task->id}}" data-target="#create_sub_task" class="btn btn-sm btn-success create-sub-modal"><i class="ri-add-line"></i> Add
                                        Sub Task </button></br>
                                    @endif
                                </div>

                                @endcan
                            </div>
                            <div class="ibox">
                                <div class="dd sub-task-div" id="nestable2">
                                    @include('tasks.sub-task.sub-task-list')
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>

            </div>
            <div id="checklist" class="tab-pane">

                <div class="panel-body">
                    @include('tasks.checklist')

                </div>

            </div>
            <div id="rejection-list" class="tab-pane">
            <div class="row">
                <div class="col-md-12">
                    <div class="ibox">
                        <h2 class="session-title">Task Rejections </h2>
                        <div class="text-right pull-right">
                            @if($task->users->contains('id', auth()->user()->id) && auth()->user()->designation->name == 'Quality Analyst')
                            <button data-toggle="modal" class="btn btn-sm btn-success" id="qa-reject" data-id="{{$task->id}}" data-type="reject"> <i class="ri-add-line"></i> Add Rejections</button>
                            @endif
                        </div>
                    </div>
                    <div class="ibox">
                        <div class="panel-body">
                            @include('tasks.showRejections')
                        </div>
                    </div>
                </div>
            </div>
           
               
            </div>
            <div id="documents" class="tab-pane">
                <div class="panel-body documents-div">
                    @include('tasks.task-documents')
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-4 col-md-12">
@unlessrole('client')
    @if(
            (
                (
                    auth()->user()->cannot('manage-tasks') && $task->users->contains('id', auth()->user()->id) && 
                    (
                        (
                            auth()->user()->designation->name == 'Quality Analyst' && in_array($task->status,['Development Completed','Under QA'])
                        ) ||
                        (
                            auth()->user()->designation->name != 'Quality Analyst' && in_array($task->status,['Backlog','In Progress'])
                        )
                    )
                ) ||
                (
                    auth()->user()->can('manage-tasks') && $task->users->contains('id', auth()->user()->id) 
                )
            )  
            &&  $task->status != 'Done'
      )
    <input type="hidden" id="percent_complete" value="{{$task->percent_complete}}">
    <div class="panel panel-primary timer-control">
        <div class="row timer-div">
            <div class="col-sm-12">
                <div class="timer-inner">
                    <img src="{{asset('images/clock-icon.svg')}}">
                   <div class="time-wrap">
                    <div>
                        <label class="f30" id="hours">00</label>hr <label class="f30" id="minutes">00</label>m <label class="f30" id="seconds">00</label>s
                    </div>
                    <div class="row text-center">
            <input type="hidden" id="task-id-timer" value="{{ $task->id }}">   
                   @php
                       $pauseInArray=in_array($task->id,Session::get('pausedTask') ?? []);

                   @endphp  
            <div class="col-md-{{ (($pauseInArray && Session::has('taskPaused'))|| (Session::get('taskPaused')==(string)$task->id) || (Session::get('taskRunning')==(string)$task->id)) ? '4':'8' }}">
                <button class="btn btn-info" data-task-id="{{ $task->id }}" id="{{  ($pauseInArray)||(Session::get('taskPaused')==(string)$task->id)? 'stop-task' :'start'}}" style="height: 100%;width: 100%">
                    <span id="timer-button"> 
                    {{ ($pauseInArray)||(Session::get('taskPaused')==(string)$task->id) ?'STOP':'START' }}                 
                   </span>
                </button>
            </div>
           
            @if(Session::has('taskPaused'))
                @if(in_array($task->id,Session::get('pausedTask')))               
                    <div class="col-md-4">           
                        <button class="btn" id="resume-task">
                            <span id="resume-button"> 
                            RESUME              
                        </span>
                        </button>              
                    </div>
                @endif
            @endif  
            @if(Session::has('taskRunning'))
             <div class="col-md-4">       
                @if( Session::get('taskRunning')==(string)$task->id )
                    <button class="btn btn-light" id="pause">
                        <span id="pause-button">PAUSE </span>
                    </button>
                @endif                  
            </div>
            @endif
            <?php
            $startStatus = 'In Progress';
            $finishStatus = "Development Completed";
            if ((auth()->user()->designation->name == 'Quality Analyst' && auth()->user()->cannot('manage-tasks'))
                || (auth()->user()->designation->name == 'Quality Analyst' && auth()->user()->can('manage-tasks') && in_array($task->status, ['Development Completed','Under QA']))) {
                $startStatus = 'Under QA';
                $finishStatus = "Done";
            }
            ?>
            <input type="hidden" id="start_task_status" value="{{ $startStatus }}"> 
            <input type="hidden" id="finish_task_status" value="{{ $finishStatus }}">   
            <div class="col-md-4">
                <button id="development_complete" class="btn btn-primary w-100" @if($task->status == 'Done' || $task->status == 'Backlog' || $task->status == 'Development Completed' || $task->percent_complete == 100) disabled @endif>
                    <span> Finish </span>
                </button>
            </div>
        </div>
                    </div>
                </div>
            </div>
            </div>

     
    </div>
    @endif
    @endunlessrole
    <div class="panel panel-primary">
        <div class="panel-heading">
            <i class="ri-information-line"></i> <strong>Assignees</strong>
        </div>
        <div class="panel-body assignees">
            @include('tasks.assignees')
        </div>
    </div>
    @if((auth()->user()->cannot('manage-tasks') && $task->users->contains('id', auth()->user()->id)) ||
    auth()->user()->can('manage-tasks'))
    <div class="panel panel-primary">
        <div class="panel-heading">
            <i class="ri-information-line"></i> <strong>Task Progress</strong>
        </div>
        <div class="ibox-content">
        @unlessrole('client')
            @can('manage-tasks')
            <input type="text" class="js-range-slider" name="my_range" data-type="single" data-min="0" data-max="100" data-from="{{$task->percent_complete}}" data-hasGrid="true" id="percent_complete" />
            <label id="completed">{{$task->percent_complete}}</label>% Completed
            @else
            <h2>
                {{$task->percent_complete}}% Completed
            </h2>
            <div class="progress progress-mini">
                <div style="width: {{$task->percent_complete}}%;" class="progress-bar progress-bar-danger">
                </div>
            </div>
            @endcan
        @endunlessrole
            <div class="row status-dropdown">
                @include('tasks.task-progress-status')
            </div>
        </div>
    </div>
    @endif
    @unlessrole('client')
        @if(auth()->user()->can('manage-tasks') || ($task->users->contains('id', auth()->user()->id) && auth()->user()->designation->name == 'Quality Analyst'))
        @if($taskCompletions->count()>0 && in_array($task->status,['Under QA']))
        <div class="panel panel-primary">
            <div class="panel-heading">
                <i class="ri-information-line"></i> <strong>Development Completion Updates</strong>
            </div>
            <div class="ibox-content">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($taskCompletions as $item)
                        <tr>
                            <td>{{$item->users->full_name}}</td>
                            <td>
                                <button class="btn btn-sm btn-success" id="reject-button" data-id="{{$item->id}}">Reject</button>
                                <button class="btn btn-sm btn-success" id="accept_button" data-row-id="{{$item->id}}">Accept</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
        @endif
    @endunlessrole
    <div class="panel panel-primary">
        <div class="panel-heading">
            <i class="ri-information-line"></i> <strong>Task Details</strong>
        </div>
        <div class="panel-body">
            <table class="table table-striped table-border">
                <tbody>
                    <tr>
                        <td><strong> Url (Jira ID) </strong></td>
                        <td class="text-left"><strong>
                                @if( $task->task_url )
                                @php $task_id = $task->task_id ? $task->task_id : 'Click' @endphp
                                <a href="{{$task->task_url}}" target="_blank" data-toggle="tooltip" data-placement="right" title="{{$task->task_url}}">{{$task_id}}
                                </a>
                                @else
                                {{ $task->task_id ? $task->task_id : 'NIL' }}
                                @endif
                            </strong>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Project </strong></td>
                        <td class="text-left">
                            @if($task->project->id)
                            <a href="{{ Helper::getProjectView().$task->project->id }}">
                                <strong>{{$task->project->project_name}}</strong></a>
                            @else
                            <strong>{{$task->project->project_name}}</strong>
                            @endif

                        </td>
                    </tr>
                    <tr>
                        <td><strong>Company </strong></td>
                        <td class="text-left"><strong>{{$task->project->client->company_name}}</strong></a></td>
                    </tr>
                    @unlessrole('client')
                    <tr>
                        <td><strong>Estimated Hours </strong></td>
                        <td class="text-left">
                            <strong>{{number_format($task->estimated_time + $task->children->sum('estimated_time'),2)}}
                                Hours</strong>
                        </td>
                    </tr>
                    @if($showActualEstimateToUser)
                    <tr>
                        <td><strong>Actual Estimated Hours </strong></td>
                        <td class="text-left">
                            <strong>{{number_format($task->actual_estimated_time + $task->children->sum('actual_estimated_time'),2)}}
                                Hours</strong>
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td><strong>Assigned Hours </strong></td>
                        <td class="text-left">
                            <strong>{{ $task->taskAssignedUsersHours->where('user_id', auth()->user()->id)->sum('hour') }} Hours</strong>
                        </td>
                    </tr>
                    @php
                    $devTime = $task->tasks_session->where('session_type', 'development')->sum('total');
                    $childs = $task->children;
                    $childDevTime = $childs->sum(function ($child) {
                    return $child->tasks_session->where('session_type', 'development')->sum('total');
                    });
                    $time = $devTime + $childDevTime;
                    @endphp
                    @if($time)
                    <tr>
                        <td><strong>Development Time </strong></td>
                        <td class="text-left"><strong>{{number_format(($time)/60, 2)}} Hours</strong></td>
                    </tr>
                    @endif
                    <tr>
                        <td><strong>Total Time Spent </strong></td>
                        <td class="text-left">
                            <strong>{{number_format($task->time_spent + $task->children->sum('time_spent'), 2)}}
                                Hours</strong>
                        </td>
                    </tr>
                    @endunlessrole
                    <tr>
                        <td><strong>Billable Hours </strong></td>
                        <td class="text-left">
                            <strong>{{number_format(($totalBilled)/60, 2)}}
                                Hours</strong>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Created By </strong></td>
                        <td class="text-left"><strong>{{$task->task_creator->fullname}}</strong></td>
                    </tr>
                    @unlessrole('client')
                    <tr>
                        <td><strong>Priority </strong></td>
                        <td class="text-left"><strong>
                                @if($task->priority == 'Low')
                                <label class="label label-success">Low</label>
                                @endif
                                @if($task->priority == 'Medium')
                                <label class="label label-primary">Medium</label>
                                @endif
                                @if($task->priority == 'High')
                                <label class="label label-danger">High</label>
                                @endif
                                @if($task->priority == 'Critical')
                                <label class="label label-critical">Critical</label>
                                @endif
                            </strong>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Deadline </strong></td>
                        <td class="text-left"><strong>{{$task->end_date_format}}</strong></td>
                    </tr>
                    @endunlessrole
                    <tr>
                        <td><strong>Tags </strong></td>
                        <td class="text-left"><strong>{{ucwords(str_replace('-', ' ', $task->tag))}}</strong></td>
                    </tr>
                    @unlessrole('client')
                    <tr>
                        <td><strong>Task Start Date </strong></td>
                        <td class="text-left"><strong>{{$task->start_date_format}}</strong></td>
                    </tr>
                    @endunlessrole
                    <tr>
                        <td><strong>Task Creation Date </strong></td>
                        <td class="text-left"><strong>{{$task->created_at_format}}</strong></td>
                    </tr>
                    <tr>
                        <td><strong>Admin Approvers</strong></td>
                        <td class="text-left"><strong> @foreach($task->approvers as $user)
                                {{$user->full_name}}
                                @if(!$loop->last)
                                {{', '}}
                                @endif
                                @endforeach</strong></td>
                    </tr>
                    <tr>
                        <td><strong>Technical Reviewer </strong></td>
                        <td class="text-left"><strong>{{$task->reviewer->full_name ?? ''}}</strong></td>
                    </tr>
                    <tr>
                        <td><strong>Branches</strong></td>
                        <td class="text-left branch-name-list"><strong>
                            @if(count($branches) > 0 )
                            @foreach($branches as $branch)
                                <a href="{{$branch->url}}" target="_blank" data-toggle="tooltip" data-placement="right" title="{{$branch->name}}">{{$branch->name}}</a><br>
                            @endforeach</strong>
                            @else
                            <strong>No Branches Added</strong>
                            @endif
                        </td>
                    </tr>

                </tbody>
            </table>
            <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#show-project-credentials"><i class="ri-eye-off-line"></i> Credentials</button>
            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#show-project-files"><i class="ri-attachment-2"></i> Documents</button>
            <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#show-branches"><i class="ri-attachment-2"></i> Branches</button>
        </div>
    </div>
</div>
</div>
@include('tasks.delete')
@include('tasks.rejectTask')
@include('tasks.rejectTask-qa')
@include('tasks.projectFiles')
@include('tasks.projectCredentials')
@include('tasks.branches')
@include('tasks.sub-task.sub-task-create')
@include('tasks.adminApprove')
@include('employeetasks.create')
@include('tasks.stop-session')
@include('tasks.comment-modals')
@include('tasks.time-exceed-reason')
@include('tasks.add-documents')

@include('employeetasks.delete')
@include('tasks.destroy')
<div class="modal custom-modal" id="edit_task_session" role="dialog">
</div>
<div id="edit_task" class="modal custom-modal" role="dialog">
</div>
@include('tasks.create-tag')