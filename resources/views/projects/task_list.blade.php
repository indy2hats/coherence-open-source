<div class="project-task" style="margin-top: 15px">

    <div class="tabs-container">

        <ul class="nav nav-tabs">

            <li class="active"><a href="#all_tasks" data-toggle="tab">Active Tasks</a></li>
            <li class=""><a href="#upcoming_tasks" data-toggle="tab">Upcoming Tasks</a></li>
            <li class=""><a href="#ongoing_tasks" data-toggle="tab">Tasks in Progress</a></li>
            <li class=""><a href="#completed_tasks" data-toggle="tab">Completed Tasks</a></li>
            <li class=""><a href="#archived_tasks" data-toggle="tab">Archived Tasks</a></li>
            @can('view-project-cost') <li class=""><a href="#cost" data-toggle="tab" id="project-cost-tab">Cost</a></li> @endcan
        </ul>
        <div class="tab-content">

            <div id="all_tasks" class="tab-pane active">

                <div class="panel-body">

                    <table class="table dataTable">

                        <thead>

                            <tr>
                                <th>Task Code</th>
                                <th>Task title</th>
                                <th>Users</th>
                                <th>Status</th>
                                <th>Priority</th>
                                <th>Created</th>
                                @can('manage-tasks')<th>Action</th>@endcan
                            </tr>

                        </thead>

                        <tbody>

                            @foreach ($allTasks as $task)
                            @if(($task->estimated_time<$task->time_spent || $task->end_date < date('Y-m-d')) &&
                                    in_array($task->status,config('overdue-status')))
                                    <tr>
                                        @else
                                    <tr>
                                        @endif
                                        <td><a href="/tasks/{{ $task->id }}">{{$task->code}}</a></td>
                                        <td><a href="/tasks/{{ $task->id }}">{{$task->title}}</a></td>
                                        <td>
                                            @foreach ($task->users as $user)
                                            {{$user->first_name}}
                                            @if(!$loop->last)
                                            {{', '}}
                                            @endif
                                            @endforeach

                                        </td>
                                        <td>
                                        <strong>
                                        @if($task->status == 'Backlog')
                                        <label class="label label-info">Backlog</label>
                                        @endif
                                        @if($task->status == 'In Progress')
                                        <label class="label label-danger">In Progress</label>
                                        @endif
                                        @if($task->status == 'Development Completed')
                                        <label class="label label-warning">Dev Completed</label>
                                        @endif
                                        @if($task->status == 'Under QA')
                                        <label class="label label-info">Under QA</label>
                                        @endif
                                        @if($task->status == 'On Hold')
                                        <label class="label label-deafult">On Hold</label>
                                        @endif
                                        @if($task->status == 'Awaiting Client')
                                        <label class="label label-plain">Awaiting Client</label>
                                        @endif
                                        @if($task->status == 'Client Review')
                                        <label class="label label-warning">Client Review</label>
                                        @endif
                                        @if($task->status == 'Done')
                                        <label class="label label-success">Done</label>
                                        @endif
                                    </strong>
                                </td>
                                        <td><span @if($task->priority == 'Critical')class="text-danger"@endif>{{$task->priority}}</span></td>
                                        <td>{{$task->created_at_format}}</td>
                                        @can('manage-tasks')<td>
                                            <span class="edit-i"><a data-tooltip="tooltip" data-placement="top"
                                                    title="Edit"><i data-id="{{$task->id}}"
                                                        class="ri-pencil-line edit-task"
                                                        aria-hidden="true"></i></a></span> |
                                            <span class="dlt-i"><a href="#" class="delete_task_onclick"
                                                    data-id="{{ $task->id }}"><a href="#"
                                                        class="delete_task_from_project_onclick"
                                                        data-id="{{ $task->id }}" data-tooltip="tooltip"
                                                        data-placement="top" title="Archive">
                                                        <i data-toggle="modal" data-target="#delete_tasks"
                                                            class="ri-archive-line" aria-hidden="true"></i>
                                                    </a>
                                            </span>
                                        </td>@endcan
                                    </tr>
                                    @endforeach
                        </tbody>

                    </table>

                </div>

            </div>

            <div id="upcoming_tasks" class="tab-pane">

                <div class="panel-body">

                    <table class="table dataTable">

                        <thead>

                            <tr>
                                <th>Task Code</th>
                                <th>Task title</th>
                                <th>Users</th>
                                <th>Status</th>
                                <th>Priority</th>
                                <th>Created</th>
                                @can('manage-tasks')<th>Action</th>@endcan
                            </tr>

                        </thead>

                        <tbody>

                            @foreach ($upcomingTasks as $task)
                            
                                <td><a href="/tasks/{{ $task->id }}">{{$task->code}}</a></td>
                                <td><a href="/tasks/{{ $task->id }}">{{$task->title}}</a></td>
                                <td>
                                    @foreach ($task->users as $user)
                                    {{$user->first_name}}
                                    @if(!$loop->last)
                                    {{', '}}
                                    @endif
                                    @endforeach

                                </td>
                                <td>
                                <strong>
                                @if($task->status == 'Backlog')
                                <label class="label label-info">Backlog</label>
                                @endif
                                @if($task->status == 'In Progress')
                                <label class="label label-danger">In Progress</label>
                                @endif
                                @if($task->status == 'Development Completed')
                                <label class="label label-warning">Dev Completed</label>
                                @endif
                                @if($task->status == 'Under QA')
                                <label class="label label-info">Under QA</label>
                                @endif
                                @if($task->status == 'On Hold')
                                <label class="label label-deafult">On Hold</label>
                                @endif
                                @if($task->status == 'Awaiting Client')
                                <label class="label label-plain">Awaiting Client</label>
                                @endif
                                @if($task->status == 'Client Review')
                                <label class="label label-warning">Client Review</label>
                                @endif
                                @if($task->status == 'Done')
                                <label class="label label-success">Done</label>
                                @endif
                                </strong>
                                </td>
                                        <td><span @if($task->priority == 'Critical')class="text-danger"@endif>{{$task->priority}}</span></td>
                                        {{-- <td>Working</td> --}}
                                        <td>{{$task->created_at_format}}</td>
                                        @can('manage-tasks')<td>
                                            <span class="edit-i"><a data-tooltip="tooltip" data-placement="top"
                                                    title="Edit"><i data-id="{{$task->id}}"
                                                        class="ri-pencil-line edit-task"
                                                        aria-hidden="true"></i></a></span> |
                                            <span class="dlt-i"><a href="#" class="delete_task_onclick"
                                                    data-id="{{ $task->id }}"><a href="#"
                                                        class="delete_task_from_project_onclick"
                                                        data-id="{{ $task->id }}" data-tooltip="tooltip"
                                                        data-placement="top" title="Archive">
                                                        <i data-toggle="modal" data-target="#delete_tasks"
                                                            class="ri-archive-line" aria-hidden="true"></i>
                                                    </a>
                                            </span>
                                        </td>@endcan
                                    </tr>
                            @endforeach
                        </tbody>

                    </table>

                </div>

            </div>

            <div id="ongoing_tasks" class="tab-pane">

                <div class="panel-body">

                    <table class="table dataTable">

                        <thead>

                            <tr>
                                <th>Task Code</th>
                                <th>Task title</th>
                                <th>Users</th>
                                <th>Status</th>
                                <th>Priority</th>
                                <th>Created</th>
                                @can('manage-tasks')<th>Action</th>@endcan
                            </tr>

                        </thead>

                        <tbody>

                            @foreach ($ongoingTasks as $task)
                            @if(($task->estimated_time<$task->time_spent || $task->end_date < date('Y-m-d')) &&
                                    in_array($task->status,config('overdue-status')))
                                    <tr>
                                        @else
                                    <tr>
                                        @endif
                                        <td><a href="/tasks/{{ $task->id }}">{{$task->code}}</a></td>
                                        <td><a href="/tasks/{{ $task->id }}">{{$task->title}}</a></td>
                                        <td>
                                            @foreach ($task->users as $user)
                                            {{$user->first_name}}
                                            @if(!$loop->last)
                                            {{', '}}
                                            @endif
                                            @endforeach

                                        </td>
                                        <td>
                                        <strong>
                                        @if($task->status == 'Backlog')
                                        <label class="label label-info">Backlog</label>
                                        @endif
                                        @if($task->status == 'In Progress')
                                        <label class="label label-danger">In Progress</label>
                                        @endif
                                        @if($task->status == 'Development Completed')
                                        <label class="label label-warning">Dev Completed</label>
                                        @endif
                                        @if($task->status == 'Under QA')
                                        <label class="label label-info">Under QA</label>
                                        @endif
                                        @if($task->status == 'On Hold')
                                        <label class="label label-deafult">On Hold</label>
                                        @endif
                                        @if($task->status == 'Awaiting Client')
                                        <label class="label label-plain">Awaiting Client</label>
                                        @endif
                                        @if($task->status == 'Client Review')
                                        <label class="label label-warning">Client Review</label>
                                        @endif
                                        @if($task->status == 'Done')
                                        <label class="label label-success">Done</label>
                                        @endif
                                    </strong>
                                </td>
                                        <td><span @if($task->priority == 'Critical')class="text-danger"@endif>{{$task->priority}}</span></td>
                                        {{-- <td>Working</td> --}}
                                        <td>{{$task->created_at_format}}</td>
                                        @can('manage-tasks')<td>
                                            <span class="edit-i"><a data-tooltip="tooltip" data-placement="top"
                                                    title="Edit"><i data-id="{{$task->id}}"
                                                        class="ri-pencil-line edit-task"
                                                        aria-hidden="true"></i></a></span> |
                                            <span class="dlt-i"><a href="#" class="delete_task_onclick"
                                                    data-id="{{ $task->id }}"><a href="#"
                                                        class="delete_task_from_project_onclick"
                                                        data-id="{{ $task->id }}" data-tooltip="tooltip"
                                                        data-placement="top" title="Archive">
                                                        <i data-toggle="modal" data-target="#delete_tasks"
                                                            class="ri-archive-line" aria-hidden="true"></i>
                                                    </a>
                                            </span>
                                        </td>@endcan
                                    </tr>
                                    @endforeach
                        </tbody>

                    </table>

                </div>

            </div>

            <div id="completed_tasks" class="tab-pane">

                <div class="panel-body">

                    <table class="table dataTable">

                        <thead>

                            <tr>
                                <th>Task Code</th>
                                <th>Task title</th>
                                <th>Users</th>
                                <th>Status</th>
                                <th>Priority</th>
                                <th>Created</th>
                                @can('manage-tasks')<th>Action</th>@endcan
                            </tr>

                        </thead>

                        <tbody>

                            @foreach ($completedTasks as $task)
                            <tr>
                                <td><a href="/tasks/{{ $task->id }}">{{$task->code}}</a></td>
                                <td><a href="/tasks/{{ $task->id }}">{{$task->title}}</a></td>
                                <td>
                                    @foreach ($task->users as $user)
                                    {{$user->first_name}}
                                    @if(!$loop->last)
                                    {{', '}}
                                    @endif
                                    @endforeach

                                </td>
                                <td>
                                        <strong>
                                        @if($task->status == 'Backlog')
                                        <label class="label label-info">Backlog</label>
                                        @endif
                                        @if($task->status == 'In Progress')
                                        <label class="label label-danger">In Progress</label>
                                        @endif
                                        @if($task->status == 'Development Completed')
                                        <label class="label label-warning">Dev Completed</label>
                                        @endif
                                        @if($task->status == 'Under QA')
                                        <label class="label label-info">Under QA</label>
                                        @endif
                                        @if($task->status == 'On Hold')
                                        <label class="label label-deafult">On Hold</label>
                                        @endif
                                        @if($task->status == 'Awaiting Client')
                                        <label class="label label-plain">Awaiting Client</label>
                                        @endif
                                        @if($task->status == 'Client Review')
                                        <label class="label label-warning">Client Review</label>
                                        @endif
                                        @if($task->status == 'Done')
                                        <label class="label label-success">Done</label>
                                        @endif
                                    </strong>
                                </td>
                                <td><span @if($task->priority == 'Critical')class="text-danger"@endif>{{$task->priority}}</span></td>
                                {{-- <td>Working</td> --}}
                                <td>{{$task->created_at_format}}</td>
                                @can('manage-tasks')<td>
                                    <span class="edit-i"><a data-tooltip="tooltip" data-placement="top" title="Edit"><i
                                                data-id="{{$task->id}}" class="ri-pencil-line edit-task"
                                                aria-hidden="true"></i></a></span> |
                                    <span class="dlt-i"><a href="#" class="delete_task_onclick"
                                            data-id="{{ $task->id }}"><a href="#"
                                                class="delete_task_from_project_onclick" data-id="{{ $task->id }}"
                                                data-tooltip="tooltip" data-placement="top" title="Archive">
                                                <i data-toggle="modal" data-target="#delete_tasks" class="ri-archive-line"
                                                    aria-hidden="true"></i>
                                            </a>
                                    </span>
                                </td>@endcan
                            </tr>
                            @endforeach
                        </tbody>

                    </table>

                </div>

            </div>

            <div id="archived_tasks" class="tab-pane">

                <div class="panel-body">

                    <table class="table dataTable">

                        <thead>

                            <tr>
                                <th>Task Code</th>
                                <th>Task title</th>
                                <th>Users</th>
                                <th>Status</th>
                                <th>Priority</th>
                                <th>Created</th>
                                @can('manage-tasks')<th>Action</th>@endcan
                            </tr>

                        </thead>

                        <tbody>
                        @isset($archivedTasks)
                            @foreach ($archivedTasks as $task)
                            @if(($task->estimated_time<$task->time_spent || $task->end_date < date('Y-m-d')) &&
                                    in_array($task->status,config('overdue-status')))
                                    <tr>
                                        @else
                                    <tr>
                                        @endif
                                        <td><a href="/tasks/{{ $task->id }}">{{$task->code}}</a></td>
                                        <td><a href="/tasks/{{ $task->id }}">{{$task->title}}</a></td>
                                        <td>
                                            @foreach ($task->users as $user)
                                            {{$user->first_name}}
                                            @if(!$loop->last)
                                            {{', '}}
                                            @endif
                                            @endforeach

                                        </td>
                                        <td>
                                        <strong>
                                        @if($task->status == 'Backlog')
                                        <label class="label label-info">Backlog</label>
                                        @endif
                                        @if($task->status == 'In Progress')
                                        <label class="label label-danger">In Progress</label>
                                        @endif
                                        @if($task->status == 'Development Completed')
                                        <label class="label label-warning">Dev Completed</label>
                                        @endif
                                        @if($task->status == 'Under QA')
                                        <label class="label label-info">Under QA</label>
                                        @endif
                                        @if($task->status == 'On Hold')
                                        <label class="label label-deafult">On Hold</label>
                                        @endif
                                        @if($task->status == 'Awaiting Client')
                                        <label class="label label-plain">Awaiting Client</label>
                                        @endif
                                        @if($task->status == 'Client Review')
                                        <label class="label label-warning">Client Review</label>
                                        @endif
                                        @if($task->status == 'Done')
                                        <label class="label label-success">Done</label>
                                        @endif
                                    </strong>
                                </td>
                                        <td><span @if($task->priority == 'Critical')class="text-danger"@endif>{{$task->priority}}</span></td>
                                        {{-- <td>Working</td> --}}
                                        <td>{{$task->created_at_format}}</td>
                                        @can('manage-tasks')<td>
                                            <span class="edit-i"><a data-tooltip="tooltip" data-placement="top"
                                                    title="Edit"><i data-id="{{$task->id}}"
                                                        class="ri-pencil-line edit-task"
                                                        aria-hidden="true"></i></a></span> |
                                            <span class="dlt-i"><a href="#" class="delete_task_onclick"
                                                    data-id="{{ $task->id }}"><a href="#"
                                                        class="destroy_task_from_project_onclick"
                                                        data-id="{{ $task->id }}" data-tooltip="tooltip"
                                                        data-placement="top" title="Delete">
                                                        <i data-toggle="modal" data-target="#destroy_tasks"
                                                            class="ri-delete-bin-line" aria-hidden="true"></i>
                                                    </a>
                                            </span>
                                        </td>@endcan
                                    </tr>
                                    @endforeach
                        @endisset
                        </tbody>

                    </table>

                </div>

            </div>

            @can('view-project-cost')
            <div id="cost" class="tab-pane">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="ibox">
                                <div class="ibox-content project-cost-report-filter m-b pull-left tabs-container">
                                    <form id="percentage-split-up-per-client-filter" action="" method="get" class="w-full" >
                                        @csrf {{ csrf_field() }}
                                            @include('projects.project_cost.filters.timerange-project-cost')
                                            @include('projects.project_cost.filters.user')
                                            @include('projects.project_cost.filters.session_type')
                                    </form>
                                    <div class="row" id="project-cost-table-container">       
                                        <div class="col-lg-12">
                                            <div class="ibox float-e-margins">
                                                <div class="ibox-content animated fadeInUp">
                                                
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
            @endcan
        
        </div>

    </div>
</div>