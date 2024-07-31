
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Edit Easy Acess Items</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('editEasyAccess')}}" id="edit_form" method="POST" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Name <span class="required-label">*</span></label>
                                <input class="form-control" type="text" name="edit_name" id="edit_name" value="">
                                <div class="text-danger text-left field-error" id="label_edit_name"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Link <span class="required-label">*</span></label>
                                <input class="form-control" type="text" name="edit_link" id="edit_link" value="">
                                <div class="text-danger text-left field-error" id="label_edit_link"></div>
                            </div>
                        </div>
                    </div>
                        <input type="hidden" id="item_id" value="" name="item_id">        
                    <div class="submit-section mt20">
                        <button class="btn btn-primary update-item">Update</button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>