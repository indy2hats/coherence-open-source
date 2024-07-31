<div class="modal custom-modal fade" id="exceed_time" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-center">Add a Reason</h4>
            </div>
            <div class="modal-body">
                <div class="form-header">
                    <label>Please enter the reason why the task went out of estimate <span class="required-label">*</span></label>
                     <textarea rows="4" class="form-control summernote exceed-reason" placeholder="Enter your reason here" name="reason" id="reason"></textarea>
                    <div class="text-danger text-left field-error" id="label_exceed_reason"></div>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row" style="padding-top: 10px">
                        <input type="hidden" id="task_id" value="">
                        <div class="col-sm-12 text-right">
                            <a class="btn btn-primary continue-btn">Add</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>