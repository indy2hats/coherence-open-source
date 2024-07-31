<div id="edit_ticket_status_modal" class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title text-center">Edit Status</h4>
        </div>
        <div class="modal-body">
            <form action="{{route('ticket-status.update', $assetTicketStatus->id)}}" id="edit_ticket_status_form" method="post" autocomplete="off">
                @csrf
                @method('PATCH')
                        <div class="form-group">
                            <label>Name <span class="required-label">*</span></label>
                            <input class="form-control" value="{{$assetTicketStatus->title}}" type="text" name="name" id="name">
                            <div class="text-danger text-left field-error" id="label_name"></div>
                        </div>
                    
                <div class="form-group">
                    <label>Description <span class="required-label"></span></label>
                    <textarea class="form-control" rows="4" name="description">{{ $assetTicketStatus->description }}</textarea>
                    <div class="text-danger text-left field-error" id="label_description"></div>
            </div>
            <div class="row">                        
                <div class="col-sm-4"> 
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" name="is_inactive_asset"  id="is_inactive_edit_asset" {{ $assetTicketStatus->is_inactive_asset == 'yes' ? 'checked' : '' }}>
                        <label class="col-form-labelform-check-label" for="is_inactive_edit_asset">Move the asset to the inactive list</label>
    
                    </div>
                </div>
                <div class="col-sm-4"> 
                    <div class="form-group form-check non-client-field">
                        <input type="checkbox" class="form-check-input" name="is_close_issue"  id="is_close_edit_issue" {{ $assetTicketStatus->is_close_issue != 'no' ? 'checked' : '' }}>
                        <label class="col-form-labelform-check-label" for="is_close_edit_issue">Mark the ticket as closed</label>
                    </div>
                </div>  
                
                <div class="col-sm-4"> 
                    <div class="form-group form-check non-client-field">
                        <input type="checkbox" class="form-check-input" name="is_allocate_asset"  id="is_allocate_edit_asset" {{ $assetTicketStatus->is_allocate_asset != 'no' ? 'checked' : '' }}>
                        <label class="col-form-labelform-check-label" for="is_allocate_edit_asset">Update the asset to the reallocated state</label>
                    </div>
                </div>  
            </div>
                <div class="submit-section">
                    <button class="btn btn-primary submit-btn update-ticket-status">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>