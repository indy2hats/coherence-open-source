
<div id="disable2FA" class="modal custom-modal animated fadeInUp" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Disable Two Factor Authentication</h4>
            </div>
            <div class="modal-body">
                <div class="form-header">
                    <p>Are you sure want to disable this feature?</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row" style="padding-top: 10px">
                        <input type="hidden" id="delete_alert_id" value="">
                        <div class="col-sm-6">
                            <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-default cancel-btn">Cancel</a>
                        </div>
                        <div class="col-sm-6 text-right">
                            <a href="javascript:void(0);" class="btn btn-primary {{ empty($google2faSecret) ? "disable2FABtn" :""  }}" id="disable-btn">Disable</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
