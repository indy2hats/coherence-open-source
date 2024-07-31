<!-- Create Task Modal -->
<div id="create_feedback" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Add Feedback</h4>
            </div>
            <div class="modal-body">
                <form action=""  id="add_feedback_form" method="POST">
                    @csrf
                    <div class="tabs-container">
                            <div class="tab-content">
                                <div role="tabpanel" id="tab-1" class="tab-pane active">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group form-focus select-focus focused">
                                                    <label>Task <span class="required-label">*</span></label>
                                                    <select class="chosen-select" id="search_task_name" name="search_task_name">
                                                        @if (!empty($searchedTask))
                                                        <option value="{{$searchedTask->id}}" selected>{{$searchedTask->title}}</option>
                                                        @else
                                                        <option value="">Select Task</option
                                    >                    @endif
                                                    </select>
                                                    <div class="text-danger text-left field-error" id="label_search_task_name"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6 ">
                                                <div class="form-group">
                                                    <label>Employee <span class="required-label">*</span></label>
                                                    <select class="chosen-select" id="user" name="user">
                                                        <option value="">Select</option>
                                                        @foreach($users as $user)
                                                        <option value="{{$user->id}}">
                                                            {{$user->full_name}}</option>
                                                        @endforeach
                                                    </select>
                                                     <div class="text-danger text-left field-error" id="label_user"></div>
                                                 </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Level of Severity <span class="required-label">*</span></label>
                                                    <select class="chosen-select" name="severity" id="severity">
                                                        <option value="" selected>Select Severity</option>
                                                        <option value="Low">Low</option>
                                                        <option value="Medium">Medium</option>
                                                        <option value="High">High</option>
                                                        <option value="Critical">Critical</option>
                                                    </select>
                                                    <div class="text-danger text-left field-error" id="label_severity"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group form-focus select-focus focused">
                                                    <label>Reason <span class="required-label">*</span></label>
                                                    <select class="chosen-select" id="reason" name="reason[]" multiple>
                                                        @foreach($qaIssues as $qaIssue)
                                                        <option value="{{$qaIssue->id}}">{{$qaIssue->title}}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="text-danger text-left field-error" id="label_reason"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Comments <span class="required-label">*</span></label>
                                                    <textarea rows="4" class="form-control summernote" placeholder="Enter your reason here" name="comments" id="comments"></textarea>
                                                    <div class="text-danger text-left field-error" id="label_comments"></div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    <div class="submit-section mt20">
                        <button class="btn btn-primary create-feedback">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /Create task Modal -->
