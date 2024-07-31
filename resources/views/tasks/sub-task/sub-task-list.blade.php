<div class="list">
    <div>

        <ul class="nav nav-tabs">

            <li class="active"><a href="#all_tasks" data-toggle="tab">Active Tasks</a></li>
            <li class=""><a href="#upcoming_tasks" data-toggle="tab">Upcoming Tasks</a></li>
            <li class=""><a href="#ongoing_tasks" data-toggle="tab">Tasks in Progress</a></li>
            <li class=""><a href="#completed_tasks" data-toggle="tab">Completed Tasks</a></li>
        </ul>
        <div class="tab-content">

            <div id="all_tasks" class="tab-pane active">
                <div class="table-responsive m-t-lg">
                    <table class="table dataTable table-hover issue-tracker">
                        <thead>
                            <tr>
                                <th style="width:13%">Title</th>
                                <th>Status</th>
                                @unlessrole('client')
                                    <th>Estimated Time</th>
                                    <th>Deadline</th>
                                    <th style="width:13%">Time Spent</th>
                                @endunlessrole
                                @can('manage-tasks')
                                <th style="width:10%">Actions</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($allSubTasks as $child)
                            <tr>
                                <td class="issue-info">
                                    <a href="{{url('/tasks/'.$child->id)}}">
                                        {{$child->title}}
                                    </a>
                                </td>
                                <td>
                                    
                                    <strong>
                                        @if($child->status == 'Backlog')
                                        <label class="label label-info">Backlog</label>
                                        @endif
                                        @if($child->status == 'In Progress')
                                        <label class="label label-danger">In Progress</label>
                                        @endif
                                        @if($child->status == 'Development Completed')
                                        <label class="label label-warning">Dev Completed</label>
                                        @endif
                                        @if($child->status == 'Under QA')
                                        <label class="label label-info">Under QA</label>
                                        @endif
                                        @if($child->status == 'On Hold')
                                        <label class="label label-deafult">On Hold</label>
                                        @endif
                                        @if($child->status == 'Awaiting Client')
                                        <label class="label label-plain">Awaiting Client</label>
                                        @endif
                                        @if($child->status == 'Client Review')
                                        <label class="label label-warning">Client Review</label>
                                        @endif
                                        @if($child->status == 'Done')
                                        <label class="label label-success">Done</label>
                                        @endif
                                    </strong>
                                </td>
                                
                                @unlessrole('client')
                                    <td>
                                    @if(in_array(auth()->user()->role->name, config('general.task_actual_estimate.view_roles')))
                                        {{$child->actual_estimated_time}} Hrs
                                    @else
                                        {{$child->estimated_time}} Hrs
                                    @endif
                                    </td>
                                    <td>
                                        {{$child->end_date_sub_task_format}}
                                    </td>

                                    <td>
                                    {{number_format($child->time_spent,2)}} Hrs
                                    </td>
                                @endunlessrole
                                @can('manage-tasks')
                                <td>
                                    <span class="edit-i">
                                        <a data-tooltip="tooltip" data-placement="top" title="Edit"><i
                                                data-id="{{$child->id}}" class="ri-pencil-line edit-task"
                                                data-parent="false"
                                                aria-hidden="true"
                                                style="padding-right: 10px;padding-left:10px"></i></a></span>

                                    <span class="dlt-i"><a href="#" data-type="child" data-toggle="modal"
                                            data-target="#delete_task" class="delete-task" data-id="{{ $child->id }}"
                                            data-tooltip="tooltip" data-placement="top" title="Archive">
                                            <i class="ri-archive-line" aria-hidden="true"></i></a></span>
                                </td>
                                @endcan
                            </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div id="upcoming_tasks" class="tab-pane ">
                <div class="table-responsive  m-t-lg">
                    <table class="table dataTable table-hover issue-tracker">
                        <thead>
                            <tr>
                                <th style="width:13%">Title</th>
                                <th>Status</th>
                                @unlessrole('client')
                                    <th>Estimated Time</th>
                                    <th>Deadline</th>
                                    <th style="width:13%">Time Spent</th>
                                @endunlessrole
                                @can('manage-tasks')
                                <th style="width:10%">Actions</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($upcomingSubTasks as $child)
                            <tr>
                                <td class="issue-info">
                                    <a href="{{url('/tasks/'.$child->id)}}">
                                        {{$child->title}}
                                    </a>
                                </td>
                                <td>
                                    <strong>
                                        @if($child->status == 'Backlog')
                                        <label class="label label-info">Backlog</label>
                                        @endif
                                        @if($child->status == 'In Progress')
                                        <label class="label label-danger">In Progress</label>
                                        @endif
                                        @if($child->status == 'Development Completed')
                                        <label class="label label-warning">Dev Completed</label>
                                        @endif
                                        @if($child->status == 'Under QA')
                                        <label class="label label-info">Under QA</label>
                                        @endif
                                        @if($child->status == 'On Hold')
                                        <label class="label label-deafult">On Hold</label>
                                        @endif
                                        @if($child->status == 'Awaiting Client')
                                        <label class="label label-plain">Awaiting Client</label>
                                        @endif
                                        @if($child->status == 'Client Review')
                                        <label class="label label-warning">Client Review</label>
                                        @endif
                                        @if($child->status == 'Done')
                                        <label class="label label-success">Done</label>
                                        @endif
                                    </strong>
                                </td>

                                
                                @unlessrole('client')
                                    <td>
                                        @if(in_array(auth()->user()->role->name, config('general.task_actual_estimate.view_roles')))
                                            {{$child->actual_estimated_time}} Hrs
                                        @else
                                            {{$child->estimated_time}} Hrs
                                        @endif
                                    </td>
                                    <td>
                                        {{$child->end_date_sub_task_format}}
                                    </td>

                                    <td>
                                    {{number_format($child->time_spent,2)}} Hrs
                                    </td>
                                @endunlessrole
                                @can('manage-tasks')
                                <td>
                                    <span class="edit-i">
                                        <a><i data-id="{{$child->id}}" class="ri-pencil-line edit-task"  data-parent="false" aria-hidden="true"
                                                style="padding-right: 10px;padding-left:10px" data-tooltip="tooltip"
                                                data-placement="top" title="Edit"></i></a></span>

                                    <span class="dlt-i"><a href="#" data-type="child" data-toggle="modal"
                                            data-target="#delete_task" class="delete-task" data-id="{{ $child->id }}"
                                            data-tooltip="tooltip" data-placement="top" title="Archive">
                                            <i class="ri-archive-line" aria-hidden="true"></i></a></span>
                                </td>
                                @endcan
                            </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="ongoing_tasks" class="tab-pane ">
                <div class="table-responsive m-t-lg">
                    <table class="table dataTable table-hover issue-tracker">
                        <thead>
                            <tr>
                                <th style="width:13%">Title</th>
                                <th>Status</th>
                                @unlessrole('client')
                                    <th>Estimated Time</th>
                                    <th>Deadline</th>
                                    <th style="width:13%">Time Spent</th>
                                @endunlessrole
                                @can('manage-tasks')
                                <th style="width:10%">Actions</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ongoingSubTasks as $child)
                            <tr>
                                <td class="issue-info">
                                    <a href="{{url('/tasks/'.$child->id)}}">
                                        {{$child->title}}
                                    </a>
                                </td>
                                <td>
                                    <strong>
                                        @if($child->status == 'Backlog')
                                        <label class="label label-info">Backlog</label>
                                        @endif
                                        @if($child->status == 'In Progress')
                                        <label class="label label-danger">In Progress</label>
                                        @endif
                                        @if($child->status == 'Development Completed')
                                        <label class="label label-warning">Dev Completed</label>
                                        @endif
                                        @if($child->status == 'Under QA')
                                        <label class="label label-info">Under QA</label>
                                        @endif
                                        @if($child->status == 'On Hold')
                                        <label class="label label-deafult">On Hold</label>
                                        @endif
                                        @if($child->status == 'Awaiting Client')
                                        <label class="label label-plain">Awaiting Client</label>
                                        @endif
                                        @if($child->status == 'Client Review')
                                        <label class="label label-warning">Client Review</label>
                                        @endif
                                        @if($child->status == 'Done')
                                        <label class="label label-success">Done</label>
                                        @endif
                                    </strong>
                                </td>
                                
                                @unlessrole('client')
                                <td>
                                    @if(in_array(auth()->user()->role->name, config('general.task_actual_estimate.view_roles')))
                                        {{$child->actual_estimated_time}} Hrs
                                    @else
                                        {{$child->estimated_time}} Hrs
                                    @endif
                                </td>
                                <td>
                                    {{$child->end_date_sub_task_format}}
                                </td>

                                <td>
                                {{number_format($child->time_spent,2)}} Hrs
                                </td>
                                @endunlessrole
                                @can('manage-tasks')
                                <td>
                                    <span class="edit-i">
                                        <a><i data-id="{{$child->id}}" class="ri-pencil-line edit-task"  data-parent="false" aria-hidden="true"
                                                style="padding-right: 10px;padding-left:10px" data-tooltip="tooltip"
                                                data-placement="top" title="Edit"></i></a></span>

                                    <span class="dlt-i"><a href="#" data-type="child" data-toggle="modal"
                                            data-target="#delete_task" class="delete-task" data-id="{{ $child->id }}"
                                            data-tooltip="tooltip" data-placement="top" title="Archive">
                                            <i class="ri-archive-line" aria-hidden="true"></i></a></span>
                                </td>
                                @endcan
                            </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="completed_tasks" class="tab-pane ">
                <div class="table-responsive m-t-lg">
                    <table class="table dataTable table-hover issue-tracker">
                        <thead>
                            <tr>
                                <th style="width:13%">Title</th>
                                <th>Status</th>
                                @unlessrole('client')
                                    <th>Estimated Time</th>
                                    <th>Deadline</th>
                                    <th style="width:13%">Time Spent</th>
                                @endunlessrole
                                @can('manage-tasks')
                                <th style="width:10%">Actions</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($completedSubTasks as $child)
                            <tr>
                                <td class="issue-info">
                                    <a href="{{url('/tasks/'.$child->id)}}">
                                        {{$child->title}}
                                    </a>
                                </td>
                                <td>
                                    <strong>
                                        @if($child->status == 'Backlog')
                                        <label class="label label-info">Backlog</label>
                                        @endif
                                        @if($child->status == 'In Progress')
                                        <label class="label label-danger">In Progress</label>
                                        @endif
                                        @if($child->status == 'Development Completed')
                                        <label class="label label-warning">Dev Completed</label>
                                        @endif
                                        @if($child->status == 'Under QA')
                                        <label class="label label-info">Under QA</label>
                                        @endif
                                        @if($child->status == 'On Hold')
                                        <label class="label label-deafult">On Hold</label>
                                        @endif
                                        @if($child->status == 'Awaiting Client')
                                        <label class="label label-plain">Awaiting Client</label>
                                        @endif
                                        @if($child->status == 'Client Review')
                                        <label class="label label-warning">Client Review</label>
                                        @endif
                                        @if($child->status == 'Done')
                                        <label class="label label-success">Done</label>
                                        @endif
                                    </strong>
                                </td>
                               
                                @unlessrole('client')
                                <td>
                                    @if(in_array(auth()->user()->role->name, config('general.task_actual_estimate.view_roles')))
                                        {{$child->actual_estimated_time}} Hrs
                                    @else
                                        {{$child->estimated_time}} Hrs
                                    @endif
                                </td>
                                <td>
                                    {{$child->end_date_sub_task_format}}
                                </td>

                                <td>
                                {{number_format($child->time_spent,2)}} Hrs
                                </td>
                                @endunlessrole
                                @can('manage-tasks')
                                <td>
                                    <span class="edit-i">
                                        <a><i data-id="{{$child->id}}" class="ri-pencil-line edit-task" data-parent="false" aria-hidden="true"
                                                style="padding-right: 10px;padding-left:10px" data-tooltip="tooltip"
                                                data-placement="top" title="Edit"></i></a></span>

                                    <span class="dlt-i"><a href="#" data-type="child" data-toggle="modal"
                                            data-target="#delete_task" class="delete-task" data-id="{{ $child->id }}"
                                            data-tooltip="tooltip" data-placement="top" title="Archive">
                                            <i class="ri-archive-line" aria-hidden="true"></i></a></span>
                                </td>
                                @endcan
                            </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>