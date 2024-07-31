<div id="add_alert" class="modal custom-modal fade" role="dialog" >
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Add New Alert</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('alerts.store')}}" id="add_form" method="POST" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>Type <span class="required-label">*</span></label>
                                 <select class="chosen-select" id="type" name="type">
                                    <option selected>Message</option>
                                    <option>Wish</option>
                                    <option>Text</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>Date <span class="required-label">*</span></label>
                                <input class="form-control datetimepicker" type="text" name="date" id="date">
                                <div class="text-danger text-left field-error" id="label_date"></div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>Title <span class="required-label">*</span></label>
                                <input class="form-control" type="text" name="title" id="title">
                                <div class="text-danger text-left field-error" id="label_title"></div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12">
                            <div class="form-group file-upload">
                                <label>Upload <span class="required-label">*</span></label>
                                <input class="form-control" type="file" name="file" id="file">
                                <div class="text-danger text-left field-error" id="label_file"></div>
                            </div>
                        </div>
                    </div>
                </form>
                
                    <div class="submit-section mt20">
                        <button class="btn btn-primary create-alert">Save</button>
                    </div>
            </div>
        </div>
    </div>
</div>