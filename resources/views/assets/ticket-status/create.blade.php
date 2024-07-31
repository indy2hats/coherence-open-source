<div id="create_ticket_status" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Add New Status</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('ticket-status.store')}}" id="add_ticket_status_form" method="POST" autocomplete="off">
                    @csrf
                    
                            <div class="form-group">
                                <label>Name <span class="required-label">*</span></label>
                                <input class="form-control" type="text" name="name" id="name">
                                <div class="text-danger text-left field-error" id="label_name"></div>
                            </div>
                            <div class="form-group">
                                <label>Description <span class="required-label">*</span></label>
                                <textarea class="form-control" rows="4" name="description" ></textarea>
                                <div class="text-danger text-left field-error" id="label_description"></div>
                            </div>
                            <div class="row">                        
                                <div class="col-sm-4"> 
                                    <div class="form-group form-check">
                                        <input type="checkbox" class="form-check-input" name="is_inactive_asset"  id="is_inactive_asset" >
                                        <label class="col-form-labelform-check-label" for="is_inactive_asset">Move the asset to the inactive list</label>
                    
                                    </div>
                                </div>
                                <div class="col-sm-4"> 
                                    <div class="form-group form-check non-client-field">
                                        <input type="checkbox" class="form-check-input" name="is_close_issue"  id="is_close_issue" >
                                        <label class="col-form-labelform-check-label" for="is_close_issue">Mark the ticket as closed</label>
                                    </div>
                                </div>
                                <div class="col-sm-4"> 
                                    <div class="form-group form-check non-client-field">
                                        <input type="checkbox" class="form-check-input" name="is_allocate_asset"  id="is_allocate_asset" >
                                        <label class="col-form-labelform-check-label" for="is_allocate_asset">Update the asset to the reallocated state</label>
                                    </div>
                                </div>          
                            </div>
                    
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn create-ticket-status">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>