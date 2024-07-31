<div id="add_easy_access" class="modal custom-modal fade" role="dialog" >
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Add New Link</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('addEasyAccess')}}" id="add_form" method="POST" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>Name <span class="required-label">*</span></label>
                                <input class="form-control" type="text" name="name" id="name">
                                <div class="text-danger text-left field-error" id="label_name"></div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>Link <span class="required-label">*</span></label>
                                <input class="form-control" type="text" name="link" id="link">
                                <div class="text-danger text-left field-error" id="label_link"></div>
                            </div>
                        </div>
                    </div>
                </form>
                
                    <div class="submit-section mt20">
                        <button class="btn btn-primary create-easy-access">Add</button>
                    </div>
            </div>
        </div>
    </div>
</div>