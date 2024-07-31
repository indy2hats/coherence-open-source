
<!-- Create Task Modal -->
<div id="add_category" class="modal custom-modal fade" role="dialog" >
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Add New Category</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('issue-records.storeCategory')}}" id="add_category_form" method="POST" autocomplete="off">
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
                        <button class="btn btn-primary submit-btn create-category">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>