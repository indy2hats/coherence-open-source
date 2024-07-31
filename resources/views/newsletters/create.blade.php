<!-- Add Employee Modal -->
<div id="add_client" class="modal custom-modal animated fade" role="dialog" tabindex='-1'>
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Add New Newsletter</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('newsletters.store')}}" id="add_newsletter_form" method="POST" autocomplete="off" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Title <span class="required-label">*</span></label>
                                <input class="form-control" type="text" name="title">
                                <div class="text-danger text-left field-error" id="label_title"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Date <span class="required-label">*</span></label>
                                <input class="form-control" id="fromdate" type="text" name="publish_date">
                                <div class="text-danger text-left field-error" id="label_publish_date"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label">Upload Screenshot <span class="required-label">*</span></label>
                                <input class="form-control" type="file" name="screenshot"/>
                                <div class="text-danger text-left field-error" id="label_screenshot"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label">Upload Newsletter <span class="required-label">*</span></label>
                                <input class="form-control" type="file" name="newsletter"/>
                                <div class="text-danger text-left field-error" id="label_newsletter"></div>
                            </div>
                        </div>
                    </div>

                </form>
                <div class="submit-section">
                    <button class="btn btn-primary submit-btn create-client">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Add Employee Modal -->