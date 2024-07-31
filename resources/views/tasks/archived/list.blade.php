<div class="table-responsive">
        <div>
            <table class="table taskTable">
                <thead>
                    <tr>
                        <th>Archived</th>
                        <th>Task Title</th>
                        <th class="col-xs-2">Jira ID</th>
                        <th>Project Name</th>
                        <th class="hide-header">Deadline</th>
                        <th>Assigned To</th>
                        <th>Priority</th>
                        <th>Status</th>
                        @can('manage-tasks')<th>Action</th>@endcan
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tasks as $task)
                    @if(($task->estimated_time<$task->time_spent || $task->end_date < date('Y-m-d')) && in_array($task->
                            status,config('overdue-status')))
                            <tr>
                                @else
                            <tr>
                                @endif
                                <td><div class="form-group form-check">
                                <input type="checkbox" class="form-check-input change-archive" data-id="{{$task->id}}" {{$task->is_archived == 1?'checked':''}} id="{{$task->id}}" >
                                <label class="col-form-label form-check-label" for="{{$task->id}}"></label>
                    
                            </div></td>

                                <td>
                                    <a href="/tasks/{{ $task->id }}">
                                        {{$task->title}}
                                        @if ($task->tag)
                                        <span class="label label-primary">{{$task->taskTag->title}}</span>
                                        @endif
                                    </a>
                                </td>
                                <td class="col-xs-2">
                                    @if($task->task_url)
                                    <a href="{{ $task->task_url }}" target="_blank">
                                        {{$task->task_id}}
                                    </a>
                                    @else
                                    {{$task->task_id}}
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ Helper::getProjectView().$task->project->id }}">{{$task->project->project_name}}
                                    </a>
                                </td>
                                <td class="hide-cell">{{$task->end_date_format}}</td>
                                <td>@foreach($task->users as $user)
                                    {!! $user->full_name."</br>" !!}
                                    @endforeach</td>
                                <td>
                                    <span @if($task->priority == 'Critical')class="text-danger"@endif>{{$task->priority}}</span>
                                </td>
                                <td class="project-completion text-center">
                                    <span @if($task->status == 'In Progress')class="label label-danger"
                                        @elseif($task->status == 'Development Completed')class="label label-warning
                                        block" @elseif($task->status == 'Under QA')class="label label-info"
                                        @elseif($task->status == 'On Hold')class="label label-success"
                                        @elseif($task->status == 'Awaiting Client')class="label label-plain"
                                        @elseif($task->status == 'Client Review')class="label label-warning"
                                        @elseif($task->status == 'Backlog')class="label label-info"
                                        @elseif($task->status == 'Done')class="label label-success
                                        block"@endif>{{$task->status}}</span>
                                </td>
                                @can('manage-tasks')
                    <td>
                        <a class="dropdown-item destroy_task" href="#" data-toggle="modal"
                            data-target="#destroy_task" data-id="{{ $task->id }}" data-tooltip="tooltip"
                            data-placement="top" title="Delete"><i class="fa fa-trash-o m-r-5"></i></a>
                    </td>
                    @endcan
                            </tr>
                            @empty
                            <tr>
                            <td colspan="7" class="text-center">No Data</td>
                            </tr>
                            @endforelse

                </tbody>
            </table>
            {{$tasks->links()}}
        </div>

</div>