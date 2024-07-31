<!-- Delete Project Modal -->
<div class="modal custom-modal fade" id="change_status" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Payroll Status Update</h4>
            </div>
            <div class="modal-body">
                <div class="form-header">
                    <p>Are you sure want to change the payroll status?</p>
                </div>
                <div class="modal-btn">
                    <div class="row" style="padding-top: 10px">
                        <input type="hidden" id="payroll-status-id" value="">
                        <input type="hidden" id="payroll-status-label" value="">
                        <div class="col-sm-6">
                            <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-default cancel-btn">Cancel</a>
                        </div>
                        <div class="col-sm-6 text-right">
                            <a href="javascript:void(0);" class="btn btn-primary continue-btn">Continue</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>