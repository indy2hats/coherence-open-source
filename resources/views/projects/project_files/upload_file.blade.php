<div id="upload_file" class="modal custom-modal animated fadeInUp" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Upload New File</h4>
            </div>
            <div class="modal-body">
                <div class="lead-assign">
                    <form onsubmit="return false;" action="{{route('project-documents.store')}}" method="post" enctype="multipart/form-data" id="upload-file-form">
                        @csrf
                            <input type="hidden" name="project_id" value="{{$id}}">
                            <input type="hidden" name="type" value="file">
                            <div class="form-group">
                                <label class="col-form-label">Name <span class="required-label">*</span></label>
                                <input class="form-control" type="text" name="name">
                                <div class="text-danger text-left field-error" id="label_name"></div>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">Select File <span class="required-label">*</span></label>
                                <input class="form-control" type="file" name="path"/>
                                <div class="text-danger text-left field-error" id="label_path"></div>
                            </div>
                    </form>
                </div>
                <div class="submit-section text-right">
                    <button class="btn btn-primary submit-btn upload-file">Upload</button>
                </div>
            </div>
        </div>
    </div>
</div>