 <!-- Create Task Modal -->
 <div id="add_task" class="modal custom-modal" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span
                            aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title text-center">Add New Task</h4>
                </div>
                <div class="modal-body">
                    <form action="{{route('tasks.store')}}" enctype="multipart/form-data" id="add_task_form"
                        method="POST" autocomplete="off">
                        @csrf
                        <div class="tabs-container">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="active"><a class="nav-link" data-toggle="tab" href="#tab-1"> Task Details</a>
                                </li>
                                @unlessrole('client')
                                <li><a class="nav-link" data-toggle="tab" href="#tab-2">Task Checklists</a></li>
                                @endunlessrole
                            </ul>
                            <div class="tab-content">
                                <div role="tabpanel" id="tab-1" class="tab-pane active">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-sm-6 col-md-6">
                                                <div class="form-group form-focus select-focus focused">
                                                    <label>Project Name<span class="required-label">*</span></label>
                                                    <select class="chosen-select form-control" id="select_project_name"
                                                        name="project_id">
                                                        <option value="">Select Project</option>
                                                        @foreach ($projects as $project)
                                                        <option value="{{$project->id}}">{{$project->project_name}}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                    <div class="text-danger text-left field-error"
                                                        id="label_project_id"></div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-6">
                                                <div class="form-group">
                                                    <label>Task Title <span class="required-label">*</span></label>
                                                    <input class="form-control" type="text" name="task_title">
                                                    <div class="text-danger text-left field-error"
                                                        id="label_task_title"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group form-focus select-focus focused">
                                                    <label for="">Parent Task</label>                                            
                                                    <select class="chosen-select form-control" id="task_parent" name="task_parent">
                                                        <option value="">Select</option>                                                              
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-6">
                                                <div class="form-group form-focus select-focus focused">
                                                    <label for="">Priority </label>
                                                    <select class="chosen-select" id="task_priority"
                                                        name="task_priority">
                                                        <option>Critical</option>
                                                        <option>High</option>
                                                        <option selected>Medium</option>
                                                        <option> Low</option>
                                                    </select>
                                                </div>
                                            </div>                                        
                                        </div> 
                                        @unlessrole('client') 
                                        <div class="row">  
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Estimated Time (Eg- 1.5=1h 30m)<span
                                                            class="required-label">*</span></label>
                                                    <div class="cal-icon">
                                                        <input class="form-control" type="number" step=".1"
                                                            name="estimated_time">
                                                        <div class="text-danger text-left field-error"
                                                            id="label_estimated_time"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Actual Estimated Time (Eg- 1.5=1h 30m)<span
                                                            class="required-label">*</span></label>
                                                    <div class="cal-icon">
                                                        <input class="form-control" type="number" step=".1"
                                                            name="actual_estimated_time">
                                                        <div class="text-danger text-left field-error"
                                                            id="label_actual_estimated_time"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">  
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Start Date <span class="required-label">*</span></label>
                                                    <input class="form-control datetimepicker" type="text"
                                                        name="start_date" data-inputmask-inputformat="dd/mm/yyyy">
                                                    <div class="text-danger text-left field-error"
                                                        id="label_start_date"></div>

                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>End Date <span class="required-label">*</span></label>
                                                    <input class="form-control datetimepicker" type="text"
                                                        name="end_date" data-inputmask-inputformat="dd/mm/yyyy">
                                                    <div class="text-danger text-left field-error" id="label_end_date">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">  
                                                                         
                                            <div class="col-sm-12">
                                                <div class="form-group form-focus select-focus focused">
                                                    <label>Assign To</label>
                                                    <select class="chosen-select form-control" id="task_assigned_users"
                                                        name="task_assigned_users[]" multiple>
                                                        @foreach ($users as $user)
                                                        <option value="{{$user->id}}">{{$user->full_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group form-focus select-focus focused">
                                                    <label>Technical Reviewer</label>
                                                    <select class="chosen-select" id="task_assigned_users"
                                                        name="reviewer_id">
                                                        <option value="">Select</option>
                                                        @foreach ($users as $user)
                                                        <option value="{{$user->id}}">{{$user->full_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group form-focus select-focus focused">
                                                    <label>Need to be approved by: </label>
                                                    <select class="chosen-select form-control m-b" id="approval_users"
                                                        name="approval_users[]" multiple>
                                                        @foreach ($admins as $user)
                                                        <option value="{{$user->id}}">{{$user->full_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                           
                                        </div>
                                        @endunlessrole
                                        <div class="row">
                                            <div class="col-sm-6 col-md-6">
                                                <div class="form-group">
                                                    <label>URL</label>
                                                    <input class="form-control" type="text" name="task_url">
                                                    <div class="text-danger text-left field-error" id="label_task_url">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-6">
                                                <div class="form-group">
                                                    <label>Task Id <small>(Jira)</small></label>
                                                    <input class="form-control" type="text" name="task_id">
                                                    <div class="text-danger text-left field-error" id="label_task_id">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <div class="row">
                                            
                                            <div class="col-sm-12 col-md-12">
                                                <div class="form-group">
                                                    <label>Description</label>
                                                    <textarea rows="4" class="form-control summernote"
                                                        placeholder="Enter your message here"
                                                        name="task_description"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6 col-md-6">
                                                <div class="form-group form-focus select-focus focused">
                                                    <label for="">Tag </label>
                                                    <select class="chosen-select form-control" id="tag" name="tag">
                                                        <option value="">Select Tag</option>
                                                        @foreach($tags as $tag)
                                                        <option value="{{$tag->slug}}">{{$tag->title}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <a href="#" class="btn btn-primary btn-xs pull-right"
                                                    data-toggle="modal" data-target="#add_tag"><i
                                                        class="ri-add-line"></i> Add Tag</a>
                                            </div>
                                            <div class="col-sm-6 col-md-6">
                                                <div class="form-group">
                                                    <label>Attach Files</label>
                                                    <input class="form-control" type="file" name="files[]" multiple />
                                                    <div class="text-danger text-left field-error" id="label_files">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-6 text-right">
                                               <span>Add to Agile Board</span> <input checked="" type="checkbox" class="i-checks" name="add_to_board"> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @unlessrole('client')
                                <div role="tabpanel" id="tab-2" class="tab-pane">
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table task-checklist">

                                                <tbody>
                                                    @foreach(\App\Models\ChecklistCategory::with('checklists')->get() as $checklistCategory)
                                                    @if($checklistCategory->checklists_count > 0)
                                                    <tr>
                                                        <td>
                                                            <p class="parent-category"><input type="checkbox"
                                                                    class="check-all">
                                                                <span>{{$checklistCategory->title}}</span></p>
                                                            @foreach($checklistCategory->checklists as $checklist)
                                                            <p class="child-category">
                                                                <input value="{{$checklist->id}}" type="checkbox"
                                                                    class="i-checks" name="checklists[]">
                                                                <span>{{$checklist->title}}</span>
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
                            <button class="btn btn-primary submit-btn add-task">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Create task Modal -->

    