<div id="upload_link" class="modal custom-modal animated fadeInUp" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Add New Link</h4>
            </div>
            <div class="modal-body">
                <div class="lead-assign">
                    <form onsubmit="return false;" action="{{route('project-documents.store')}}" method="post" enctype="multipart/form-data" id="upload-link-form">
                        @csrf
                            <input type="hidden" name="project_id" value="{{$id}}">
                            <input type="hidden" name="type" value="link">
                            <div class="form-group">
                                <label class="col-form-label">Name <span class="required-label">*</span></label>
                                <input class="form-control" type="text" name="name_link">
                                <div class="text-danger text-left field-error" id="label_name_link"></div>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">Place the Link <span class="required-label">*</span></label>
                                <textarea rows="4" class="form-control" placeholder="Enter your message here" name="path_link"></textarea>
                                <div class="text-danger text-left field-error" id="label_path_link"></div>
                            </div>
                    </form>
                </div>
                <div class="submit-section text-right">
                    <button class="btn btn-primary submit-btn upload-link">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>