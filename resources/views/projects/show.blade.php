<div id="main">
    <div id="edit_project" class="modal custom-modal fade" role="dialog"></div>
    <!-- Page Title -->
    <div class="row">
        <div class="col-md-7 pull-left">
                <h2 class="page-title">Project Details</h3>
        </div>
            <div class="col-md-5 text-right ml-auto m-b-30">
                
        @can('manage-tasks')
                <a href="#" class="btn btn-w-m btn-success create-modal" data-toggle="modal" data-target="#create_task"><i class="ri-add-line"></i> Add Task</a>
        @endcan

            </div>
    </div>
    <!-- /Page Title -->
    <!-- Row end -->
    <div class="main animated fadeInUp">
        <div class="row">
            <div class="col-lg-8 col-xl-9">
                <div class="ibox-content">
                    <div class="row m-b-lg m-t-lg">
                        <div class="col-md-6">
                            <div class="profile-image">
                                <img src="@if($project->image){{ asset('storage/'.$project->image) }}@else{{ asset('img/company.png') }}@endif" class="img-circle circle-border m-b-md" alt="profile">
                            </div>
                            <div class="profile-info">
                                <h2 data-id="{{$project->id}}" id="project_id"><strong> {{$project->project_name}}</strong>
                                    @if($project->priority == 'Low')
                                    <span class="badge badge-success">High</span>
                                    @endif
                                    @if($project->priority == 'Medium')
                                    <span class="badge badge-primary">High</span>
                                    @endif
                                    @if($project->priority == 'High')
                                    <span class="badge badge-danger">High</span>
                                    @endif
                                    @can('manage-projects')
                                        <span class="edit-i">
                                            <a><i data-id="{{$project->id}}" class="ri-pencil-line edit-project" aria-hidden="true"></i></a>
                                        </span>
                                    @endcan
                                    @if($project->is_archived == 1)      <i class="ri-delete-bin-line"></i>@endif
                                </h2>
                                <table>
                                    <tr>
                                        <td>
                                            <h3>Company : <strong>{{ $project->client->company_name }}</strong></h3>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Status:
                                            @if($project->status == 'Active')
                                            <span class="badge badge-success">Active</span>
                                            @endif
                                            @if($project->status == 'Cancelled')
                                            <span class="badge badge-primary">Cancelled</span>
                                            @endif
                                            @if($project->status == 'Closed')
                                            <span class="badge badge-danger">Closed</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>{{count($allTasks)}}</strong> Tasks
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="col-lg-6">
                                <div class="widget mandy-bg p-sm text-center">
                                    <div class="widget-inner">
                                        <i class="fa fa-clock-o fa-4x"></i>
                                        <h1 class="m-xs" id="overdue"></h1>
                                        <h3 class="font-bold no-margins">
                                            Overdue
                                        </h3>
                                        <small>Tasks</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="widget beer-bg p-sm text-center">
                                    <div class="widget-inner">
                                        <i class="fa fa-flag-o fa-4x"></i>
                                        <h1 class="m-xs" id="pending"></h1>
                                        <h3 class="font-bold no-margins">
                                            Pending
                                        </h3>
                                        <small>Tasks</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($project->site_url != null)
                    <div style="margin-top: 5px">
                        Project URL : {{$project->site_url}}<a href="{{$project->site_url}}" target="blank"> <i class="fa fa-external-link"></i></a>
                    </div>
                    @endif
                    @if($project->description != null)
                    <div class="ibox-footer">
                        <div class="row">
                            <div  style="padding: 5px">
                                Description :
                            </div>
                            <div style="border: 1px solid #ccc;border-radius: 5px;padding: 5px">{!!$project->description!!}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="task">
                    <!-- Task list blade -->
                    <h2 class="page-title" style="font-size: 25px;">Project Tasks 
                        @if($isKanbanView)
                            <a href="/agile-board/{{$project->id}}" class="btn btn-w-m btn-info">Kanban View</a>
                        @endif
                    </h2>
                    @include('projects.task_list')
                </div>
            </div>
            <div class="col-lg-4 col-xl-3">
                <!-- Overall Status -->
                <div class="panel panel-info">

                    <div class="panel-heading">

                        <i class="fa fa-percent"></i> Overall Status

                    </div>

                    <div class="panel-body">
                        <div class="ibox float-e-margins">

                            <div>

                                <div id="gauge"></div>

                            </div>

                        </div>
                    </div>
                </div>
                <!-- Project managers -->
                <div class="panel panel-info">

                    <div class="panel-heading">

                        <i class="fa fa-briefcase"></i> Project Users

                    </div>
                    <div class="panel-body">
                        <div class="managers">
                            @include('projects.project_managers')
                        </div>
                    </div>
                </div>

                <div class="panel panel-info">

                    <div class="panel-heading">

                        <i class="fa fa-info-circle"></i> Project Details

                    </div>

                    <div class="panel-body">
                        <table class="table table-striped table-border">
                            <tbody>
                                <tr>
                                    <td><strong>Category :</strong></td>
                                    <td class="text-right"><strong>{{$project->category}}</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Type :</strong></td>
                                    <td class="text-right"><strong>{{$project->project_type}}</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Cost :</strong></td>
                                    <td class="text-right"><strong>{{$project->rate}}</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Estimated Hours :</strong></td>
                                    <td class="text-right"><strong>{{$estimatedTime}} Hours</strong></td>
                                </tr>
                                @if($showActualEstimateToUser) 
                                <tr>
                                    <td><strong>Actual Estimated Hours :</strong></td>
                                    <td class="text-right"><strong>{{$actualEstimatedTime}} Hours</strong></td>
                                </tr>
                                @endif
                                <tr>
                                    <td><strong>Time Spent :</strong></td>
                                    <td class="text-right"><strong>{{$timeSpent}} Hours</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Created :</strong></td>
                                    <td class="text-right"><strong>{{$project->created_at_format}}</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Start Date :</strong></td>
                                    <td class="text-right"><strong>{{$project->start_date_format}}</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Deadline :</strong></td>
                                    <td class="text-right"><strong>{{$project->end_date_format}}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                        @can('view-project-credentials')
                            <a href="/project-credentials/{{ $project->id }}"><button class="btn btn-info btn-sm"><i class="ri-eye-off-line"></i> Manage Credentials</button></a>
                        @endcan
                        @can('view-project-documents')
                            <a href="/project-documents/{{ $project->id }}"><button class="btn btn-primary btn-sm"><i class="ri-attachment-2"></i> Manage Documents</button></a>
                        @endcan

                    </div>



                </div>

            </div>
        </div>
        @include('projects.add_project_manager')
        @include('tasks.create')

        <div id="edit_task" class="modal custom-modal fade" role="dialog">
            {{-- @include('tasks.edit') --}}
        </div>

        @include('tasks.create-tag')

        <div class="modal custom-modal fade" id="delete_tasks" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title text-center">Archive Task</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-header">
                            <p>Are you sure you want to archive?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <div class="row">
                                <input type="hidden" id="delete_task_id" value="">
                                <div class="col-sm-6 ">
                                    <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-default float-right cancel-btn">Cancel</a>
                                </div>
                                <div class="col-sm-6 text-right">
                                    <a href="javascript:void(0);" class="btn btn-primary continue-btn">Archive</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal custom-modal fade" id="destroy_tasks" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title text-center">Delete Task</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-header">
                            <p>Are you sure want to delete?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <div class="row">
                                <input type="hidden" id="destroy_task_id" value="">
                                <div class="col-sm-6 ">
                                    <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-default float-right cancel-btn">Cancel</a>
                                </div>
                                <div class="col-sm-6 text-right">
                                    <a href="javascript:void(0);" class="btn btn-primary continue-btn">Delete</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>