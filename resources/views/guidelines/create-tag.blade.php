<div id="create_tag" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Add New Tag</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('addTag')}}" id="add_tag_form" method="POST" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Category <span class="required-label">*</span></label>
                                <input class="form-control category" type="text" name="category" id="category">
                                <div class="text-danger text-left field-error" id="label_add_category"></div>
                            </div>
                        </div>
                    </div>

                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn create-tag">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>