
<!-- Create Task Modal -->
<div id="add_tag" class="modal custom-modal fade" role="dialog" >
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Add New Tag</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('storeTaskTag')}}" id="add_tag_form" method="POST" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>Title <span class="required-label">*</span></label>
                                <input class="form-control" type="text" name="title" id="title">
                                <div class="text-danger text-left field-error" id="label_title"></div>
                            </div>
                        </div>
                    </div>
                    <div class="submit-section mt20">
                        <button class="btn btn-primary submit-btn create-tag">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>