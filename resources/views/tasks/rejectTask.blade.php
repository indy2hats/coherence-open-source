<div class="modal custom-modal fade" id="reject_task_modal" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Reject Task</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('rejectionUpdate')}}" id="task_rejection_form" method="POST" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
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
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Reason <span class="required-label">*</span></label>
                                 <select class="chosen-select" id="reason" name="reason[]" multiple>
                                   @foreach($qaIssues as $issue)
                                   <option value="{{$issue->id}}">{{$issue->title}}</option>
                                   @endforeach
                                </select>
                                <div class="text-danger text-left field-error" id="label_reason"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Comments </label>
                                <textarea rows="4" class="form-control summernote" placeholder="Enter your reason here" name="comments" id="comments"></textarea>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="reject_id" value="" name="id">
                </form>
                <div class="modal-btn">
                    <div class="row" style="padding-top: 10px">
                        <div class="col-sm-6">
                            <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary float-right cancel-btn">Cancel</a>
                        </div>
                        <div class="col-sm-6 text-right">
                            <a class="btn btn-primary continue-btn">Reject</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>