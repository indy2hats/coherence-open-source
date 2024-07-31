<div class="table-responsive">
        <div>
            <table class="table taskTable">
                <thead>
                    <tr>
                        <th>Task Title</th>
                        <th class="col-3">Jira ID</th>
                        <th>Project Name</th>
                        @unlessrole('client')
                            <th class="hide-header">Deadline</th>
                        @endunlessrole
                        <th>Assigned To</th>
                        @unlessrole('client')
                            <th>Estimated Time</th>
                            <th>Priority</th>
                        @endunlessrole
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($tasks) > 0)
                    @foreach ($tasks as $task)
                    @if(($task->estimated_time<$task->time_spent || $task->end_date < date('Y-m-d')) && in_array($task->
                            status,config('overdue-status')))
                            <tr>
                                @else
                            <tr>
                                @endif

                                <td>
                                    <a href="/tasks/{{ $task->id }}">
                                        {{$task->title}}
                                        @if ($task->tag)
                                        <span class="label label-primary">{{$task->taskTag->title}}</span>
                                        @endif
                                    </a>
                                </td>
                                <td class="col-3"> 
                                    @if($task->task_url)
                                    <a href="{{ $task->task_url }}" target="_blank">
                                        {{$task->task_id}}
                                    </a>
                                    @else
                                    {{$task->task_id}}
                                    @endif
                                </td>
                                <td>
                                    @if($task->project->id)
                                    <a href="{{ Helper::getProjectView().$task->project->id }}">{{$task->project->project_name}}</a>
                                    @else
                                        {{$task->project->project_name}}
                                    @endif
                                </td>
                                @unlessrole('client')
                                    <td class="hide-cell">{{$task->end_date_format}}</td>
                                @endunlessrole
                                <td>@foreach($task->users as $user)
                                    {!! $user->full_name."</br>" !!}
                                    @endforeach</td>
                                @unlessrole('client')
                                    <td>@if(in_array(auth()->user()->role->name, config('general.task_actual_estimate.view_roles')))
                                            {{$task->actual_estimated_time}}hr 
                                        @else
                                            {{$task->estimated_time}}hr 
                                        @endif
                                    </td>
                                    <td>
                                        <span @if($task->priority == 'Critical')class="text-danger"@endif>{{$task->priority}}</span>
                                    </td>
                                @endunlessrole
                                <td class="project-completion text-center">
                                    <span @if($task->status == 'In Progress')class="label label-danger"
                                        @elseif($task->status == 'Development Completed')class="label label-warning"
                                        @elseif($task->status == 'Under QA')class="label label-info"
                                        @elseif($task->status == 'On Hold')class="label label-success"
                                        @elseif($task->status == 'Awaiting Client')class="label label-plain"
                                        @elseif($task->status == 'Client Review')class="label label-warning"
                                        @elseif($task->status == 'Backlog')class="label label-info"
                                        @elseif($task->status == 'Done')class="label label-success"
                                        @endif>{{$task->status}}</span>
                                </td>
                                <td>
                                    <span class="view-i">
                                        <a href="/tasks/{{ $task->id }}" data-tooltip="tooltip" data-placement="top"
                                            title="View">
                                            <span class="edit-i">
                                                <i data-toggle="modal" data-target="#add_task_time" class="ri-eye-line"
                                                    aria-hidden="true"></i></a></span>
                                    @can('manage-tasks')                                                      
                                    <span class="edit-i">
                                        <a data-tooltip="tooltip" data-placement="top" title="Edit"><i
                                                data-id="{{$task->id}}" class="ri-pencil-line edit-task"
                                                aria-hidden="true"></i></a></span>

                                    <span class="dlt-i"><a href="#" class="delete_task_onclick"
                                            data-id="{{ $task->id }}" data-tooltip="tooltip" data-placement="top"
                                            title="Archive">
                                            <i data-toggle="modal" data-target="#delete_task" class="ri-archive-line"
                                                aria-hidden="true"></i></a></span>
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                        @else
                        <tr>
						<td colspan="8" align="center">No Data Found</td>
					</tr>
                    @endif
                </tbody>
            </table>
            {{$tasks->links()}}
        </div>

</div>