<div id="create_credential" class="modal custom-modal animated fadeInUp" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Add New Project Credential</h4>
            </div>
            <div class="modal-body">
                <div class="lead-assign">
              <form onsubmit="return false;" id="create_credential_id" action="{{route('project-credentials.store')}}" method="POST" autocomplete="off" enctype="multipart/form-data" class="create_credential_form">
                    @csrf
                    <input type="hidden" name="project_id" value="{{$id}}">
                    <div class="form-group">
                        <label class="col-form-label">Type <span class="required-label">*</span></label>
                         <input class="form-control" type="text" name="type" id="type_id">
                        <div class="text-danger text-left field-error" id="label_type"></div>
                    </div>
                     <div class="form-group">
                        <label class="col-form-label">Value <span class="required-label">*</span></label>
                        <textarea rows="4" class="form-control summernote" placeholder="Enter your message here" name="value" id="value_id"></textarea>
                         <div class="text-danger text-left field-error" id="label_value"></div>
                     </div>
                     <div class="form-group">
                          <label class="col-form-label">Attach File</label>
                          <input class="form-control" type="file" name="file"/>
                          <div class="text-danger text-left field-error" id="label_file"></div>
                      </div>
                     <div class="submit-section text-right">
                    <a type="button" class="btn btn-primary create-new">Add</a>
                    </div>
              </form>
                </div>
            </div>
        </div>
    </div>
</div>