<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                    class="sr-only">Close</span></button>
            <h4 class="modal-title text-center">Edit Task</h4>
        </div>
        <div class="modal-body">
            <form action="{{route('tasks.update', $task->id)}}" enctype="multipart/form-data" id="edit_task_form"
                method="POST" autocomplete="off">
                @csrf
                @method('PATCH')
                <div class="tabs-container">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="active"><a class="nav-link" data-toggle="tab" href="#edit-tab-1"> Task Details</a>
                        </li>
                        @unlessrole('client')
                        <li><a class="nav-link" data-toggle="tab" href="#edit-tab-2">Task Checklists</a></li>
                        @endunlessrole
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" id="edit-tab-1" class="tab-pane active">
                            <div class="panel-body">
                                <input type="hidden" name="project_id" value="{{$task->project_id}}">
                                <input type="hidden" name="task_id" value="{{$task->id}}">
                                <div class="row">
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group form-focus select-focus focused">
                                            <label for="">Project</label>
                                            <select class="chosen-select" id="edit_project_id" name="edit_project_id">
                                                @foreach ($projects as $project)
                                                <option value="{{$project->id}}"
                                                    {{$task->project->id == $project->id ? 'selected' : ''}}>
                                                    {{$project->project_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> 
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>Task title <span class="required-label">*</span></label>
                                            <input class="form-control" type="text" name="edit_task_title"
                                                id="edit_task_title" value="{{$task->title}}">
                                            <div class="text-danger text-left field-error" id="label_edit_task_title">
                                            </div>
                                        </div>
                                    </div>                              
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group form-focus select-focus focused">
                                            <label for="">Parent Task</label>                                            
                                            <select class="chosen-select" id="task_parent" name="task_parent"
                                            {{ $task->children->isEmpty() ? '':'disabled'
                                             }}>
                                                <option value="">Select</option>
                                                @foreach ($parentTasks as $taskItem )
                                                <option value="{{ $taskItem->id }}" {{ $taskItem->id==$task->parent_id ? 'Selected':'' }}>{{ $taskItem->title }}</option>
                                                @endforeach                                              
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>Task code</label>
                                            <input class="form-control" type="text" name="edit_task_code"
                                                value="{{$task->code}}" readonly>
                                        </div>
                                    </div>                                    
                                </div>
                                @unlessrole('client')
                                <div class="row">
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group form-focus select-focus focused">
                                            <label for="">Priority</label>
                                            <select class="chosen-select form-control m-b" id="edit_task_priority"
                                                name="edit_task_priority">
                                                <option {{$task->priority == 'Critical' ? 'selected' : ''}}>Critical</option>
                                                <option {{$task->priority == 'High' ? 'selected' : ''}}>High</option>
                                                <option {{$task->priority == 'Medium' ? 'selected' : ''}}>Medium
                                                </option>
                                                <option {{$task->priority == 'Low' ? 'selected' : ''}}> Low</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group form-focus select-focus focused">
                                            <label>Assign to </label>
                                            <select class="chosen-select form-control m-b" id="edit_task_assigned_users"
                                                name="edit_task_assigned_users[]" multiple>
                                                @foreach ($users as $user)
                                                <option value="{{$user->id}}"
                                                    {{ (in_array($user->id, $taskUsers)) ? 'selected': '' }}>
                                                    {{$user->full_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>                                    
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Estimated Time (Eg- 1.5=1h 30m)<span
                                                    class="required-label">*</span></label>
                                            <div class="cal-icon">
                                                <input class="form-control" type="number" step=".1"
                                                    name="edit_estimated_time" id="edit_estimated_time"
                                                    value="{{$task->estimated_time}}">
                                            </div>
                                            <div class="text-danger text-left field-error"
                                                id="label_edit_estimated_time"></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Actual Estimated Time (Eg- 1.5=1h 30m)<span
                                                    class="required-label">*</span></label>
                                            <div class="cal-icon">
                                                <input class="form-control" type="number" step=".1"
                                                    name="edit_actual_estimated_time"  id="edit_actual_estimated_time"
                                                    value="{{$task->actual_estimated_time}}">
                                                <div class="text-danger text-left field-error"
                                                    id="label_edit_actual_estimated_time"></div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                
                                <div class="row">                                    
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Start Date <span class="required-label">*</span></label>
                                            <input class="form-control datetimepicker" type="text"
                                                name="edit_task_start_date" value="{{$task->start_date_show}}">
                                            <div class="text-danger text-left field-error"
                                                id="label_edit_task_start_date"></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Deadline <span class="required-label">*</span></label>
                                            <input class="form-control datetimepicker" type="text"
                                                name="edit_task_end_date" value="{{$task->end_date_show}}">
                                            <div class="text-danger text-left field-error"
                                                id="label_edit_task_end_date"></div>
                                        </div>
                                    </div>                                    
                                </div>
                                @endunlessrole
                                <div class="row">                                   
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Percentage Completed</label>
                                            <div class="cal-icon">
                                                <input class="form-control" type="text" name="edit_percent_complete"
                                                    id="percent_complete" value="{{$task->percent_complete}}" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group form-focus select-focus focused">
                                            <label>Technical Reviewer</label>
                                            <select class="chosen-select" id="task_assigned_users" name="reviewer_id">
                                                <option value="">Select</option>
                                                @foreach ($users as $user)
                                                <option value="{{$user->id}}" @if($user->id == $task->reviewer_id)
                                                    selected @endif>{{$user->full_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                   
                                   

                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>URL</label>
                                            <input class="form-control" type="text" name="edit_task_url" id="edit_url"
                                                value="{{$task->task_url}}">
                                            <div class="text-danger text-left field-error" id="label_edit_task_url">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>Task Id <small>(Jira)</small></label>
                                            <input class="form-control" type="text" name="edit_task_id"
                                                id="edit_task_id" value="{{$task->task_id}}">
                                            <div class="text-danger text-left field-error" id="label_edit_task_id">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group form-focus select-focus focused">
                                            <label>Status</label>
                                            <input class="form-control" type="text" id="status" name="status"
                                                value="{{$task->status}}" readonly>
                                            <!-- <select class="chosen-select" id="status" name="status">
                                                @include('partials.module-partials.task-status-edit')
                                            </select> -->
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group form-focus select-focus focused">
                                            <label for="">Tag </label>
                                            <select class="chosen-select" id="tag" name="tag">
                                                <option value="">Select Tag</option>
                                                @foreach($tags as $tag)
                                                <option {{$task->tag == $tag->slug ? 'selected' : ''}}
                                                    value="{{$tag->slug}}">{{$tag->title}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <a href="#" class="btn btn-primary btn-xs pull-right mb-4" data-toggle="modal"
                                            data-target="#add_tag"><i class="ri-add-line"></i> Add Tag</a>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                @unlessrole('client')
                                    <div class="col-sm-6">
                                        <div class="form-group form-focus select-focus focused">
                                            <label>Need to be approved by: </label>
                                            <select class="chosen-select form-control m-b" id="approval_users"
                                                name="approval_users[]" multiple>
                                                @foreach ($admins as $user)
                                                <option value="{{$user->id}}"
                                                    {{ (in_array($user->id, $task->approvers->pluck('id')->toArray())) ? 'selected': '' }}>
                                                    {{$user->full_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endunlessrole
                                    
                                   
                                </div>
                                <div class="row ">
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea rows="2" class="form-control summernote" placeholder="Enter your message here" name="edit_task_description">{{$task->description}}</textarea>
                                    </div>
                                </div>
                                <div class="row ">
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>Attach Files</label>
                                            <input class="form-control" type="file" name="files[]" multiple />
                                            <div class="text-danger text-left field-error" id="label_files"></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6 mt-15">
                                    <div class="text-right">
                                               <span>Add to Archive List</span> <input @if($task->is_archived == 1) checked @endif type="checkbox" class="i-checks" name="is_archived"> 
                                            </div>
                                    <div class="text-right mt-15">
                                               <span>Add to Agile Board</span> <input @if($task->add_to_board == 1) checked @endif type="checkbox" class="i-checks" name="add_to_board"> 
                                            </div>
                                        </div>
                                </div>
                                @if($task->documents->count() > 0 )
                                <h4 style="margin-bottom:10px;">Task Documents</h4>
                                <div class="row">
                                    @foreach($task->documents as $document)
                                    <div class="col-sm-3 col-md-3 doc-repeat">
                                        <a target="_blank" href="{{ asset('storage/'.$document->path)}}">
                                            <p
                                                style="font-size:14px;text-align: center; border:1px solid #eee;padding:10px;    word-break: break-word;">
                                                <i class="fa fa-file clear" aria-hidden="true"
                                                    style="font-size:25px;"></i> {{explode('/', $document->path)[2]}}
                                            </p>
                                        </a>
                                        <a href="{{url('/tasks/delete-document/'.$document->id)}}"
                                            class="delete-doc text-danger"
                                            style="z-index:99999;position: absolute;top: 5px;right: 25px;"><i
                                                class="ri-delete-bin-line" aria-hidden="true"></i></a>
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                        @unlessrole('client')
                        <div role="tabpanel" id="edit-tab-2" class="tab-pane">
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table task-checklist">

                                        <tbody>
                                            @foreach(\App\Models\ChecklistCategory::with('checklists')->get() as $checklistCategory)
                                            @if($checklistCategory->checklists_count > 0)
                                            <tr>
                                                <td>
                                                    <p class="parent-category"><input type="checkbox" class="check-all">
                                                        <span>{{$checklistCategory->title}}</span></p>
                                                    @foreach($checklistCategory->checklists as $checklist)
                                                    <p class="child-category">
                                                        <input @if(in_array($checklist->id,
                                                        $task->checklists->pluck('id')->toArray())) checked @endif
                                                        value="{{$checklist->id}}" type="checkbox" class="i-checks"
                                                        name="checklists[]"> <span>{{$checklist->title}}</span>
                                                    </p>
                                                    @endforeach
                                                </td>
                                            </tr>
                                            @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endunlessrole
                    </div>
                </div>

                <div class="submit-section mt20">
                    <button class="btn btn-primary submit-btn update-task" data-reload="false">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>