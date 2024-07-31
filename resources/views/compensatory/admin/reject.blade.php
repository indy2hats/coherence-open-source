<div class="modal custom-modal fade" id="reject_reason" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Reject Application</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('rejectApplication')}}" id="application_rejection_form" method="POST" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Reason <span class="required-label">*</span></label>
                                <textarea rows="4" class="form-control" placeholder="Enter your reason here" name="reason"></textarea>
                                <div class="text-danger text-left field-error" id="label_reason"></div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="reject_application_id" value="" name="application_id">
                </form>
                <div class="modal-btn delete-action">
                    <div class="row" style="padding-top: 10px">
                        <div class="col-sm-6">
                            <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary float-right cancel-btn">Cancel</a>
                        </div>
                        <div class="col-sm-6 text-right">
                            <a href="javascript:void(0);" class="btn btn-primary continue-btn">Reject</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>