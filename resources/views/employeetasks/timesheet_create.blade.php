<div class="modal custom-modal fade" id="add-session" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Add New Session</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('task-session.store')}}" method="post" autocomplete="off" id="create_session_form">
                    <input type="hidden" id="sheet_task_id" name="task_id" value="">
                    @csrf
                        <div class="row">
                            <div class='col-sm-12'>
                                <label class="col-form-label">Date <span class="required-label">*</span></label>
                                <div class="form-group" id="data_1">
                                    <div class="input-group date">
                                        <span class="input-group-addon"><i class="ri-calendar-2-line"></i></span><input type="text" class="form-control" id="sheet_date" value="" name="date">
                                    </div>
                                </div>
                                <div class="text-danger text-left field-error" id="label_date"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="col-form-label">Time<span class="required-label">*</span></label>
                                    <div>
                                        <input type="text" class="form-control" name="total" id="add-session-box-time" required>
                                        <div class="text-danger text-left field-error" id="label_total"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group form-focus select-focus focused">
                                <label for="">Session Type </label>
                                <select class="chosen-select" id="session_type" name="session_type">
                                    <!-- <option @if(auth()->user()->role->name=="employee") selected @endif value="development">Development</option>
                                    <option @if(in_array(auth()->user()->role->name, ["project-manager", "administrator"])) selected @endif value="project-management">Project Management</option>
                                    <option value="qa">QA</option>
                                    <option value="others">Others</option> -->
                                    @foreach(\App\Models\SessionType::all() as $sessionType)
                                        <option  @if(auth()->user()->role->name=="employee" && $sessionType == "employee") selected @endif @if(in_array(auth()->user()->role->name, ["project-manager", "administrator"]) && $sessionType->slug == "project-management") selected @endif value="{{$sessionType->slug}}">{{$sessionType->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="col-form-label">Comments <span class="required-label">*</span></label>
                                    <div>
                                        <textarea rows="4" class="form-control" value="" name="comments" id="add-session-box-comments"></textarea>
                                        <div class="text-danger text-left field-error" id="label_comments"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </form>
                <div class="row">
                    <div class="submit-section text-right col-sm-12">
                        <button class="btn btn-primary submit-btn create-session">Add</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>