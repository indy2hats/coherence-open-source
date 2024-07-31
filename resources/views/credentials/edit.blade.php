    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Edit Credential</h4>
            </div>
            <div class="modal-body">
                <div class="lead-assign">
              <form onsubmit="return false;" id="edit_credential_id" action="{{route('my-credentials.update', $item->id)}}" method="POST" autocomplete="off">
                   @csrf
                            <input type="hidden" name="id" value="{{$item->id}}">
                            <div class="form-group">
                                <label class="col-form-label">Title <span class="required-label">*</span></label>
                                 <input class="form-control" type="text" name="title" id="edit_title" value="{{$item->title}}">
                                <div class="text-danger text-left field-error" id="label_edit_title"></div>
                            </div>
                             <div class="form-group">
                                <label class="col-form-label">Content <span class="required-label">*</span></label>
                                <textarea rows="4" class="form-control summernote" placeholder="Enter your content here" name="content" id="edit_content">{{$item->content}}</textarea>
                                 <div class="text-danger text-left field-error" id="label_edit_content"></div>
                             </div>
                             <div class="submit-section text-right">
                    <a type="button" class="btn btn-primary update-data">Update</a>
                </div>
              </form>
                </div>
                
            </div>
        </div>
    </div>