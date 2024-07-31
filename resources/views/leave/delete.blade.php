<!-- Delete Leave Modal -->
<div class="modal custom-modal fade" id="delete_leave" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Delete Leave</h4>
            </div>
            <div class="modal-body">
                <div class="form-header">
                    <p>Are you sure want to cancel the leave?</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row" style="padding-top: 10px">
                        <input type="hidden" id="delete_leave_id" value="">
                        <div class="col-sm-6">
                        <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-default cancel-btn">Cancel</a>
                        </div>
                        <div class="col-sm-6 text-right">
                        <a href="javascript:void(0);" class="btn btn-primary continue-btn">Delete Leave</a>   
                       
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Delete Leave Modal -->