<div class="modal custom-modal fade" id="ticket_raise_asset" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Add The Asset Issue</h4>
            </div>
            <div class="modal-body">
                <form  action="{{ route('assets.ticketRaiseAsset') }}" id="ticket_raise_asset_form" method="POST" autocomplete="off">
                    @csrf
                    <input type="hidden" id="ticket_raise_asset_id" name="id" value="">
                    <div class="form-header">
                        <label>Type of Complaint<span class="required-label">*</span></label>
                         <input class="form-control" type="text" name="type">
                        <div class="text-danger text-left field-error" id="label_type"></div>
                    </div>
                <div class="form-header">
                    <label>Please provide the reason for raising the ticket<span class="required-label">*</span></label>
                     <textarea rows="4" class="form-control summernote ticket-raise-issue" placeholder="Enter your issue here" name="reason" id="reason"></textarea>
                    <div class="text-danger text-left field-error" id="label_reason"></div>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row" style="padding-top: 10px">
                        <div class="col-sm-12 text-right">
                            <button class="btn btn-primary submit-btn ticket_raise_asset">Submit</button>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>