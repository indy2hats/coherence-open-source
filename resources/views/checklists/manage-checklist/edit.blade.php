<div id="edit_item" class="modal custom-modal fade" role="dialog" >
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Edit Item</h4>
            </div>
            <div class="modal-body">
                <form action="" id="edit_form" method="POST" autocomplete="off">
                    @csrf
                    @method('PATCH')
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Title <span class="required-label">*</span></label>
                                <input class="form-control" type="text" name="edit_title" id="edit_title">
                                <div class="text-danger text-left field-error" id="label_edit_title"></div>
                            </div>
                        </div>
                    <input type="hidden" id="edit_item_id" value="">      
                    <div class="col-sm-12  submit-section ">
                        <button class="btn btn-primary update-item" type="submit">Update</button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div>
